<?php
/**
 * Created by JetBrains PhpStorm.
 * User: iceman
 * Date: 3/30/13
 * Time: 9:59 PM
 * To change this template use File | Settings | File Templates.
 */
require_once dirname(__FILE__) . "/session/AuthManager.php";

if (session_status() == PHP_SESSION_NONE)
    session_start();


if (AuthManager::getLoggedInUserId()) {
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
    require_once "views/login_interface.php";
}

?>