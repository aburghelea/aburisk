<?php
require_once("config.php");

class Database
{

    public static function  connect()
    {
        return new mysqli(HOST, USER, PASSWORD, DATABASE, PORT, SOCKET) or DIE("Could not connect ");
    }


}

Database::connect();
?>