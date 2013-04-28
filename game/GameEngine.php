<?php

/**
 * User: Alexandru George Burghelea
 * Date: 11.03.2013
 * Time: 12:42 AM
 * For : PWeb 2013
 */

require_once dirname(__FILE__) . "/../inc/IGameEngine.inc.php";
//foreach (glob("../dao/*.php") as $filename)
//{
//    require_onceonce($filename);
//}
require_once dirname(__FILE__) . "/../dao/actual/Game.php";
require_once dirname(__FILE__) . "/../dao/actual/User.php";
require_once dirname(__FILE__) . "/../dao/actual/Galaxy.php";
require_once dirname(__FILE__) . "/../dao/actual/Planet.php";
require_once dirname(__FILE__) . "/../dao/actual/Planet_Neighbour.php";
require_once dirname(__FILE__) . "/../dao/actual/Planet_Game.php";
require_once dirname(__FILE__) . "/../dao/actual/User_Game.php";
require_once dirname(__FILE__) . "/GameState.php";
require_once dirname(__FILE__) . "/ShipAttackJudge.php";

class GameEngine implements IGameEngine
{
    private $game;
    private $winner;

    /**
     * Extracts the game with the specified id from the database or creates an
     * new game if the id 0
     * @param int $idGame the desired games id
     * @param int $noPlayers number of necessary players to start the game
     * @param int $idHost the hosts id
     */
    public function __construct($idGame = 0, $noPlayers = 2, $idHost = 1)
    {
        $gameDao = new Game();
        $planetDao = new Planet();
        $planetGameDao = new Planet_Game();
        $this->ships = 18;
        if ($idGame > 0) {
            $games = $gameDao->getRowsByField('id', $idGame);
            if (!empty($games)) {
                $this->game = current($games);
                return;
            }

        }
        $idGame = $gameDao->insertRow(array('noPlayers' => $noPlayers, 'current_player_id' => $idHost, 'state' => GameState::WAITING_PLAYERS));
        $this->game = current($gameDao->getRowsByField('id', $idGame));
        $planets = $planetDao->getRowsByField('"1"', '1');
        foreach ($planets as $planet) {
            $planetGameDao->insertRow(array('planet_id' => $planet->id, 'game_id' => $idGame));
        }
        $this->joinGame($idHost);
    }

    /**
     * Adds the user to the current game
     * @param $idUser
     * @return int -1 if the user doesn't exist or is already in the game, 1 otherwise
     */
    public function joinGame($idUser)
    {

        $user_game = new User_Game();
        $user = new User();
        $users = $user->getRowsByField('id', $idUser);
        if (empty($users))
            return -1;
        $games = $user_game->getRowsByArray(array('user_id' => $idUser, 'game_id' => $this->game->getId()));
        if (!empty($games))
            return -1;
        $user_game->insertRow(array('user_id' => $idUser, "game_id" => $this->game->getId()));
        $user = current($users);
        $user->played_games++;
        $user->updateRows(array("played_games" => $user->played_games), "id", $idUser);
        $this->signalUpdate(-1);

        return 1;
    }

    /**
     * Changes the state of the game
     * @param string $state the new state
     */
    public function changeState($state)
    {
        $this->game->state = $state;
        $this->game->updateRows(array("state" => $state), 'id', $this->game->getId());
    }

    public function signalUpdate($exceptUserId)
    {
        $userGameDao = new User_Game();
        $userGameDao->updateRows(array("dirty" => "true"), 'game_id', $this->game->getId());
    }

    /**
     * Changes the user who is going to move
     * @param int $idUser the new user
     * @return int 1 turn has been changed, -1 otherwise
     */
    public function changeTurn($idUser)
    {
        if (!$this->isUserInThisGame($idUser))
            return -1;

        $this->game->current_player_id = $idUser;
        $this->game->updateRows(array("current_player_id" => $idUser), 'id', $this->game->getId());
//        $this->signalUpdate($idUser);

        return $idUser;
    }

    /**
     * Ends the game and declares the winner
     * @param int $idUser the winner
     * @return boolean if the operation succeded
     */
    public function endGame($idUser = null)
    {
        $this->game->updateRows(array("current_player_id" => $idUser, 'state' => GameState::GAME_END), 'id', $this->game->getId());
        $this->game->current_player_id = $idUser;
        $planetGamesDao = new Planet_Game();
        $userGameDao = new User_Game();
        $userGameDao->deleteRowsByField('game_id', $this->game->getId());
        $planetGamesDao->deleteRowsByField('game_id', $this->game->getId());
        if ($idUser) {
            if (!$this->isUserInThisGame($idUser))
                return -1;

            $user = new User();
            $user = current($user->getRowsByField('id', $idUser));

            $user->won_games++;
            $user->updateRows(array("won_games" => $user->won_games), "id", $idUser);

//            $this->signalUpdate($idUser);
            return $user->getId() != null;
        }

        return true;
    }

