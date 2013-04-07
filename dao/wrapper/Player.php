<?php
/**
 * User: Alexandru George Burghelea
 * For : PWeb 2013
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