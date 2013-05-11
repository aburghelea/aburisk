<?php
/**
 * User: Alexandru George Burghelea
 * For : PWeb 2013
 */

require_once dirname(__FILE__) . "/script-constants.php";
require_once dirname(__FILE__) . "/../dao/actual/User.php";
require_once dirname(__FILE__) . "/../session/AuthManager.php";
require_once dirname(__FILE__) . "/../logger/Aburlog.php";
require_once dirname(__FILE__) . "/../resources/libs/recaptcha/aburiskcaptcha.php";


if (session_status() == PHP_SESSION_NONE)
    session_start();

if (areParamsSet($_POST)) {

    $changed = User::changePassword($_POST[S_USERNAME], $_POST[S_PASSWORD]);
    if ($changed) {
        header('Location: ' . $_SERVER['CONTEXT_PREFIX']);
        exit();
    }
}
header('Location: ' . $_SERVER['CONTEXT_PREFIX'] . '/profileupdate.php');
exit();


function areParamsSet()
{

    $_POST[S_USERNAME] = AuthManager::getLoggedInUserName();
    error_log( AuthManager::getLoggedInUserName());
    return isset($_POST[S_USERNAME]) && isset($_POST[S_PASSWORD]) && strlen($_POST[S_PASSWORD]) > 0;

}

?>