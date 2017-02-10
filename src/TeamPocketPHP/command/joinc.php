<?php

namespace TeamPocketPHP\command;

use TeamPocketPHP\Main;
use pocketmine\command\Command;
use pocketmine\command\CommandExecutor;
use pocketmine\command\CommandSender;
use pocketmine\Player;
use pocketmine\utils\Config;
use pocketmine\math\Vector3;

class joinc implements CommandExecutor{

    private $owner;
    public function __construct($owner){
		$this->plugin = $owner;
    }

    public function onCommand(CommandSender $sender, Command $cmd, $label, array $args){
        $fcmd = $cmd->getName();
			 if($fcmd == "join"){
				if($sender instanceof Player){
					$this->plugin->gameMannager->joinGame($sender, $args[0]);
				}
			}	
		}
	}