<?php
/**
 * User: Alexandru George Burghelea
 * Date: 13.03.2013
 * Time: 12:03
 * For : PWeb 2013
 */

require_once dirname(__FILE__) . "/script-constants.php";
require_once dirname(__FILE__) . "/../dao/actual/User.php";
require_once dirname(__FILE__) . "/../session/AuthManager.php";
require_once dirname(__FILE__) . "/../logger/Aburlog.php";

if (session_status() == PHP_SESSION_NONE)
    session_start();

if (areParamsSet($_POST)) {
    $userId = User::register($_POST[S_USERNAME], $_POST[S_EMAIL], $_POST[S_PASSWORD]);
    if ($userId > 0) {
        AuthManager::userId($userId);

        header('Location: ' . $_SERVER['CONTEXT_PREFIX'] . '/login.php?registered=true');
        exit();
    } else {
        header('Location: ' . $_SERVER['CONTEXT_PREFIX'] . '/login.php?registered=false');
        exit();
    }
} else {
    header('Location: ' . $_SERVER['CONTEXT_PREFIX'] . '/login.php?registered=false');
    exit();
}


function areParamsSet()
{
    if (!isset($_POST[S_USERNAME]) || !isset($_POST[S_EMAIL]) || !isset($_POST[S_PASSWORD])) {
        return false;
    }

    if (!strlen($_POST[S_USERNAME]) || !strlen($_POST[S_EMAIL]) || !strlen($_POST[S_PASSWORD])) {
        return false;
    }

    return true;
}

?>