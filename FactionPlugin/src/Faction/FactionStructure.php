<?php

namespace Faction;


use pocketmine\Server;
use pocketmine\utils\Config;
use Faction\FactionCore;

use pocketmine\Player;


class FactionStructure 
{



    /*
     * This function allow us to create for each Faction a file where we found the informations
     */

    public static function getFile($configName)
    {
        return new Config(FactionCore::getInstance()->getDataFolder() . "Factions/" . $configName, Config::JSON);
    }

    /*
     * Here we can create a faction.
     */

     public function createFaction(Player $player, $name)
     {

        $directory = FactionCore::getInstance()->getDataFolder() . "Factions/";


        if($this->hasFaction($player))
        {

            return $player->sendMessage(FactionCore::ERROR . "Vous avez déja une faction, donc vous ne pouvez pas en créer une autre !");

        }

        if($this->factionExist($name))
        {

            return $player->sendMessage(FactionCore::ERROR . "Cette faction existe déja, veuillez choisir un autre nom");

        }

        if(strlen($name) >= 12)
        {
            return $player->sendMessage(FactionCore::ERROR . "Le nom que vous avez rentré a plus ou égale à 12 characteres, veuillez choisir un nom plus petit.");
        }

        $player->sendMessage(FactionCore::PREFIX . "Vous venez de créer votre nouvel Faction nommée §6{$name}");

        $file = self::getFile($name);
        $form = [

            "name" => $name,
            "description" => null,
            "strenght" => 0,
            "joueurs" => [
                "leader" => [$player->getName()],
                "officers" => [],
                "members" => []
            ],
            "home" => [],
            "allies" => [],
            "claim" => []
        
        ];

        $list = self::getFile("factionPlayers");
        $list->set($player->getName(), $name);
        $list->save();

        $file->set($name, $form);
        $file->save();

     }

     /*
      * Here we can check if a faction exist.
      */

     public function factionExist($faction)
     {

        $directory = self::getFile("factionPlayers");

        if(in_array($faction, array_values($directory->getAll())))
        {
            return true;

        }

        return false;

     }

     /*
      * Here we can delete a faction if it exist.
      */

     public function deleteFaction($faction)
     {

         if($this->factionExist($faction))
         {

             $file = FactionCore::getInstance()->getDataFolder() . "Factions/" . $faction . ".json";
             $listFaction = self::getFile("factionPlayers");

             @unlink(realpath($file));
             $fac = implode("", array_keys($listFaction->getAll(), $faction));

             $listFaction->remove($fac);
             $listFaction->save();

         }

     }

     /*
      * Here we can see a player if he is in a faction.
      */

      public function hasFaction(Player $player)
      {
          if(in_array($player->getName(), self::getFile("factionPlayers")->getAll(true)))
              return true;

          return false;
      }

      /*
       * Here we can get a faction information as an array.
       */

      public function getFaction($faction)
      {

        $directory = FactionCore::getInstance()->getDataFolder() . "Factions/";

        if(self::getFile($faction) !== null)
        {
            return self::getFile($faction);
        }

        return null;

      }

      /*
       * Here we can get the player faction rank.
       */

      public function getFactionRank(Player $player, $faction)
      {

        if($this->hasFaction($player))
        {

            $file = $this->getFaction($faction)->get($faction);

            if(in_array($player->getName(), $file["joueurs"]["leader"]))
            {
                return "**";
            }

            if(in_array($player->getName(), $file["joueurs"]["officers"]))
            {
                return "*";
            }

            return "";

        }

        return "?";

      }

      /*
       * Here we can get a player faction if he have a faction and return null if false.
       */

      public function getPlayerFaction(Player $player)
      {

        $directory = FactionCore::getInstance()->getDataFolder() . "Factions/";

        if($this->hasFaction($player))
        {

            $list = self::getFile("factionPlayers");
            return $list->get($player->getName());

        }

        return null;

      }

      /*
       * Here we can get all player of a faction.
       */

      public function getFactionPlayers($faction)
      {


          $list = self::getFile("factionPlayers");
          return array_keys($list->getAll(), $faction);

      }

      public function sendMessageToFaction($faction, $message)
      {

          $list = $this->getFactionPlayers($faction);

          foreach ($list as $name)
          {
              $player = Server::getInstance()->getPlayer($name);
              $player->sendMessage("§9[§6Faction§9] " . $message);
          }

      }

      /*
       * Here we can invite a player to a faction.
       */

      public function addPlayer(Player $player, $faction)
      {

          $fac = $this->getFaction($faction);
          $players = $fac->get($faction)["joueurs"]["members"];

          $file = self::getFile("factionPlayers");

          if(!$this->hasFaction($player))
          {

              $this->sendMessageToFaction($faction, "§fUn nouveau joueur nommée §6{$player->getName()} §fvient de rejoindre la faction.");

              array_push($players, $player->getName());
              $fac->save();

              $file->set($player->getName(), $faction);
              $file->save();

          }

      }

      /*
       * Here we can remove a faction from a faction
       */

       public function kickPlayer(Player $player, $faction)
       {
        $faction = $this->getFaction($faction);
       }   

       public function promotePlayer(Player $player, $faction)
       {
        $file = $this->getFaction($faction);
       }
     

}