<?php
/**
 * Created by JetBrains PhpStorm.
 * User: iceman
 * Date: 4/6/13
 * Time: 4:58 PM
 * To change this template use File | Settings | File Templates.
 */
require_once dirname(__FILE__) . "/../dao/actual/User.php";

if (session_status() == PHP_SESSION_NONE)
    session_start();

class AuthManager
{
    private static $userDao = NULL;

    private static function userDao()
    {
        if (!isset(self::$userDao))
            self::$userDao = new User();

        return self::$userDao;
    }

    public static function getLoggedInUserId()
    {
        $isSet = isset($_SESSION['user_id']);

        return $isSet ? $_SESSION['user_id'] : false;
    }

    public static function userId($user = null)
    {
        if ($user == null)
            unset($_SESSION['user_id']);
        else
            $_SESSION['user_id'] = $user;
    }

    public static function getLoggedInUserName()
    {
        return self::userDao()->getRowsByField('id', $_SESSION['user_id'])[0]->username;
    }
}

?>