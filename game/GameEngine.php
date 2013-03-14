<?php

/**
 * User: Alexandru George Burghelea
 * Date: 11.03.2013
 * Time: 12:42 AM
 * For : PWeb 2013
 */

require_once("../interface/IGameEngine.inc.php");
//foreach (glob("../dao/*.php") as $filename)
//{
//    require_once($filename);
//}
require_once("../dao/Game.php");
require_once("../dao/User.php");
require_once("../dao/Galaxy.php");
require_once("../dao/Planet.php");
require_once("../dao/Planet_Neighbour.php");
require_once("../dao/Planet_Game.php");
require_once("../dao/User_Game.php");
require_once("GameState.php");
require_once("ShipAttackJudge.php");

class GameEngine implements IGameEngine
{
    private $game;

    /**
     * Extrage jocul cu id-ul $idGame din baza de date sau creeaza un joc nou daca $idGame este 0
     * @param int $idGame id-ul jocul care se doreste extras
     * @param int $noPlayers este numarul de jucatori necesar pentru a incepe jocul
     * @param int $idHost $idHost este identificatorul utilizatorului care creeaza jocul
     */
    public function __construct($idGame = 0, $noPlayers = 2, $idHost = 1)
    {
        $gameDao = new Game();
        if ($idGame > 0) {
            $games = $gameDao->getRowsByField('id', $idGame);
            if (!empty($games)) {
                $this->game = current($games);
                return;
            }
        }

        $idGame = $gameDao->insertRow(array('noPlayers' => $noPlayers, 'current_player_id' => $idHost, 'state' => GameState::WAITING_PLAYERS));
        $this->game = current($gameDao->getRowsByField('id', $idGame));

        $this->joinGame($idHost);
    }

    /**
     * Alătură un jucător jocului curent
     * @param $idUser
     * @return int -1 dacă utilizatorul nu există sau dacă este deja alăturat acelui joc, 1 altfel
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

        return 1;
    }

    /**
     * Schimba starea jocului
     * @param string $state starea in care se schimba
     */
    public function changeState($state)
    {
        $this->game->state = $state;
        $this->game->updateRows(array("state" => $state), 'id', $this->game->getId());
    }

    /**
     * Cedeaza tura urmatorului jucator
     * @param int $idUser userul care va muta
     * @return int 1 daca s-a cedat tura, -1 altfel
     */
    public function changeTurn($idUser)
    {
        if (!$this->isUserInThisGame($idUser))
            return -1;

        $this->game->current_player_id = $idUser;
        $this->game->updateRows(array("current_player_id" => $idUser), 'id', $this->game->getId());

        return $idUser;
    }

    /**
     * Incheie un joc si proclama un castigator
     * @param int $idUser userul castigator
     * @return int id-ul castigatorului daca s-a putut termina jocul, -1 altfel
     */
    public function endGame($idUser)
    {
        if (!$this->isUserInThisGame($idUser))
            return -1;

        $this->game->updateRows(array("current_player_id" => $idUser, 'state' => GameState::GAME_END), 'id', $this->game->getId());
        $user = new User();
        $user = current($user->getRowsByField('id', $idUser));

        $user->won_games++;
        $user->updateRows(array("won_games" => $user->won_games), "id", $idUser);
        return $user->getId();
    }

    /**
     * Revedica o planeta neocupata
     * @param int $idPlanet planeta dorita
     * @param int $idUser revendicatorl
     * @return int 1 daca planeta poate fi ocupata(e libera si datele sunt valide), -1 altfel
     */
    public function claimPlanet($idPlanet, $idUser)
    {
        if ($this->planetIsClaimable($idPlanet, $idUser))
            return -1;

        $planet_in_game = new Planet_Game();
        $planet_in_game->insertRow(array("planet_id" => $idPlanet, "game_id" => $this->game->getId(), "owner_id" => $idUser, "noships" => 1));

        return 1;

    }

    /**
     * Plaseaza o nava pe planeta daca ea apartine userului
     * @param int $idPlanet Planeta pe care se doreste sa se plaseze o nava
     * @param int $idUser Detinatorul planetei
     * @return int 1 daca s-a plasat nava, -1 nu
     */
    public function deployShip($idPlanet, $idUser)
    {
        $planet = $this->planetIsClaimed($idPlanet, $idUser);
        if ($this->planetIsClaimed($idPlanet, $idUser) == -1)
            return -1;
        $planet_game = new Planet_Game();
        $pg = current($planet_game->getRowsByField('id', $planet));

        $planet_game->updateRows(array("noships" => $pg->noships + 1), 'id', $pg->getId());

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

        return array($firstPlanet->owner_id, $secondPlanet->owner_id);

    }

    /**
     * Muta un numar de name intre doua planete ale aceluiasi user si daca pe planeta sursa
     * ramane minim o nava
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

        return array($firstPlanet->noships, $secondPlanet->noships);
    }

    /**
     * @return Game, the current game
     */
    public function getGame()
    {
        return $this->game;
    }

    /**
     * Verifica daca userul este in joc.
     * @param int $idUser userul
     * @return bool true daca userul este in jocul curent, false altfel
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
    public function planetIsClaimable($idPlanet, $idUser)
    {
        $planet = new Planet();
        $planet_in_game = new Planet_Game();
        $planet = $planet->getRowsByField('id', $idPlanet);
        $planets = $planet_in_game->getRowsByArray(array('planet_id' => $idPlanet, "game_id" => $this->game->getId()));

        return !$this->isUserInThisGame($idUser) || empty($planet) || !empty($planets);
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

}
