<?php

/**
 * User: Alexandru George Burghelea
 * Date: 11.03.2013
 * Time: 10:54
 * For : PWeb 2013
 */

require_once("GenericDao.php");

class User extends GenericDao
{
    protected $id;
    public $username;
    public $email;
    protected $password;
    public $played_games;
    public $won_games;


    function __construct()
    {
        self::$TABLE_NAME = 'users';
        parent::__construct();
    }

    function __toString()
    {
        return "Planet: " . $this->id . " - " . $this->username . " - " . $this->email . " - " . $this->played_games . " - " . $this->won_games;
    }

    /**
     * @param string $username Numele de utilizator dorit
     * @param string $email Emailul dorit
     * @param string $password Parola dorita
     * @return int Id-ul inregistrarii daca s-a realizat insertul cu scucces, -1 altfel
     */
    public static function register($username, $email, $password)
    {
        $user_exists = self::alreadyExists($username, $email);

        if ($user_exists)
            return -1;
        $inserter = new User();
        return $inserter->insertRow(array('username' => $username, 'password' => $password, 'email' => $email));
    }

    protected static function alreadyExists($username, $email)
    {
        $finder = new User();
        return $finder->getRowsByField('username', $username) || $finder->getRowsByField('email', $email);
    }
}

?>