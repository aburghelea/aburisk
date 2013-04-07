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
require_once dirname(__FILE__) . "/../game/GameEngine.php";
require_once dirname(__FILE__) . "/AuthManager.php";

if (session_status() == PHP_SESSION_NONE)
    session_start();

class GameManager
{

    public static function getGameId()
    {
        self::updateEngagedGame(AuthManager::getLoggedInUserId());
        $isInGame = isSet($_SESSION['game_engine']);

        return $isInGame ? $_SESSION['game_engine'] : false;
    }

    public static function setGameId($game = null)
    {
        if ($game == null)
            unset($_SESSION['game_engine']);
        else
            $_SESSION['game_engine'] = $game;
    }

    public static function getGame()
    {
        if (isset($_SESSION['game_engine'])) {
            return ($_SESSION['game_engine']->getGame());
        }

        return null;
    }

    private static function getGameEngine()
    {
        if (isset($_SESSION['game_engine'])) {
            return $_SESSION['game_engine'];
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
            self::setGameId(new GameEngine($game));
        } else {
            self::setGameId(null);
        }
    }

    public static function getJoinedPlayers() {
        $userGameDao = new User_Game();
        return $userGameDao->getJoinedPlayers(self::getGame());
    }

    public static function needsMorePlayers() {
        $joined = self::getJoinedPlayers();
        $needed = self::getGame()->noplayers;

        return $needed > $joined;
    }

    public static function advanceStageIfNecessary()
    {
        if (strcmp(self::getGame()->state, 'WAITING_PLAYERS') == 0 && !self::needsMorePlayers()) {
            $nextState = self::getGameEngine()->getNextState();
            self::getGameEngine()->changeState($nextState);
        }
    }
}