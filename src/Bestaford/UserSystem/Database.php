<?php

namespace Bestaford\UserSystem;

use SQLite3;
use SQLite3Stmt;
use SQLite3Result;

/**
 * Class Database
 * @package Bestaford\UserSystem
 */
class Database {

	/**
	 * @var UserSystem
	 */
	public UserSystem $plugin;

	/**
	 * @var SQLite3
	 */
	public SQLite3 $database;

	/**
	 * @var SQLite3Stmt
	 */
	public SQLite3Stmt $statement;

	/**
	 * @var SQLite3Result
	 */
	public SQLite3Result $result;

	/**
	 * Database constructor.
	 * @param UserSystem $plugin
	 * @param string $name
	 */
	public function __construct(UserSystem $plugin, string $name) {
		$this->plugin = $plugin;
		$this->database = new SQLite3($plugin->getDataFolder().$name.".db");
		$this->createTable($name);
	}

	/**
	 * @param string $name
	 */
	public function createTable(string $name) {
		$this->database->exec(stream_get_contents($this->plugin->getResource($name.".sql")));
	}

	/**
	 * @param string $query
	 */
	public function prepare(string $query) {
		$this->statement = $this->database->prepare($query);
	}

	/**
	 * @param string $name
	 * @param $value
	 */
	public function bind(string $name, $value) {
		$this->statement->bindValue($name, $value);
	}

	public function execute()  {
		$this->result = $this->statement->execute();
	}

	/**
	 * @return array
	 */
	public function get() : array {
		return $this->result->fetchArray(SQLITE3_ASSOC);
	}
}