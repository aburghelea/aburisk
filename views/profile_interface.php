<!DOCTYPE HTML>
<html>
<?php require_once "head.php" ?>

<?php

require_once dirname(__FILE__) . "/../dao/actual/User.php";
if (session_status() == PHP_SESSION_NONE)
    session_start();
if (isset($_GET['id']))
    $id = $_GET['id'];
else
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



<body>
<div id="wrapper">
    <?php require_once "header.php" ?>
    <div id="page">
        <div id="content-center" style="width: 300px">
            <div id="profile">
                <h1>
                    Profile info
                </h1>
                <ul class="style2">
                    <li class="error_msg" style="width: auto !important; padding: 5px">
                            If you have just created the profile via Google Accounts, please change your password,
                            otherwise it will be the same with your email.

                    </li>
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
                            <a href="javascript:void(0);">
                                Games Played:
                            </a>
                        </h3>

                        <p>
                            <?php echo $user->played_games ?>
                        </p>

                    </li>
                    <li style="clear:both">
                        <h3>
                            <a href="javascript:void(0);">
                                Games Won
                            </a>
                        </h3>
                        <?php echo $user->won_games ?>
                        <p>

                        </p>
                    </li>
                    <li>
                        <a href="profileupdate.php" class="button-style">Update password</a>
                    </li>
                </ul>
            </div>
        </div>
    </div>


    <?php require_once "footer.html" ?>
</div>
</body>
</html>