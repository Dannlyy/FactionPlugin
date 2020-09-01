<?php

namespace FactionPlugin;

use FactionPlugin\lang\BaseLang;
use pocketmine\Server;
use pocketmine\utils\Config;

class FMethods
{

    private $factions; #Data file
    private static $file = "info/players_factions";

    public $chat_array = [];
    public $allychat_array = [];
    public $desc = [];


    public function getFile($configName)
    {
        return new Config(Core::getInstance()->getDataFolder() . $configName . ".json", Config::JSON);
    }

    /*
     * This function let you see if the Faction selected exists or not.
     */

    public function existFaction($name)
    {
        if (file_exists(Core::getInstance()->getDataFolder() . $name . ".json")) return true;
        return false;
    }

    /*
     * This function let you create a faction.
     */

    public function createFaction(FPlayer $player, $name)
    {
        $file = $this->getFile($name); # We create for each Faction a file.
        $second_file = $this->getFile("info/players_factions"); # It's the second file where we save the name of faction and rank.
        $third_file = $this->getFile("info/faction_power"); #To avoid lags so we can make a toplist without problems.

        $file->set("Name", $name);

        $file->set("Home", null);
        $file->set("Description", "");

        $file->set("Date", date("Y") . " " . date("m") . " " . date("d"));

        $file->set("Leader", $player->getName());
        $file->set("Officers", []);
        $file->set("Members", []);
        $file->set("Allies", []);

        $file->set("Power", 0);
        $file->set("Level", 1);
        $file->set("Kills", 0);

        $file->set("Claims", []);
        $file->set("ClaimsFeatures", []);
        $file->set("Guardian", null);

        $file->save();

        $second_file->set($player->getName(), $name . " Leader"); # Here i save the second file informations.
        $second_file->save();

        $third_file->set($name, "0 1"); # Here i save the second file informations.
        $third_file->save();

    }


    /*
     * This function let you delete a faction.
     */

    public function removeFaction($name)
    {

        $file = $this->getFile("info/players_factions");
        
        $faction_power = $this->getFile("info/faction_power");
        $faction = Core::getInstance()->getDataFolder() . $name . ".json"; # This is the faction file.

        if(isset($this->getSpecificInformation($name, "Leader"))) unset($this->desc[$this->getSpecificInformation($name, "Leader")]);
        $faction_power->remove($name);

        foreach ($this->getFactionPlayers($name) as $player) {

            $file->remove($player);

            if (in_array($player, $this->chat_array)) {
                unset($this->chat_array[$player]);
            }

            if (in_array($player, $this->allychat_array)) {
                unset($this->allychat_array[$player]);
            }

        }

        @unlink($faction); # Here i delete the faction file.

        $faction_power->save();
        $file->save();

    }

    /*
     * This function let you see if a player have a  faction
     */

    public function hasFaction($playerName)
    {

        $file = $this->getFile("info/players_factions");
        if ($file->exists($playerName)) {
            return true;
        }

        return false;
    }

    /*
     * This function allow you to see the Faction of a player.
     */

    public function getPlayerFaction($playerName)
    {
        $file = $this->getFile("info/players_factions");

        if ($this->hasFaction($playerName)) {

            $search = explode(" ", $file->get($playerName));
            return $search[0]; # This is the name of the Faction
        }

        return null; # If the faction don't exist the returned value is null.
    }

    /*
     * This functon allow to get the player rank as stars (** for leader, * for officers, none for players)
     */

    public function getFactionRank($playerName)
    {
        $file = $this->getFile("info/players_factions");
        $search = explode(" ", $file->get($playerName));

        if (isset($search[1]) and $search[1] === "Leader") {
            return "**";
        }

        if (isset($search[1]) and $search[1] === "Captains") {
            return "*";
        }

        return "";

    }

    /*
     * This function allow you to get all players of a specific faction.
     */

    public function getFactionPlayers($faction)
    {

        $file = $this->getFile($faction);
        $playersList = array_merge((array)$file->get("Leader"), $file->get("Officers"), $file->get("Members"));

        return $playersList; # This returned array is containing all the players in the faction (just names and not Objects)

    }

    /*
     * This function allow you to get all players of his allies.
     */

    public function getAllyPlayers($faction)
    {

        $file = $this->getFile($faction);
        $allies = $file->get("Allies");

        $playersList = [];

        foreach ($allies as $ally) {
            $playersList[] = $this->getFactionPlayers($ally);
        }

        return $playersList; # This returned array is containing all the players on all ally's factions (just names and not Objects)

    }

    /*
     * This function allow to send messages to a specific faction.
     */

    public function sendMessageToFaction($faction, $message)
    {

        $factionPlayers = $this->getFactionPlayers($faction);
        $lang = BaseLang::translate();

        foreach ($factionPlayers as $name) {

            $player = Server::getInstance()->getPlayer($name);

            if($player !== null)
                $player->sendMessage(
                    $lang["FACTION_CHAT_PREFIX"]
                    . $message
                );

        }

    }

    public function sendPopupToFaction($faction, $message)
    {

        $factionPlayers = $this->getFactionPlayers($faction);
        $lang = BaseLang::translate();

        foreach ($factionPlayers as $name) {

            $player = Server::getInstance()->getPlayer($name);

            if($player !== null)
                $player->sendTip(
                    $message
                );

        }

    }

    /*
     * This function allow to send messages to a his allies.
     */

