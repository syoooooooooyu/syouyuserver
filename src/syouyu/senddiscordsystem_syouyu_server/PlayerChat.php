<?php

namespace syouyu\senddiscordsystem_syouyu_server;

use pocketmine\event\player\PlayerChatEvent;
use pocketmine\Server;

class PlayerChat implements \pocketmine\event\Listener{


	public function e(PlayerChatEvent $e){
		$name = $e->getPlayer()->getName();
		$content = $e->getMessage();
		Server::getInstance()->getAsyncPool()->submitTask(new Async("`$name: $content`"));
	}
}