    /**
     * Claims an unocupied planet
     * @param int $idPlanet desired planet
     * @param int $idUser claimer
     * @return int 1 if the planet has been claimed, -1 otherwise
     */
    public function claimPlanet($idPlanet, $idUser)
    {
        if ($this->planetIsNotClaimable($idPlanet, $idUser))
            return -1;

        $planet_in_game = new Planet_Game();
        $planet_in_game->updateRows(array("game_id" => $this->game->getId(), "owner_id" => $idUser, "noships" => 1), "planet_id", $idPlanet);
        $this->ships -= 1;

        return 1;
    }

    /**
     * Places a ship on the desired planet, if the planet belongs to the user
     * @param int $idPlanet desire planet
     * @param int $idUser planets owner
     * @return int 1 if the ship has been deployed, -1 otherwise
     */
    public function deployShip($idPlanet, $idUser)
    {
        $planet = $this->planetIsClaimed($idPlanet, $idUser);
        if ($this->planetIsClaimed($idPlanet, $idUser) == -1)
            return -1;
        $planet_game = new Planet_Game();
        $pg = current($planet_game->getRowsByField('id', $planet));

        $planet_game->updateRows(array("noships" => $pg->noships + 1), 'id', $pg->getId());
        $this->ships -= 1;
//        $this->signalUpdate($idUser);
        return 1;
    }

    /**
     * The user identified by $idUser attacks from planet1, plannet2 with $noShips
     * @param int $idPlanet1 attacking planet
     * @param int $idPlanet2 defending planet
     * @param int $noShips ships to use in attack
     * @param int $idUser attacking user;
     * @return array|int If battle was carried -> (ships on first planet, ships on second planet), -1 otherwise
     */
    public function attack($idPlanet1, $idPlanet2, $noShips, $idUser)
    {
        $fp = $this->planetIsClaimed($idPlanet1, $idUser);
        $sp = $this->planetIsClaimed($idPlanet2, $idUser);

        if ($fp < 0)
            return -1;

        if ($sp > 0)
            return -3;
        $sp = $this->planetIsClaimed($idPlanet2, '%');

        $pg_finder = new Planet_Game();

        $firstPlanet = current($pg_finder->getRowsByField('id', $fp));
        $secondPlanet = current($pg_finder->getRowsByField('id', $sp));

        $verdict = ShipAttackJudge::judge($firstPlanet->noships, $secondPlanet->noships, $noShips);
        if ($verdict == -1) {
            return -2;
        }

        print_r($verdict);
        $firstPlanet->noships = $verdict['A'];

        if ($verdict['D'] == 0) {
            $secondPlanet->noships = $verdict['C'];
            $secondPlanet->owner_id = $firstPlanet->owner_id;
        } else {
            $secondPlanet->noships = $verdict['D'];
        }


        $pg_finder->updateRows(array("noships" => $firstPlanet->noships, 'owner_id' => $firstPlanet->owner_id), 'id', $firstPlanet->getId());
        $pg_finder->updateRows(array("noships" => $secondPlanet->noships, 'owner_id' => $secondPlanet->owner_id), 'id', $secondPlanet->getId());

//        $this->signalUpdate($idUser);
        return array($firstPlanet->owner_id, $secondPlanet->owner_id);
    }

    /**
     * Moves a number of ships between two planets belonging to the same user if on
     * the source planet remains a minimum of 1 ship
     * @param int $idPlanet1 source planet
     * @param int $idPlanet2 destination planet
     * @param int $noShips number of ships
     * @param int $idUser planet owner
     * @return array|int -1 one if argumes are invalid, array with the number of remaining ships on the source planet
     *                   and the number of ships on the destination planet
     */
    public function move($idPlanet1, $idPlanet2, $noShips, $idUser)
    {

        $fp = $this->planetIsClaimed($idPlanet1, $idUser);
        $sp = $this->planetIsClaimed($idPlanet2, $idUser);

        if ($fp < 0 || $sp < 0)
            return -1;
        $pg_finder = new Planet_Game();

        $firstPlanet = current($pg_finder->getRowsByField('id', $fp));
        $secondPlanet = current($pg_finder->getRowsByField('id', $sp));

        if ($firstPlanet->noships - 1 < $noShips) {
            return -1;
        }

        $firstPlanet->noships -= $noShips;
        $secondPlanet->noships += $noShips;

        $pg_finder->updateRows(array("noships" => $firstPlanet->noships), 'id', $firstPlanet->getId());
        $pg_finder->updateRows(array("noships" => $secondPlanet->noships), 'id', $secondPlanet->getId());

//        $this->signalUpdate($idUser);
        return array($firstPlanet->noships, $secondPlanet->noships);
    }

    /**
     * @return Game the current game
     */
    public function getGame()
    {
        return $this->game;
    }

