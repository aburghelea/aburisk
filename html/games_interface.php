<?php
require_once dirname(__FILE__) . "/../dao/Game.php";
require_once dirname(__FILE__) . "/../dao/User_Game.php";
require_once dirname(__FILE__) . "/../dao/User.php";

if (session_status() == PHP_SESSION_NONE)
    session_start();

if (!isSet($_SESSION['user_id'])) {
    header('Location: ' . $_SERVER['CONTEXT_PREFIX'] . '/index.php');
    exit();
}

$gameDao = new Game();
$userGameDao = new User_Game();
$userDao = new User();
$game_list = $gameDao->getRowsByField('state', 'WAITING_PLAYERS');
$liClass = "class='first'";
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
                <ul class="style1 scrolable">
                    <?php

                    foreach ($game_list as $index => $game) {
                        $joined = count($userGameDao->getRowsByField('game_id', $game->getId()));


                        if ($joined < $game->noplayers) {
                            $hostUser = $userDao->getRowsByField('id', $game->getCurrentPlayerId())[0];

                            ?>
                            <li <?php echo $liClass?> >
                                <h3>
                                    <?php echo $index + 1?>)
                                    Game created by
                                    <a href='/profile.php?id=<?php $game->getCurrentPlayerId() ?>'>
                                        <?php echo $hostUser->username ?>
                                    </a>

                                    <form method="post" action="scripts/join-game.php">
                                        <input type="hidden" name="idGame" value="<?php echo  $game->getId() ?>">
                                        <input type="hidden" name="idUser" value="<?php echo $_SESSION['user_id'] ?>">
                                        <a href='#' class="join-style" onclick="submitForm(this)"> JOIN GAME</a>
                                    </form>
                                </h3>

                                <p>
                                    Needed players:
                                    <?php echo $game->noplayers ?></p>

                                <p>
                                    Joined Players:
                                    <?php echo $joined ?>
                                </p>
                            </li>
                            <?php
                            if ($index == 0)
                                $liClass = '';
                        }
                    }
                    ?>

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
                <div class=''>
                    <input type='hidden' name='idHost' value='<?php echo $_SESSION['user_id']; ?>'/>
                </div>
                <p>
                    <!--                    <input type=submit name="submit" style="display: none" value="Submit"/>-->
                    <a href="javascript:void(0);" class="button-style" onclick="submitForm(this)">
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