<?php

namespace syouyu\moneysystem_syouyu_server\command;

use pocketmine\command\CommandSender;
use pocketmine\command\utils\CommandException;
use pocketmine\lang\Translatable;
use pocketmine\permission\Permission;
use pocketmine\permission\PermissionManager;
use pocketmine\player\Player;
use pocketmine\Server;
use pocketmine\utils\TextFormat;
use syouyu\moneysystem_syouyu_server\MoneySystemAPI;

class minusMoney extends \pocketmine\command\Command{

	public function __construct(string $name = "minusmoney", Translatable|string $description = "お金を減らす", Translatable|string|null $usageMessage = null, array $aliases = []){
		parent::__construct($name, $description, $usageMessage, $aliases);
	}

	/**
	 * @inheritDoc
	 */
	public function execute(CommandSender $sender, string $commandLabel, array $args){
		// TODO: Implement execute() method.
		if(isset($args[0], $args[1])){
			if($sender instanceof Player){
				if(!Server::getInstance()->isOp($sender->getName())) return;
			}
			$player = Server::getInstance()->getPlayerByPrefix($args[0]);
			if(!$player instanceof Player) return;
			MoneySystemAPI::getInstance()->minusMoney($player, (int)$args[1]);
			$sender->sendMessage(TextFormat::AQUA."[MONEY] セットしました。");
		}else{
			$sender->sendMessage(TextFormat::AQUA."[MONEY] セットできませんでした。");
		}
	}
}