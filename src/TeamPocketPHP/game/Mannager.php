<?php

	/* TODO
		-sistema de espectadores
		-sistema de estadisticas
	*/
		

namespace TeamPocketPHP\game;

use pocketmine\utils\Config;
use pocketmine\level\Position;
use pocketmine\math\Vector3;

use TeamPocketPHP\task\timer;
use TeamPocketPHP\Main;

class Mannager{

    public $players = [];
    public $spectators = [];
    
    public $map;
    public $status = "waiting";
    
    public $maxPlayerCount = null;
    public $minPlayerCount = null;

	public $taskid = null;
	
    public function __construct(Main $main, $game){
		$this->plugin = $main;
		$this->game = $game;
		$this->data = yaml_parse_file($this->getPath());
		
		$this->map = $this->data[$this->game]['level'];
		$this->maxPlayerCount = $this->data[$this->game]['max-slots'];
		$this->minPlayerCount = $this->data[$this->game]['min-slots'];
    }
	
	public function getPath(){
        return $this->plugin->getDataFolder() . "games/".strtolower($this->game) . ".yml";
    }
	
	
	public function joinLobby($sender){
		if($this->arrayCount() < $this->maxPlayerCount){
			if(!$this->isplaying($sender)){
				if(!$this->isStarted()){
					$this->addPlayer($sender);
					$this->startTask();
				}else{
					$sender->sendMessage("Ya comenzo");
				}
			}else{
				$sender->sendMessage("ya estas jugando");
			}
	}else {
		$sender->sendMessage("Full");
		}
	}
	
	public function isStarted(){
		if($this->status == "waiting"){
		    return false;
		}else{
			return true;
		}
	}
	
	public function isStarting(){
		if($this->status == "starting"){
		    return true;
		}else{
			return false;
		}
	}
	
	public function isPlaying($sender){
		if(in_array($sender, $this->players)){
		    return true;
		}else{
			return false;
		}
	}
	
	public function addPlayer($sender){
		array_push($this->players, $sender);
		$this->teleportTo($sender, $this->getPos());
		$this->plugin->playing[$sender->getName()] = $this->game;
		//$this->plugin->kit->getkit($sender);
	}
	
	public function removePlayer($sender){
		if($this->isPlaying($sender)){
			unset($this->players[array_search($sender, $this->players)]);
			unset($this->plugin->playing[$sender->getName()]);
		}
		$this->detectStop();
	}
	
	public function detectStop(){
		if($this->isStarted() and $this->arrayCount() == 1){
			foreach($this->plugin->getServer()->getOnlinePlayers() as $online) foreach($this->players as $sender)
			$online->sendMessage($sender->getName() . ' gano');
			$this->stopGame();
		}
	}
	
	public function stopGame(){
		foreach($this->players as $senders){
			$senders->teleport($this->plugin->getServer()->getDefaultLevel()->getSpawnLocation());
			unset($this->players[array_search($senders, $this->players)]);
			$senders->getInventory()->clearAll();
			$senders->removeAllEffects();
			$senders->setHealth(20);
		}
		$this->stopTask();
		/* cerrar juego */
		unset($this->plugin->gameData[$this->game]);
	}
  
	public function startTask(){
		if($this->taskid == null){
			$task = new timer($this->plugin, $this->game);
			$handler = $this->plugin->getServer()->getScheduler()->scheduleRepeatingTask($task, 1 * 20);
			$this->taskid = $task->getTaskId();
			$task->sethandler($handler);
		}
	}
	
	public function stopTask(){
		$this->plugin->getServer()->getScheduler()->cancelTask($this->taskid);
	}
	
	public function getPos(){
		return 'pos'.$this->arrayCount();
	}
	
	public function teleportTo($sender, $area = null){
		$this->plugin->getServer()->loadLevel($this->map);
		$lvl = $this->plugin->getServer()->getLevelByName($this->map);	
		$sender->teleport(new position($this->data[$area]["x"], $this->data[$area]["y"], $this->data[$area]["z"], $lvl));
	}
	
	public function arrayCount(){
		return count($this->players);
	}
}