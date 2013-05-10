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

        while ($elem != null && $elem->getId() != $user_id) {
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

    public static function isModified()
    {
        $gameId = self::getGameId();
        if ($gameId != false) {
            $userGameDao = new User_Game();
            $userGames = $userGameDao->getRowsByArray(array("user_id" => AuthManager::getLoggedInUserId(), "game_id" => $gameId));

            return ($userGames == null || $userGames[0]->dirty == "true" )? true : false;
        }

        return false;
    }

    public static function getAnimationData() {
        $gameId = self::getGameId();

        if ($gameId != false) {
            $userGameDao = new User_Game();
            $userGame = $userGameDao->getRowsByArray(array("user_id" => AuthManager::getLoggedInUserId(), "game_id" => $gameId))[0];

            $rtn = array("to"=>$userGame->rto, "from"=>$userGame->rfrom, "with"=>$userGame->rwith);
            return $userGame->dirty == "true" ? $rtn : false;
        }

        return false;
    }

    public static function setModified($dirty = false)
    {
        $dirty = $dirty == true ? "true" : "false";
        $gameId = self::getGameId();
        if ($gameId != false) {
            $userGameDao = new User_Game();
            $userGameDao->updateRows(array("dirty" => $dirty), "user_id", AuthManager::getLoggedInUserId());
            if ($dirty == "false") {
                $userGameDao->updateRows(array("rto" => 0, "rfrom" => 0, "rwith" => 0), "user_id", AuthManager::getLoggedInUserId());
            }
        }
    }

    public static function  coldLoad()
    {
        self::setModified(true);
    }

    public static function getGameEngine()
    {
        self::updateEngagedGame(AuthManager::getLoggedInUserId());
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

    public static function advanceStage()
    {
        self::updateEngagedGame(AuthManager::getLoggedInUserId());
        $state = GameManager::getGame()->state;
        if ($state == GameState::SHIP_PLACING || $state == GameState::ATTACK)
            GameManager::getGameEngine()->changeState(GameState::SHIP_PLACING);
        GameManager::getGameEngine()->changeTurn(GameManager::getNextPlayer(GameManager::getCurrentPlayerId()));
    }

    public static function advanceStageIfNecessary($necessary = false)
    {
        self::updateEngagedGame(AuthManager::getLoggedInUserId());
        if ($necessary != false) {
            if (strcmp(self::getGame()->state, 'ATTACK') == 0) {
                GameManager::getGameEngine()->changeTurn(GameManager::getNextPlayer(self::getCurrentPlayerId()));
            } else if (strcmp(self::getGame()->state, 'SHIP_PLACING') == 0) {
                $bonus = self::getGameEngine()->getShipBonus(self::getCurrentPlayerId());
                self::increaseShips(6);
                self::increaseShips($bonus);
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
        $planetGameDao = new Planet_Game();
        foreach ($users as $player) {
            $ships = 0;
            $planetGames = $planetGameDao->getRowsByArray(array('owner_id' => $player->getId(), "game_id" => self::getGame()->getId()));
            for ($i = count($planetGames) - 1; $i >= 0; --$i) {
                $ships += $planetGames[$i]->noships;
            }
            $playerHolder = new Player($player);
            $playerHolder->setPlanets(count($planetGames));
            $playerHolder->setShips($ships);
            $score = floor($ships / (count($planetGames) + 1) * 0.7 + $ships * 0.3);
            $playerHolder->setScore($score);
            $players[] = $playerHolder;
        }
        usort($players, "self::sortPlayer");
        return $players;
    }

    private static function sortPlayer($a, $b)
    {
        if ($a->getScore() == $b->getScore())
            return 0;
        if ($a->getScore() > $b->getScore())
            return -1;
        return 1;
    }

    public static function getRemainingShips()
    {
        return unserialize($_SESSION['ships']);
    }

    public static function decreaseShips($with = 1)
    {
        $ships = unserialize($_SESSION['ships']);
        $_SESSION['ships'] = serialize($ships - $with);
    }

    public static function increaseShips($with = 1)
    {
        $ships = unserialize($_SESSION['ships']);
        $_SESSION['ships'] = serialize($ships + $with);
    }

    public static function initShips()
    {
        $_SESSION['ships'] = serialize(18);
    }

    public static function getWinner()
    {
        return self::getGameEngine()->getWinner();
    }
}