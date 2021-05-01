<?php

namespace Bestaford\UserSystem;

use pocketmine\plugin\PluginBase;

class UserSystem extends PluginBase {

	/**
	 * @var Database
	 */
	public Database $database;

	public function onEnable() {
		$this->database = new Database($this, "users");
		$this->getServer()->getPluginManager()->registerEvents(new EventListener(), $this);
	}
}