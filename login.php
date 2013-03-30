<?php
/**
 * Created by JetBrains PhpStorm.
 * User: iceman
 * Date: 3/30/13
 * Time: 9:59 PM
 * To change this template use File | Settings | File Templates.
 */


if (session_status() == PHP_SESSION_NONE)
    session_start();


if (isset($_SESSION['user_id'])) {
    redirectHome();
} else if (isset($_GET['registered='])) {
    if ($_GET['registered='] === true) {
        doLogin();
    } else if ($_GET['registered='] === false) {
        echo "Doing register";
    }
} else {
    showLogin();
}

function redirectHome()
{
    header('Location: ' . $_SERVER['CONTEXT_PREFIX']);
}

function doLogin()
{
    require_once "login.php";
}

function showLogin()
{
    require_once "html/login_interface.php";
}

?>