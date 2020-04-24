<?php

namespace FactionPlugin;

use pocketmine\plugin\PluginBase;
use pocketmine\utils\TextFormat as Color;

class Core extends PluginBase {
	
	private static $instance;
	private static $faction;

	public function onEnable()
	{
		$this->getLogger()->info(Color::GRAY . " The plugin has been enabled succesfully.");
		
		self::$instance = $this;
	}
	
	/*
	 * This function return the instance of this file (Core)
	 */
	
	public static function getInstance()
	{
		return self::$instance;
	}

}
