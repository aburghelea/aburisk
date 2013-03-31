<?php

require_once dirname(__FILE__) . "/../dao/User.php";
if (session_status() == PHP_SESSION_NONE)
    session_start();
if (isset($_GET['id']))
    $id = $_GET['id'];
else
    if (isset($_SESSION['user_id'])) {
        $id = $_SESSION['user_id'];
    }

if (!isset($id)) {
    header('Location: ' . $_SERVER['CONTEXT_PREFIX'] . '/index.php');
    exit();
}
$user = new User();
$user = $user->getRowsByField('id', $id)[0];

if ($user == null ) {
    header('Location: ' . $_SERVER['CONTEXT_PREFIX'] . '/index.php');
    exit();
}
?>

<!DOCTYPE HTML>
<html>
<?php require_once "head.html" ?>

<body>
<div id="wrapper">
    <?php require_once "header.php" ?>
    <div id="page">
        <div id="content-center" style="width: 300px">
            <div id="tbox1">
                <h1>Profile info</h1>
                <ul class="style2">
                    <li class="">
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
                                Email:
                            </a>
                        </h3>

                        <p>
                            <?php echo $user->email ?>
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

                </ul>
            </div>
        </div>
    </div>


    <?php require_once "footer.html" ?>
</div>
</body>
</html>