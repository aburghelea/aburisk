<?php
/**
 * User: Alexandru George Burghelea
 * Date: 3/14/13
 * Time: 9:54 PM
 * For : PWeb 2013
 */

require_once dirname(__FILE__)."/script-constants.php";
require_once dirname(__FILE__)."/../game/GameEngine.php";

if (areParamsSet()) {
    $gameEngine = new GameEngine(0, $_POST[S_NOPLAYERS], $_POST[S_IDHOST]);
    if ($gameEngine->getGame() != null)
        echo "Created a game with the id " . $gameEngine->getGame()->getId() . "<br/>";
    else
        echo "Game was not created<br/>";
} else {
    echo "Use all the necessary params<br/>";
}
function areParamsSet()
{
    return isset($_POST[S_IDHOST]) && isset($_POST[S_NOPLAYERS]);
}

?>