<?php

declare(strict_types = 1);

namespace Bestaford\UserSystem\module;

use Bestaford\UserSystem\UserSystem;
use pocketmine\event\Listener;

/**
 * Class Module
 * @author Bestaford
 * @link https://talk.24serv.pro/u/bestaford
 * @package Bestaford\UserSystem\module
 */
class Module implements Listener {

    public function __construct() {
        $this->getPlugin()->getLogger()->info("Loaded ".get_called_class()." module");
    }

    /**
     * @return UserSystem
     */
    public function getPlugin() : UserSystem {
        return UserSystem::getInstance();
    }
}