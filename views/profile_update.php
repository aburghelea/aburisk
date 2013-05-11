<?php
/**
 * User: Alexandru George Burghelea
 * For : PWeb 2013
 */

require_once "head.php";

require_once dirname(__FILE__) . "/../dao/actual/User.php";
if (session_status() == PHP_SESSION_NONE)
    session_start();

$id = AuthManager::getLoggedInUserId();
if (!isset($id)) {
    header('Location: ' . $_SERVER['CONTEXT_PREFIX'] . '/index.php');
    exit();
}
$user = new User();
$user = $user->getRowsByField('id', $id)[0];
if ($user == null) {
    header('Location: ' . $_SERVER['CONTEXT_PREFIX'] . '/index.php');
    exit();
}
?>

<!DOCTYPE HTML>
<html>


<body>
<div id="wrapper">
    <?php require_once "header.php" ?>
    <div id="page">
        <div id="content-center" style="width: 300px">
            <div id="profile">
                <h1>
                    Profile info
                </h1>

                <form method="post" action="scripts/change-password.php">
                    <ul class="style2">
                        <li>
                            <h3>
                                <a href="javascript:void(0);">
                                    Username:
                                </a>
                            </h3>

                            <p>
                                <?php echo $user->username ?>
                            </p>
                        </li>
                        <li>

                            <h3>
                                <input type="text" name="password" placeholder="New Password">
                            </h3>
                            <div class="clearfix"/>
                        </li>
                        <li>
                            <a href='javascript:void(0)' class="join-style" onclick="submitForm(this)">Save</a>
                        </li>
                    </ul>
                </form>
            </div>
        </div>
    </div>


    <?php require_once "footer.html" ?>
</div>
</body>
</html>