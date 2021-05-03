<?php

declare(strict_types = 1);

namespace Bestaford\UserSystem;

use Bestaford\UserSystem\form\LoginForm;
use Bestaford\UserSystem\form\RegistrationForm;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\Player;
use pocketmine\plugin\PluginBase;
use Bestaford\UserSystem\utils\Database;
use pocketmine\utils\Config;

/**
 * Class UserSystem
 * @author Bestaford
 * @link https://talk.24serv.pro/u/bestaford
 * @package Bestaford\UserSystem
 */
class UserSystem extends PluginBase implements Listener {

    /** @var Database */
    private Database $database;

    /** @var Config */
    private Config $config;

    /** @var array */
    private array $players = [];

    /**
     * Plugin start point.
     */
    public function onEnable() : void {
        $this->database = new Database($this, "users");
        $this->saveDefaultConfig();
        $this->config = new Config($this->getDataFolder()."config.yml", Config::YAML);
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
    }

    /**
     * Send registration or login form when player join.
     *
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
     * @api
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
     * @api
     */
    public function isLogined(Player $player) : bool {
        return false;
    }

    /**
     * Returns setting from configuration file by key.
     *
     * @param string $key
     * @param mixed $default
     * @return bool|mixed
     * @api
     */
    public function getProperty(string $key, $default = false) {
        return $this->config->get($key, $default);
    }
}