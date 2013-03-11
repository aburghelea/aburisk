<?php

/**
 * User: Alexandru George Burghelea
 * Date: 11.03.2013
 * Time: 10:42 AM
 * For : PWeb 2013
 */

require_once("GenericDao.php");

class User_Game extends GenericDao
{
    protected $user_id;
    public $score;
    protected $games_id;

    function __construct()
    {
        self::$TABLE_NAME = 'users_games';
        parent::__construct();
    }

    function __toString()
    {
        return "User Game: " . $this->user_id . " - " . $this->score . " - " . $this->games_id;
    }
}



$ug = new User_Game();
$ugs = $ug->getRowsByField('user_id', '1');
foreach ($ugs as $obj) {
    echo $obj . "\n";
}
?>