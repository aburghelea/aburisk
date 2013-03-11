<?php
/**
 * User: Alexandru George Burghelea
 * Date: 11.03.2013
 * Time: 14:28
 * For : PWeb 2013
 */
require_once("../game/GameEngine.php");

$ge = new GameEngine();
echo $ge->getGame()."\n";
?>