<?php

namespace syouyu\senddiscordsystem_syouyu_server;

use pocketmine\scheduler\AsyncTask;
use pocketmine\utils\Config;
use syouyu\server_core\Main;

class async extends AsyncTask{

	/** @var string  */
	private static string $str;
	/** @var string */
	private static string $username;
	/** @var Config  */
	private Config $config;
	const url = "";

	public function __construct(string $username, string $str){
		self::$str = $str;
		self::$username = $username;
		if(!file_exists(Main::getInstance()->getDataFolder()."Async")) mkdir(Main::getInstance()->getDataFolder()."Async");
		$this->config = new Config(Main::getInstance()->getDataFolder()."Async/url.yml");
		if(!$this->config->exists("url")) {
			$this->config->set("url", "https://");
			$this->config->save();
		}
	}

	public function onRun() : void{
		// TODO: Implement onRun() method.
		self::send(self::$str, self::$username);
	}

	public static function send($str, $name){
		$cr = curl_init();
		curl_setopt($cr, CURLOPT_URL, self::url);
		curl_setopt($cr, CURLOPT_POST, true);
		curl_setopt($cr, CURLOPT_POSTFIELDS, json_encode(["username" => $name, "avatar_url" => "https://github.com/qiita.png","content" => $str]));
		curl_setopt($cr, CURLOPT_HTTPHEADER, [
			"Content-Type: application/json",
		]);
		curl_setopt($cr, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($cr, CURLOPT_SSL_VERIFYHOST, false);
		curl_exec($cr);
		curl_close($cr);
	}
}
