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
//echo $ge->getGame()."\n";

echo $ge->getGame()."\n";
//print_r($ge->getGame()->getRowsByField('id',$ge->getGame()->getId()));

//echo "Change turn".$ge->changeTurn(2)."\n";

//echo $ge->joinGame(3)."\n";
//echo $ge->joinGame(2)."\n";
//echo $ge->joinGame(2)."\n";

$ge->joinGame(3);
$ge->joinGame(2);
//echo "Change turn".$ge->changeTurn(3)."\n";
//echo "Change turn".$ge->changeTurn(2)."\n";
$ge->changeTurn(3);
$ge->changeTurn(2);

echo "Claiming planet ".$ge->claimPlanet(1,2)."\n";
echo "Claiming planet ".$ge->claimPlanet(2,2)."\n";
echo "Claiming planet ".$ge->claimPlanet(3,1)."\n";
echo "Claiming planet ".$ge->claimPlanet(4,1)."\n";

echo $ge->deployShip(2,2);
echo $ge->deployShip(2,2);
echo $ge->deployShip(2,2);
echo $ge->deployShip(2,2);

echo "\n";
echo "move ";
print_r($ge->move(3,1,1,2));
print_r($ge->move(2,1,1,2));
print_r($ge->move(2,1,1,2));
print_r($ge->move(2,1,1,2));
print_r($ge->move(2,1,1,2));
print_r($ge->move(2,1,1,2));

?>