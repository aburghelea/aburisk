<?php

/**
 * User: Alexandru George Burghelea
 * Date: 11.03.2013
 * Time: 10:42 AM
 * For : PWeb 2013
 */

require_once("../generic/GenericDao.php");

class User_Game extends GenericDao
{
    protected $user_id;
    public $score;
    protected $game_id;

    function __construct()
    {
        self::$TABLE_NAME = 'users_games';
        parent::__construct();
    }

    function __toString()
    {
        return "User Game: " . $this->user_id . " - " . $this->score . " - " . $this->game_id;
    }
}

?>