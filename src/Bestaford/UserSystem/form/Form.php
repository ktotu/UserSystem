<?php

/*
 * @author jojoe77777
 * @link https://github.com/jojoe77777/FormAPI
 */

declare(strict_types = 1);

namespace Bestaford\UserSystem\form;

use pocketmine\form\Form as IForm;
use pocketmine\Player;

abstract class Form implements IForm {

    /**
     * @var array
     */
    protected array $data = [];

    /**
     * @var callable|null
     */
    private $callable;

    /**
     * Form constructor.
     * @param callable|null $callable
     */
    public function __construct(?callable $callable) {
        $this->callable = $callable;
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
        if ($callable !== null) {
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
}