<!DOCTYPE HTML>
<html>
<?php

require_once "head.php";
require_once dirname(__FILE__) . "/../dao/actual/Planet_Neighbour.php";
require_once dirname(__FILE__) . "/../dao/actual/Planet.php";
require_once dirname(__FILE__) . "/../dao/actual/User_Game.php";

$planetDao = new Planet();
$planetNeighboursDao = new Planet_Neighbour();
$userGameDao = new User_Game();
$planetsJSON = json_encode($planetDao->getRowsByField('"1"', '1'));
$connectiosJSON = json_encode($planetNeighboursDao->getRowsByField('"1"', '1'));

GameManager::updateEngagedGame(AuthManager::getLoggedInUserId());
$game = GameManager::getGame();

?>

<body>

<div id="wrapper">
    <?php require_once "header.php" ?>
    <div id="page">
        <div id="content">
            <?php if (isset($game)) { ?>
                <object id='map' onload='ABURISK.map.init(<?php echo $planetsJSON ?>,<?php echo $connectiosJSON ?> )'
                        type="image/svg+xml" width="750" height="421" data="views/map.svg"></object>
            <?php
            } else {
                ?>
                <h2>Nu esti angajat in nici un joc</h2>
            <?php
            }
            ?>
        </div>
        <div id="sidebar">
            <div id="tbox1">
                <?php if (isset($game)) { ?>
                    <h2 style="float: left">Game <?php echo $game->id; ?> </h2>

                    <div style="float: right">

                        <form action="scripts/end-game.php" method="post">
                            <input type="hidden" name='idGame' value="<?php echo $game->id ?>">
                            <a href="javascript:void(0);" class="join-style" onclick="submitForm(this)">End game</a>
                        </form>
                    </div>
                    <div class="clearfix"></div>

                    <ul class="style2">
                        <li class="first">
                           <h3> State </h3>

                            <p><a href="javascript:void(0)"><?php echo $game->state; ?></a></p>
                        </li>
                        <li>
                           <h3>Needed / Joined players</h3>

                            <p><a href="javascript:void(0)"> <?php echo "$game->noplayers / ".GameManager::getJoinedPlayers(); ?></a></p>
                        </li>

                        <li>
                            <h3>
                                    Needs more players?
                            </h3>

                            <p><a href="javascript:void(0)"> <?php echo GameManager::needsMorePlayers() ? "Yes" : "No"; ?></a></p>
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