<?php

namespace syouyu\moneysystem_syouyu_server\event;

use pocketmine\block\BlockLegacyIds;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\block\BlockPlaceEvent;
use pocketmine\event\Listener;
use syouyu\moneysystem_syouyu_server\JobSystemAPI;
use syouyu\moneysystem_syouyu_server\LandAPI;
use syouyu\moneysystem_syouyu_server\MoneySystemAPI;
use syouyu\server_core\Main;

class BlockEvent implements Listener{

	/**
	 * @priority LOWEST
	 */
	public function b(BlockBreakEvent $event){
		$block = $event->getBlock();
		$player = $event->getPlayer();
		if(!LandAPI::getInstance()->canChange($block->getPosition(), $player)){
			$event->cancel();
			$player->sendMessage("ここは誰かの土地です！");
		}else{
			$ini = parse_ini_file(Main::getInstance()->resource()."money.ini");
			if(JobSystemAPI::getInstance()->getJob($player) === JobSystemAPI::tree){
				$add = match ($block->getId()){
					BlockLegacyIds::LOG, BlockLegacyIds::LOG2 => (int) $ini["tree-money"],
					default => 0,
  				};
				MoneySystemAPI::getInstance()->plusMoney($player, $add);
			}elseif(JobSystemAPI::getInstance()->getJob($player) === JobSystemAPI::mine){
				$add = match ($block->getId()) {
					BlockLegacyIds::STONE => (int) $ini["mine-stone-money"],
					BlockLegacyIds::COAL_ORE => (int) $ini["mine-coal-money"],
					BlockLegacyIds::IRON_ORE => (int) $ini["mine-iron-money"],
					BlockLegacyIds::GOLD_ORE => (int) $ini["mine-gold-money"],
					BlockLegacyIds::DIAMOND_ORE => (int) $ini["mine-diamond-money"],
					BlockLegacyIds::REDSTONE_ORE => (int) $ini["mine-redstone-money"],
					default => 0,
				};
				MoneySystemAPI::getInstance()->plusMoney($player, $add);
			}
		}
	}
	/**
	 * @priority LOWEST
	 */
	public function p(BlockPlaceEvent $event){
		$block = $event->getBlock();
		$player = $event->getPlayer();
		if(!LandAPI::getInstance()->canChange($block->getPosition(), $player)){
			$event->cancel();
			$player->sendMessage("ここは誰かの土地です！");
		}else{
			$ini = parse_ini_file(Main::getInstance()->resource()."money.ini");
			if(JobSystemAPI::getInstance()->getJob($player) === JobSystemAPI::build){
				MoneySystemAPI::getInstance()->plusMoney($player, (int)$ini["build-money"]);
			}
		}
	}
}