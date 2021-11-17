<?php

namespace syouyu\moneysystem_syouyu_server;

use Exception;
use pocketmine\player\Player;
use pocketmine\utils\Config;
use syouyu\server_core\Main;

class JobSystemAPI{

	const Jobs = ["無職", "木こり", "探鉱者", "建築士"];
	const neet = 0;
	const tree = 1;
	const mine = 2;
	const build = 3;

	/** @var JobSystemAPI  */
	private static self $instance;
	/** @var Config  */
	private Config $config;

	public function __load(Main $plugin, string $resources){
		self::$instance = $this;
		if(!file_exists($plugin->getDataFolder()."Job")) mkdir($plugin->getDataFolder()."Job");
		$this->config = new Config($plugin->getDataFolder()."Job/Job.yml", Config::YAML);
		if(!file_exists($resources."resources")) mkdir($resources."resources");
	}

	public function register(Player $player){
		if(!$this->config->exists($player->getName())){
			$this->config->set($player->getName(), 0);
			$this->config->save();
		}
	}

	public function removeJob(Player $player){
		$this->config->set($player->getName(), 0);
		$this->config->save();
	}

	/**
	 * @throws Exception
	 */
	public function setJob(Player $player, int $job){
		if($job < 0 || $job > 3){
			throw new Exception("$job is not 0 ~ 3");
		}
		$this->config->set($player->getName(), $job);
		$this->config->save();
	}

	public function getJob(Player $player):int{
		return (int)$this->config->get($player->getName());
	}
	public static function getInstance():self{
		return self::$instance;
	}
}