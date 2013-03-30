<?php
/**
 * Created by JetBrains PhpStorm.
 * User: iceman
 * Date: 3/31/13
 * Time: 12:36 AM
 * To change this template use File | Settings | File Templates.
 */

require_once  dirname(__FILE__) . "/../dao/User.php";

if (session_status() == PHP_SESSION_NONE)
    session_start();

if (isSet($_SESSION['user_id'])) {
    profileLink();
} else
    loginLink();

function profileLink()
{
    $username = new User();
    $username = $username->getRowsByField('id', $_SESSION['user_id'])[0]->username;
    ?>
    <a href="/aburisk/profile.php" >[ <?php echo $username ?> ]</a>
    </li>
    <li>
    <a href="/aburisk/scripts/logout.php" >Logout</a>

<?php
}

function loginLink()
{
    ?>
    <a href="/aburisk/login.php" >Login</a>
<?php
}

?>