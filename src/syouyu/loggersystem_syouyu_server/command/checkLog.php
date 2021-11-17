<?php

namespace syouyu\loggersystem_syouyu_server\command;

use pocketmine\command\CommandSender;
use pocketmine\command\utils\CommandException;
use pocketmine\item\Item;
use pocketmine\item\ItemFactory;
use pocketmine\item\ItemIds;
use pocketmine\lang\Translatable;
use pocketmine\nbt\NoSuchTagException;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\Tag;
use pocketmine\permission\Permission;
use pocketmine\permission\PermissionManager;
use pocketmine\player\Player;
use pocketmine\Server;
use pocketmine\utils\TextFormat;
use syouyu\loggersystem_syouyu_server\LoggerSystemAPI;

class checkLog extends \pocketmine\command\Command{

	public function __construct(string $name = "checklog", Translatable|string $description = "チェックログを確認", Translatable|string|null $usageMessage = null, array $aliases = []){
		parent::__construct($name, $description, $usageMessage, $aliases);
	}
	/**
	 * @inheritDoc
	 */
	public function execute(CommandSender $sender, string $commandLabel, array $args){
		// TODO: Implement execute() method.
		if($sender instanceof Player){
			if(!Server::getInstance()->isOp($sender->getName())) return;
			if(LoggerSystemAPI::getInstance()->isOn($sender)){
				LoggerSystemAPI::getInstance()->setOn($sender, false);
				$sender->sendMessage(TextFormat::AQUA."[LOG] ログ確認モードを無効にしました。");
			}else{
				LoggerSystemAPI::getInstance()->setOn($sender, true);
				$sender->sendMessage(TextFormat::AQUA."[LOG] ログ確認モードを有効にしました。");
			}
		}
	}
}