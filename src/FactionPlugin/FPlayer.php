<?php

namespace FactionPlugin;

use pocketmine\Player;

class FPlayer extends Player {
	
	public function __construct(SourceInterface $interface, string $ip, int $port)
    {
        parent::__construct($interface, $ip, $port);
        $this->players_factions = new Config($this->getDataFolder() . "players_factions.yml", Config::YAML);
        $this->factions = new Config($this->getDataFolder() . "factions.yml", Config::YAML);
	}

	/* Faction Methods */
	public function getFaction()
	{
		return $this->players_factions->get($this->getName())["Faction"];
	}

	public function setFaction(mixed $name = null) : string
	{
		$this->players_factions->set($this->getName()["Faction"], $name);
	}

	public function getFactionRank()
	{
		$faction = $this->getFaction();

		foreach($this->factions->get($faction) as $informations => $array)
		{
			if(in_array($this->getName(), $array))
				return $informations;
				break;
		}
	}

}