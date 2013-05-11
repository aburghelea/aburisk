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
    private $ships;
    private $planets;
    private $score = -1;

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
        $username = $this->user->username;
        $pos = strpos($username, "@");
        $username = substr($username, 0, $pos);
        return $username;
    }

    public function setScore($score)
    {
        $this->score = $score;
    }

    public function setShips($ships)
    {
        $this->ships = $ships;
    }

    public function setPlanets($planets)
    {
        $this->planets = $planets;
    }

    public function getScore()
    {
        return $this->score;
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
        return ['username' => $this->getUsername(),
            'id' => $this->getId(),
            "score" => $this->score,
            'planets' => $this->planets,
            'ships' => $this->ships
        ];
    }
}