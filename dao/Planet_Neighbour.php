<?php

/**
 * User: Alexandru George Burghelea
 * Date: 11.03.2013
 * Time: 10:30 AM
 * For : PWeb 2013
 */

require_once("GenericDao.php");

class Planet_Neighbour extends GenericDao
{
    protected $first_planet_id;
    protected $second_planet_id;

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

$pn = new Planet_Neighbour();
$pns = $pn->getRowsByField('first_planet_id',1);
foreach ($pns as $obj) {
    echo $obj . "\n<br/>";
}

?>