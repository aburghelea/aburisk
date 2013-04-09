<?php
/**
 * User: Alexandru George Burghelea
 * Date: 09.04.2013
 * Time: 14:41
 * For : PWeb 2013
 */

session_start();

require_once dirname(__FILE__) . "/../game/GameEngine.php";
require_once dirname(__FILE__) . "/../session/GameManager.php";

$gameEngine = new GameEngine($_GET['id']);

for ($i = 1; $i < 18; $i++)
    $claim_staus = $gameEngine->claimPlanet($i, $i % 2 == 0 ? 3 : 1);

?>