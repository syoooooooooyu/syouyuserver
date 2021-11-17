<?php

namespace syouyu\moneysystem_syouyu_server;

use JetBrains\PhpStorm\Pure;
use pocketmine\math\AxisAlignedBB;
use pocketmine\math\Vector3;
use pocketmine\player\Player;
use pocketmine\world\World;

class Hanni{

	/** @var array  */
	private static array $startPos = [];
	/** @var array  */
	private static array $endPos = [];
	/** @var array  */
	private static array $world = [];

	public static function startP(Player $player, Vector3 $vector3){
		self::$startPos[$player->getName()] = $vector3;
	}

	#[Pure] public static function isSetStartP(Player $player) : bool{
		return isset(self::$startPos[$player->getName()]);
	}

	public static function endP(Player $player, Vector3 $vector3){
		self::$endPos[$player->getName()] = $vector3;
	}

	#[Pure] public static function isSetEndP(Player $player) : bool{
		return isset(self::$endPos[$player->getName()]);
	}

	public static function setWorld(Player $player, World $world){
		self::$world[$player->getName()] = $world->getDisplayName();
	}

	public static function getAxisBB(Player $player): ?AxisAlignedBB{
		if(isset(self::$startPos[$player->getName()]) and isset(self::$endPos[$player->getName()])){
			/** @var Vector3 $startP */
			$startP = self::$startPos[$player->getName()];
			$endP = self::$endPos[$player->getName()];
			return new AxisAlignedBB($startP->x, $startP->y, $startP->z, $endP->x, $endP->y, $endP->z);
		}
		return null;
	}

	#[Pure] public static function getStartPos(Player $player): ?Vector3{
		if(self::isSetStartP($player)){
			return self::$startPos[$player->getName()];
		}
		return null;
	}

	#[Pure] public static function getEndPos(Player $player): ?Vector3{
		if(self::isSetEndP($player)){
			return self::$endPos[$player->getName()];
		}
		return null;
	}
}