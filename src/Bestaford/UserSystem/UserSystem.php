<?php

declare(strict_types = 1);

namespace Bestaford\UserSystem;

use Bestaford\UserSystem\module\Authorization;
use Bestaford\UserSystem\provider\ProviderInterface;
use Bestaford\UserSystem\provider\SQLite3Provider;
use pocketmine\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;

/**
 * Class UserSystem
 * @author Bestaford
 * @link https://talk.24serv.pro/u/bestaford
 * @package Bestaford\UserSystem
 */
class UserSystem extends PluginBase {

    const ERROR_MISSING_PROPERTY = "Missing configuration file property: ";

    /** @var UserSystem|null */
    private static ?UserSystem $instance = null;

    /** @var Config */
    private Config $config;

    /** @var ProviderInterface */
    private ProviderInterface $provider;

    /** @var bool */
    private bool $loaded = false;

    /**
     * Set instance, loads config, data and modules.
     */
    public function onEnable() : void {
        self::$instance = $this;
        $this->loadConfig();
        $this->loadProvider();
        $this->loadModules();
    }

    /**
     * Loads the config or copies the standard one.
     */
    private function loadConfig() : void {
        if($this->saveDefaultConfig()) {
            $this->getLogger()->info("Copied default config");
        }
        $this->config = new Config($this->getDataFolder()."config.yml", Config::YAML);
        $this->getLogger()->info("Config loaded");
    }

    /**
     * Selects a data provider and creates a connection.
     */
    private function loadProvider() : void {
        switch($this->getProperty("provider")) {
            case "sqlite":
                $this->provider = new SQLite3Provider($this);
                break;
            case "mysqli":
                //TODO
                break;
            default:
                $this->provider = new SQLite3Provider($this);
        }
        $this->getLogger()->info("Data provider: ".$this->provider->getName());
    }

    /**
     * Loads enabled modules from config.
     */
    private function loadModules() : void {
        if(!$this->loaded) {
            if($this->getProperty("authorization.enable")) {
                $this->getServer()->getPluginManager()->registerEvents(new Authorization(), $this);
            }
            $this->loaded = true;
            $this->getLogger()->info("Plugin loaded successfully");
        }
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
        //TODO: throw exception for safe error handling
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

    /**
     * @return UserSystem
     */
    public static function getInstance() : UserSystem {
        return self::$instance;
    }
}