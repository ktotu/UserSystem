<?php

declare(strict_types = 1);

namespace Bestaford\UserSystem\provider;

use Bestaford\UserSystem\UserSystem;
use pocketmine\Player;

/**
 * Interface ProviderInterface
 * @author Bestaford
 * @link https://talk.24serv.pro/u/bestaford
 * @package Bestaford\UserSystem\provider
 */
interface ProviderInterface {

    /**
     * ProviderInterface constructor.
     * @param UserSystem $plugin
     */
    public function __construct(UserSystem $plugin);

    /**
     * Returns true if the player is registered, false otherwise. Name is case insensitive.
     *
     * @param Player $player
     * @return bool
     */
    public function isRegistered(Player $player) : bool;

    /**
     * Writes data about a new player to the database.
     * Returns true if the row was written successfully, false otherwise.
     *
     * @param Player $player
     * @param string $password
     * @return bool
     */
    public function registerPlayer(Player $player, string $password) : bool;
}