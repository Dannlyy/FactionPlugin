<?php

namespace FactionPlugin;

use pocketmine\utils\Config;

class FMethods
{

    private $factions; #Data file
    private $players_factions; #Data file

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

    public function createFaction(Player $player, $name)
    {
        $file = $this->getFile($name); # We create for each Faction a file.
        $second_file = $this->getFile("players_factions"); # It's the second file where we save the name of faction and rank.

        $form = [
            "Home" => [], #Coords of the faction home (middle coords X/Z)
            "Date" => date("Y") . date("m") . date("d"), #Format Year . Month . Day

            "Leader" => $player->getName(), #Name of the leader
            "Officers" => [], #Array of Captains
            "Members" => [], #Array of Members
            "Allies" => [], #Array of all allies

            "Power" => "0", #Faction Power
            "Balance" => "0", #Faction balance
            "Kills" => "0", #Number of kill (all members summered)

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

        $file->remove(implode("", array_keys($file->getAll(), $name))); # Here i search all keys with the same value to delete all keys.
        @unlink($faction); # Here i delete the faction file.

        $file->save();

    }

    /*
     * This function let you see the faction of a Player.
     */

    public function hasFaction($playerName)
    {

        $file = $this->getFile("players_factions");
        if ($file->exists($this->getName())) {
            return true;
        }

        return false;
    }

    public function getPlayerFaction($playerName)
    {
        $file = $this->getFile("players_factions");

        if ($this->hasFaction($playerName)) {
            $search = explode(" ", $file->get($playerName));
            return $search[0];
        }

        return null;
    }

    public function getFactionRank($playerName)
    {
        $file = $this->getFile("players_factions");
        $search = explode(" ", $file->get($playerName));

        if ($search[0] === "Leader") {
            return "**";
        }

        if ($search[0] === "Officer") {
            return "*";
        }

        return "";

    }

}
