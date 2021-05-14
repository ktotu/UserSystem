<?php

declare(strict_types = 1);

namespace Bestaford\UserSystem\form;

use pocketmine\Player;

/**
 * Class LoginForm
 * @author Bestaford
 * @link https://talk.24serv.pro/u/bestaford
 * @package Bestaford\UserSystem\form
 */
class LoginForm extends CustomForm {

    /**
     * LoginForm constructor.
     */
    public function __construct() {
        parent::__construct(function(Player $player, array $data = null) {
            if($data === null) {
                return;
            }
        });
        $this->setTitle("login");
        $this->addLabel("login");
    }
}