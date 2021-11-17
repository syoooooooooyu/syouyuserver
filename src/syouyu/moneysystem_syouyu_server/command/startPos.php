<?php

namespace syouyu\moneysystem_syouyu_server\command;

use pocketmine\command\CommandSender;
use pocketmine\command\utils\CommandException;
use pocketmine\lang\Translatable;
use pocketmine\math\Vector3;
use pocketmine\player\Player;
use pocketmine\utils\TextFormat;
use syouyu\moneysystem_syouyu_server\Hanni;

class startPos extends \pocketmine\command\Command{

	public function __construct(string $name = "startpos", Translatable|string $description = "土地保護の始点を設定", Translatable|string|null $usageMessage = null, array $aliases = []){
		parent::__construct($name, $description, $usageMessage, $aliases);
	}

	/**
	 * @inheritDoc
	 */
	public function execute(CommandSender $sender, string $commandLabel, array $args){
		// TODO: Implement execute() method.
		if($sender instanceof Player){
			Hanni::startP($sender, new Vector3($sender->getPosition()->getFloorX(), $sender->getPosition()->getFloorY(), $sender->getPosition()->getFloorZ()));
			$sender->sendMessage(TextFormat::GREEN."[LAND] 始点を設定しました。");
		}
	}
}