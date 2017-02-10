<?php

namespace TeamPocketPHP\command;

use TeamPocketPHP\Main;
use pocketmine\command\Command;
use pocketmine\command\CommandExecutor;
use pocketmine\command\CommandSender;
use pocketmine\Player;
use pocketmine\utils\Config;
use pocketmine\math\Vector3;

class setarena implements CommandExecutor{

    private $owner;
    public function __construct($owner){
		$this->plugin = $owner;
    }

    public function onCommand(CommandSender $sender, Command $cmd, $label, array $args){
        $fcmd = $cmd->getName();
			 if($fcmd == "usimulator" and $sender->isop()){
				if($sender instanceof Player){
					$this->game = new Config($this->plugin->getDataFolder() . 'games/'.$args[1].".yml", Config::YAML);
					$x = (int)$sender->getx();
					$y = (int)$sender->gety();
					$z = (int)$sender->getz();
					$level = $sender->getlevel()->getname();
				}
				
				if($args[0] == "create"){
					$this->game->setNested($args[1], ["level" => $level, "max-slots" => 16, "min-slots" => 2]);
					$this->game->save();
					
				}elseif($args[0] == "set"){
					if(is_file($this->plugin->getDataFolder() .'games/' . $args[1].".yml")){
						$this->game->setNested($args[2], [
						"x" => $x,
						"y" => $y,
						"z" => $z]);
						
						$this->game->setNested($args[1] . '.level',$level);
						$this->game->save();
					}	
				}
			}
		}
	}