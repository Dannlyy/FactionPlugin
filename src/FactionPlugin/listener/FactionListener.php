<?php

namespace FactionPlugin\listener;

use FactionPlugin\FPlayer;
use pocketmine\event\Listener;
use pocketmine\event\player\{PlayerCreationEvent};

class FactionListener implements Listener
{

    public function creation(PlayerCreationEvent $event)
    {
        $event->setPlayerClass(FPlayer::class);
    }

}
