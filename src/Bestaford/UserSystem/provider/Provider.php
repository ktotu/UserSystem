<?php

declare(strict_types = 1);

namespace Bestaford\UserSystem\provider;

use Bestaford\UserSystem\UserSystem;

/**
 * Interface Provider
 * @author Bestaford
 * @link https://talk.24serv.pro/u/bestaford
 * @package Bestaford\UserSystem\provider
 */
interface Provider {

    /**
     * Provider constructor.
     * @param UserSystem $plugin
     */
    public function __construct(UserSystem $plugin);

    /**
     * Returns true if query executed successfully, false otherwise.
     *
     * @param string $query
     * @return bool
     */
    public function exec(string $query) : bool;

    /**
     * Prepare statement for bind and execute.
     *
     * @param string $query
     */
    public function prepare(string $query) : void;

    /**
     * Bind parameter by number.
     *
     * @param int $param
     * @param mixed $var
     */
    public function bindParam(int $param, $var) : void;

    /**
     * Execute the statement.
     */
    public function execute() : void;

    /**
     * Fetch result table to assoc array.
     *
     * @return array
     */
    public function fetch() : array;
}