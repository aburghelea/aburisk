<?php
/**
 * User: Alexandru George Burghelea
 * Date: 3/14/13
 * Time: 10:47 PM
 * For : PWeb 2013
 */

require_once("script-constants.php");
require_once("../game/GameEngine.php");

if (areParamsSet($_GET)) {
    $gameEngine = new GameEngine($_GET[S_IDGAME]);
    if ($gameEngine->getGame() != null)
        echo "Attacking a plent on game with the id " . $gameEngine->getGame()->getId() . "<br/>";
    else
        echo "Game was not retrieved<br/>";

    $attack_status = $gameEngine->attack($_GET[S_IDPLANET1], $_GET[S_IDPLANET2], $_GET[S_NOSHIPS], $_GET[S_IDUSER]);
    if (!($attack_status < 0)) {
        echo "Attack successfull<br/>";
        var_dump($attack_status);
    } else
        echo "Defeated<br/>";

} else {
    echo "Use all the necessary params<br/>";
}
function areParamsSet($_GET)
{
    return
        isset($_GET[S_IDGAME]) &&
        isset($_GET[S_IDUSER]) &&
        isset($_GET[S_IDPLANET1]) &&
        isset($_GET[S_IDPLANET2]) &&
        isset($_GET[S_NOSHIPS]);
}

?>