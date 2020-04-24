<?php

namespace FactionPlugin;

use pocketmine\plugin\PluginBase;
use pocketmine\utils\TextFormat as Color;

class Core extends PluginBase {

	public function onEnable()
	{
		$this->getLogger()->info(Color::DARK_GREEN . " enabled !");
	}

	public function onDisable()
	{
		$this->getLogger()->info(Color::DARK_RED . " disabled !");
	}

}