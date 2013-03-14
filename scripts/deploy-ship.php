<?php
/**
 * User: Alexandru George Burghelea
 * Date: 3/14/13
 * Time: 10:41 PM
 * For : PWeb 2013
 */

require_once("script-constants.php");
require_once("../game/GameEngine.php");

if (areParamsSet($_GET)) {
    $gameEngine = new GameEngine($_GET[S_IDGAME]);
    if ($gameEngine->getGame() != null)
        echo "Deploying ships on game with the id " . $gameEngine->getGame()->getId() . "<br/>";
    else
        echo "Game was not retrieved<br/>";

    $deploy = $gameEngine->deployShip($_GET[S_IDPLANET], $_GET[S_IDUSER]);
    if ($deploy > 0)
        echo "Troops deployed<br/>";
    else
        echo "No reinforcements comming<br/>";

} else {
    echo "Use all the necessary params<br/>";
}
function areParamsSet($_GET)
{
    return isset($_GET[S_IDGAME]) && isset($_GET[S_IDUSER]) && isset($_GET[S_IDPLANET]);
}

?>