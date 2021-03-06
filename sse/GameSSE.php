<?php
/**
 * User: Alexandru George Burghelea
 * For : PWeb 2013
 */

require_once dirname(__FILE__) . "/../session/GameManager.php";
header('Content-Type: text/event-stream');
header('Cache-Control: no-cache');

function sendData($id)
{
    $return = null;
    try {
        if (GameManager::isModified() === true) {
            $return = array("status" => "UPDATE");
            $return['animation_info'] = GameManager::getAnimationData();
            $return['player_list'] = GameManager::getPlayers();
            $return['action'] = !GameManager::isLoggedInPlayersTurn() ? 'NONE' : GameManager::getGame()->state;
            $return['state'] = GameManager::getGame()->state;
            $return['ships'] = GameManager::getRemainingShips();
            $return['neededPlayers'] = GameManager::getGame()->noplayers;
            $return['joinedPlayers'] = GameManager::getJoinedPlayersNumber();
            $winner = GameManager::getWinner();
            if ($winner != NULL)
                $winner = new Player($winner);
            $return['winner'] = $winner;
            $username = GameManager::getCurrentPlayerUsername();
            $username = substr($username, 0, strpos($username, "@"));
            $currentPlayer = array("id" => GameManager::getCurrentPlayerId(), "username" => $username);
            $return['currentPlayer'] = $currentPlayer;
        } else {
            $return = array("status" => "EXIT");
        }
    } catch (Exception $e) {

    }


    echo "id: $id" . PHP_EOL;
    echo "retry: 1200" . PHP_EOL;
    echo "data: " . json_encode($return) . PHP_EOL;
    echo PHP_EOL;
    ob_flush();
    flush();
}

function sendNo($id)
{
    $return = array("status" => "HALT");
    echo "id: $id" . PHP_EOL;
    echo "retry: 1200" . PHP_EOL;
    echo "data: " . json_encode($return) . PHP_EOL;
    echo PHP_EOL;
    ob_flush();
    flush();
}

$serverTime = time();

if (false != GameManager::isModified()) {
    sendData($serverTime);
    GameManager::setModified();
} else {
    sendNo($serverTime);
}