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

if (areParamsSet($_POST)) {
    $gameEngine = new GameEngine($_POST[S_IDGAME]);
    if ($gameEngine->getGame() != null)
        echo "Deploying ships on game with the id " . $gameEngine->getGame()->getId() . "<br/>";
    else
        echo "Game was not retrieved<br/>";

    $deploy = -1;
    if (GameManager::getRemainingShips() > 0)
        $deploy = $gameEngine->deployShip($_POST[S_IDPLANET], $_POST[S_IDUSER]);
    if ($deploy > 0) {
        GameManager::decreaseShips();
        GameManager::advanceStageIfNecessary();
        echo "Troops deployed<br/>";
    } else
        echo "No reinforcements comming<br/>";

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