<?php

namespace syouyu\senddiscordsystem_syouyu_server;

use pocketmine\scheduler\AsyncTask;

class async extends AsyncTask{

	/** @var string  */
	private static string $str;
	/** @var string */
	private static string $username;
	const url = "";

	public function __construct(string $username, string $str){
		self::$str = $str;
		self::$username = $username;
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
		curl_exec($cr);
		curl_close($cr);
	}
}
