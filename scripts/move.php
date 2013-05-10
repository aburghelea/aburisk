<?php
/**
 * User: Alexandru George Burghelea
 * Date: 3/14/13
 * Time: 11:01 PM
 * For : PWeb 2013
 */

require_once dirname(__FILE__) . "/script-constants.php";
require_once dirname(__FILE__) . "/../game/GameEngine.php";
require_once dirname(__FILE__) . "/../logger/Aburlog.php";

if (areParamsSet($_POST)) {
    $gameEngine = new GameEngine($_POST[S_IDGAME]);
    if ($gameEngine->getGame() != null)
        Aburlog::getInstance()->logInfo("Moving ships on game with id", $_POST[S_IDGAME]);
    else
        Aburlog::getInstance()->logInfo("Game retrive fail", $_POST[S_IDGAME]);

    $move_status = $gameEngine->move($_POST[S_IDPLANET1], $_POST[S_IDPLANET2], $_POST[S_NOSHIPS], $_POST[S_IDUSER]);
    if ($move_status < 0) {
        Aburlog::getInstance()->logInfo("Move fail", $_POST[S_IDGAME]);

    } else {
        Aburlog::getInstance()->logError("Game idiotic call to move", $_POST);
    }
}
function areParamsSet()
{
    return
        isset($_POST[S_IDGAME]) &&
        isset($_POST[S_IDUSER]) &&
        isset($_POST[S_IDPLANET1]) &&
        isset($_POST[S_IDPLANET2]) &&
        isset($_POST[S_NOSHIPS]);
}


?>