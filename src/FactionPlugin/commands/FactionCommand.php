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
        $this->setDescription("Access to the faction's commands");
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

                if ($sender->hasFaction()) {
                    $sender->sendMessage($lang["ALREADY_IN_FAC"]);
                    return true;
                }

                /*
                 * If we have a name and args[1] (name) is alphanumeric
                 */

                if (!empty($args[1]) && ctype_alnum($args[1])) {

                    $name = $args[1];

                    if (strlen($args[1]) > 12) {
                        $sender->sendMessage($lang["NAME_TOO_LONG"]);
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

                /*
                 * If the sender have a faction and he is the leader
                */

                $fmethods->removeFaction($name);

                $sender->sendMessage(
                    str_replace(
                        "[NAME]",
                        $name,
                        $lang["DELETED_SUCCESS"]
                    )
                );

                Server::getInstance()->broadcastMessage(
                    str_replace(
                        ["[PLAYER]", "[NAME]"],
                        [$sender->getName(), $name],
                        $lang["ALERT_DELETED_SUCCESS"]
                    )
                );

            }

            /*
             * This statement allow a player to create a faction
             */

            if ($args[0] === "info") {

                if (!empty($args[1])) {

                    $name = $args[1];

                    /*
                     * The faction name does not exist
                     */

                    if (!$fmethods->existFaction($name)) {
                        $sender->sendMessage($lang["INVALID_FAC_NAME"]);
                        return true;
                    }

                    $sender->sendMessage(
                        str_replace(
                            [
                                "[NAME]",
                                "[DESCRIPTION]",
                                "[POWER]",
                                "[BALANCE]",
                                "[KILLS]",
                                "[DATE]",
                                "[LEADER]",
                                "[CAPTAINS]",
                                "[MEMBERS]",
                                "[ALLIES]"
                            ],
                            [
                                $name,
                                $fmethods->getSpecificInformation($name, "Description"),
                                $fmethods->getSpecificInformation($name, "Power"),
                                $fmethods->getSpecificInformation($name, "Balance"),
                                $fmethods->getSpecificInformation($name, "Kills"),
                                $fmethods->getSpecificInformation($name, "Date"),
                                $fmethods->getSpecificInformation($name, "Leader"),
                                (empty($fmethods->getSpecificInformation($name, "Captains")) ? "None" : implode(", ", $fmethods->getSpecificInformation($name, "Captains"))),
                                (empty($fmethods->getSpecificInformation($name, "Members")) ? "None" : implode(", ", $fmethods->getSpecificInformation($name, "Members"))),
                                (empty($fmethods->getSpecificInformation($name, "Allies")) ? "None" : implode(", ", $fmethods->getSpecificInformation($name, "Allies")))
                            ], 
                            $lang["FACTION_INFORMATIONS"]
                        )
                    );

                } else {

                    /*
                     * The sender try to see his faction informations
                     */

                    /*
                     * The sender is not on a faction
                     */

                    if (!$sender->hasFaction()) {
                        $sender->sendMessage($lang["NO_FACTION"]);
                        return true;
                    }

                    /*
                     * If the sender have a faction
                     */
                    $name = $sender->getFaction();

                    $sender->sendMessage(
                        str_replace(
                            [
                                "[NAME]",
                                "[DESCRIPTION]",
                                "[POWER]",
                                "[BALANCE]",
                                "[KILLS]",
                                "[DATE]",
                                "[LEADER]",
                                "[CAPTAINS]",
                                "[MEMBERS]",
                                "[ALLIES]"
                            ],
                            [
                                $name,
                                $fmethods->getSpecificInformation($name, "Description"),
                                $fmethods->getSpecificInformation($name, "Power"),
                                $fmethods->getSpecificInformation($name, "Balance"),
                                $fmethods->getSpecificInformation($name, "Kills"),
                                $fmethods->getSpecificInformation($name, "Date"),
                                $fmethods->getSpecificInformation($name, "Leader"),
                                (empty($fmethods->getSpecificInformation($name, "Captains")) ? "None" : implode(", ", $fmethods->getSpecificInformation($name, "Captains"))),
                                (empty($fmethods->getSpecificInformation($name, "Members")) ? "None" : implode(", ", $fmethods->getSpecificInformation($name, "Members"))),
                                (empty($fmethods->getSpecificInformation($name, "Allies")) ? "None" : implode(", ", $fmethods->getSpecificInformation($name, "Allies")))
                            ],
                            $lang["FACTION_INFORMATIONS"]
                        )
                    );

                }

            }

            /*
             * This statement allow a player to speak together his faction mates
             */

            if ($args[0] === "chat") {

                /*
                 * The sender is not on a faction
                 */

                if (!$sender->hasFaction()) {
                    $sender->sendMessage($lang["NO_FACTION"]);
                    return true;
                }

                /*
                 * Check if the sender have already did the command
                 */

                if ($sender->hasFactionChat()) {

                    $sender->removeFactionChat();
                    $sender->sendMessage($lang["EXITED_FACTION_CHAT"]);
                    return true;
                }

                $sender->addFactionChat();
                $sender->sendMessage($lang["ENTERED_FACTION_CHAT"]);

            }

            /*
             * This statement let us to set a description to your faction.
             */

            if ($args[0] === "desc") {

                /*
                 * See if the player has a faction.
                 */

                if (!$sender->hasFaction()) {
                    $sender->sendMessage($lang["NO_FACTION"]);
                    return true;
                }

                /*
                 * Check if the player has the permision.
                 */

                if ($sender->getFactionRank() !== "Leader") {
                    $sender->sendMessage($lang["INVALID_PERM"]);
                    return true;
                }

                /*
                 * Check if the description is empty.
                 */

                if (empty($args[1])) {
                    $sender->sendMessage($lang["EMPTY_DESC"]);
                    return true;
                }

                if (!empty($args[1]) && ctype_alnum($args[1])) {

                    $sender->sendMessage($lang["DESC_TIMER"]);
                    $fmethods->desc[$sender->getName()] = time() + 45;

                } else {

                    $sender->sendMessage($lang["WRONG_FORMAT"]);

                }

            }

        } else {
            $sender->sendMessage("Please run this command in-game.");
            return true;
        }
    }
}