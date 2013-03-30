<?php
/**
 * User: Alexandru George Burghelea
 * Date: 3/14/13
 * Time: 11:01 PM
 * For : PWeb 2013
 */

require_once dirname(__FILE__)."/script-constants.php";
require_once dirname(__FILE__)."/../game/GameEngine.php";

if (areParamsSet($_GET)) {
    $gameEngine = new GameEngine($_GET[S_IDGAME]);
    if ($gameEngine->getGame() != null)
        echo "Moving ships on game with id " . $gameEngine->getGame()->getId() . "<br/>";
    else
        echo "Game was not retrieved<br/>";

    $move_status = $gameEngine->move($_GET[S_IDPLANET1], $_GET[S_IDPLANET2], $_GET[S_NOSHIPS], $_GET[S_IDUSER]);
    if (!($move_status < 0)) {
        echo "Move successfull<br/>";
        var_dump($move_status);
    } else
        echo "Could not move<br/>";

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