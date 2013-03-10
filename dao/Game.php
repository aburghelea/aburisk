<?php
/**
 * User: Alexandru George Burghelea
 * Date: 3/10/13
 * Time: 11:57 PM
 * For : PWeb 2013
 */
require_once("GenericDao.php");
class Game extends GenericDao
{
    protected  $id;
    public $noplayers;
    public $state;
    public $current_player_id;

    function __construct()
    {
        self::$TABLE_NAME  = 'games';
        parent::__construct();
    }

    function __toString()
    {
        return "Game: " . $this->id . " - " . $this->noplayers." - " . $this->state." - " . $this->current_player_id;
    }

}

$game = new Game();
$games = $game->getRowsByField('id', '1');
foreach ($games as $gl){
    echo $gl."\n";
}
