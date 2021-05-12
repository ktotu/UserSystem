<?php

declare(strict_types = 1);

namespace Bestaford\UserSystem\provider;

use Bestaford\UserSystem\UserSystem;
use SQLite3;
use SQLite3Result;
use SQLite3Stmt;

class SQLite implements Provider {

    /** @var SQLite3 */
    private SQLite3 $database;

    /** @var SQLite3Stmt */
    private SQLite3Stmt $statement;

    /** @var SQLite3Result */
    private SQLite3Result $result;

    /**
     * SQLite constructor.
     * @param UserSystem $plugin
     */
    public function __construct(UserSystem $plugin) {
        $path = str_replace("%data", $plugin->getDataFolder(), $plugin->getProperty("sqlite.path"));
        $name = $plugin->getProperty("sqlite.name");
        $this->database = new SQLite3($path.$name);
    }

    /**
     * Returns true if query executed successfully, false otherwise.
     *
     * @param string $query
     * @return bool
     */
    public function exec(string $query) : bool {
        return $this->database->exec($query);
    }

    /**
     * Prepare statement for bind and execute.
     *
     * @param string $query
     */
    public function prepare(string $query) : void {
        $this->statement = $this->database->prepare($query);
    }

    /**
     * Bind parameter by number.
     *
     * @param int $param
     * @param mixed $var
     */
    public function bindParam(int $param, $var) : void {
        $this->statement->bindParam($param, $var);
    }

    /**
     * Execute the statement.
     */
    public function execute() : void {
        $this->result = $this->statement->execute();
    }

    /**
     * Fetch result table to assoc array.
     *
     * @return array
     */
    public function fetch() : array {
        $row = [];
        while($res = $this->result->fetchArray(SQLITE3_ASSOC)) {
            $row[] = $res;
        }
        return $row;
    }
}