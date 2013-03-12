<?php

/**
 * User: Alexandru George Burghelea
 * Date: 11.03.2013
 * Time: 10:18 AM
 * For : PWeb 2013
 */

require_once('../generic/GenericDao.php');

class Planet_Game extends GenericDao
{
    protected $id;
    public $planet_id;
    public $owner_id;
    public $game_id;
    public $noships;
    public $x_axis;
    public $y_axis;
    public $radius;

    function __construct()
    {
        self::$TABLE_NAME = 'planets_games';
        parent::__construct();
    }

    function __toString()
    {
        return "Planet_game: " . $this->planet_id . " - " . $this->owner_id . " - " . $this->game_id . " - " . $this->noships . " - " . $this->x_axis . " - " . $this->y_axis . " - " . $this->radius;
    }

    public function getId()
    {
        return $this->id;
    }

}

?>