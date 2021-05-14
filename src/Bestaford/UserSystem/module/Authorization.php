<?php

declare(strict_types = 1);

namespace Bestaford\UserSystem\module;

use Bestaford\UserSystem\form\LoginForm;
use Bestaford\UserSystem\form\RegistrationForm;
use pocketmine\event\player\PlayerJoinEvent;

/**
 * Class Authorization
 * @author Bestaford
 * @link https://talk.24serv.pro/u/bestaford
 * @package Bestaford\UserSystem
 */
class Authorization extends Module {

    /**
     * Send registration or login form when player join.
     *
     * @param PlayerJoinEvent $event
     */
    public function onPlayerJoin(PlayerJoinEvent $event) : void {
        $player = $event->getPlayer();
        if($this->getPlugin()->isRegistered($player)) {
            if($this->getPlugin()->isLogined($player)) {

            } else {
                $player->sendForm(new LoginForm());
            }
        } else {
            $player->sendForm(new RegistrationForm());
        }
    }
}