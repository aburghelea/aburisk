<?php
/**
 * User: Alexandru George Burghelea
 * For : PWeb 2013
 */

require_once dirname(__FILE__) . "/../session/GameManager.php";
require_once dirname(__FILE__) . "/../dao/actual/Planet.php";
require_once dirname(__FILE__) . "/../dao/actual/Planet_Neighbour.php";
require_once dirname(__FILE__) . "/../logger/Aburlog.php";

if (!isset($_GET['about']) && !isset($_POST['about']))
    exit;
$about = isset($_GET['about']) ? $_GET['about'] : $_POST['about'];

if (strcmp($about, "planets_games") == 0) {
    planetsInfo();
}

if (strcmp($about, "planet_connections") == 0) {
    planet_connections();
}

if (strcmp($about, "all") == 0) {
    all();
}

function all() {
    $planetDao = new Planet();
    $planetNeighboursDao = new Planet_Neighbour();
    $planetsGamesDao = new Planet_Game();
    $planets = $planetDao->getRowsByField('"1"', '1');
    $connectios = $planetNeighboursDao->getRowsByField('"1"', '1');
    $planets_games = $planetsGamesDao->getRowsByField('game_id', GameManager::getGameId());

    $rtn = array();
    $rtn['planets'] = $planets;
    $rtn['connections'] = $connectios;
    $rtn['planetsGames'] = $planets_games;

    echo json_encode($rtn);
}

function planetsInfo()
{
    $planetsGamesDao = new Planet_Game();
    $planets = $planetsGamesDao->getRowsByField('game_id', GameManager::getGameId());
    echo json_encode($planets);
}

function planet_connections()
{
    $planetDao = new Planet();
    $planetNeighboursDao = new Planet_Neighbour();
    $planetsJSON = $planetDao->getRowsByField('"1"', '1');
    $connectiosJSON = $planetNeighboursDao->getRowsByField('"1"', '1');

    echo json_encode(array("planets" => $planetsJSON, "connections" => $connectiosJSON));
}

?>