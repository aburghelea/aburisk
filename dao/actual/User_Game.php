<?php

/**
 * User: Alexandru George Burghelea
 * Date: 11.03.2013
 * Time: 10:42 AM
 * For : PWeb 2013
 */

require_once dirname(__FILE__) . "/../generic/GenericDao.php";

/**
 * User_Game CRUD/domain
 */
class User_Game extends GenericDao
{
    public $user_id;
    public $score;
    public $game_id;
    public  $dirty;

    function __construct()
    {
        self::$TABLE_NAME = 'users_games';
        parent::__construct();
    }

    function getJoinedPlayers($game)
    {
        if (!isset($game))
            return 0;

        $userGameDao = new User_Game();
        $games = $userGameDao->getRowsByField('game_id', $game->id);
        if (is_array($games))
            return count($games);

        return 0;
    }

    function __toString()
    {
        return "User Game: " . $this->user_id . " - " . $this->score . " - " . $this->game_id;
    }

}

?>