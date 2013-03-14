<?php

/**
 * User: Alexandru George Burghelea
 * Date: 11.03.2013
 * Time: 09:54 AM
 * For : PWeb 2013
 */

require_once("../generic/GenericDao.php");

/**
 * Planet CRUD/domain
 */
class Planet extends GenericDao
{
    protected $id;
    public $name;
    protected $containing_galaxy_id;
    public $image;

    function __construct()
    {
        self::$TABLE_NAME = 'planets';
        parent::__construct();
    }

    function __toString()
    {
        return "Planet: " . $this->id . " - " . $this->name . " - " . $this->containing_galaxy_id . " - " . $this->image;
    }

}

?>