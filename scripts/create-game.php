<?php
/**
 * User: Alexandru George Burghelea
 * Date: 3/14/13
 * Time: 9:54 PM
 * For : PWeb 2013
 */

require_once dirname(__FILE__) . "/script-constants.php";
require_once dirname(__FILE__) . "/../game/GameEngine.php";
require_once dirname(__FILE__)."/../session/GameManager.php";
require_once dirname(__FILE__) . "/../logger/Aburlog.php";

if (areParamsSet()) {
    $gameEngine = new GameEngine(0, $_POST[S_NOPLAYERS], $_POST[S_IDHOST]);
    if ($gameEngine->getGame() != null) {
        Aburlog::getInstance()->logInfo("Created game", $gameEngine->getGame()->getId());
        GameManager::setGameId($gameEngine);
        GameManager::initShips();
        header('Location: ' . $_SERVER['CONTEXT_PREFIX'] . '/game.php');
        exit();
    } else
        Aburlog::getInstance()->logInfo("Game not created",$_POST);
} else {
    Aburlog::getInstance()->logError("Game idiotic call to create-game",$_POST);
}

header('Location: ' . $_SERVER['CONTEXT_PREFIX'] . '/games_list.php');
exit();
function areParamsSet()
{
    return isset($_POST[S_IDHOST]) && isset($_POST[S_NOPLAYERS]);
}

?>