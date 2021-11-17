<?php

namespace syouyu\moneysystem_syouyu_server\command;

use pocketmine\command\CommandSender;
use pocketmine\command\utils\CommandException;
use pocketmine\lang\Translatable;
use pocketmine\permission\DefaultPermissionNames;
use pocketmine\permission\Permission;
use pocketmine\permission\PermissionAttachment;
use pocketmine\permission\PermissionAttachmentInfo;
use pocketmine\permission\PermissionManager;
use pocketmine\permission\PermissionParser;
use pocketmine\permission\PermissionParserException;
use pocketmine\player\Player;
use syouyu\moneysystem_syouyu_server\MoneySystemAPI;

class showPayedHistory extends \pocketmine\command\Command{

	public function __construct(string $name = "showpayedhistory", Translatable|string $description = "", Translatable|string|null $usageMessage = null, array $aliases = []){
		parent::__construct($name, $description, $usageMessage, $aliases);
		$this->setPermission("op");
	}

	/**
	 * @inheritDoc
	 */
	public function execute(CommandSender $sender, string $commandLabel, array $args){
		// TODO: Implement execute() method.
		if(!$sender instanceof Player){
			MoneySystemAPI::getInstance()->showPayedHistory((int)$args[0]);
		}
	}
}