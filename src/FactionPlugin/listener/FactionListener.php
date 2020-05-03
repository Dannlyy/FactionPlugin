<?php

namespace FactionPlugin\listener;

use FactionPlugin\Core;
use FactionPlugin\FMethods;
use FactionPlugin\FPlayer;
use FactionPlugin\lang\BaseLang;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerChatEvent;
use pocketmine\event\player\PlayerCreationEvent;

class FactionListener implements Listener
{

    public function customChat(PlayerChatEvent $event)
    {

        $player = $event->getPlayer();
        $fmethods = new FMethods();

        $lang = BaseLang::translate();

        if (in_array($player->getName(), Core::getMethods()->desc)) {
            if (Core::getMethods()->desc[$player->getName()] < time()) {
                $player->sendMessage($lang["DESC_TIMEOUT"]);
                $event->setCancelled();

            } else {

                $fmethods->setDescription($player->getFaction(), $event->getMessage());
                $fmethods->sendMessageToFaction($player->getFaction(), $lang["DESC_ADDED_SUCCESS"]);

                unset($fmethods->desc[$player->getName()]);
                $event->setCancelled();

            }
        }

        if ($player->hasFactionChat()) {

            $event->setCancelled();
            $fmethods->sendMessageToFaction($player->getFaction(), $player->getName() . " §r-> " . $event->getMessage());

        }

        if ($player->hasAllyChat()) {
            $event->setCancelled();
            $fmethods->sendMessageToAlly($player->getFaction(), $player->getName() . " §r-> " . $event->getMessage());
        }
    }

    public function creation(PlayerCreationEvent $event)
    {
        $event->setPlayerClass(FPlayer::class);
    }

}
