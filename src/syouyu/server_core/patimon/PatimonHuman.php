<?php

namespace syouyu\server_core\patimon;

use pocketmine\entity\Location;
use pocketmine\entity\Skin;
use pocketmine\nbt\tag\CompoundTag;

class PatimonHuman extends \pocketmine\entity\Human{

	public function __construct(Location $location, Skin $skin, ?CompoundTag $nbt = null){
		parent::__construct($location, $skin, $nbt);
	}
}