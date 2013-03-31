<?php
if (session_status() == PHP_SESSION_NONE)
    session_start();

if (!isSet($_SESSION['user_id'])) {
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
        <div id="content">
            <!----------- ADD HERE ------------>


            <div id="tbox3">
                <h2>Games list</h2>
                <ul class="style1">
                    <li class="first"><a href="#">Twitter</a></li>
                    <li><a href="#">Facebook</a></li>
                </ul>
            </div>

        </div>
        <div id="sidebar">
            <form id="creategame" name="creategame" method="post" action="scripts/create-game.php">

                <h2>New Game</h2>
                <ul class="style2">
                    <li>
                        <h3>
                            Number of players:
                        </h3>
                        <p>
                            <input type="number" name="noplayers" id="noplayers"
                                   style="position: inherit" placeholder="Number of players"
                                    min='2' max='5' value="2"
                                />
                        </p>
                    </li>
                </ul>
                <div class='hidden'>
                    <input type='text' name='idHost' value='
                    <?php echo $_SESSION['user_id']; ?>
                    ' />
                </div>
                   <p>
                       <input type=submit  name="submit" style="display: none" value="Submit"/>
                       <a href="javascript:void(0);" class="button-style" onclick="document.creategame.submit.click();">
                           Create New Game
                       </a>
                   </p>
            </form>
        </div>
    </div>


    <?php require_once "footer.html" ?>
</div>
</body>
</html>