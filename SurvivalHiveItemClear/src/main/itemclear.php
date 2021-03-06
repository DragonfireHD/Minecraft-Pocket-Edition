<?php

namespace main;

use pocketmine\utils\TextFormat as MT;
use pocketmine\permission\Permission;
use pocketmine\Player;
use pocketmine\Server;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\IPlayer;
use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;
use pocketmine\level\Level;
use pocketmine\level\Position;
use pocketmine\plugin\Plugin;
use pocketmine\utils\Config;

class itemclear extends PluginBase implements Listener 
{
	public function onEnable()
	{
		$this->getServer()->getPluginManager()->registerEvents($this, $this);
		$this->getLogger()->info(MT::AQUA."-=SH=-ItemClear Plugin loading...!");
		$this->saveDefaultConfig();
		$cfg = $this->getConfig();
		$this->permissions = $cfg->get('Permissions');
	}
	
	public function onCommand(CommandSender $sender, Command $command, $label, array $args) 
	{
		switch(strtolower($command->getName()))
		{
			case "itemclear":
				if($this->permissions == true)
				{
					if($sender->isOp() || $sender->hasPermission('survivalhive.itemclear'))
					{
						$this->onItemclear($sender);
						return true;
					}
					else
					{
						$sender->sendMessage(MT::RED.'You dont have the permissions to use this command!');
						return true;
					}
				}
				else
				{
					$this->onItemclear($sender);
					return true;
				}	
				break;
		}
	}
	
	public function onItemclear($sender)
	{
		foreach($this->plugin->getServer()->getLevels() as $level)
		{
			$levelname = $level->getName();
			foreach($this->plugin->getServer()->getLevelbyName($levelname)->getEntities() as $entity)
			{
				if(!$entity instanceof Player){$entity->kill();}
			}
		}
		$sender->sendMessage(MT::RED.'All entities cleared');
	}
	
	public function onDisable()
    {
    	$this->getLogger()->info(MT::AQUA."Plugin unloaded!");
    }
}