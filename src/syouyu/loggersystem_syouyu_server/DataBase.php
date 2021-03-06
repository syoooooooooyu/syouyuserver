<?php

namespace syouyu\loggersystem_syouyu_server;

use pocketmine\item\ItemFactory;
use pocketmine\player\Player;
use syouyu\server_core\Main;

class DataBase extends \SQLite3{

	/** @var self */
	private static self $instance;
	/** @var Main */
	private Main $main;

	public function __construct(Main $main){
		parent::__construct($main->getDataFolder()."LoggerSystem/LoggerSystem.db", SQLITE3_OPEN_READWRITE|SQLITE3_OPEN_CREATE);
		$sql = "CREATE TABLE IF NOT EXISTS logdata (xyz TEXT PRIMARY KEY, who TEXT , action TEXT, time TEXT, id INT,meta INT)";
		$this->query($sql);
		$this->main = $main;
		self::$instance = $this;
	}

	public function registerLog(int $x, int $y, int $z, string $level, int $id, int $meta,Player $player,String $eventType){
		$xyz ="x{$x}y{$y}z{$z}w{$level}";
		$who = $player->getName();
		$time = date("Y/m/d-H:i:s");
		$this->query("INSERT OR REPLACE INTO logdata VALUES('$xyz',   '$who',  '$eventType', '$time', '$id','$meta')");
	}

	public function checkLog(int $x, int $y, int $z, string $level, Player $player){
		$xyz ="x{$x}y{$y}z{$z}w{$level}";
		$result = $this->query("SELECT who, action, id, meta, time FROM logdata WHERE xyz = '$xyz'");
		$results = $result->fetchArray(SQLITE3_ASSOC);
		if(!isset($results["who"])){
			$player->sendPopup("[{$this->main->getName()}]{$x},{$y},{$z},{$level} ここにログは存在していません");
		}else{
			if($result){
				if($results['action'] === "b"){
					$pb = "破壊";
				}else{
					$pb = "設置";
				}
				$itemname = ItemFactory::getInstance()->get($results['id'], $results['meta'], 1)->getName();
				$player->sendPopup("§c[座標] {$x},{$y},{$z},{$level}\n[日時] {$results['time']}\n[行動者] {$results['who']}\n[行動]{$pb}\n[物] {$results['id']}:{$results['meta']} {$itemname}");
			}
		}
	}

	public static function getInstance(): self {
		return self::$instance;
	}
}