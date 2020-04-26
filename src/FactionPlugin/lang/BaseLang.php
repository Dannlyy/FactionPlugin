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
                "§6/f delete §r-> §6Delete your own faction",

            "CREATED_SUCCESS" => "§6--} Faction §r§4[NAME] §r§6has been §r§2created",
            "ALERT_CREATED_SUCCESS" => "§6--} §r§4[PLAYER] §r§6have §r§2created§r§6 the faction §r§4[NAME]",

            "DELETED_SUCCESS" => "§6--} Faction §r§4[NAME] §r§6has been §r§4deleted",
            "ALERT_DELETED_SUCCESS" => "§6--} §r§6Your faction §4[NAME] §6has been §r§4deleted§r§6 successfully",

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
