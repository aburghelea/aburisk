<?php
/**
 * User: Alexandru George Burghelea
 * Date: 3/14/13
 * Time: 10:05 PM
 * For : PWeb 2013
 */

require_once("script-constants.php");
require_once("../game/GameEngine.php");

if (areParamsSet($_GET)) {
    $gameEngine = new GameEngine($_GET[S_IDGAME]);
    if ($gameEngine->getGame() != null)
        echo "Retrived game with the id " . $gameEngine->getGame()->getId() . "<br/>";
    else
        echo "Game was not retrieved<br/>";

    $join_status = $gameEngine->joinGame($_GET[S_IDUSER]);
    if ($join_status == 1)
        echo "Successfully joined game<br/>";
    else
        echo "Could not join game <br/>";
} else {
    echo "Use all the necessary params<br/>";
}
function areParamsSet($_GET)
{
    return isset($_GET[S_IDGAME]) && isset($_GET[S_IDUSER]);
}

?>
