<?php
/**
 * User: Alexandru George Burghelea
 * Date: 3/14/13
 * Time: 3:42 PM
 * For : PWeb 2013
 */

require_once dirname(__FILE__)."/script-constants.php";
require_once dirname(__FILE__) . "/../dao/actual/User.php";
require_once dirname(__FILE__) . "/../dao/actual/User_Game.php";
require_once dirname(__FILE__) . "/../session/AuthManager.php";
require_once dirname(__FILE__) . "/../session/GameManager.php";

if (session_status() == PHP_SESSION_NONE)
    session_start();


if (areParamsSet()) {
    $userId = User::login($_POST[S_USERNAME], $_POST[S_PASSWORD]);
    if ($userId > 0) {
        echo "User exists in database <br/>";
        setSessionDetalils($userId);
    }
    else
        echo "User doesn't exists in the database<br/>";
} else {
    echo "Use all the necessary params<br/>";
}

header('Location: ' . $_SERVER['CONTEXT_PREFIX'] .'/login.php');

function areParamsSet()
{
     return isset($_POST[S_USERNAME]) && isset($_POST[S_PASSWORD]);
}

function setSessionDetalils($userId)
{
    AuthManager::userId($userId);
    $userGameDao = new User_Game();
    $userGames = $userGameDao->getRowsByField('user_id', $userId);
    if (is_array($userGames)) {
        if (count($userGames) > 1){
            //TODO: should clean some of them;
        }
        $game = current($userGames)->game_id;
        GameManager::setGame($game);
    }
}

?>
