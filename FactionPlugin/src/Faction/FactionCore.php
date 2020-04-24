<?php

namespace Faction;


use Faction\commands\FactionCommands;
use pocketmine\plugin\PluginBase;


class FactionCore extends PluginBase 
{

    private static $instance;
    private static $structure;

    const PREFIX = "§9(§6Indiana§9) §f";
    const ERROR = "§9(§6Indiana§9) §7";

    public function onEnable()
    {

        self::$instance = $this;
        self::$structure = new FactionStructure();

        @mkdir($this->getDataFOlder() . "Factions/");

        $this->getServer()->getCommandMap()->register("f", new FactionCommands($this));

    }

    /*
     * Here we found all functions that the Main file use to run.
     * TODO: Commands, Events, File structure.
     */

    public static function getInstance()
    {
        return self::$instance;
    }

    public static function getUtils()
    {
        return self::$structure;
    }

}