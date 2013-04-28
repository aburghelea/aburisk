<?php
/**
 * User: Alexandru George Burghelea
 * For : PWeb 2013
 */

require_once dirname(__FILE__) . "/../session/GameManager.php";
require_once dirname(__FILE__) . "/../dao/actual/Planet.php";
require_once dirname(__FILE__) . "/../dao/actual/Planet_Neighbour.php";
if (!isset($_GET['about']) && !isset($_POST['about']))
    exit;
$about = isset($_GET['about']) ? $_GET['about'] : $_POST['about'];

if (strcmp($about, "planets") == 0) {
    planetsInfo();
}

if (strcmp($about, "all") == 0) {
    all();
}


function planetsInfo()
{
    $planetsGamesDao = new Planet_Game();
    $planets = $planetsGamesDao->getRowsByField('game_id', GameManager::getGameId());
    echo json_encode($planets);
}

function all()
{
    $planetDao = new Planet();
    $planetNeighboursDao = new Planet_Neighbour();
    $planetsJSON = $planetDao->getRowsByField('"1"', '1');
    $connectiosJSON = $planetNeighboursDao->getRowsByField('"1"', '1');

    echo json_encode(array("planets" => $planetsJSON, "connections" => $connectiosJSON));
}

?>