    /**
     * Checks if the users is in the game
     * @param int $idUser the user
     * @return bool
     */
    private function isUserInThisGame($idUser)
    {
        $user = new User();
        $users = $user->getRowsByField('id', $idUser);


        $user_game = new User_Game();
        $user_games = $user_game->getRowsByArray(array("user_id" => $idUser, "game_id" => $this->game->getId()));

        return !empty($users) && !empty($user_games);
    }

    /**
     * Checks to see if planet is claimable
     * @param int $idPlanet desired planet
     * @param int $idUser claimer
     * @return bool if is claimable
     */
    public function planetIsNotClaimable($idPlanet, $idUser)
    {
        $planet = new Planet();
        $planet_in_game = new Planet_Game();
        $planet = $planet->getRowsByField('id', $idPlanet);
        $planets = $planet_in_game->getRowsByArray(array('planet_id' => $idPlanet, "game_id" => $this->game->getId()));
        $validPlanet = !empty($planets) && $planets[0]->owner_id != null;

        $inGame = !$this->isUserInThisGame($idUser) || empty($planet) || $validPlanet;
        return $inGame;
    }

    /**
     * Checks if the planet is claimed
     * @param int $idPlanet
     * @param int $idUser
     * @return int -1 if the planet is unclaimed, the planets_games's id if it's unclaimed
     */
    public function planetIsClaimed($idPlanet, $idUser)
    {
        $planet = new Planet();
        $planet_in_game = new Planet_Game();
        $planet = $planet->getRowsByField('id', $idPlanet);
        $planets = $planet_in_game->getRowsByArray(array('planet_id' => $idPlanet, "game_id" => $this->game->getId(), "owner_id" => $idUser));

        if (!$this->isUserInThisGame($idUser) || empty($planet) || empty($planets))
            return -1;

        $planet_game = current($planets);
        return $planet_game->getId();
    }

    public function getNextState()
    {
        $currentState = $this->game->state;
        if (strcmp($currentState, 'WAITING_PLAYERS') == 0)
            return 'PLANET_CLAIM';
        if (strcmp($currentState, 'PLANET_CLAIM') == 0)
            return 'SHIP_PLACING';
        if (strcmp($currentState, 'SHIP_PLACING') == 0)
            return 'ATTACK';
        if (strcmp($currentState, 'ATTACK') == 0)
            return 'SHIP_PLACING';

        return 'END_GAME';
    }

    public function getCurrentPlayerUsername()
    {
        $userDao = new User();
        $users = $userDao->getRowsByField('id', $this->game->current_player_id);
        if (is_array($users))
            return current($users)->username;

        return 'none';
    }

    public function getPlayers()
    {
        $userDao = new User();
        return $userDao->getUsersFromGame($this->game->id);
    }


    public function claimablePlanetsExist()
    {
        $planetsGamesDao = new Planet_Game();
        $planets = $planetsGamesDao->getRowsByArray(array("game_id" => $this->game->getId()));

        foreach ($planets as $planet)
            if ($planet->owner_id == null)
                return true;

        return false;
    }

    function getShipBonus($userId)
    {
        $planetGameDao = new Planet_Game();
        $planetDao = new Planet();

        $allPlanets = $planetDao->getRowsByField("'1'", "1");
        $planetGalaxyMap = array();
        $bonusesMap = array();
        foreach ($allPlanets as $planet)
            $planetGalaxyMap[$planet->id] = $planet->containing_galaxy_id;

        $myPlanetGames = $planetGameDao->getRowsByArray(array("owner_id" => $userId, "game_id" => $this->game->id));
        if (is_array($myPlanetGames))
            foreach ($myPlanetGames as $planetGame) {
                $galaxyId = $planetGalaxyMap[$planetGame->planet_id];
                if (!array_key_exists($galaxyId, $bonusesMap)) {
                    $bonusesMap[$galaxyId] = 0;
                }
                $bonusesMap[$galaxyId] += 1;
            }

        $oponentPlanetGame = $planetGameDao->getRowsByArray(array("owner_id not" => $userId, "game_id" => $this->game->id));
        if (is_array($oponentPlanetGame))
            foreach ($oponentPlanetGame as $planetGame) {
                $galaxyId = $planetGalaxyMap[$planetGame->planet_id];
                if (array_key_exists($galaxyId, $bonusesMap))
                    $bonusesMap[$galaxyId] = 0;

            }

        $bonusesMap = array_map('self::halfCeil', $bonusesMap);
        $bonus = array_sum($bonusesMap);
        return $bonus;
    }

    function isGameOver($userId)
    {
        $planetGameDao = new Planet_Game();
        $myPlanetGames = $planetGameDao->getRowsByArray(array("owner_id" => $userId, "game_id" => $this->game->id));

        return count($myPlanetGames) == 18;
    }

    static function halfCeil($nr)
    {
        return intval(ceil($nr / 2));
    }

    public function getWinner()
    {

        $userDao = new User();
        $user = $userDao->getRowsByField("id", $this->game->current_player_id);
        return current($user);
    }


}
