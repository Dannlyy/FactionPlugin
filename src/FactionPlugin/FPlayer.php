<?php

namespace FactionPlugin;

use pocketmine\network\SourceInterface;
use pocketmine\Player;
use pocketmine\utils\Config;

class FPlayer extends Player
{


    public function __construct(SourceInterface $interface, string $ip, int $port)
    {
        parent::__construct($interface, $ip, $port);
    }

    /*
     * This function let you see the player faction.
     */

    public function getFaction()
    {

        $file = new Config(Core::getInstance()->getDataFolder() . "players_factions.json", Config::JSON);

        $search = explode(" ", $file->get($this->getName()));
        return $search[0];

    }

    /*
     * This function let you see the player faction rank.
     */

    public function getFactionRank()
    {

        $file = new Config(Core::getInstance()->getDataFolder() . "players_factions.json", Config::JSON);

        $search = explode(" ", $file->get($this->getName()));
        return $search[1];

    }

    /*
     * This function let you see if a player has a faction.
     */

    public function hasFaction()
    {
        $file = new Config(Core::getInstance()->getDataFolder() . "players_factions.json", Config::JSON);
        if ($file->exists($this->getName())) {
            return true;
        }

        return false;
    }

    /*
    * This function let you see if a player has been turned on faction chat
    */

    public function hasFactionChat()
    {
        if (!in_array($this->getName(), FMethods::$chat_array)) {
            return false;
        }
        return true;
    }

    /*
     * This function let you see how to add a player to his faction chat
     */

    public function addFactionChat()
    {
        FMethods::$chat_array[] = $this->getName();
    }

    /*
     * This function let you see how to remove a player to his faction chat
     */

    public function removeFactionChat()
    {
        unset(FMethods::$chat_array[$this->getName()]);
    }

    /*
     * This function allow you tu get faction information as an array.
     */

    public function getFactionInformations()
    {

        $methods = new FMethods();
        $file = $methods->getFile($this->getFaction());

        return $file->get($this->getFaction());

    }

}