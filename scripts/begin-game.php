<?php
/**
 * User: Alexandru George Burghelea
 * Date: 3/14/13
 * Time: 10:17 PM
 * For : PWeb 2013
 */

require_once dirname(__FILE__)."/script-constants.php";
require_once dirname(__FILE__)."/../game/GameEngine.php";
require_once dirname(__FILE__) . "/../logger/Aburlog.php";

if (areParamsSet($_GET)) {
    $gameEngine = new GameEngine($_GET[S_IDGAME]);
    if ($gameEngine->getGame() != null)
        echo "Changing state of game with the id " . $gameEngine->getGame()->getId() . "<br/>";
    else
        Aburlog::getInstance()->logInfo("Game retrive fail", $_POST[S_IDGAME]);

    $gameEngine->changeState(GameState::PLANET_CLAIM);

} else {
    Aburlog::getInstance()->logError("Game idiotic call to game-begin", $_POST);
}
function areParamsSet($_GET)
{
    return isset($_GET[S_IDGAME]);
}

?>