<?php

namespace syouyu\moneysystem_syouyu_server\command;

use pocketmine\command\CommandSender;
use pocketmine\command\utils\CommandException;
use pocketmine\lang\Translatable;
use pocketmine\math\Vector3;
use pocketmine\player\Player;
use pocketmine\utils\TextFormat;
use syouyu\moneysystem_syouyu_server\Hanni;

class endPos extends \pocketmine\command\Command{

	public function __construct(string $name = "endpos", Translatable|string $description = "土地保護の終点を設定", Translatable|string|null $usageMessage = null, array $aliases = []){
		parent::__construct($name, $description, $usageMessage, $aliases);
	}
	/**
	 * @inheritDoc
	 */
	public function execute(CommandSender $sender, string $commandLabel, array $args){
		// TODO: Implement execute() method.
		if($sender instanceof Player){
			if(!Hanni::isSetStartP($sender)) return;
			Hanni::endP($sender, new Vector3($sender->getPosition()->getFloorX(), $sender->getPosition()->getFloorY(), $sender->getPosition()->getFloorZ()));
			$axis = Hanni::getAxisBB($sender);
			$xLong = (int)$axis->getXLength();
			$zLong = (int)$axis->getZLength();
			$a = $xLong * $zLong;
			$a = $a * 50;
			$a = (int) $a;
			$sender->sendMessage(TextFormat::GREEN."[LAND] 終点を設定しました。");
			$sender->sendMessage(TextFormat::GREEN."[LAND] 価格は". $a ."円です。購入する場合は /land コマンドを実行してください。");
		}
	}
}