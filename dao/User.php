<?php

/**
 * User: Alexandru George Burghelea
 * Date: 11.03.2013
 * Time: 10:54
 * For : PWeb 2013
 */

require_once("../generic/GenericDao.php");

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

    /**
     * Verifica daca userul exista in baza de date.
     * @param string $username
     * @param string $password
     * @return int Id-ul din baza a userului daca exista, -1 in caz contrar
     */
    public static function login ($username, $password)
    {
        $finder = new User();
        $users = $finder->getRowsByArray(array('username' => $username, "password" => $password),'id','ASC',1);
        if (empty($users))
            return -1;
        $users = current($users);

        return $users->id;
    }

    /**
     * @return int -1
     */
    public static function logout ()
    {
        return -1;
    }

    /**
     * Checks if a user with same username or email exist
     * @param $username
     * @param $email
     * @return bool true if the username or email are already used, false otherwise
     */
    protected static function alreadyExists($username, $email)
    {
        $finder = new User();
        return $finder->getRowsByField('username', $username) || $finder->getRowsByField('email', $email);
    }
}

?>