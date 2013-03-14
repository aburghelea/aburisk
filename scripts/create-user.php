<?php
/**
 * User: Alexandru George Burghelea
 * Date: 13.03.2013
 * Time: 12:03
 * For : PWeb 2013
 */

const PASSWORD = 'password';
const EMAIL = 'email';
const USERNAME = 'username';

require_once("../dao/User.php");

var_dump($_GET);

if (areParamsSet($_GET)) {
    $register = User::register($_GET[USERNAME], $_GET[EMAIL], $_GET[PASSWORD]);
    if ($register > 0)
        echo "User with id " . $register . " has been creaded<br/>";
    else
        echo "User creation has failed<br/>";
} else {
    echo "Use all the necessary params<br/>";
}

function areParamsSet($_GET)
{
    if (!isset($_GET[USERNAME]) || !isset($_GET[EMAIL]) || !isset($_GET[PASSWORD])) {
        return false;
    }

    return true;
}

?>