<?php

require_once("config.php");
require_once("../base/IDatabase.inc.php");

class Database implements IDatabase
{

    private static $instance = NULL;

    private static function create()
    {
        self::$instance = new mysqli(HOST, USER, PASSWORD, DATABASE, PORT);

        if (self::$instance->connect_error) {
            die('Connect Error (' . self::$instance->connect_errno . ') ' . self::$instance->connect_error);
        }

        return self::$instance;
    }

    public static function connect()
    {
        if (!isset(self::$instance)) {
            return Database::create();
        }
        return self::$instance;
    }

}

?>