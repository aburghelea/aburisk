<?php
/**
 * User: Alexandru George Burghelea
 * Date: 11.03.2013
 * Time: 14:28
 * For : PWeb 2013
 */
require_once("../game/GameEngine.php");
require_once("../game/GameState.php");

$ge = new GameEngine();
echo $ge->getGame()."\n";

echo $ge->getGame()."\n";
print_r($ge->getGame()->getRowsByField('id',$ge->getGame()->getId()));

echo "Change turn".$ge->changeTurn(2)."\n";

echo $ge->joinGame(3)."\n";
echo $ge->joinGame(2)."\n";
echo $ge->joinGame(2)."\n";

echo "Change turn".$ge->changeTurn(3)."\n";
echo "Change turn".$ge->changeTurn(2)."\n";

$ge->changeState('TEST');

?>