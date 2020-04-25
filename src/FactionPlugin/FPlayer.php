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
	public function getFaction() : mixed
	{
		return $this->players_factions->get($this->getName())["Faction"];
	}

	public function setFaction(mixed $name = null, mixed $rank = null)
	{
		$this->players_factions->set($this->getName(), [
			"Faction" => $name,
			"Rank" => $rank
		]);
	}

	public function getFactionRank()
	{
		return $this->players_factions->get($this->getName()["Rank"]);
	}

}