    public function sendMessageToAlly($faction, $message)
    {

        $factionPlayers = $this->getAllyPlayers($faction);
        $lang = BaseLang::translate();

        foreach ($allyPlayers as $name) {

            $player = Server::getInstance()->getPlayer($name);
            if($player !== null)
                $player->sendMessage(
                    "§9[§6{$faction}§9] §f"
                    . $message
                );

        }

    }

    /*
     * This function allow to get the allies of a faction.
     */

    public function getAllies($faction)
    {
        $information = $this->getFile($faction);
        return $information->get("Allies");
    }

    /*
     * This function let you change the description of faction.
     */

    public function setDescription($faction, $desc)
    {
        $this->setSpecificInformation($faction, "Description", $desc);
    }

    /*
     * This function return the description.
     */

    public function getSpecificInformation($faction, $information)
    {
        $file = $this->getFile($faction);
        return $file->get($information);
    }

    public function setSpecificInformation($faction, $type, $info, $supplement = null)
    {
        $file = $this->getFile($faction);

        if($suuplement === null)
            $file->set($type, $info);

        if($supplement !== null)
            $file->set($type, $info + $points);

        $file->save();    
    }

    /*
     * This function allow you to add points to faction :
     */


    public function addPoints($faction, $points)
    {
        if($this->getFactionLevel($faction) * 10 <= $this->getPoints($faction)) {

            $this->sendMessageToFaction("§Votre faction a augmenté de level ! elle est niveau §6{$this->getFactionLevel($faction)} §f(§6LVL§f) maintenant !");
            $this->addFactionLevel($faction);

            $pointsLeft = $this->getPoints($faction) - ($this->getFactionLevel($faction) * 10);
            $this->setSpecificInformation($faction, "Points", $pointsLeft);

            return true;
        }

        $this->setSpecificInformation($faction, "Points", $points, $this->getPoints($faction));
    }

    /*
     * This function return the points of a faction.
     */

    public function getPoints($faction)
    {
        $information = $this->getFile($faction);
        return $information->get("Points");
    }

    /*
     * This function allow you to add level to faction :
     */


    public function addFactionLevel($faction)
    {
        $this->setSpecificInformation($faction, "Level", 1, $this->getFactionLevel($faction));
    }

    /*
     * This function return the kills of a faction.
     */

    public function getFactionLevel($faction)
    {
        $information = $this->getFile($faction);
        return $information->get("Level");
    }


    /*
     * This function allow you to add kills to faction :
     */


    public function addKill($faction)
    {
        $this->setSpecificInformation($faction, "Kills", 1, $this->getKills($faction));
    }

    /*
     * This function return the kills of a faction.
     */

    public function getKills($faction)
    {
        $information = $this->getFile($faction);
        return $information->get("Kills");
    }

    /*
     * This function allow you to add power to faction :
     */

    public function setPower($faction, $power)
    {
        $this->setSpecificInformation($faction, "Power", $power, $this->getPower($faction));
    }


    /*
     * This function return the power of a faction.
     */

    public function getPower($faction)
    {
        $information = $this->getFile($faction);
        return $information->get("Power");
    }

    /*
     * This function allow you to see if the 2 players are allies.
     */

    public function areAllies(FPlayer $player, FPlayer $second)
    {

        if (!$player->hasFaction() or !$second->hasFaction()) return false;

        $faction = $this->getAllies($player->getFaction());
        $faction2 = $second->getFaction();

        if (in_array($faction2, $faction)) {
            return true;
        }

        return false;

    }

    /*
     * This function show you the top kills/ Balance. (TO FIX)
     */

    public function getTopFactions(FPlayer $player, $type)
    {

        $file = $this->getFile("info/faction_power");
        $array = $file->getAll();

        $topLevel = [];
        $topPower = [];

        $message = [];
        $top = 1;

        switch($type) {
            case "power":

                foreach($array as $factions => $value) {

                    $explode = explode(" ", $values);

                    $power = $explode[0];
                    $topPower[$factions] = $power;

                }

                foreach ($topPower as $factions => $value) {
                    if ($top === 10) break;
                    $message[] = "§9(§6#{$top}§9) - §6{$factions} §favec un total de power(s) égale à §6{$value}\n";

                    $top++;
                }
                

            break;

            case "level"

                foreach($array as $factions => $value) {

                    $explode = explode(" ", $values);

                    $level = $explode[1];
                    $topLevel[$factions] = $power;

                }  

                foreach ($topPower as $factions => $value) {
                    if ($top === 10) break;
                    $message[] = "§9(§6#{$top}§9) - §6{$factions} §favec un total de level(s) égale à §6{$value}\n";

                    $top++;
                }

            break;

            default:

                foreach($array as $factions => $value) {

                    $explode = explode(" ", $values);

                    $level = $explode[1];
                    $topLevel[$factions] = $power;

                } 

                foreach ($topPower as $factions => $value) {
                    if ($top === 10) break;
                    $message[] = "§9(§6#{$top}§9) - §6{$factions} §favec un total de level(s) égale à §6{$value}\n";

                    $top++;
                }

        }


        $message = implode(" ", $message);
        return $player->sendMessage("§9[§6Classement§9] §fVoici le classement de {$type}(s) de faction : \n{$message}\n§f- Vous pouvez voir aussi le classement de faction avec §6/f top <power/level>.");

    }

}
