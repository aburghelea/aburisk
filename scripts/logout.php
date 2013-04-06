<?php
/**
 * User: Alexandru George Burghelea
 * Date: 3/14/13
 * Time: 9:46 PM
 * For : PWeb 2013
 */
require_once dirname(__FILE__) . "/../dao/actual/User.php";
//$logoutStatus = User::logout();
//
//if ($logoutStatus < 0)
//    echo "NOT IMPLEMENTED YET<br/>";
if (session_status() == PHP_SESSION_NONE)
    session_start();

session_unset();
header('Location: ' . $_SERVER['CONTEXT_PREFIX'] .'');
?>