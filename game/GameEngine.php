<?php

/**
 * User: Alexandru George Burghelea
 * Date: 11.03.2013
 * Time: 12:42 AM
 * For : PWeb 2013
 */

require_once("../interface/IGameEngine.inc.php");
require_once("../dao/Game.php");
require_once("../dao/User_Game.php");
require_once("../dao/User.php");
require_once("GameState.php");

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
        echo "New id ".$idGame."\n";
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

        $games = $user_game->getRowsByArray(array('user_id'=>$idUser, 'game_id' => $this->game->getId()));
        if (!empty($games))
            return -1;

        $user_game->insertRow(array('user_id' => $idUser, "game_id" => $this->game->getId()));

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

    public function changeTurn($idUser)
    {
        $user = new User();
        $users = $user->getRowsByField('id', $idUser);

        if (empty($users))
            return -1;

        $user_game = new User_Game();
        $user_games = $user_game ->getRowsByArray(array("user_id" => $idUser, "game_id" => $this->game->getId()));

        if (empty($user_games))
            return -1;

        $this->game->current_player_id = $idUser;
        $this->game->updateRows(array("current_player_id" => $idUser), 'id', $this->game->getId());

        return $idUser;
    }

    public function endGame($idUser)
    {
    }

    public function claimPlanet($idPlanet, $idUser)
    {
        // TODO: Implement claimPlanet() method.
    }

    public function deployShip($idPlanet, $idUser)
    {
        // TODO: Implement deployShip() method.
    }

    public function attack($idPlanet1, $idPlanet2, $noShips, $idUser)
    {
        // TODO: Implement attack() method.
    }

    public function move($idPlanet1, $idPlanet2, $noShips, $idUser)
    {
        // TODO: Implement move() method.
    }

    public function getGame()
    {
        return $this->game;
    }

}
