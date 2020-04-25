<?php

namespace FactionPlugin;

use pocketmine\plugin\PluginBase;
use pocketmine\utils\TextFormat as Color;
use pocketmine\utils\Config;

class Core extends PluginBase {
	
	private static $instance;

	public function onEnable()
	{
		$this->getLogger()->info(Color::DARK_GREEN . " The plugin has been enabled succesfully.");
		self::$instance = $this;

		if(!$this->getDataFolder())
		{
			@mkdir($this->getDataFolder());
			$factions = new Config($this->getDataFolder() . "factions.yml", Config::YAML);
			$players = new Config($this->getDataFolder() . "players_factions.yml", Config::YAML);
		}
	}

	public function onDisable()
	{
		$this->getLogger()->info(Color::DARK_RED . " The plugin has been disabled succesfully.");
	}
	
	/*
	 * This function return the instance of this file (Core)
	 */
	
	public static function getInstance()
	{
		return self::$instance;
	}

}
