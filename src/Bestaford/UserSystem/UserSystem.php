<?php

declare(strict_types = 1);

namespace Bestaford\UserSystem;

use Bestaford\UserSystem\form\LoginForm;
use Bestaford\UserSystem\form\RegistrationForm;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;
use SQLite3;

/**
 * Class UserSystem
 * @author Bestaford
 * @link https://talk.24serv.pro/u/bestaford
 * @package Bestaford\UserSystem
 */
class UserSystem extends PluginBase implements Listener {

    const ERROR_MISSING_PROPERTY = "Missing configuration file property: ";

    /** @var SQLite3 */
    private SQLite3 $database;

    /** @var Config */
    private Config $config;

    public function onEnable() : void {
        $this->database = new SQLite3($this->getDataFolder()."users.db");
        $this->database->exec(stream_get_contents($this->getResource("users.sql")));
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
     * Returns true if the player is registered, false otherwise. Name is case insensitive.
     *
     * @param Player $player
     * @return bool
     */
    public function isRegistered(Player $player) : bool {
        $name = strtolower($player->getName());
        return !is_null($this->database->querySingle("SELECT * FROM users WHERE name = '$name'"));
    }

    /**
     * Returns true when player has online or offline session, false otherwise.
     *
     * @param Player $player
     * @return bool
     */
    public function isLogined(Player $player) : bool {
        return false; //TODO
    }

    /**
     * Returns true when player has online session, false otherwise.
     *
     * @param Player $player
     * @return bool
     */
    public function isPlaying(Player $player) : bool {
        return false; //TODO
    }

    /**
     * Registers the player on the server.
     * Returns false if the player was already registered or an error occurred,
     * true if registration was successful.
     *
     * @param Player $player
     * @param string $password
     * @return bool
     */
    public function register(Player $player, string $password) : bool {
        if($this->isRegistered($player)) {
            return false;
        } else {
            return $this->addUser($player, $password);
        }
    }

    /**
     * Writes data about a new player to the database.
     * Returns true if the row was written successfully, false otherwise.
     *
     * @param Player $player
     * @param string $password
     * @return bool
     */
    private function addUser(Player $player, string $password) : bool {
        $statement = $this->database->prepare("INSERT INTO users (name, full_name, display_name, password_hash, address, uuid, xuid) VALUES (:name, :full_name, :display_name, :password_hash, :address, :uuid, :xuid)");
        $statement->bindValue(":name", strtolower($player->getName()));
        $statement->bindValue(":full_name", $player->getName());
        $statement->bindValue(":display_name", $player->getDisplayName());
        $statement->bindValue(":password_hash", password_hash($password, PASSWORD_DEFAULT));
        $statement->bindValue(":address", $player->getAddress());
        $statement->bindValue(":uuid", $player->getUniqueId()->toString());
        $statement->bindValue(":xuid", $player->getXuid());
        $statement->execute();
        return $this->database->changes() == 1;
    }

    /**
     * If key is simple: returns setting from configuration file by key.
     * If $query is several keys separated by a dot: iterates the keys
     * through the arrays up to the last one in the request and returns the value
     *
     * Examples:
     * query "server_name" will return $this->config->get("server_name")
     *
     * query "registration.form.title" will iterate array "registration",
     * then "form" and will return value by key "title" of "form" array
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
     * Regular expression checks if the password contains:
     * at least one lowercase latin letter [a-z]
     * at least one uppercase latin letter [A-Z]
     * at least one number [0-9]
     * at least one special character
     * does not contain spaces
     * and contains at least 8 characters
     *
     * @param string $text
     * @return bool
     */
    public static function isValidPassword(string $text) : bool {
        return preg_match("/^(?=\S+[0-9])(?=\S+[a-z])(?=\S+[A-Z])(?=\S+\W).{8,}$/", $text) == 1;
    }
}