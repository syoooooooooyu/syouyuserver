<?php

namespace syouyu\senddiscordsystem_syouyu_server;

use pocketmine\scheduler\AsyncTask;

class async extends AsyncTask{

	/** @var string  */
	private static string $str;

	public function __construct(string $str){
		self::$str = $str;
	}

	public function onRun() : void{
		// TODO: Implement onRun() method.
		self::send(self::$str);
	}

	public static function send($str){
		$cr = curl_init();
		curl_setopt($cr, CURLOPT_URL, "https://discord.com/api/webhooks/902146671348170793/fijYEFHEur9sQpc7ldksKjhGEE0o7EftAfLQvSAqjP63U-5eJ0qg-y7a1ArXYfJ9gfz2");
		curl_setopt($cr, CURLOPT_POST, true);
		curl_setopt($cr, CURLOPT_POSTFIELDS, json_encode(["username" => "test", "avatar_url" => "https://github.com/qiita.png","content" => $str]));
		curl_setopt($cr, CURLOPT_HTTPHEADER, [
			"Content-Type: application/json",
		]);
		curl_close($cr);
	}
}