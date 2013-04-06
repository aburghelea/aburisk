<?php

/**
 * User: Alexandru George Burghelea
 * Date: 3/2/13
 * Time: 15:31 PM
 * For : PWeb 2013
 */

require_once dirname(__FILE__) . "/config.php";
require_once dirname(__FILE__) . "/../../inc/IDatabase.inc.php";

/**
 * Singleton container for mysqli connection
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