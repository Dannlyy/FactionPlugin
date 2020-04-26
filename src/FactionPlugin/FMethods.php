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


    public function getFile($configName)
    {
        return new Config(Core::getInstance()->getDataFolder() . $configName . ".json", Config::JSON);
    }

    /*
     * This function return all indented informations about a faction
     */
    public function getFactionInformations($name)
    {
        $file = $this->getFile($name);
        return $file->getAll();
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

        $form = [
            "Home" => "", #Coords of the faction home (middle coords X/Z)
            "Description" => "",
            "Date" => date("Y") . " " . date("m") . " " . date("d"), #Format Year . Month . Day

            "Leader" => $player->getName(), #Name of the leader
            "Officers" => [], #Array of Captains
            "Members" => [], #Array of Members
            "Allies" => [], #Array of all allies

            "Level" => 1, #Faction level
            "Power" => 0, #Faction Power
            "Balance" => 0, #Faction balance
            "Kills" => 0, #Number of kill (all members summered)

            "Claims" => []
        ];

        $file->set($name, $form); # Here i save the informations of the faction.
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

        @unlink($faction); # Here i delete the faction file.

        foreach ($file->getAll() as $player => $value) {
            if (strpos($value, $name) !== false) {
                $file->remove($player);
                $this->removeFactionChat($player);
            }
        }

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

        $file = $this->getFile(self::$file);
        $playersList = [];

        foreach ($file->getAll() as $player => $value) {
            if (strpos($value, $faction) !== false)
                $playersList[] = $player;
        }

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
                str_replace("[FACTION]", $player->getFaction(), $lang["FACTION_CHAT_PREFIX"]) 
                . $message
            );

        }

    }

    /*
     * This function let you see if a player has been turned on faction chat
     */

    public function hasFactionChat($name)
    {
        if (!array_search($name, $this->chat_array)) {
            return false;
        }
        return true;
    }

    /*
     * This function let you see how to add a player to his faction chat
     */

    public function addFactionChat($name)
    {
        set($this->chat_array[$name]);
    }

    /*
     * This function let you see how to remove a player to his faction chat
     */

    public function removeFactionChat($name)
    {
        unset($this->chat_array[$name]);
    }

}
