<?php

namespace FactionPlugin;

use pocketmine\network\SourceInterface;
use pocketmine\Player;
use pocketmine\utils\Config;

class FPlayer extends Player
{

    $faction = null
    $factionRank = null;


    public function __construct(SourceInterface $interface, string $ip, int $port)
    {
        parent::__construct($interface, $ip, $port);
    }

    public function onConnect() {

        $file = new Config(Core::getInstance()->getDataFolder() . "info/players_factions.json", Config::JSON);
        $search = explode(" ", $file->get($this->getName()));

        $this->faction = (isset($search[0]) === false ? null : $search[0]);
        $this->factionRank = (isset($search[1]) === false ? null : $search[1]);

    }

    /*
     * This function let you see the player faction.
     */

    public function getFaction()
    {
        return $this->faction;
    }

    /*
     * This function let you see the player faction rank.
     */

    public function getFactionRank()
    {
        return $this->factionRank;
    }

    public function getFactionRankMessage()
    {
        if($this->getFactionRank() === "Leader") return "**";
        if($this->getFactionRank() === "Officer") return "*";
        
        return "";
    }

    /*
     * This function let you see if a player has a faction.
     */

    public function hasFaction()
    {
        if ($this->faction !== null) {
            return true;
        }

        return false;
    }

    /*
    * This function let you see if a player has been turned on faction chat
    */

    public function hasFactionChat()
    {
        if (!in_array($this->getName(), Core::getMethods()->chat_array)) {
            return false;
        }
        return true;
    }

    /*
     * This function let you see how to add a player to his faction chat
     */

    public function addFactionChat()
    {
        Core::getMethods()->chat_array[] = $this->getName();
    }

    /*
     * This function let you see how to remove a player to his faction chat
     */

    public function removeFactionChat()
    {
        if ($this->hasFactionChat()) {
            unset(Core::getMethods()->chat_array[$this->getName()]);
        }

    }

    /*
    * This function let you see if a player has been turned on ally chat
    */

    public function hasAllyChat()
    {
        if (!in_array($this->getName(), Core::getMethods()->allychat_array)) {
            return false;
        }
        return true;
    }

    /*
     * This function let you see how to add a player to his faction chat
     */

    public function addAllyChat()
    {
        Core::getMethods()->allychat_array[] = $this->getName();
    }

    /*
     * This function let you see how to remove a player to his faction chat
     */

    public function removeAllyChat()
    {
        if ($this->hasFactionChat()) {
            unset(Core::getMethods()->allychat_array[$this->getName()]);
        }

    }

    /*
     * This function allow you to see if you're in alliance with the second player.
     */

    public function isAllyWith(FPlayer $player)
    {
        $faction = new FMethods();
        return $faction->areAllies($this, $player);
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