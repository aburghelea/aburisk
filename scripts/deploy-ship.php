<?php
/**
 * User: Alexandru George Burghelea
 * Date: 3/14/13
 * Time: 10:41 PM
 * For : PWeb 2013
 */

require_once dirname(__FILE__) . "/script-constants.php";
require_once dirname(__FILE__) . "/../game/GameEngine.php";
require_once dirname(__FILE__) . "/../session/GameManager.php";
$rtn = array();
if (areParamsSet($_POST)) {
    $gameEngine = new GameEngine($_POST[S_IDGAME]);
    if ($gameEngine->getGame() == null) {
        $rtn['status'] = 'GAME_RETRIVE_FAIL';
        echo json_encode($rtn);
    }

    $deploy = -1;
    if (GameManager::getRemainingShips() > 0)
        $deploy = $gameEngine->deployShip($_POST[S_IDPLANET], $_POST[S_IDUSER]);
    if ($deploy > 0) {
        $rtn['status'] = 'SUCCESS';
        echo json_encode($rtn);
        $gameEngine->signalUpdate();
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