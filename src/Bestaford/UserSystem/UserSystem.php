<?php

/*
 * @author Bestaford
 * @link https://talk.24serv.pro/u/bestaford
 */

declare(strict_types = 1);

namespace Bestaford\UserSystem;

use Bestaford\UserSystem\form\LoginForm;
use Bestaford\UserSystem\form\RegistrationForm;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\Player;
use pocketmine\plugin\PluginBase;
use Bestaford\UserSystem\util\Database;

class UserSystem extends PluginBase implements Listener {

    /**
     * @var Database
     */
    private Database $database;

    /**
     * Plugin start point.
     */
    public function onEnable() : void {
        $this->database = new Database($this, "users");
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
    }

    /**
     * @param PlayerJoinEvent $event
     */
    public function onPlayerJoin(PlayerJoinEvent $event) : void {
        $player = $event->getPlayer();
        if($this->isRegistered($player)) {
            if($this->isLogined($player)) {

            } else {
                $player->sendForm(new LoginForm($this));
            }
        } else {
            $player->sendForm(new RegistrationForm($this));
        }
    }

    /**
     * @param Player $player
     * @return bool
     */
    public function isRegistered(Player $player) : bool {
        $this->database->prepare("SELECT * FROM users WHERE name = :name");
        $this->database->bind(":name", $player->getName());
        $this->database->execute();
        return count($this->database->get()) > 0;
    }

    /**
     * @param Player $player
     * @return bool
     */
    public function isLogined(Player $player) : bool {
        return false;
    }
}