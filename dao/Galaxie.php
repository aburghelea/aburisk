<?php
require_once("GenericDao.php");

/**
 * User: Alexandru George Burghelea
 * Date: 3/10/13
 * Time: 10:53 PM
 * For : PWeb 2013
 */
class Galaxie extends GenericDao
{
    protected  $id;
    public $name;

    function __construct()
    {
        self::$TABLE_NAME  = 'galaxies';
        parent::__construct();
    }

    function __toString()
    {
        return "Galaxie: " . $this->id . " - " . $this->name;
    }

}

$galaxie = new Galaxie();
$galaxies = $galaxie->getRowsByField('id', '1');
foreach ($galaxies as $gl){
    echo $gl."\n";
}
?>
