<?php
/**
 * User: Alexandru George Burghelea
 * Date: 3/14/13
 * Time: 10:28 PM
 * For : PWeb 2013
 */

require_once dirname(__FILE__) . "/script-constants.php";
require_once dirname(__FILE__) . "/../game/GameEngine.php";
require_once dirname(__FILE__) . "/../session/GameManager.php";
$rtn = array();
if (areParamsSet($_POST)) {
    $gameEngine = new GameEngine($_POST[S_IDGAME]);
    if ($gameEngine->getGame() == null)  {
        $rtn['status'] = 'GAME_RETRIVE_FAIL';
        echo json_encode($rtn);
    }

    $claim_staus = $gameEngine->claimPlanet($_POST[S_IDPLANET], $_POST[S_IDUSER]);
    if ($claim_staus > 0) {
        $rtn['status'] = 'SUCCESS';
        $rtn['owner'] = AuthManager::getLoggedInUserId();
        echo json_encode($rtn);
        $gameEngine->changeTurn(GameManager::getNextPlayer($_POST[S_IDUSER]));
        $gameEngine->signalUpdate($_POST[S_IDUSER]);
        GameManager::decreaseShips();
        GameManager::advanceStageIfNecessary();
    } else {
        $rtn['status'] = 'NOT_CLAIMED';
        echo json_encode($rtn);
    }

} else {
    $rtn['status'] = 'INSUFICIENT_PARAMS';
    echo json_encode($rtn);
}

//header('Location: ' . $_SERVER['CONTEXT_PREFIX'] . '/game.php');
//exit();

function areParamsSet()
{
    if (!isset($_POST[S_IDGAME]))
        $_POST[S_IDGAME] = GameManager::getGame()->id;
    if (!isset($_POST[S_IDUSER]))
        $_POST[S_IDUSER] = AuthManager::getLoggedInUserId();

    return isset($_POST[S_IDGAME]) && isset($_POST[S_IDUSER]) && isset($_POST[S_IDPLANET]);
}

?>