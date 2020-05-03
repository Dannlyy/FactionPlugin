<?php

namespace FactionPlugin;

use FactionPlugin\commands\FactionCommand;
use FactionPlugin\listener\FactionListener;
use FactionPlugin\listener\FightListener;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;
use pocketmine\utils\TextFormat as Color;

class Core extends PluginBase
{

    private static $instance;
    private static $methods;

    public function onEnable()
    {

        $this->getLogger()->info(Color::DARK_GREEN . " The plugin has been enabled successfully.");

        self::$instance = $this;
        self::$methods = new FMethods();

        $command = $this->getServer()->getCommandMap();
        $command->register("f", new FactionCommand($this));

        $this->getServer()->getPluginManager()->registerEvents(new FactionListener(), $this);
        $this->getServer()->getPluginManager()->registerEvents(new FightListener(), $this);

        if (!$this->getDataFolder()) {

            @mkdir($this->getDataFolder());
            @mkdir($this->getDataFolder() . "/info");
            $create_file = new Config($this->getDataFolder() . "players_factions.json", Config::YAML);
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

    /*
     * This function return the FMethods instance.
     */

    public static function getMethods()
    {
        return self::$methods;
    }

}
