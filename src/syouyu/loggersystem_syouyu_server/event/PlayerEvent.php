<?php

namespace syouyu\loggersystem_syouyu_server\event;

use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\block\BlockEvent;
use pocketmine\event\block\BlockPlaceEvent;
use pocketmine\nbt\NoSuchTagException;
use pocketmine\player\Player;
use syouyu\loggersystem_syouyu_server\DataBase;
use syouyu\loggersystem_syouyu_server\LoggerSystemAPI;
use syouyu\server_core\Main;

class PlayerEvent implements \pocketmine\event\Listener{

	/**
	 * @priority MONITOR
	 * @param BlockBreakEvent $event
	 * @handleCancelled
	 */
	public function b(BlockBreakEvent $event){
		$this->checkLog($event, "b");
	}
	/**
	 * @priority MONITOR
	 * @param BlockPlaceEvent $event
	 * @handleCancelled
	 */
	public function p(BlockPlaceEvent $event){
		$this->checkLog($event, "p");
	}

	/**
	 * @param BlockPlaceEvent|BlockBreakEvent $event
	 * @param string     $eventType
	 *
	 * @return bool
	 */
	private function checkLog(BlockPlaceEvent|BlockBreakEvent $event, string $eventType) {
		$player = $event->getPlayer();
		if ($player instanceof Player) {
			$block = $event->getBlock();
			$floorVec = $block->getPosition()->floor();
			$x = $floorVec->x;
			$y = $floorVec->y;
			$z = $floorVec->z;
			$world = $block->getPosition()->getWorld()->getFolderName();
			$cls = DataBase::getInstance();
			if(LoggerSystemAPI::getInstance()->isOn($player)){
				$cls->checklog($x, $y, $z, $world, $player);
				$event->cancel();
			}else{
				$id = $block->getId();
				$meta = $block->getMeta();
				$cls->registerlog($x, $y, $z, $world, $id, $meta, $player, $eventType);
			}
		}
		return true;
	}
}