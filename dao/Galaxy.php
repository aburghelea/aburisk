<?php
/**
 * User: Alexandru George Burghelea
 * Date: 3/10/13
 * Time: 10:53 PM
 * For : PWeb 2013
 */

require_once("../generic/GenericDao.php");

/**
 * Gaxaly CRUD/domain
 */
class Galaxy extends GenericDao
{
    protected $id;
    public $name;

    function __construct()
    {
        self::$TABLE_NAME = 'galaxies';
        parent::__construct();
    }

    function __toString()
    {
        return "Galaxy: " . $this->id . " - " . $this->name;
    }

}

?>
