<?php

namespace FactionPlugin\listener;

use FactionPlugin\FMethods;
use FactionPlugin\FPlayer;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerChatEvent;
use pocketmine\event\player\PlayerCreationEvent;

class FactionListener implements Listener
{

    public function factionChat(PlayerChatEvent $event)
    {
        $player = $event->getPlayer();
        $fmethods = new FMethods();

        if ($player->hasPlayerChat()) {

            $event->setCancelled();
            $fmethods->sendMessageToFaction($player->getFaction(), $event->getMessage());
            return true;

        }
    }

    public function creation(PlayerCreationEvent $event)
    {
        $event->setPlayerClass(FPlayer::class);
    }

}
