<?php

declare(strict_types = 1);

namespace Bestaford\UserSystem\form;

use Bestaford\UserSystem\UserSystem;
use pocketmine\form\Form as IForm;
use pocketmine\Player;

/**
 * Class Form
 * @author jojoe77777
 * @link https://github.com/jojoe77777/FormAPI
 * @package Bestaford\UserSystem\form
 */
abstract class Form implements IForm {

    /** @var array */
    protected array $data = [];

    /** @var callable|null */
    private $callable;

    /** @var UserSystem */
    private UserSystem $plugin;

    /**
     * Form constructor.
     * @param callable|null $callable
     * @param UserSystem $plugin
     */
    public function __construct(?callable $callable, UserSystem $plugin) {
        $this->callable = $callable;
        $this->plugin = $plugin;
    }

    /**
     * @return callable|null
     */
    public function getCallable() : ?callable {
        return $this->callable;
    }

    /**
     * @param callable|null $callable
     */
    public function setCallable(?callable $callable) {
        $this->callable = $callable;
    }

    /**
     * @param Player $player
     * @param mixed $data
     */
    public function handleResponse(Player $player, $data) : void {
        $this->processData($data);
        $callable = $this->getCallable();
        if($callable !== null) {
            $callable($player, $data);
        }
    }

    /**
     * @param $data
     */
    public function processData(&$data) : void {
    }

    /**
     * @return array
     */
    public function jsonSerialize() : array {
        return $this->data;
    }

    /**
     * @return UserSystem
     */
    public function getPlugin() : UserSystem {
        return $this->plugin;
    }
}