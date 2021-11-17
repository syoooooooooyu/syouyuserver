<?php

namespace syouyu\moneysystem_syouyu_server\command;

use pocketmine\command\CommandSender;
use pocketmine\command\utils\CommandException;
use pocketmine\lang\Translatable;
use pocketmine\player\Player;
use pocketmine\utils\TextFormat;
use syouyu\moneysystem_syouyu_server\Hanni;
use syouyu\moneysystem_syouyu_server\LandAPI;
use syouyu\moneysystem_syouyu_server\MoneySystemAPI;

class land extends \pocketmine\command\Command{

	public function __construct(string $name = "land", Translatable|string $description = "土地を購入する", Translatable|string|null $usageMessage = null, array $aliases = []){
		parent::__construct($name, $description, $usageMessage, $aliases);
	}

	/**
	 * @inheritDoc
	 */
	public function execute(CommandSender $sender, string $commandLabel, array $args){
		// TODO: Implement execute() method.
		if($sender instanceof Player){
			if(Hanni::isSetStartP($sender) and Hanni::isSetEndP($sender)){
				$axis = Hanni::getAxisBB($sender);
				if(LandAPI::getInstance()->canRegisterLand($axis, $sender)){
					$money = MoneySystemAPI::getInstance()->getMoney($sender);
					$xLong = (int)$axis->getXLength();
					$zLong = (int)$axis->getZLength();
					$a = $xLong * $zLong;
					$a = $a * 50;
					$a = (int) $a;
					if($money >= $a){
						MoneySystemAPI::getInstance()->minusMoney($sender, $a);
						LandAPI::getInstance()->registerLand($axis, $sender, $sender->getWorld()->getDisplayName());
						$sender->sendMessage(TextFormat::AQUA."[LAND] 土地を購入しました。");
					}else{
						$sender->sendMessage(TextFormat::RED."[LAND] お金が足りないため、土地を購入できませんでした。");
					}
				}else{
					$sender->sendMessage(TextFormat::RED."[LAND] そこの土地は他プレイヤーが保有しています。");
				}
			}
		}
	}
}