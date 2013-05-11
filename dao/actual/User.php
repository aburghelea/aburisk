<?php

/**
 * User: Alexandru George Burghelea
 * Date: 11.03.2013
 * Time: 10:54
 * For : PWeb 2013
 */

require_once dirname(__FILE__) . "/../generic/GenericDao.php";

/**
 * User CRUD/domain
 */
class User extends GenericDao
{
    protected $id;
    public $username;
    public $email;
    protected $password;
    protected $salt;
    public $played_games;
    public $won_games;

    const usersInGame = "select * from %s where id in (select user_id from users_games where game_id = %s)";
    const cost = 10;

    function __construct()
    {
        self::$TABLE_NAME = 'users';
        parent::__construct();
    }

    function __toString()
    {
        return "USER: " . $this->id . " - " . $this->username . " - " . $this->email . " - " . $this->played_games . " - " . $this->won_games . " ** ";
    }

    /**
     * @param string $username Numele de utilizator dorit
     * @param string $email Emailul dorit
     * @param string $password Parola dorita
     * @return int Id-ul inregistrarii daca s-a realizat insertul cu scucces, -1 altfel
     */
    public static function register($username, $email, $password)
    {
        $user_exists = self::alreadyExists($username);

        if ($user_exists)
            return -1;
        $inserter = new User();
        $password = self::getSaltedPassword($password);
        return $inserter->insertRow(array('username' => $username, 'password' => $password, 'email' => $email));
    }

    /**
     * Verifica daca userul exista in baza de date.
     * @param string $username
     * @param string $password
     * @return int Id-ul din baza a userului daca exista, -1 in caz contrar
     */
    public static function login($username, $password)
    {
        $finder = new User();
//        $users = $finder->getRowsByArray(array('username' => $username, "password" => $password), 'id', 'ASC', 1);
        $users = $finder->getRowsByField("username", $username, 'id', 'ASC', 1);
        if (empty($users))
            return -1;
        $users = current($users);

        $validPass = self::getSaltedPassword($password, $users->password) == $users->password;
        return $validPass ? $users->id : -1;
    }

    /**
     * @return int -1
     */
    public static function logout()
    {
        return -1;
    }

    /**
     * Checks if a user with same username or email exist
     * @param $username
     * @return bool true if the username or email are already used, false otherwise
     */
    public static function alreadyExists($username)
    {
        $finder = new User();
        $users = $finder->getRowsByField('username', $username);
        return $users != null && !empty($users);
    }

    public function getId()
    {
        return $this->id;
    }


    function getUsersFromGame($gid)
    {
        $userDao = new User();

        $query = sprintf(self::usersInGame, self::$TABLE_NAME, $gid);
        return $userDao->getCustomRows($query);
    }

    private static function  getRandomSalt()
    {
        return strtr(base64_encode(mcrypt_create_iv(16, MCRYPT_DEV_URANDOM)), '+', '.');
    }

    private static function getRandomBlowfishSalt()
    {
        return sprintf('$2a$%02d', self::cost) . self::getRandomSalt();
    }

    private static function getSaltedPassword($password, $salt = null)
    {
        $salt = $salt == null ? self::getRandomBlowfishSalt() : self::getRandomBlowfishSalt();
        $password = str_replace("+", ".", $password);
        return crypt($password, $salt);
    }
}

?>