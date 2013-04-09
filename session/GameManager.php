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
require_once dirname(__FILE__) . "/../dao/wrapper/Player.php";
require_once dirname(__FILE__) . "/../game/GameEngine.php";
require_once dirname(__FILE__) . "/AuthManager.php";

if (session_status() == PHP_SESSION_NONE)
    session_start();

/**
 * Class GameManager
 * Mainly a static wrapper over GameEngine
 */
class GameManager
{
    private static $ships;

    public static function getNextPlayer($user_id)
    {
        $players = self::getPlayers();
        $elem = current($players);
        $players[] = current($players);

        echo $user_id . "</br>";
        while ($elem != null && $elem->getId() != $user_id) {
            echo "**</br>";
            $elem = next($players);
        }
        $elem = next($players);
        return $elem->getId();
    }

    public static function getGameId()
    {
        $game = self::getGame();
        return isset($game) ? $game->getId() : false;
    }

    public static function setGameId($game = null)
    {
        if ($game == null) {
            unset($_SESSION['game_engine']);
        } else {
            $_SESSION['game_engine'] = $game;
        }
    }

    public static function getGame()
    {
        self::updateEngagedGame(AuthManager::getLoggedInUserId());
        if (isset($_SESSION['game_engine'])) {
            return ($_SESSION['game_engine']->getGame());
        }

        return null;
    }

    public static function getGameEngine()
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

    public static function getJoinedPlayersNumber()
    {
        $userGameDao = new User_Game();
        return $userGameDao->getJoinedPlayers(self::getGame());
    }

    public static function needsMorePlayers()
    {
        $joined = self::getJoinedPlayersNumber();
        $needed = self::getGame()->noplayers;

        return $needed > $joined;
    }

    public static function advanceStageIfNecessary($necessary = false)
    {
        self::updateEngagedGame(AuthManager::getLoggedInUserId());
        if ($necessary != false) {
            if (strcmp(self::getGame()->state, 'ATTACK') == 0) {
                //TODO: check if end
                $bonus = self::getGameEngine()->getShipBonus(self::getCurrentPlayerId());
                self::increaseShips(6);
                self::increaseShips($bonus);
                self::getGameEngine()->changeTurn(GameManager::getNextPlayer(self::getCurrentPlayerId()));


            }
            $nextState = self::getGameEngine()->getNextState();
            self::getGameEngine()->changeState($nextState);


        } else {
            if (strcmp(self::getGame()->state, 'WAITING_PLAYERS') == 0 && !self::needsMorePlayers()) {
                $nextState = self::getGameEngine()->getNextState();
                self::getGameEngine()->changeState($nextState);
            }

            if (strcmp(self::getGame()->state, 'PLANET_CLAIM') == 0 && !self::getGameEngine()->claimablePlanetsExist()) {
                $nextState = self::getGameEngine()->getNextState();
                self::getGameEngine()->changeState($nextState);
            }

            if (strcmp(self::getGame()->state, 'SHIP_PLACING') == 0 && !self::getGameEngine()->claimablePlanetsExist()) {
                $nextState = self::getGameEngine()->getNextState();
                self::getGameEngine()->changeState($nextState);
            }

            if (strcmp(self::getGame()->state, 'ATTACK') == 0 && !self::getGameEngine()->claimablePlanetsExist()) {
                $nextState = self::getGameEngine()->getNextState();
                self::getGameEngine()->changeState($nextState);
            }
        }
    }

    public static function getCurrentPlayerUsername()
    {
        return self::getGameEngine()->getCurrentPlayerUsername();
    }

    public static function getCurrentPlayerId()
    {
        return self::getGameEngine()->getGame()->current_player_id;
    }

    public static function isLoggedInPlayersTurn()
    {
        return self::getCurrentPlayerId() == AuthManager::getLoggedInUserId();
    }

    public static function getPlayers()
    {
        $users = self::getGameEngine()->getPlayers();
        $players = array();
        foreach ($users as $player)
            $players[] = new Player($player);
        return $players;
    }

    public static function getRemainingShips()
    {
        return $_SESSION['ships'];
    }

    public static function decreaseShips($with = 1)
    {
        $_SESSION['ships'] = $_SESSION['ships'] - $with;
    }

    public static function increaseShips($with = 1)
    {
        $_SESSION['ships'] = $_SESSION['ships'] + $with;
    }

    public static function initShips()
    {
        $_SESSION['ships'] = 18;
    }

    public static function getWinner()
    {
        return self::getGameEngine()->getWinner();
    }
}