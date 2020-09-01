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
                "§9[§6Help§9] §fListe des commandes de faction :\n§7------" . Color::EOL .
                "§9- §6/f create (name) §f-> §6Créer votre propre faction." . Color::EOL .
                "§9- §6/f delete §f-> §6Supprimer votre propre faction." . Color::EOL .
                "§9- §6/f info §f-> §6Voir les informations d'une faction ou de la votre." . Color::EOL .
                "§9- §6/f desc §f-> §6Ajouter une description pour votre faction." . Color::EOL .
                "§9- §6/f chat/allychat §f-> §6Activer ou désactiver le chat de faction ou alliés." . Color::EOL .
                "§9- §6/f top (power/level) §f-> §6Voir le classement de Factions." . Color::EOL
                "§7------",

            "FACTION_INFORMATIONS" =>
                "§9[§6Faction§9] §fInformations à-propos la faction §6[NAME] §f:" . Color::EOL .
                "§9- §fCréer le §6[DATE]" . Color::EOL .
                "§9- §fDescription : §6[DESCRIPTION]" . Color::EOL .
                "§7-----" . Color::EOL .
                "§9- §fPower : §6[POWER]" Color::EOL .
                "§9- §fLevel : §6[LEVEL] §r| §fPoints : §6[POINTS]" . Color::EOL .
                "§9- §fKills : §6[KILLS]" . Color::EOL .
                "§9- §fLeader : §6[LEADER]" . Color::EOL .
                "§9- §fCaptains : §6[CAPTAINS]" . Color::EOL .
                "§9- §fMembers : §6[MEMBERS]" . Color::EOL .
                "§9- §fAllies : §6[ALLIES]",

            "EXITED_FACTION_CHAT" => "§9[§6Faction§9] §fVous avez désactivé §6le chat §fde faction avec succés !",
            "ENTERED_FACTION_CHAT" => "§9[§6Faction§9] §fVous avez activé le chat de faction avec succés !",
            "FACTION_CHAT_PREFIX" => "§6[§rFaction§6] §6",
            "FACTION_NO_FIGHT" => "§9- §6Vous êtes dans la même faction §9-",

            "EXITED_ALLY_CHAT" => "§9[§6Faction§9] §fVous avez désactivé §6le chat des ally §favec succés !",
            "ENTERED_ALLY_CHAT" => "§9[§6Faction§9] §fVous avez activé §6le chat des ally §favec succés !",
            "ALLIES_NO_FIGHT" => "§9- §6Vous êtes des allié ! §9-",

            "EMPTY_DESC" => "§9[§6Faction§9] §fVeuillez entrer une description !",
            "DESC_TOO_LONG" => "§9[§6Faction§9] §fLe nom que vous avez choisis est trop long (§655 characters maximum§f)",
            "DESC_ADDED_SUCCESS" => "§9[§6Faction§9] §fVous avez mis une description pour votre faction avec succés !",
            "DESC_TIMER" => "§9[§6Faction§9] §fVous avez §645 seconde(s) §fpour écrire votre description !",
            "DESC_TIMEOUT" => "§9[§6Faction§9] §fLe temps est écoulé ! refaites §6/f desc §fpour écrire à nouveau votre description.",

            "WRONG_FORMAT" => "§9[§6Faction§9] §fVeuillez entrer la bonne format !",
            "WRONG_LIST_TYPE" => "§9[§6FAction§9] §fEntrer §6/f top (power/level) §fpour voir le classement de faction !",


            "CREATED_SUCCESS" => "§9[§6Faction§9] §6[PLAYER] §fa crée la faction §6[NAME] §f!",
            "NAME_TOO_LONG" => "§9[§6Faction§9] §fLe nom que vous avez choisis est trop long (§614 characters maximum§f)",

            "DELETED_SUCCESS" => "§9[§6Faction§9] §fVous avez supprimé votre faction §6[NAME] §favec succés !",
            "ALERT_DELETED_SUCCESS" => "§9[§6Faction§9] §6[PLAYER] §fa supprimé la faction §6[NAME] §f!",

            "ERROR" => "§9[§6Faction§9] §7Une erreur a eu lieu, réessaiez encore une fois !",
            "INVALID_PERM" => "§9[§6Faction§9] §7Vous n'avez pas la permission d'exécuter cette commande !",
            "INVALID_COMMAND" => "§9[§6Faction§9] §7Cette commande n'existe pas, faites §6/f help§7 pour avoir une liste de commandes.",
            "INVALID_FAC_NAME" => "§9[§6Faction§9] §7Le nom que vous avez choisi n'existe pas, réessaiez un autre nom !",
            "ALREADY_IN_FAC" => "§9[§6Faction§9] §7Veuillez quitter votre faction pour pouvoir exécuter cette commande !",
            "NO_FACTION" => "§9[§6Faction§9] §7Créer une faction en faisant §6/f create (nom) §fpour pouvoir exécuter cette commande !",
            "ALREADY_EXIST" => "§9[§6Faction§9] §7Cette faction existe déja !",
        ];
	}


}
