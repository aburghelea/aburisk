<?php

/**
 * User: Alexandru George Burghelea
 * Date: 11.03.2013
 * Time: 10:54
 * For : PWeb 2013
 */

require_once("GenericDao.php");

class User extends GenericDao
{
    protected $id;
    public $username;
    public $email;
    protected $password;
    public $played_games;
    public $won_games;


    function __construct()
    {
        self::$TABLE_NAME = 'users';
        parent::__construct();
    }

    function __toString()
    {
        return "Planet: " . $this->id . " - " . $this->username . " - " . $this->email . " - " . $this->played_games . " - " . $this->won_games;
    }
}

?>