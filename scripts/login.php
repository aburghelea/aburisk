<?php
/**
 * User: Alexandru George Burghelea
 * Date: 3/14/13
 * Time: 3:42 PM
 * For : PWeb 2013
 */

require_once dirname(__FILE__) . "/script-constants.php";
require_once dirname(__FILE__) . "/../dao/actual/User.php";
require_once dirname(__FILE__) . "/../dao/actual/User_Game.php";
require_once dirname(__FILE__) . "/../session/AuthManager.php";
require_once dirname(__FILE__) . "/../session/GameManager.php";
require_once dirname(__FILE__) . "/../logger/Aburlog.php";
require_once dirname(__FILE__) . "/../resources/libs/recaptcha/aburiskcaptcha.php";

if (session_status() == PHP_SESSION_NONE)
    session_start();


if (areParamsSet()) {
    if (!validCaptcha()) {
        header('Location: ' . $_SERVER['CONTEXT_PREFIX'] . '/login.php?captcha=captcha');
        exit();
    }
    $userId = User::login($_POST[S_USERNAME], $_POST[S_PASSWORD]);
    if ($userId > 0) {
        setSessionDetalils($userId);
        header('Location: ' . $_SERVER['CONTEXT_PREFIX']);
        exit();

    } else
        Aburlog::getInstance()->logInfo("User does not exists", $_POST[S_USERNAME]);
} else {
    Aburlog::getInstance()->logError("Game idiotic call to login", $_POST);
}
header('Location: ' . $_SERVER['CONTEXT_PREFIX'] . '/login.php?login_error=true');

function areParamsSet()
{
    return isset($_POST[S_USERNAME]) && isset($_POST[S_PASSWORD]);
}

function setSessionDetalils($userId)
{
    AuthManager::userId($userId);
    GameManager::initShips();
    GameManager::updateEngagedGame($userId);
}

?>
