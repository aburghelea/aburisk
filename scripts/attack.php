<?php
/**
 * User: Alexandru George Burghelea
 * Date: 3/14/13
 * Time: 10:47 PM
 * For : PWeb 2013
 */

require_once dirname(__FILE__) . "/script-constants.php";
require_once dirname(__FILE__) . "/../game/GameEngine.php";
require_once dirname(__FILE__) . "/../session/GameManager.php";

if (areParamsSet($_POST)) {
    $gameEngine = new GameEngine($_POST[S_IDGAME]);
    if ($gameEngine->getGame() == null) {
        $rtn['status'] = 'GAME_RETRIVE_FAIL';
        echo json_encode($rtn);
    }

    $attack_status = $gameEngine->attack($_POST[S_IDPLANET1], $_POST[S_IDPLANET2], $_POST[S_NOSHIPS], $_POST[S_IDUSER]);
    if (!($attack_status < 0)) {
        $rtn['status'] = 'SUCCESS';
        $rtn['owner'] = AuthManager::getLoggedInUserId();

        $gameEngine->signalUpdate();
        if (GameManager::getGameEngine()->isGameOver(GameManager::getCurrentPlayerId())) {
            $gameEngine->endGame(GameManager::getCurrentPlayerId());
        }
        $rtn['winner'] = AuthManager::getLoggedInUserId();
        echo json_encode($rtn);
    } else {
        $rtn['status'] = 'DEFEATED';
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

    return
        isset($_POST[S_IDGAME]) &&
        isset($_POST[S_IDUSER]) &&
        isset($_POST[S_IDPLANET1]) &&
        isset($_POST[S_IDPLANET2]) &&
        isset($_POST[S_NOSHIPS]);
}

?>