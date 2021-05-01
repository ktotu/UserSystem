<?php

declare(strict_types = 1);

namespace Bestaford\UserSystem;

use pocketmine\plugin\PluginBase;
use Bestaford\UserSystem\util\Database;
use Bestaford\UserSystem\util\EventListener;

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