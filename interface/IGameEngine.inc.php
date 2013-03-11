<?php

/**
 * User: Alexandru George Burghelea
 * Date: 02.03.2012
 * Time: 11:56 PM
 * For : PWeb 2013
 */

interface IGameEngine
{

    /*
     * extrage jocul cu id-ul $idGame din baza de date sau creeaza un joc nou daca $idGame este 0
     * $noPlayers este numarul de jucatori necesar pentru a incepe jocul
     *$idHost este identificatorul utilizatorului care creeaza jocul
     */
    public function __construct($idGame = 0, $noPlayers = 0, $idHost = 0);

    /*
     * alătură un jucător jocului curent
     * întoarce -1 dacă utilizatorul nu există sau dacă este deja alăturat acelui joc
     * întoarce 1 în caz contr ar
     */
    public function joinGame($idUser);

    /* schimba starea jocului */
    public function changeState($state);

    /* schimba identificatorul utilizatorului al carui rand este in joc */
    public function changeTurn($idUser);

    /* seteaza id-ul jucatorului si trece jocul in starea SFARSIT_JOC */
    public function endGame($idUser);


    /*
     * utilizatorul cu identificatorul $idUser încearcă să revendice planeta cu identificatorul $idPlanet
     * întoarce -1 dacă planeta este deținută de un alt jucător sau dacă utilizatorul sau planeta nu există
     * întoarce 1 în caz contrar
     */
    public function claimPlanet($idPlanet, $idUser);

    /* similar cu metoda anterioară, doar că planeta este deja deținută de utilizator */
    public function deployShip($idPlanet, $idUser);

    /*
     * utilizatorul cu identificatorul $idUser atacă planeta cu identificatorul $idUser2 trimițând $noShips
     * nave de pe planeta cu identificatorul $idPlanet1
     * întoarce -1 dacă planeta cu identificatorul $idPlanet1 nu aparține utilizatorului cu identificatorul
     * $idUser
     * întoarce -1 dacă planeta cu identificatorul $idPlanet2 aparține utilizatorului cu identificatorul
     * $idUser
     * întoarce -1 dacă pe planeta cu identificatorul $idPlanet1 nu există cel puțin $noShips+1 nave
     * întoarce un array (x, y), în caz contrar, unde x este numărul de nave rămase pe planeta cu
     * identificatorul $idPlanet1, iar y este numărul de planete rămase pe planeta cu identificatorul
     * $idPlanet2
     */
    public function attack($idPlanet1, $idPlanet2, $noShips, $idUser);

    /*
     * utilizatorul cu identificatorul $idUser mută pe planeta cu identificatorul $idUser2 $noShips
     * nave de pe planeta cu identificatorul $idPlanet1
     * întoarce -1 dacă planeta cu identificatorul $idPlanet1 nu aparține utilizatorului cu identificatorul
     * $idUser
     * întoarce -1 dacă planeta cu identificatorul $idPlanet2 nu aparține utilizatorului cu identificatorul
     * $idUser
     * întoarce -1 dacă pe planeta cu identificatorul $idPlanet1 nu există cel puțin $noShips+1 nave
     * întoarce un array (x, y), în caz contrar, unde x este numărul de nave rămase pe planeta cu
     * identificatorul $idPlanet1, iar y este numărul de planete rămase pe planeta cu identificatorul
     * $idPlanet2
     */
    public function move($idPlanet1, $idPlanet2, $noShips, $idUser);
}

?>