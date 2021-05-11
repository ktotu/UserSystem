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
     * @param UserSystem $plugin
     * @param bool $error
     */
    public function __construct(UserSystem $plugin, bool $error = false) {
        parent::__construct(function(Player $player, array $data = null) {
            if($data === null) {
                $player->sendForm(new RegistrationForm($this->getPlugin()));
                return;
            }
            $password = $data["password"];
            if(UserSystem::isValidPassword($password)) {
                $this->getPlugin()->register($player, $password);
            } else {
                $player->sendForm(new RegistrationForm($this->getPlugin(), true));
            }
        }, $plugin);
        $this->setTitle($this->getPlugin()->getProperty("registration.form.title"));
        $this->addLabel($this->getPlugin()->getProperty("registration.form.label"));
        $text = $this->getPlugin()->getProperty("registration.form.input.text");
        $placeholder = $this->getPlugin()->getProperty("registration.form.input.placeholder");
        $default = $this->getPlugin()->getProperty("registration.form.input.default");
        $this->addInput($text, $placeholder, $default, "password");
    }
}