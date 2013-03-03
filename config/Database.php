<?php

require_once("config.php");
require_once("../base/IDatabase.inc.php");

/**
 * Singleton for mysqli connection
 */
class Database implements IDatabase
{

    private static $instance = NULL;

    private function __construct()
    {
    }

    /**
     * @return mysqli|null A connection to the database
     */
    public static function connect()
    {
        if (!isset(self::$instance)) {
            self::$instance = new mysqli(HOST, USER, PASSWORD, DATABASE, PORT);

            if (self::$instance->connect_error) {
                die('Connect Error (' . self::$instance->connect_errno . ') ' . self::$instance->connect_error);
            }
        }
        return self::$instance;
    }

}

?>