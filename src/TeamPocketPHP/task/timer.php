<?php

namespace TeamPocketPHP\task;

use TeamPocketPHP\Main;
use pocketmine\scheduler\PluginTask;
use pocketmine\utils\Config;
use pocketmine\utils\TextFormat;

class timer extends PluginTask{
    private $plugin;
	public $timer = 0;

    public function __construct(Main $owner, $game){
        $this->plugin = $owner;
		$this->game = $game;
		$this->gameData = $this->plugin->games[$this->game];
        parent::__construct($owner);
    }
	
    public function OnRun($tick){
		if($this->gameData->status == "waiting"){
			foreach($this->gameData->players as $sender)
			$sender->sendPopup("Esperando " .floor( $this->gameData->maxPlayerCount - $this->gameData->arrayCount()). " Jugadores");
			if($this->gameData->arrayCount() == $this->gameData->minPlayerCount){
				$this->gameData->status = "starting";
				$this->timer = 10;
			}
			}elseif($this->gameData->status == "starting"){
				foreach($this->gameData->players as $sender)
				$sender->sendPopup("comenzando en {$this->timer}");
				if($this->timer == 0){
					$this->gameData->status = "running";
					/* solo un test, sera customizable */
					$this->timer = 900;
				}
				}elseif($this->gameData->status == "running"){
					foreach($this->gameData->players as $sender)
					$sender->sendPopup($this->timer);
					if($this->timer == 0){
						$this->gameData->stopGame();
		            }
				}
			$this->timer -= 1;
		}
	}