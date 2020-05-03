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
             * This part of the code give the statement for add power on death
             */

            if ($damager->hasFaction()) {
                if ($entity->getHealth() === 0) {

                    if($entity->hasFaction()) {
                        $damager_faction = $damager->getFaction();
                        Core::getMethods()->setPower($damager_faction, Core::getConfigFile("configuration", "faction")["power"] );

                        $entity_faction = $entity->getFaction();
                        Core::getMethods()->setPower($entity_faction, -(Core::getConfigFile("configuration", "faction")["power"]) );

                    } else {
                        $damager_faction = $damager->getFaction();
                        Core::getMethods()->setPower($damager_faction, Core::getConfigFile("configuration", "faction")["power"] );
                    }

                }
            }

        }

    }

}