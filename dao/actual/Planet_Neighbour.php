<?php

/**
 * User: Alexandru George Burghelea
 * Date: 11.03.2013
 * Time: 10:30 AM
 * For : PWeb 2013
 */

/**
 * Planet_Neighbour CRUD/domain
 */
require_once dirname(__FILE__) . "/../generic/GenericDao.php";

class Planet_Neighbour extends GenericDao
{
    public $first_planet_id;
    public $second_planet_id;

    function __construct()
    {
        self::$TABLE_NAME = 'planets_neighbours';
        parent::__construct();
    }

    function __toString()
    {
        return "Neighbours: " . $this->first_planet_id . " - " . $this->second_planet_id;
    }
}

?>