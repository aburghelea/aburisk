<?php
/**
 * User: Alexandru George Burghelea
 * For : PWeb 2013
 */


/**
 * Class Player
 * Wrapper over users to send to the interface the players from a game
 */
class Player implements JsonSerializable
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

    /**
     * (PHP 5 &gt;= 5.4.0)<br/>
     * Specify data which should be serialized to JSON
     * @link http://php.net/manual/en/jsonserializable.jsonserialize.php
     * @return mixed data which can be serialized by <b>json_encode</b>,
     * which is a value of any type other than a resource.
     */
    public function jsonSerialize()
    {
        return ['username' => $this->getUsername(), 'id' => $this->getId()];
    }
}