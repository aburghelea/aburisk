<?php
/**
 * User: Alexandru George Burghelea
 * Date: 3/14/13
 * Time: 10:28 PM
 * For : PWeb 2013
 */

require_once dirname(__FILE__) . "/script-constants.php";
require_once dirname(__FILE__) . "/../game/GameEngine.php";
require_once dirname(__FILE__)."/../session/GameManager.php";

if (areParamsSet($_POST)) {
    $gameEngine = new GameEngine($_POST[S_IDGAME]);
    if ($gameEngine->getGame() != null)
        echo "Claiming planet on game with the id " . $gameEngine->getGame()->getId() . "<br/>";
    else
        echo "Game was not retrieved<br/>";

    $claim_staus = $gameEngine->claimPlanet($_POST[S_IDPLANET], $_POST[S_IDUSER]);
    if ($claim_staus > 0) {
        $gameEngine->changeTurn(GameManager::getNextPlayer($_POST[S_IDUSER]));


    } else
        echo "Planet not claimed<br/>";

} else {
    echo "Use all the necessary params<br/>";
}

header('Location: ' . $_SERVER['CONTEXT_PREFIX'] . '/game.php');
exit();

function areParamsSet()
{
    return isset($_POST[S_IDGAME]) && isset($_POST[S_IDUSER]) && isset($_POST[S_IDPLANET]);
}

?>