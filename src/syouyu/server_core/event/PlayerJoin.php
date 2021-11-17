<?php

namespace syouyu\server_core\event;

use JetBrains\PhpStorm\Pure;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\network\mcpe\protocol\PlaySoundPacket;
use pocketmine\player\Player;
use pocketmine\scheduler\ClosureTask;
use pocketmine\Server;
use pocketmine\utils\TextFormat;
use pocketmine\world\sound\AnvilUseSound;
use pocketmine\world\sound\ExplodeSound;
use pocketmine\world\sound\Sound;
use syouyu\moneysystem_syouyu_server\MoneySystemAPI;
use syouyu\server_core\Main;
use syouyu\server_core\patimon\PatimonHuman;

class PlayerJoin implements Listener{

	/**
	 * @param PlayerJoinEvent $e
	 */
	public function e(PlayerJoinEvent $e){
		$player = $e->getPlayer();
		$player->setImmobile();
		$this->sendTitle($player);
	}

	function sendTitle(Player $player){
		$main = Main::getInstance();
		$scheduler = $main->getScheduler();
		$player->setImmobile();
		$player->broadcastSound(new AnvilUseSound());
		$scheduler->scheduleDelayedTask(new ClosureTask(
			function() use ($player, $scheduler): void{
				if($player->isOnline()){
					$player->sendTitle(TextFormat::BOLD."S".TextFormat::RED."Y".TextFormat::WHITE."OU".TextFormat::RED."Y". TextFormat::WHITE."U");
					$player->broadcastSound(new ExplodeSound());
					$player->sendSubTitle("WELCOME TO SYOUYU SERVER");
					$player->setImmobile(false);
				}
			}
		), 20);
	}
}