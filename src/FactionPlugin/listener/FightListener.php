<?php


namespace FactionPlugin\listener;

use FactionPlugin\Core;
use FactionPlugin\FPlayer;
use FactionPlugin\lang\BaseLang;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\Listener;

class FightListener implements Listener
{

    /*
     * Here to stop pvp beetwen faction players.
     */

    public function onFight(EntityDamageByEntityEvent $event)
    {
        $entity = $event->getEntity();
        $damager = $event->getDamager();

        $lang = BaseLang::translate();

        if ($entity instanceof FPlayer and $damager instanceof FPlayer) {

            /*
             * Here to stop 2 member of a faction to fight.
             */

            if ($entity->getFaction() === $damager->getFaction()) {
                return $event->setCancelled();
            }

            /*
             * This function stop two player allies to fight.
             */

            if (Core::getMethods()->areAllies($entity, $damager)) {

                $damager->sendPopup($lang["ALLIES_NO_FIGHT"]);
                return $event->setCancelled();

            }

            /*
             * This part of the code give the two function
             */

            if ($entity->hasFaction()) {
                $faction = $entity->getFaction();
                Core::getMethods()->setPower($faction, -8);
            }

            if ($damager->hasFaction()) {
                $faction = $damager->getFaction();
                Core::getMethods()->setPower($faction, 8);
            }

        }

    }

}