<?php

declare(strict_types = 1);

namespace Bestaford\UserSystem\provider;

use Bestaford\UserSystem\UserSystem;
use pocketmine\Player;
use SQLite3;

/**
 * Class SQLite3Provider
 * @author Bestaford
 * @link https://talk.24serv.pro/u/bestaford
 * @package Bestaford\UserSystem\provider
 */
class SQLite3Provider implements ProviderInterface {

    /** @var SQLite3 */
    private SQLite3 $database;

    /**
     * SQLite3Provider constructor.
     * @param UserSystem $plugin
     */
    public function __construct(UserSystem $plugin) {
        $path = str_replace("%data", $plugin->getDataFolder(), $plugin->getProperty("sqlite.path"));
        $name = $plugin->getProperty("sqlite.name");
        $this->database = new SQLite3($path.$name);
        $this->database->exec(stream_get_contents($plugin->getResource("users.sql")));
    }

    /**
     * Returns true if the player is registered, false otherwise. Name is case insensitive.
     *
     * @param Player $player
     * @return bool
     */
    public function isRegistered(Player $player) : bool {
        $name = strtolower($player->getName());
        $result = $this->database->query("SELECT * FROM users WHERE name = '$name'");
        return !empty($result->fetchArray());
    }

    /**
     * Writes data about a new player to the database.
     * Returns true if the row was written successfully, false otherwise.
     *
     * @param Player $player
     * @param string $password
     * @return bool
     */
    public function registerPlayer(Player $player, string $password) : bool {
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
     * Returns provider name.
     *
     * @return string
     */
    public function getName() : string {
        return "SQLite3";
    }
}