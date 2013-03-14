<?php
/**
 * User: Alexandru George Burghelea
 * Date: 13.03.2013
 * Time: 12:03
 * For : PWeb 2013
 */

require_once("script-constants.php");
require_once("../dao/User.php");

if (areParamsSet($_GET)) {
    $register = User::register($_GET[S_USERNAME], $_GET[S_EMAIL], $_GET[S_PASSWORD]);
    if ($register > 0)
        echo "User with id " . $register . " has been creaded<br/>";
    else
        echo "User creation has failed<br/>";
} else {
    echo "Use all the necessary params<br/>";
}

function areParamsSet($_GET)
{
    if (!isset($_GET[S_USERNAME]) || !isset($_GET[S_EMAIL]) || !isset($_GET[S_PASSWORD])) {
        return false;
    }

    return true;
}

?>