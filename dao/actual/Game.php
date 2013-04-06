<?php
/**
 * User: Alexandru George Burghelea
 * Date: 3/10/13
 * Time: 11:57 PM
 * For : PWeb 2013
 */

require_once dirname(__FILE__) . "/../generic/GenericDao.php";

/**
 * Game CRUD/domain
 */
class Game extends GenericDao
{
    const gamesUserCanJoin = "select * from games where state not like 'GAME_END'  and id not in (select game_id from users_games where user_id = %s)";

    public $id;
    public $noplayers;
    public $state;
    public $current_player_id;

    function __construct()
    {
        self::$TABLE_NAME = 'games';
        parent::__construct();
    }

    function __toString()
    {
        return "Game: " . $this->id . " - " . $this->noplayers . " - " . $this->state . " - " . $this->current_player_id;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getCurrentPlayerId()
    {
        return $this->current_player_id;
    }

    function getGamesUserCanPlay($uid)
    {
        $gameDao = new Game();

        return $gameDao->getCustomRows(sprintf(self::gamesUserCanJoin, $uid));
    }

}

?>