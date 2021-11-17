<?php

namespace syouyu\moneysystem_syouyu_server\command;

use pocketmine\command\CommandSender;
use pocketmine\command\utils\CommandException;
use pocketmine\form\Form;
use pocketmine\form\FormValidationException;
use pocketmine\lang\Translatable;
use pocketmine\player\Player;
use pocketmine\utils\TextFormat;
use syouyu\moneysystem_syouyu_server\JobSystemAPI;

class job extends \pocketmine\command\Command{

	public function __construct(string $name = "job", Translatable|string $description = "職業を選択", Translatable|string|null $usageMessage = null, array $aliases = []){
		parent::__construct($name, $description, $usageMessage, $aliases);
	}

	/**
	 * @inheritDoc
	 */
	public function execute(CommandSender $sender, string $commandLabel, array $args){
		// TODO: Implement execute() method.
		if($sender instanceof Player){
			$sender->sendForm(new class implements Form{

				public function handleResponse(Player $player, $data) : void{
					// TODO: Implement handleResponse() method.
					if($data === null) return;
					JobSystemAPI::getInstance()->setJob($player, JobSystemAPI::Jobs[$data]);
					if($data === 0){
						$player->sendMessage(TextFormat::AQUA."[JOB] 離職しました。");
						return;
					}
					$player->sendMessage(TextFormat::AQUA."[JOB] ". JobSystemAPI::Jobs[$data]."になりました。");
				}

				public function jsonSerialize(){
					// TODO: Implement jsonSerialize() method.
					return[
						"type" => "form",
						"title" => "職業を選択",
						"content" => "職業を選択してください",
						"buttons" => [
							[
								"text" => JobSystemAPI::Jobs[0]
							],
							[
								"text" => JobSystemAPI::Jobs[1]
							],
							[
								"text" => JobSystemAPI::Jobs[2]
							],
							[
								"text" => JobSystemAPI::Jobs[3]
							]
						]
					];
				}
			});
		}
	}
}