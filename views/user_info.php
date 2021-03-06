<?php
/**
 * Created by JetBrains PhpStorm.
 * User: iceman
 * Date: 3/31/13
 * Time: 12:36 AM
 * To change this template use File | Settings | File Templates.
 */

require_once  dirname(__FILE__) . "/../dao/actual/User.php";

if (session_status() == PHP_SESSION_NONE)
    session_start();

if (!AuthManager::getLoggedInUserId()) {
    loginLink();
} else {
    profileLink();
}

function profileLink()
{
    $username = AuthManager::getLoggedInUserName();
    ?>
    <li>
        <a href="/aburisk/profile.php" accesskey="p">[ <?php echo $username ?> ]</a>
    </li>
    <li>
        <a href="/aburisk/scripts/logout.php" accesskey="l">Logout</a>
    <li>
<?php
}

function loginLink()
{
    ?>
    <li>
        <a href="/aburisk/login.php" accesskey="l">Login/Register</a>
    </li>
<?php
}

?>