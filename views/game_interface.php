<!DOCTYPE HTML>
<html>
<?php

require_once "head.php";

GameManager::updateEngagedGame(AuthManager::getLoggedInUserId());
GameManager::setModified(true);
$game = GameManager::getGame();

?>

<script>

</script>


<body>

<div id="wrapper">
    <?php require_once "header.php" ?>
    <div id="page">
        <div id="content">
            <?php if (isset($game)) { ?>
                <object id='mapContainer'
                        onload='ABURISK.map.init(initSSE)'
                        type="image/svg+xml" width="750" height="421" data="views/map.svg"></object>
            <?php } else { ?>
                <h2>Nu esti angajat in nici un joc</h2>
            <?php } ?>
        </div>
        <div id="sidebar">
            <div id="tbox1">
                <?php if (isset($game)) { ?>

                    <ul class="style2">
                        <li class="first">
                            <a id="actions" href="javascript:void(0);" class="button-style"
                               onclick="ABURISK.game.changeInnerState();"
                               style=" float:left">
                                Change Stage
                            </a>

                            <form action="scripts/end-game.php" method="post" style=" float:right">
                                <input type="hidden" name='idGame' value="<?php echo $game->id ?>"/>
                                <a href="javascript:void(0);" class="button-style" onclick="submitForm(this)">End
                                    game <?php echo $game->id ?></a>
                            </form>
                            <div class="clearfix"></div>
                        </li>
                        <li>
                            <h3>
                                Statistics
                            </h3>

                            <p> Current player: <span id="currentPlayer"></span></p>

                            <p style="border-bottom: 1px dotted">Time to act:
                                <span id="timeToAct"></span>&nbsp; seconds
                            </p>

                            <p>State: <span id='state'></span></p>

                            <p>
                                Available ships: <span id="remainingShips"></span>
                            </p>

                            <p>Needed players:
                                <a href="javascript:void(0)" id="neededPlayers"></a>
                            </p>

                            <p>Joined players:
                                <a href="javascript:void(0)" id="joinedPlayers"></a>
                            </p>


                        </li>
                        <li id="userlist">
                            <h3>
                                Players
                            </h3>

                            <div id='player_list'></div>
                        </li>


                    </ul>
                <?php } ?>
            </div>
        </div>
    </div>

    <?php require_once "footer.html" ?>

</div>
</body>
</html>