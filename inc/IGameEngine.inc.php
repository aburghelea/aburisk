<?php

/**
 * User: Alexandru George Burghelea
 * Date: 02.03.2012
 * Time: 11:56 PM
 * For : PWeb 2013
 */

interface IGameEngine
{

    /**
     * Extracts the game with the specified id from the database or creates an
     * new game if the id 0
     * @param int $idGame the desired games id
     * @param int $noPlayers number of necessary players to start the game
     * @param int $idHost the hosts id
     */
    public function __construct($idGame = 0, $noPlayers = 0, $idHost = 0);

    /**
     * Adds the user to the current game
     * @param $idUser
     * @return int -1 if the user doesn't exist or is already in the game, 1 otherwise
     */
    public function joinGame($idUser);

    /**
     * Changes the state of the game
     * @param string $state the new state
     */
    public function changeState($state);

    /**
     * Changes the user who is going to move
     * @param int $idUser the new user
     * @return int 1 turn has been changed, -1 otherwise
     */
    public function changeTurn($idUser);

    /**
     * Ends the game and declares the winner
     * @param int $idUser the winner
     * @return int the winners id if the operation succeded, -1 otherwise
     */
    public function endGame($idUser);

    /**
     * Claims an unocupied planet
     * @param int $idPlanet desired planet
     * @param int $idUser claimer
     * @return int 1 if the planet has been claimed, -1 otherwise
     */
    public function claimPlanet($idPlanet, $idUser);

    /**
     * Places a ship on the desired planet, if the planet belongs to the user
     * @param int $idPlanet desire planet
     * @param int $idUser planets owner
     * @return int 1 if the ship has been deployed, -1 otherwise
     */
    public function deployShip($idPlanet, $idUser);

    /**
     * The user identified by $idUser attacks from planet1, plannet2 with $noShips
     * @param int $idPlanet1 attacking planet
     * @param int $idPlanet2 defending planet
     * @param int $noShips ships to use in attack
     * @param int $idUser attacking user;
     * @return array|int If battle was carried -> (ships on first planet, ships on second planet), -1 otherwise
     */
    public function attack($idPlanet1, $idPlanet2, $noShips, $idUser);

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
    public function move($idPlanet1, $idPlanet2, $noShips, $idUser);
}

?>