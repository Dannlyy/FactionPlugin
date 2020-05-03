<?php

namespace FactionPlugin\lang;

use pocketmine\utils\TextFormat as Color;

class BaseLang {

	/*
	 * This function return the translate of all messages
	 */

	public static function translate()
	{
		return [

            "FACTION_HELP" =>
                "§6--} Faction help" . Color::EOL .
                "§6/f create [name] §r-> §6Create your own faction" . Color::EOL .
                "§6/f delete §r-> §6Delete your own faction" . Color::EOL .
                "§6/f info §r-> §6See a faction information" . Color::EOL .
                "§6/f top §r-> §6Get the classement of Factions.",

            "FACTION_INFORMATIONS" =>
                "§6--} Informations about [NAME] faction" . Color::EOL .
                "§6Description : [DESCRIPTION]" . Color::EOL .
                "§6Power : [POWER] §r| §6Balance : [BALANCE] §r| §6Kills : [KILLS]" . Color::EOL .
                "§6Created at [DATE]" . Color::EOL .
                "§6Leader : [LEADER]" . Color::EOL .
                "Captains : [CAPTAINS]" . Color::EOL .
                "Members : [MEMBERS]" . Color::EOL .
                "Allies : [ALLIES]",

            "EXITED_FACTION_CHAT" => "§6--} §r§2Faction chat has been disabled !",
            "ENTERED_FACTION_CHAT" => "§6--} §r§4Faction chat has been enabled !",
            "FACTION_CHAT_PREFIX" => "§6[§rFaction§6] §6",

            "EMPTY_DESC" => "§6--} §4Please write your description (55 characters maximum)",
            "DESC_TOO_LONG" => "§6--} §4Your description is too long (55 characters maximum)",
            "DESC_ADDED_SUCCESS" => "§6--} §4The description of the fac has been changed successfully",
            "DESC_TIMER" => "§6--} §4You have 45 seconds to write the description.",
            "DESC_TIMEOUT" => "§6--} §4Time out, use §6/f desc §4to write the description again.",

            "WRONG_FORMAT" => "§6--} §4Please enter the right format.",
            "WRONG_LIST_TYPE" => "§6--} §4Please choose §6Kills §4or §6Balance §4to get the list.",


            "CREATED_SUCCESS" => "§6--} Faction §r§4[NAME] §r§6has been §r§2created",
            "ALERT_CREATED_SUCCESS" => "§6--} §r§4[PLAYER] §r§6have §r§2created§r§6 the faction §r§4[NAME]",
            "NAME_TOO_LONG" => "§6--} §r§4The entered name is too long (more than 12 characters)",

            "DELETED_SUCCESS" => "§6--} Faction §r§4[NAME] §r§6has been §r§4deleted",
            "ALERT_DELETED_SUCCESS" => "§6--} §r§4[PLAYER] §r§6have §r§4deleted§r§6 the faction §r§4[NAME]",

            "ERROR" => "§6--} §r§4An error occured, please try again",
            "INVALID_PERM" => "§6--} §r§4You don't have the right permission for do that",
            "INVALID_COMMAND" => "§6--} §r§4Invalid command",
            "INVALID_FAC_NAME" => "§6--} §r§4Invalid faction name",
            "ALREADY_IN_FAC" => "§6--} §r§4Please leave / delete your faction first",
            "NO_FACTION" => "§6--} §r§4You are not in a faction !",
            "ALREADY_EXIST" => "§6--} §r§4This faction already exist",
        ];
	}


}
