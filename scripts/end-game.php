<?php
/**
 * User: Alexandru George Burghelea
 * For : PWeb 2013
 */
require_once dirname(__FILE__) . "/script-constants.php";
require_once dirname(__FILE__) . "/../game/GameEngine.php";
require_once dirname(__FILE__) . "/../session/GameManager.php";

if (areParamsSet($_POST)) {
    $gameEngine = new GameEngine($_POST[S_IDGAME]);

    $end_status = $gameEngine->endGame();
    if ($end_status == 1) {
        GameManager::setGameId(null);
        header('Location: ' . $_SERVER['CONTEXT_PREFIX'] . '/games_list.php');
        exit();
    } else {
        header('Location: ' . $_SERVER['CONTEXT_PREFIX'] . '/games.php');
        exit();
    }
} else {
    echo "Use all the necessary params<br/>";
    header('Location: ' . $_SERVER['CONTEXT_PREFIX'] . '/games.php');
    exit();
}

function areParamsSet()
{
    return isset($_POST[S_IDGAME]);
}

?>