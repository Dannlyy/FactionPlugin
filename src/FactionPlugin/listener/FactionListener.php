<?php

namespace FactionPlugin\listener;

use FactionPlugin\Core;
use FactionPlugin\FMethods;
use FactionPlugin\FPlayer;
use FactionPlugin\lang\BaseLang;

use pocketmine\event\Listener;

use pocketmine\event\player\PlayerChatEvent;
use pocketmine\event\player\PlayerCreationEvent;
use pocketmine\event\player\PlayerJoinEvent;

class FactionListener implements Listener
{

    /*
     * Here we gonna give player object some information :
     */

    public function creation(PlayerCreationEvent $event)
    {
        $event->setPlayerClass(FPlayer::class);
    }

    public function onJoin(PlayerJoinEvent $event)
    {
        $event->getPlayer()->onConnect();
    }


   /*
    * Here we manage some faction stuff :
    */


    public function customChat(PlayerChatEvent $event)
    {

        $player = $event->getPlayer();
        $fmethods = new FMethods();

        $lang = BaseLang::translate();

        if (in_array($player->getName(), Core::getMethods()->desc)) {

            if (Core::getMethods()->desc[$player->getName()] < time()) {

                $player->sendMessage($lang["DESC_TIMEOUT"]);
                return $event->setCancelled();

            } else {

                if(strlen($event->getMessage()) <= 55) {

                    $fmethods->setDescription($player->getFaction(), $event->getMessage());
                    $fmethods->sendMessageToFaction($player->getFaction(), $lang["DESC_ADDED_SUCCESS"]);

                    unset($fmethods->desc[$player->getName()]);
                    return $event->setCancelled();

                } else {

                    $player->sendMessage($lang["DESC_TOO_LONG"]);
                    return $event->setCancelled();

                }

            }
        }

        if ($player->hasFactionChat()) {

            $event->setCancelled();
            $fmethods->sendMessageToFaction($player->getFaction(), $player->getName() . " §r-> §a" . $event->getMessage());

        }

        if ($player->hasAllyChat()) {
            $event->setCancelled();
            $fmethods->sendMessageToAlly($player->getFaction(), $player->getName() . " §r-> §a" . $event->getMessage());
        }
    }

    //TO DO : Claims


}
