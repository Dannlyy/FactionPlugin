<?php

namespace FactionPlugin\commands;

use FactionPlugin\Core;
use pocketmine\Server;
use pocketmine\command\PluginCommand;

date_default_timezone_set("UTC");

class FactionCommand extends PluginCommand {

    private $factions; //Data file
    private $fac_form; //Form for register a faction
    private $player_form; //Form for register faction into player's faction data

    public function __construct(Core $plugin)
    {
        parent::__construct("f", $plugin);
        $this->setDescription("Cette commande permet d'avoir accés au commandes de Faction.");
        $this->factions = new Config($this->getDataFolder() . "factions.yml", Config::YAML);
        $this->players_factions = new Config($this->getDataFolder() . "players_factions.yml", Config::YAML);
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args) : bool 
    {
        $lang = BaseLang::translate();

    	if(sizeof($args) === 0)
    	{
    		$sender->sendMessage($lang["INVALID_COMMAND"]);
            return true;
    	}

        /*
         * This statement return the help message
         */

        if($args[0] === "help")
        {
            $sender->sendMessage($lang["FACTION_HELP"]);
            return true;
        }

        /*
         * This statement allow a player to create a faction
         */

        if($args[0] === "create")
        {

            /*
             * If we have a name and args[1] (name) is alphanumeric
             */

            if($args[1] && ctype_alnum($args[1]))
            {
                $name = $args[1];

                /*
                 * Faction already exist
                 */
                if(in_array($name, $this->factions->getAll()))
                {
                    $sender->sendMessage($lang["ALREADY_EXIST"]);
                    return true;
                }

                if($this->players_factions->get($sender->getName())["Faction"] != "")
                {
                    $sender->sendMessage($lang["ALREADY_IN_FAC"]);
                    return true;
                }

                /*
                 * If the faction name is valid and doesn't already exist
                 */

                $this->fac_form = [
                    "Home" => "", #Coords of the faction home (middle coords X/Z)
                    "Date of creation" => [
                        "Day" => date("d"), #Day
                        "Month" => date("m"), #Month
                        "Year" => date("Y") #Year

                        "Second" => date("s") #Second
                        "Minute" => date("i"), #Minute
                        "Hour" => date("g A"), #Hour (12 Hours Format)
                    ],

                    "Leader" => $sender->getName(), #Name of the leader
                    "Captains" => [] #Array of Captains
                    "Members" => [], #Array of Members
                    "Allies" => [], #Array of all allies

                    "Power" => "0", #Faction Power
                    "Balance" => "0", #Faction balance
                    "Kills" => "0" #Number of kill (all members summered)
                ];
                $this->player_form = [
                    "Faction" => $name
                ];

                $this->factions->set($name, $this->fac_form);
                $this->player_form->set($sender->getName(), $this->player_form);

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

        if($args[0] === "delete")
        {

            /*
             * If we have a name and args[1] (name) is alphanumeric
             */

            if($args[1] && ctype_alnum($args[1]))
            {
                $name = $args[1];

                /*
                 * Invalid faction ?
                 */
                if(!in_array($name, $this->factions->getAll()))
                {
                    $sender->sendMessage($lang["ERROR"]);
                    return true;
                }

                /*
                 * The sender is not on a faction
                 */
                if($this->players_factions->get($sender->getName())["Faction"] == "")
                {
                    $sender->sendMessage($lang["NO_FACTION"]);
                    return true;
                }

                /*
                 * The sender is not the leader
                 */
                if($this->factions->get($name)["Leader"] !== $sender->getName())
                {
                    $sender->sendMessage($lang["INVALID_PERM"]);
                    return true;
                }

                /*
                 * If the sender is the leader and the faction is valid
                 */
                foreach ($this->factions->get($name) as $informations => $value)
                {
                    if($informations === "Members")
                    {
                        foreach ($informations as $member)
                        {
                            $this->player_form->set($member["Faction"], "");
                        }
                    }

                    if($informations === "Captains")
                    {
                        foreach ($informations as $captain)
                        {
                            $this->player_form->set($captain["Faction"], "");
                        }
                    }

                    if($informations === "Allies")
                    {
                        foreach ($informations as $ally)
                        {
                            $allies = $this->factions->get($ally["Allies"]);
                            unset($allies[$name]);
                        }
                    }

                }
                $this->factions->remove($name);
                $this->player_form->set($sender->getName()["Faction"], "");

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

            /*
             * The name is not valid
             */

            } else {
                $sender->sendMessage($lang["INVALID_FAC_NAME"]);
            }
        }

    }
}