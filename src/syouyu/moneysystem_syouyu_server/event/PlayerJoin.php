<?php

namespace syouyu\moneysystem_syouyu_server\event;

use pocketmine\event\player\PlayerJoinEvent;
use syouyu\moneysystem_syouyu_server\JobSystemAPI;
use syouyu\moneysystem_syouyu_server\MoneySystemAPI;

class PlayerJoin implements \pocketmine\event\Listener{

	public function e(PlayerJoinEvent $event){
		if(!MoneySystemAPI::getInstance()->isRegistered($event->getPlayer())){
			MoneySystemAPI::getInstance()->registerPlayer($event->getPlayer());
		}
		JobSystemAPI::getInstance()->register($event->getPlayer());
	}
}