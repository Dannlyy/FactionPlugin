<?php

namespace FactionPlugin;

use FactionPlugin\lang\BaseLang;
use pocketmine\Server;
use pocketmine\utils\Config;

class FMethods
{

    private $factions; #Data file
    private $players_factions; #Data file

    private static $file = "players_factions";

    public $chat_array = [];
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
        $second_file = $this->getFile("players_factions"); # It's the second file where we save the name of faction and rank.

        $file->set("Name", $name);
        $file->set("Home", "");
        $file->set("Description", "");
        $file->set("Date", date("Y") . " " . date("m") . " " . date("d"));
        $file->set("Leader", $player->getName());
        $file->set("Captains", []);
        $file->set("Members", []);
        $file->set("Allies", []);
        $file->set("Power", 0);
        $file->set("Balance", 0);
        $file->set("Kills", 0);
        $file->set("Claims", []);

        $file->save();

        $second_file->set($player->getName(), $name . " Leader"); # Here i save the second file informations.
        $second_file->save();

    }


    /*
     * This function let you delete a faction.
     */

    public function removeFaction($name)
    {

        $file = $this->getFile("players_factions");
        $faction = Core::getInstance()->getDataFolder() . $name . ".json"; # This is the faction file.

        foreach ($this->getFactionPlayers($name) as $player) {

            $file->remove($player);

            if (in_array($player, $this->chat_array)) {
                $this->removeFactionChat($player);
            }
        }

        @unlink($faction); # Here i delete the faction file.
        $file->save();

    }
    /*
     * This function let you see if a player have a  faction
     */

    public function hasFaction($playerName)
    {

        $file = $this->getFile("players_factions");
        if ($file->exists($this->getName())) {
            return true;
        }

        return false;
    }

    /*
     * This function allow you to see the Faction of a player.
     */

    public function getPlayerFaction($playerName)
    {
        $file = $this->getFile("players_factions");

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
        $file = $this->getFile("players_factions");
        $search = explode(" ", $file->get($playerName));

        if ($search[1] === "Leader") {
            return "**";
        }

        if ($search[1] === "Captains") {
            return "*";
        }

        return "";

    }

    /*
     * This function allow you to get all players of a specific faction.
     * PS : We use this to send message to the whole faction ect..
     */

    public function getFactionPlayers($faction)
    {

        $file = $this->getFile($faction);
        $playersList = array_merge((array)$file->get("Leader"), $file->get("Captains"), $file->get("Members"));

        return $playersList; # This returned array is containing all the players in the faction (just names and not Objects)

    }

    /*
     * This function allow to send messages to a specific faction.
     * PS : We use this function for faction chat or ally chat or to send message to the whole faction.
     */

    public function sendMessageToFaction($faction, $message)
    {

        $factionPlayers = $this->getFactionPlayers($faction);
        $lang = BaseLang::translate();

        foreach ($factionPlayers as $name) {

            $player = Server::getInstance()->getPlayer($name);
            $player->sendMessage(
                $lang["FACTION_CHAT_PREFIX"]
                . $message
            );

        }

    }

    /*
     * This function let you change the description of faction.
     */

    public function setDescription($faction, $desc)
    {

        $file = $this->getFile($faction);

        $file->set("Description", $desc);
        $file->save();

    }

    /*
     * This function allow to update power of a faction.
     */

    public function setPower($faction, $power)
    {

        $file = $this->getFile($faction);

        $file->set("Power", $this->getPower($faction) + $power);
        $file->save();

    }

    /*
     * This function return the description.
     */

    public function getSpecificInformation($faction, $information)
    {
        $file = $this->getFile($faction);
        return $file->get($information);
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
     * This function allow to get the allies of a faction.
     */

    public function getAllies($faction)
    {
        $information = $this->getFile($faction);
        return $information->get("Allies");
    }

    /*
     * This function let you see how to remove a player to his faction chat
     */

    public function removeFactionChat($player)
    {
        unset($this->chat_array[$player]);
    }

}
