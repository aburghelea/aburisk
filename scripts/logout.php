<?php
/**
 * User: Alexandru George Burghelea
 * Date: 3/14/13
 * Time: 9:46 PM
 * For : PWeb 2013
 */
require_once("../dao/User.php");
$logoutStatus = User::logout();

if ($logoutStatus < 0)
    echo "NOT IMPLEMENTED YET<br/>";

?>