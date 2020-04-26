<?php

namespace FactionPlugin;

use pocketmine\network\SourceInterface;
use pocketmine\Player;
use pocketmine\utils\Config;

class FPlayer extends Player
{

    private $file;
    private $chat_array;

    public function __construct(SourceInterface $interface, string $ip, int $port)
    {
        parent::__construct($interface, $ip, $port);
        $this->file = new Config(Core::getInstance()->getDataFolder() . "players_factions.json", Config::JSON);
        $this->chat_array = [];
    }

    /*
     * This function let you see the player faction.
     */

    public function getFaction()
    {

        $search = explode(" ", $this->file->get($this->getName()));
        return $search[0];

    }

    /*
     * This function let you see the player faction rank.
     */

    public function getFactionRank()
    {

        $search = explode(" ", $this->file->get($this->getName()));
        return $search[1];

    }

    /*
     * This function let you see if a player has a faction.
     */

    public function hasFaction()
    {
        if ($this->file->exists($this->getName())) {
            return true;
        }

        return false;
    }

}