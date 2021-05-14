<?php

declare(strict_types = 1);

namespace Bestaford\UserSystem\form;

use Bestaford\UserSystem\UserSystem;
use pocketmine\Player;

/**
 * Class RegistrationForm
 * @author Bestaford
 * @link https://talk.24serv.pro/u/bestaford
 * @package Bestaford\UserSystem\form
 */
class RegistrationForm extends CustomForm {

    /**
     * RegistrationForm constructor.
     * @param bool $error
     */
    public function __construct(bool $error = false) {
        parent::__construct(function(Player $player, array $data = null) {
            if($data === null) {
                $player->sendForm(new RegistrationForm());
                return;
            }
            $password = $data["password"];
            if(UserSystem::isValidPassword($password)) {
                $this->getPlugin()->registerPlayer($player, $password);
            } else {
                $player->sendForm(new RegistrationForm(true));
            }
        });
        $this->setTitle($this->getPlugin()->getProperty("authorization.registration.form.title"));
        $this->addLabel($this->getPlugin()->getProperty("authorization.registration.form.label"));
        $text = $this->getPlugin()->getProperty("authorization.registration.form.input.text");
        $placeholder = $this->getPlugin()->getProperty("authorization.registration.form.input.placeholder");
        $default = $this->getPlugin()->getProperty("authorization.registration.form.input.default");
        $this->addInput($text, $placeholder, $default, "password");
    }
}