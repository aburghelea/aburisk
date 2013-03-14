<?php
/**
 * User: Alexandru George Burghelea
 * Date: 3/14/13
 * Time: 3:42 PM
 * For : PWeb 2013
 */

require_once("script-constants.php");
require_once("../dao/User.php");

if (areParamsSet($_GET)) {
    $loginStatus = User::login($_GET[S_USERNAME], $_GET[S_PASSWORD]);
    if ($loginStatus > 0)
        echo "User exists in database <br/>";
    else
        echo "User doesn't exists in the database<br/>";
} else {
    echo "Use all the necessary params<br/>";
}

function areParamsSet($_GET)
{
    return isset($_GET[S_USERNAME]) && isset($_GET[S_PASSWORD]);
}

?>
