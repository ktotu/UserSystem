<?php

/*
 * @author Bestaford
 * @link https://talk.24serv.pro/u/bestaford
 */

declare(strict_types = 1);

namespace Bestaford\UserSystem\form;

use Bestaford\UserSystem\UserSystem;
use pocketmine\Player;

class LoginForm extends CustomForm {

    /**
     * LoginForm constructor.
     * @param UserSystem $plugin
     */
    public function __construct(UserSystem $plugin) {
        parent::__construct(function(Player $player, array $data = null) {
            if($data === null) {
                return;
            }
        }, $plugin);
        $this->setTitle("login");
        $this->addLabel("login");
    }
}