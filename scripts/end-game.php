<?php
/**
 * User: Alexandru George Burghelea
 * For : PWeb 2013
 */
require_once dirname(__FILE__) . "/script-constants.php";
require_once dirname(__FILE__) . "/../game/GameEngine.php";
require_once dirname(__FILE__) . "/../session/GameManager.php";
require_once dirname(__FILE__) . "/../logger/Aburlog.php";

if (areParamsSet($_POST)) {
    $gameEngine = new GameEngine($_POST[S_IDGAME]);

    $gameEngine->signalUpdate();
    $end_status = $gameEngine->endGame(null, true);
    if ($end_status == 1) {
        GameManager::setGameId(null);
        Aburlog::getInstance("Game ended", $gameEngine);
    }

} else {
    Aburlog::getInstance()->logError("Game idiotic call to end-game", $_POST);
}
header('Location: ' . $_SERVER['CONTEXT_PREFIX'] . '/games_list.php');
exit();


function areParamsSet()
{
    if (!isset($_POST[S_IDGAME]))
        $_POST[S_IDGAME] = GameManager::getGame()->id;
    return isset($_POST[S_IDGAME]);
}

?>