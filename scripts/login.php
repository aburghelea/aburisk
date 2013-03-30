<?php
/**
 * User: Alexandru George Burghelea
 * Date: 3/14/13
 * Time: 3:42 PM
 * For : PWeb 2013
 */

require_once dirname(__FILE__)."/script-constants.php";
require_once dirname(__FILE__)."/../dao/User.php";

if (session_status() == PHP_SESSION_NONE)
    session_start();


if (areParamsSet()) {
    $loginStatus = User::login($_POST[S_USERNAME], $_POST[S_PASSWORD]);
    if ($loginStatus > 0) {
        echo "User exists in database <br/>";
        $_SESSION['user_id'] = $loginStatus;
    }
    else
        echo "User doesn't exists in the database<br/>";
} else {
    echo "Use all the necessary params<br/>";
}

header('Location: ' . $_SERVER['CONTEXT_PREFIX'] .'/login.php');

function areParamsSet()
{
     return isset($_POST[S_USERNAME]) && isset($_POST[S_PASSWORD]);
}

?>
