<?php

namespace syouyu\server_core;

use JetBrains\PhpStorm\Pure;
use pocketmine\permission\Permission;
use pocketmine\permission\PermissionManager;
use pocketmine\Server;
use syouyu\loggersystem_syouyu_server\command\checkLog;
use syouyu\loggersystem_syouyu_server\LoggerSystemAPI;
use syouyu\moneysystem_syouyu_server\command\addMoney;
use syouyu\moneysystem_syouyu_server\command\endPos;
use syouyu\moneysystem_syouyu_server\command\land;
use syouyu\moneysystem_syouyu_server\command\minusMoney;
use syouyu\moneysystem_syouyu_server\command\myMoney;
use syouyu\moneysystem_syouyu_server\command\setMoney;
use syouyu\moneysystem_syouyu_server\command\showPayedHistory;
use syouyu\moneysystem_syouyu_server\command\startPos;
use syouyu\moneysystem_syouyu_server\JobSystemAPI;
use syouyu\moneysystem_syouyu_server\LandAPI;
use syouyu\moneysystem_syouyu_server\MoneySystemAPI;
use syouyu\senddiscordsystem_syouyu_server\async;
use syouyu\senddiscordsystem_syouyu_server\PlayerChat;
use syouyu\server_core\event\PlayerJoin;

class Main extends \pocketmine\plugin\PluginBase{

	/** @var Main  */
	public static Main $instance;

	protected function onEnable() : void{
		$this->registerEvent();
		PermissionManager::getInstance()->addPermission(new Permission("op"));
		self::$instance = $this;
		$api = new MoneySystemAPI();
		$api->__load($this);
		$api = new LoggerSystemAPI();
		$api->__load($this);
		$api = new LandAPI();
		$api->__load($this);
		$api = new JobSystemAPI();
		$api->__load($this, $this->getFile());
		$this->registerCommands();
		Server::getInstance()->getAsyncPool()->submitTask(new async("起動", "サーバーが起動しました。"));
	}

	public function onDisable() : void{
		LoggerSystemAPI::getInstance()->onDisable();
		Server::getInstance()->getAsyncPool()->submitTask(new async("終了", "サーバーがシャットダウンしました。"));
	}

	#[Pure] public function resource() : string{
		return $this->getFile()."resources/";
	}

	public function registerCommands(){
		$this->getServer()->getCommandMap()->registerAll("syouyu-server", [
			new addMoney(),
			new endPos(),
			new land(),
			new minusMoney(),
			new myMoney(),
			new setMoney(),
			new showPayedHistory(),
			new startPos(),
			new checkLog(),
		]);
	}

	public static function getInstance(): self{
		return self::$instance;
	}

	function registerEvent(): void{
		$manager = $this->getServer()->getPluginManager();
		$manager->registerEvents(new PlayerJoin(), $this);
		$manager->registerEvents(new PlayerChat(), $this);
	}
}
