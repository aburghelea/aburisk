<?php
/**
 * User: Alexandru George Burghelea
 * For : PWeb 2013
 */


/**
 * Class Player
 * Wrapper over users to send to the interface the players from a game
 */
class Player
{
    private $user;

    function __construct($user)
    {
        $this->user = $user;
    }

    public function getId()
    {
        return $this->user->getId();
    }

    public function getUsername()
    {
        return $this->user->username;
    }

}