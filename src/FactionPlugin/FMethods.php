<?php

namespace FactionPlugin;

class FMethods {

	private $factions; #Data file
	private $players_factions; #Data file

	public function __construct()
	{
        $this->players_factions = new Config($this->getDataFolder() . "players_factions.yml", Config::YAML);
        $this->factions = new Config($this->getDataFolder() . "factions.yml", Config::YAML);
	}

	public function existFaction(string $name) : bool
	{
		if(in_array($name, $this->factions->getAll()))
			return true
		else
			return false
	}

	public function createFaction(string $name)
	{
		$form = [
            "Home" => "", #Coords of the faction home (middle coords X/Z)
            "Date of creation" => [
                "Day" => date("d"), #Day
                "Month" => date("m"), #Month
                "Year" => date("Y"), #Year

                "Second" => date("s"), #Second
                "Minute" => date("i"), #Minute
                "Hour" => date("g A"), #Hour (12 Hours Format)
            ],

            "Leader" => $this->getName(), #Name of the leader
            "Captains" => [], #Array of Captains
            "Members" => [], #Array of Members
            "Allies" => [], #Array of all allies

            "Power" => "0", #Faction Power
            "Balance" => "0", #Faction balance
            "Kills" => "0" #Number of kill (all members summered)
        ];

        $this->factions->set($name, $form);
	}

	public function removeFaction(string $name)
	{
		foreach($this->factions->get($name) as $informations => $value)
        {
            if($informations === "Members")
            {
                foreach($informations as $member)
                {
                    $this->player_form->set($member["Faction"], null);
                }
            }

            if($informations === "Captains")
            {
                foreach($informations as $captain)
                {
                    $this->player_form->set($captain["Faction"], null);
                }
            }

            if($informations === "Allies")
            {
                foreach($informations as $ally)
                {
                	$allies = $this->factions->get($ally["Allies"]);
                    unset($allies[$name]);
                }
            }

        }
        $this->factions->remove($name);        
	}

}
