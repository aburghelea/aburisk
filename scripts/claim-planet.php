<?php
/**
 * User: Alexandru George Burghelea
 * Date: 3/14/13
 * Time: 10:28 PM
 * For : PWeb 2013
 */

require_once("script-constants.php");
require_once("../game/GameEngine.php");

if (areParamsSet($_GET)) {
    $gameEngine = new GameEngine($_GET[S_IDGAME]);
    if ($gameEngine->getGame() != null)
        echo "Claiming planet on game with the id " . $gameEngine->getGame()->getId() . "<br/>";
    else
        echo "Game was not retrieved<br/>";

    $claim_staus = $gameEngine->claimPlanet($_GET[S_IDPLANET], $_GET[S_IDUSER]);
    if ($claim_staus > 0)
        echo "Planet claimed<br/>";
    else
        echo "Planet not claimed<br/>";

} else {
    echo "Use all the necessary params<br/>";
}
function areParamsSet($_GET)
{
    return isset($_GET[S_IDGAME]) && isset($_GET[S_IDUSER]) && isset($_GET[S_IDPLANET]);
}

?>