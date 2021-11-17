<?php

namespace syouyu\moneysystem_syouyu_server;

use Exception;
use pocketmine\player\Player;
use pocketmine\utils\Config;
use pocketmine\utils\TextFormat;
use SQLite3;
use syouyu\moneysystem_syouyu_server\command\addMoney;
use syouyu\moneysystem_syouyu_server\command\minusMoney;
use syouyu\moneysystem_syouyu_server\command\myMoney;
use syouyu\moneysystem_syouyu_server\command\setMoney;
use syouyu\moneysystem_syouyu_server\event\PlayerJoin;
use syouyu\server_core\Main;

class MoneySystemAPI{

	/** @var Config|null  */
	protected ?Config $config = null;
	/** @var MoneySystemAPI|null  */
	private static ?MoneySystemAPI $API = null;
	/** @var SQLite3|null  */
	protected ?SQLite3 $sql = null;
	/** @var SQLite3|null  */
	public ?SQLite3 $land = null;
	/** @var Config|null  */
	protected ?Config $landId = null;

	public function __load(Main $plugin){
		self::$API = $this;
		if(!file_exists($plugin->getDataFolder()."MoneySystem")) mkdir($plugin->getDataFolder()."MoneySystem");
		$this->config = new Config($plugin->getDataFolder()."MoneySystem/MoneySystem.yml", Config::YAML);
		new Config($plugin->getDataFolder()."MoneySystem/Id.yml", Config::YAML, [
			"id" => 0
		]);
		$this->land = new SQLite3($plugin->getDataFolder()."MoneySystem/Land.db");
		$this->land->exec("CREATE TABLE IF NOT EXISTS Land(Id INTEGER PRIMARY KEY NOT NULL, Player TEXT NOT NULL, Invite TEXT NOT NULL, Axis TEXT NOT NULL, World TEXT NOT NULL )");
		$this->sql = new SQLite3($plugin->getDataFolder()."MoneySystem/PlayerPayed.db");
		$this->sql->exec("CREATE TABLE IF NOT EXISTS Payed(Id INTEGER PRIMARY KEY NOT NULL ,PlayerFrom TEXT NOT NULL, PlayerTo TEXT NOT NULL, Pay INTEGER NOT NULL, M TEXT NOT NULL, D TEXT NOT NULL)");
		$api = new LandAPI();
		$api->__load($plugin);
		$plugin->getServer()->getPluginManager()->registerEvents(new PlayerJoin(), $plugin);
	}

	public function showPayedHistory(int $limit = 30){
		$sql = $this->sql;
		$stmt = $sql->prepare("SELECT * FROM Payed ORDER BY Id DESC LIMIT $limit");
		$result = $stmt->execute();
		while($res = $result->fetchArray(SQLITE3_ASSOC)){
			Main::getInstance()->getLogger()->info("On ".$res["M"]."/".$res["D"].", ".$res["PlayerFrom"] ." Payed ".$res["PlayerTo"]." ".$res["Pay"]);
		}
	}

	public function isRegistered(Player $player): bool{
		return $this->config->exists($player->getName());
	}

	public function pay(Player|string $from, Player|string $to, int $money):void{
		if($this->getMoney($from) - $money >= 0){
			$this->minusMoney($from, $money);
			$this->plusMoney($to, $money);
			$sql = $this->sql;
			$m = date("m");
			$d = date("d");
			$config = new Config( Main::getInstance()->getDataFolder()."MoneySystem/Id.yml", Config::YAML);
			$id = $config->get("id");
			$config->set("id", $id + 1);
			$config->save();
			$sql->exec("INSERT INTO Payed VALUES((int)'$id','{$from->getName()}', '{$to->getName()}', (int)'$money', '$m', '$d')");
		}else{
			$from->sendMessage(TextFormat::RED."お金が足りません。");
			$to->sendMessage(TextFormat::RED.$from->getName()."さんがあなたに".$money."のお金を支払おうとしましたが、失敗しました。");
		}
	}

	public function getMoney(Player|string $player): int{
		if($player instanceof Player) $player = $player->getName();
		return $this->config->exists($player) ? (int)$this->config->get($player):0;
	}

	public function setMoney(Player|string $player, int $money):void{
		if($player instanceof Player) $player = $player->getName();
		if(!$this->config->exists($player)) return;
		$this->config->set($player, $money);
		$this->config->save();
	}

	public function plusMoney(Player|string $player, int $plus):void{
		$this->setMoney($player, $this->getMoney($player) + $plus);
	}

	public function minusMoney(Player|string $player, int $minus):void{
		$this->setMoney($player, $this->getMoney($player) - $minus);
	}

	public function registerPlayer(Player|string $player):void{
		if($player instanceof Player) $player = $player->getName();
		$this->config->set($player, 1000);
		$this->config->save();
	}

	public static function getInstance():?self{
		return self::$API;
	}
}