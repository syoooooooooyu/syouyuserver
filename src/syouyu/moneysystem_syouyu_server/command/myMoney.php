<?php

namespace syouyu\moneysystem_syouyu_server\command;

use pocketmine\command\CommandSender;
use pocketmine\command\utils\CommandException;
use pocketmine\lang\Translatable;
use pocketmine\player\Player;
use pocketmine\utils\TextFormat;
use syouyu\moneysystem_syouyu_server\MoneySystemAPI;

class myMoney extends \pocketmine\command\Command{

	public function __construct(string $name = "mymoney", Translatable|string $description = "所持金を確認", Translatable|string|null $usageMessage = null, array $aliases = []){
		parent::__construct($name, $description, $usageMessage, $aliases);
	}

	/**
	 * @inheritDoc
	 */
	public function execute(CommandSender $sender, string $commandLabel, array $args){
		// TODO: Implement execute() method.
		if($sender instanceof Player){
			$money = MoneySystemAPI::getInstance()->getMoney($sender);
			$sender->sendMessage(TextFormat::AQUA."[MONEY] 所持金は $money 円です。");
		}
	}
}