<?php
/**
 * Created by JetBrains PhpStorm.
 * User: iceman
 * Date: 4/6/13
 * Time: 10:12 PM
 * To change this template use File | Settings | File Templates.
 */
require_once dirname(__FILE__) . "/../dao/actual/User_Game.php";
require_once dirname(__FILE__) . "/../dao/actual/Game.php";

if (session_status() == PHP_SESSION_NONE)
    session_start();

class GameManager
{

    public static function getGameId()
    {
        $isInGame = isSet($_SESSION['game_id']);

        return $isInGame ? $_SESSION['game_id'] : false;
    }

    public static function setGameId($game = null)
    {
        if ($game == null)
            unset($_SESSION['game_id']);
        else
            $_SESSION['game_id'] = $game;
    }

    public static function getGame()
    {
        if (isset($_SESSION['game_id'])) {
            $gameDao = new Game();
            return current($gameDao->getRowsByField('id', $_SESSION['game_id']));
        }

        return null;
    }

    public static function updateEngagedGame($userId)
    {
        $userGameDao = new User_Game();
        $userGames = $userGameDao->getRowsByField('user_id', $userId);
        if (is_array($userGames)) {
            if (count($userGames) > 1) {
                //TODO: should clean some of them;
            }
            $game = current($userGames)->game_id;
            self::setGameId($game);
        } else {
            self::setGameId(null);
        }
    }
}