<?php

namespace FactionPlugin\commands;


use FactionPlugin\Core;
use pocketmine\command\PluginCommand;

class FactionCommand extends PluginCommand
{
    public function __construct(Core $plugin)
    {
        parent::__construct("f", $plugin);
        $this->setDescription("Cette commande permet d'avoir accés au commandes de Faction.");
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args)
    {
    	if(sizeof($args) === 0)
    	{
    		$sender->sendMessage("");
    	}
    }
}