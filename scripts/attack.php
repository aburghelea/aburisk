<?php
/**
 * User: Alexandru George Burghelea
 * Date: 3/14/13
 * Time: 10:47 PM
 * For : PWeb 2013
 */

require_once dirname(__FILE__) . "/script-constants.php";
require_once dirname(__FILE__) . "/../game/GameEngine.php";
require_once dirname(__FILE__) . "/../session/GameManager.php";

if (areParamsSet($_POST)) {
    $gameEngine = new GameEngine($_POST[S_IDGAME]);
    if ($gameEngine->getGame() != null) {
        echo "Attacking a plent on game with the id " . $gameEngine->getGame()->getId() . "<br/>";
    }
    else
        echo "Game was not retrieved<br/>";

    $attack_status = $gameEngine->attack($_POST[S_IDPLANET1], $_POST[S_IDPLANET2], $_POST[S_NOSHIPS], $_POST[S_IDUSER]);
    if (!($attack_status < 0)) {
        echo "Attack successfull<br/>";
        if (GameManager::getGameEngine()->isGameOver(GameManager::getCurrentPlayerId())) {
            $gameEngine->endGame(GameManager::getCurrentPlayerId());
        }
    } else
        echo "Defeated<br/>";

} else {
    echo "Use all the necessary params<br/>";
}

header('Location: ' . $_SERVER['CONTEXT_PREFIX'] . '/game.php');
exit();

function areParamsSet()
{

    echo "1" . (isset($_POST[S_IDGAME]) ? "DA" : "NU") . "<br/>";
    echo "2" . (isset($_POST[S_IDUSER]) ? "DA" : "NU") . "<br/>";
    echo "3" . (isset($_POST[S_IDPLANET1]) ? "DA" : "NU") . "<br/>";
    echo "4" . (isset($_POST[S_IDPLANET2]) ? "DA" : "NU") . "<br/>";
    echo "5" . (isset($_POST[S_NOSHIPS]) ? "DA" : "NU") . "<br/>";
    return
        isset($_POST[S_IDGAME]) &&
        isset($_POST[S_IDUSER]) &&
        isset($_POST[S_IDPLANET1]) &&
        isset($_POST[S_IDPLANET2]) &&
        isset($_POST[S_NOSHIPS]);
}

?>