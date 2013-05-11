<?php
/**
 * User: Alexandru George Burghelea
 * Date: 3/14/13
 * Time: 10:05 PM
 * For : PWeb 2013
 */

require_once dirname(__FILE__) . "/script-constants.php";
require_once dirname(__FILE__) . "/../game/GameEngine.php";
require_once dirname(__FILE__) . "/../session/GameManager.php";
require_once dirname(__FILE__) . "/../logger/Aburlog.php";

if (areParamsSet($_POST)) {
    $gameEngine = new GameEngine($_POST[S_IDGAME]);
    if ($gameEngine->getGame() != null)
        Aburlog::getInstance()->logInfo("Joining game with id", $_POST[S_IDGAME]);
    else
        Aburlog::getInstance()->logInfo("Game retrive fail", $_POST[S_IDGAME]);

    $join_status = $gameEngine->joinGame($_POST[S_IDUSER]);
    if ($join_status == 1) {
        GameManager::setGameId($gameEngine);
        GameManager::initShips();
        GameManager::advanceStageIfNecessary();
        header('Location: ' . $_SERVER['CONTEXT_PREFIX'] . '/game.php');
        exit();
    } else {
        Aburlog::getInstance()->logWarn("Can not join game",$_POST);
    }
} else {
    Aburlog::getInstance()->logError("Game idiotic call to join-game",$_POST);
}
header('Location: ' . $_SERVER['CONTEXT_PREFIX'] . '/gameslist.php');
exit();

function areParamsSet()
{
    return isset($_POST[S_IDGAME]) && isset($_POST[S_IDUSER]);
}

?>
