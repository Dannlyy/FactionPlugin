<?php


namespace Faction\commands;


use Faction\FactionCore;
use pocketmine\command\CommandSender;
use pocketmine\command\PluginCommand;
use pocketmine\Server;


class FactionCommands extends PluginCommand
{

    public function __construct(FactionCore $plugin)
    {
        parent::__construct("f", $plugin);
        $this->setDescription("Cette commande permet d'utiliser les diffférentes commandes de Faction.");
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args)
    {

        if(empty($args[0]))
        {
            return $sender->sendMessage(FactionCore::ERROR . "Ecrivez §6/f help §7pour une liste des différentes commandes disponible à éxécuter.");
        }

        /*
         * Create function
         */

        $factionUtils = FactionCore::getUtils();

        if($args[0] === "help")
        {
            return $sender->sendMessage(FactionCore::PREFIX . "§fVoici la liste de toutes les commandes de Faction:\n- §6/f create §fpour créer votre faction.\n- §6/f delete ou disband §fpour supprimer votre faction.\n- §6/f invite §fpour inviter un joueur dans votre faction.");
        }

        if($args[0] === "create")
        {
            if($factionUtils->hasFaction($sender)) return $sender->sendMessage(FactionCore::ERROR . "Vous avez déja une faction, donc vous ne pouvez pas en créer une autre !");

            if(!empty($args[1]))
            {

                return $factionUtils->createFaction($sender, $args[1]);

            } else {

                return $sender->sendMessage(FactionCore::ERROR. "Veuillez entrer un nom pour créer votre faction!");

            }

        }

        /*
         * Delete function
         */

        if(
            $args[0] === "delete" or
            $args[0] === "disband"
        ){

            if($factionUtils->hasFaction($sender))
            {

                $faction = $factionUtils->getPlayerFaction($sender);

                $sender->sendMessage(FactionCore::PREFIX . "Vous venez de supprimer votre faction §6{$faction} §favec succés.");
                $factionUtils->deleteFaction($faction);

            } else {

                return $sender->sendMessage(FactionCore::ERROR . "Vous n'avez pas encore de Faction, créer vous une en éxécutant la commande §6/f create §f!");

            }

        }

        /*
         * Invite function
         */

        if($args[0] === "invite")
        {
            if(empty($args[1]))
            {

                return $sender->sendMessage(FactionCore::ERROR . "Veuillez entrer le nom du joueur afin que vous puissiez le recruter.");

            } else {

                if($factionUtils->hasfaction($sender))
                {

                    $player = Server::getInstance()->getPlayer($args[1]);

                    if($factionUtils->hasFaction($player))
                    {

                        $factionUtils->invitePlayer($player, $factionUtils->getPlayerFaction($sender));
                        return $sender->sendMessage(FactionCore::ERROR . "Ce joueur est déja dans une faction, un message a été envoyé à lui pour quiter sa faction.");

                    } else {

                        $factionUtils->invitePlayer($player, $factionUtils->getPlayerFaction($sender));
                        return $sender->sendMessage(FactionCore::PREFIX . "Vous avez bien invité le joueur §6{$player->getName()} §fà votre Faction avec succés.");

                    }

                } else {

                    return $sender->sendMessage(FactionCore::ERROR . "Vous n'avez pas encore de Faction, créer vous une en éxécutant la commande §6/f create §f!");

                }

            }

        }

        /*
         * Kick function
         */

    }

}