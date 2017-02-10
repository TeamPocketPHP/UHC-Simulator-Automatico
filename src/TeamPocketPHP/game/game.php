<?php

namespace TeamPocketPHP\game;

use pocketmine\Server;
use pocketmine\Player;

use TeamPocketPHP\Main;

class game{
	
	private $owner;
	
	public function __construct(Main $owner){
		$this->plugin = $owner;
	}
	
	public function createGameData($game){
		$this->plugin->games[$game] = new Mannager($this->plugin, $game);
	}
	
	public function joinGame($sender, $game){
		if(!isset($this->plugin->games[$game]) and is_file($this->plugin->getDataFolder() ."games/".strtolower($game) . ".yml")){
			$this->createGameData($game);
		}
		$this->plugin->games[$game]->joinLobby($sender);
	}
	
	public function getPlayerGame($sender){
		if(isset($this->plugin->playing[$sender->getName()])){
			return $this->plugin->playing[$sender->getName()];
		}
	}
	
	public function isPlaying($sender){
		if(isset($this->plugin->games[$this->getPlayerGame($sender)])){
			return $this->plugin->games[$this->getPlayerGame($sender)]->isPlaying($sender);
		}
	}
}	