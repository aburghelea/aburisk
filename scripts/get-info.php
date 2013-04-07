<?php
/**
 * User: Alexandru George Burghelea
 * For : PWeb 2013
 */

require_once dirname(__FILE__)."/../session/GameManager.php";
if (!isset($_GET['about']) && !isset($_POST['about']))
    exit;
$about = isset($_GET['about']) ? $_GET['about'] : $_POST['about'];

if (strcmp($about, "planets") == 0) {
    planetsInfo();
}



function planetsInfo(){
    $planetsGamesDao = new Planet_Game();
    $planets = $planetsGamesDao->getRowsByField('game_id', GameManager::getGameId());
    echo json_encode($planets);
}
?>