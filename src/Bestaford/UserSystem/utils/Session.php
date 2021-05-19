<?php

declare(strict_types = 1);

namespace Bestaford\UserSystem\utils;

use pocketmine\Player;

/**
 * Class Session
 * @author Bestaford
 * @link https://talk.24serv.pro/u/bestaford
 * @package Bestaford\UserSystem\module
 */
class Session {

    /** @var Player */
    private Player $player;

    /** @var string */
    private string $name;

    /** @var string */
    private string $fullName;

    /** @var string */
    private string $displayName;

    /** @var string */
    private string $passwordHash;

    /** @var string */
    private string $address;

    /** @var string */
    private string $uuid;

    /** @var string */
    private string $xuid;

    /** @var bool */
    private bool $isOnline = true;

    /**
     * @return Player
     */
    public function getPlayer() : Player {
        return $this->player;
    }

    /**
     * @return string
     */
    public function getName() : string {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getFullName() : string {
        return $this->fullName;
    }

    /**
     * @return string
     */
    public function getDisplayName() : string {
        return $this->displayName;
    }

    /**
     * @return string
     */
    public function getPasswordHash() : string {
        return $this->passwordHash;
    }

    /**
     * @return string
     */
    public function getAddress() : string {
        return $this->address;
    }

    /**
     * @return string
     */
    public function getUuid() : string {
        return $this->uuid;
    }

    /**
     * @return string
     */
    public function getXuid() : string {
        return $this->xuid;
    }

    /**
     * @return bool
     */
    public function isOnline() : bool {
        return $this->isOnline;
    }
}