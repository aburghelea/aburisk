<!DOCTYPE HTML>
<html>
<?php require_once "head.php" ?>

<?php
require_once dirname(__FILE__) . "/../dao/Game.php";
require_once dirname(__FILE__) . "/../dao/User_Game.php";
require_once dirname(__FILE__) . "/../dao/User.php";

if (session_status() == PHP_SESSION_NONE)
    session_start();

if (!AuthManager::getLoggedInUserId()) {
    header('Location: ' . $_SERVER['CONTEXT_PREFIX'] . '/');
    exit();
}

$gameDao = new Game();
$userGameDao = new User_Game();
$userDao = new User();
//$game_list = $gameDao->getRowsByField('state not', 'GAME_END');

$uid = AuthManager::getLoggedInUserId();
$gamesUserCanJoin = "select * from games where state not like 'GAME_END'  and id not in (select game_id from users_games where user_id = $uid)";
$game_list = $gameDao->getCustomRows($gamesUserCanJoin);

$liClass = "class='first'";
?>

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

                    foreach ((array)$game_list as $index => $game) {
                        $joined = count($userGameDao->getRowsByField('game_id', $game->getId()));

                        if ($joined < $game->noplayers) {
                            $hostUser = $userDao->getRowsByField('id', $game->getCurrentPlayerId())[0];

                            ?>
                            <li <?php echo $liClass?> >
                                <h3>
                                    <?php echo $index + 1?>)
                                    Game created by
                                    <a href='/aburisk/profile.php?id=<?php echo $game->getCurrentPlayerId() ?>'>
                                        <?php echo $hostUser->username ?>
                                    </a>

                                    <?php if ($game->state === 'WAITING_PLAYERS') { ?>
                                        <form method="post" action="scripts/join-game.php">
                                            <input type="hidden" name="idGame" value="<?php echo  $game->getId() ?>">
                                            <input type="hidden" name="idUser"
                                                   value="<?php echo AuthManager::getLoggedInUserId() ?>">
                                            <a href='javascript:void(0)' class="join-style" onclick="submitForm(this)">JOIN
                                                GAME</a>
                                        </form>
                                    <?php } else { ?>
                                        <form method="post" action="scripts/watch-game.php">
                                            <input type="hidden" name="idGame" value="<?php echo  $game->getId() ?>">
                                            <input type="hidden" name="idUser"
                                                   value="<?php echo AuthManager::getLoggedInUserId() ?>">
                                            <a href='javascript:void(0)' class="join-style" onclick="submitForm(this)">
                                                &nbsp;SPECTATE&nbsp;</a>
                                        </form>
                                    <?php } ?>
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
                <input type='hidden' name='idHost' value='<?php echo AuthManager::getLoggedInUserId() ?>'/>
                <a href="javascript:void(0);" class="button-style" onclick="submitForm(this)">
                    Create New Game
                </a>
            </form>
        </div>
    </div>


    <?php require_once "footer.html" ?>
</div>
</body>
</html>