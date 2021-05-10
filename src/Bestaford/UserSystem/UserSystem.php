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

    const ERROR_MISSING_PROPERTY = "Missing configuration file property: ";

    /** @var Database */
    private Database $database;

    /** @var Config */
    private Config $config;

    //TODO: sessions array

    /**
     * Plugin start point.
     */
    public function onEnable() : void {
        $this->database = new Database($this, "users");
        $this->database->createTable("users");
        $this->database->createTable("passwords");
        $this->saveResource("config.yml", true); //TODO: save default config without replace
        $this->config = new Config($this->getDataFolder()."config.yml", Config::YAML);
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
        $this->getLogger()->info("Plugin enabled successfully.");
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

    /**
     * Returns setting from configuration file by key.
     *
     * @param string $query
     * @return mixed
     */
    public function getProperty(string $query) {
        $keys = explode(".", $query);
        if(count($keys) == 1 && $keys[0] == $query) {
            $key = $keys[0];
            if($this->config->exists($key)) {
                return $this->config->get($key);
            } else {
                $error = self::ERROR_MISSING_PROPERTY.$key;
                $this->getLogger()->error($error);
                return $error;
            }
        } else {
            $data = [];
            foreach($keys as $key) {
                if(empty($data)) {
                    if($this->config->exists($key)) {
                        $data = $this->config->get($key);
                    } else {
                        $error = self::ERROR_MISSING_PROPERTY.$key." [$query]";
                        $this->getLogger()->error($error);
                        return $error;
                    }
                } else {
                    if(is_array($data) && isset($data[$key])) {
                        $data = $data[$key];
                    } else {
                        $error = self::ERROR_MISSING_PROPERTY.$key." [$query]";
                        $this->getLogger()->error($error);
                        return $error;
                    }
                }
            }
        }
        return $data;
    }

    public function onDisable() {
        $this->getLogger()->info("Plugin disabled successfully.");
    }

    /**
     * @param string $text
     * @return bool
     */
    public static function isValidPassword(string $text) : bool {
        return preg_match("/^(?=\S+[0-9])(?=\S+[a-z])(?=\S+[A-Z])(?=\S+\W).{8,}$/", $text) == 1;
    }
}