<?php

namespace syouyu\loggersystem_syouyu_server;

use pocketmine\player\Player;
use SQLite3;
use syouyu\loggersystem_syouyu_server\command\checkLog;
use syouyu\loggersystem_syouyu_server\event\PlayerEvent;
use syouyu\server_core\Main;

class LoggerSystemAPI{
	/** @var LoggerSystemAPI  */
	private static LoggerSystemAPI $instance;
	/** @var SQLite3 */
	private SQLite3 $log;
	/** @var array  */
	private array $isOn = [];

	public function __load(Main $plugin){
		if(!file_exists($plugin->getDataFolder()."LoggerSystem")) mkdir($plugin->getDataFolder()."LoggerSystem");
		$this->log = new DataBase($plugin);
		$plugin->getServer()->getPluginManager()->registerEvents(new PlayerEvent(), $plugin);
		self::$instance = $this;
	}

	public static function getInstance():self{
		return self::$instance;
	}

	public function setOn(Player $player, bool $bool){
		$this->isOn[$player->getName()] = $bool;
	}

	public function isOn(Player $player):bool {
		if(isset($this->isOn[$player->getName()])){
			return $this->isOn[$player->getName()];
		}else{
			return false;
		}
	}

	public function getDB(): SQLite3{
		return $this->log;
	}

	public function onDisable() {
		$this->log->close();
	}
}