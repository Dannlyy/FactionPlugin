<?php

namespace FactionPlugin\listener;

use FactionPlugin\FPlayer;
use pocketmine\Listener;
use pocketmine\event\player\{
	PlayerJoinEvent,
	PlayerQuitEvent,
	PlayerInteractEvent,
	PlayerCreationEvent
};

class FactionListener implements Listener {

	public function creation(PlayerCreationEvent $event)
	{
		$event->getPlayer()->setPlayerClass(FPlayer::class);
	}

}
