<?php

namespace FactionPlugin\commands;

use FactionPlugin\Core;
use FactionPlugin\FMethods;
use FactionPlugin\FPlayer;
use FactionPlugin\lang\BaseLang;
use pocketmine\command\CommandSender;
use pocketmine\command\PluginCommand;
use pocketmine\Server;

date_default_timezone_set("UTC");

class FactionCommand extends PluginCommand
{

    private $factions; //Data file
    private $fac_form; //Form for register a faction
    private $player_form; //Form for register faction into player's faction data

    public function __construct(Core $plugin)
    {
        parent::__construct("f", $plugin);
        $this->setDescription("Cette commande permet d'avoir accés au commandes de Faction.");
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args)
    {
        if ($sender instanceof FPlayer) {
            $lang = BaseLang::translate();
            $fmethods = new FMethods();

            if (empty($args)) {
                $sender->sendMessage($lang["INVALID_COMMAND"]);
                return true;
            }

            /*
             * This statement return the help message
             */

            if ($args[0] === "help") {
                $sender->sendMessage($lang["FACTION_HELP"]);
                return true;
            }

            /*
             * This statement allow a player to create a faction
             */

            if($args[0] === "create") {

                /*
                 * If we have a name and args[1] (name) is alphanumeric
                 */

                if (!empty($args[1]) && ctype_alnum($args[1])) {
                    $name = $args[1];

                    if ($sender->hasFaction()) {
                        $sender->sendMessage($lang["ALREADY_IN_FAC"]);
                        return true;
                    }

                    /*
                    * Faction already exist
                    */

                    if ($fmethods->existFaction($name)) {
                        $sender->sendMessage($lang["ALREADY_EXIST"]);
                        return true;
                    }

                    /*
                     * If the faction name is valid and doesn't already exist
                     */

                    $fmethods->createFaction($sender, $name);

                    $sender->sendMessage(
                        str_replace(
                            "[NAME]",
                            $name,
                            $lang["CREATED_SUCCESS"]
                        )
                    );

                    Server::getInstance()->broadcastMessage(
                        str_replace(
                            ["[PLAYER]", "[NAME]"],
                            [$sender->getName(), $name],
                            $lang["ALERT_CREATED_SUCCESS"]
                        )
                    );

                    /*
                     * The name is not valid
                     */

                } else {
                    $sender->sendMessage($lang["INVALID_FAC_NAME"]);
                }

            }

            /*
             * This statement allow a player to delete a faction
             */

            if($args[0] === "delete") {
                $name = $sender->getFaction();

                /*
                 * The sender is not on a faction
                 */

                if (!$sender->hasFaction()) {
                    $sender->sendMessage($lang["NO_FACTION"]);
                    return true;
                }

                /*
                 * The sender is not the leader
                 */

                if ($sender->getFactionRank() !== "Leader") {
                    $sender->sendMessage($lang["INVALID_PERM"]);
                    return true;
                }

                $fmethods->removeFaction($name);
                $sender->sendMessage(
                    str_replace(
                        "[NAME]",
                        $name,
                        $lang["DELETED_SUCCESS"]
                    )
                );

            }
        } else {
            $sender->sendMessage("Please run this command in-game.");
            return true;
        }
    }
}