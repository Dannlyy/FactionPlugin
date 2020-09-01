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
                $damager->sendPopup($lang["FACTION_NO_FIGHT"]);
                return $event->setCancelled();
            }

            /*
             * This function stop two player allies to fight.
             */

            if ($damager->isAllyWith($entity)) {

                $damager->sendPopup($lang["ALLIES_NO_FIGHT"]);
                return $event->setCancelled();

            }

        }

    }

    /*
     * Here we add power and remove to factions :
     */

    public function onDeath(PlayerDeathEvent $event)
    {

        $cause = $event->getPlayer()->getLastDamageCause();

        if($event instanceof EntityDamageByEntityEvent) {

            $damager = $cause->getDamager();
            $entity = $event->getPlayer();

            if ($damager->hasFaction()) {

                if($entity->hasFaction()) {

                    $damager_faction = $damager->getFaction();
                    Core::getMethods()->setPower($damager_faction, Core::getConfigFile("configuration", "faction")["power"]);
   
                    Core::getMethods()->addPoints($damager_faction, 1);

                    $entity_faction = $entity->getFaction();
                    Core::getMethods()->setPower($entity_faction, -(Core::getConfigFile("configuration", "faction")["power"]));

                } else {

                    $damager_faction = $damager->getFaction();
                    Core::getMethods()->setPower($damager_faction, Core::getConfigFile("configuration", "faction")["power"]);
                }

            }
        }
    }

}