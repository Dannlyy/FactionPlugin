<?php

namespace FactionPlugin\listener;

use FactionPlugin\FPlayer;
use FactionPlugin\FMethods;
use pocketmine\event\Listener;
use pocketmine\event\player\{PlayerChatEvent, PlayerCreationEvent};

class FactionListener implements Listener
{

	public function chat(PlayerChatEvent $event)
	{
		$player = $event->getPlayer();
		$fmethods = new FMethods();
		if ($fmethods->hasPlayerChat($player->getName())) {
			$event->setCancelled(true);
			$fmethods->sendMessageToFaction($player->getFaction(), $event->getMessage());
			return true;
		}
	}

    public function creation(PlayerCreationEvent $event)
    {
        $event->setPlayerClass(FPlayer::class);
    }

}
