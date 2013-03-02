<?php

require_once("config.php");

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

//$connection = Database::connect();
//$query = "SELECT * FROM users where id = 1";
//$stmt = $connection->prepare($query);
//$stmt->execute();
//$stmt->bind_result($id, $username, $email, $password, $played_games, $won_games);
//$stmt->fetch();
//$stmt->close();
//echo $id . "&nbsp" . $username . "&nbsp" . $email . "&nbsp" . $password . "&nbsp" . $played_games . "&nbsp" . $won_games . "</br>";

?>