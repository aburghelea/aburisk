<?php
/**
 * User: Alexandru George Burghelea
 * Date: 3/14/13
 * Time: 9:54 PM
 * For : PWeb 2013
 */

require_once("script-constants.php");
require_once("../game/GameEngine.php");

if (areParamsSet($_GET)) {
    $gameEngine = new GameEngine(0, $_GET[S_NOPLAYERS], $_GET[S_IDHOST]);
    if ($gameEngine->getGame() != null)
        echo "Created a game with the id " . $gameEngine->getGame()->getId() . "<br/>";
    else
        echo "Game was not created<br/>";
} else {
    echo "Use all the necessary params<br/>";
}
function areParamsSet($_GET)
{
    return isset($_GET[S_IDHOST]) && isset($_GET[S_NOPLAYERS]);
}

?>