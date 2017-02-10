<?php

namespace TeamPocketPHP;

use pocketmine\plugin\PluginBase;
use pocketmine\utils\TextFormat;
use pocketmine\Server;
use pocketmine\Player;
use pocketmine\command\PluginCommand;

use TeamPocketPHP\game\game;
use TeamPocketPHP\command\setarena;
use TeamPocketPHP\command\joinc;

class Main extends PluginBase{
	
	public $games = [];
	public $playing = [];
	
	public function onEnable(){
		@mkdir($this->getDataFolder());
		$this->register();
		$this->addNewCommands();
	}
	
	public function register(){
		$this->gameMannager = new game($this);
		$this->eventlistener = new EventListener($this);
	}
	
	public function addNewCommands(){
		$this->registerc(['usimulator'],new setarena($this));
		$this->registerc(['join'],new joinc($this));
	}
	
	public function registerc($cmd = [], $listener){
		foreach($cmd as $c){
		$r = new PluginCommand($c,$this);
		$r->setExecutor($listener);
		$r = $this->getServer()->getCommandMap()->register($c,$r);
		}
	}
}