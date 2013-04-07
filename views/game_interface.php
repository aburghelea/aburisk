<!DOCTYPE HTML>
<html>
<?php

require_once "head.php";
require_once dirname(__FILE__) . "/../dao/actual/Planet_Neighbour.php";
require_once dirname(__FILE__) . "/../dao/actual/Planet.php";

$planetDao = new Planet();
$planetNeighboursDao = new Planet_Neighbour();
$planetsJSON = json_encode($planetDao->getRowsByField('"1"', '1'));
$connectiosJSON = json_encode($planetNeighboursDao->getRowsByField('"1"', '1'));

GameManager::updateEngagedGame(AuthManager::getLoggedInUserId());
$game = GameManager::getGame();
$gameId = null;
if (isset($game))
    $gameId = $game->id;

?>

<body>

<div id="wrapper">
    <?php require_once "header.php" ?>
    <div id="page">
        <div id="content">
            <?php if ($gameId) { ?>
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
                <?php if (isset($gameId)) { ?>
                    <h2 style="float: left">Game <?php echo $gameId; ?> </h2>

                    <div style="float: right">

                        <form action="scripts/end-game.php" method="post">
                            <input type="hidden" name='idGame' value="<?php echo $gameId ?>">
                            <a href="javascript:void(0);" class="join-style" onclick="submitForm(this)">End game</a>
                        </form>
                    </div>
                <?php } ?>
                <div class="clearfix"></div>
                <ul class="style2">
                    <li class="first">
                        <h3><a href="#"> Maecenas luctus lectus </a></h3>

                        <p><a href="#"> Quisque dictum integer nisl risus, sagittis convallis, rutrum id, congue, and
                                nibh .</a></p>
                    </li>
                    <li>
                        <h3><a href="#"> Integer gravida nibh </a></h3>

                        <p><a href="#"> Quisque dictum integer nisl risus, sagittis convallis, rutrum id, congue, and
                                nibh .</a></p>
                    </li>
                    <li>
                        <h3><a href="#"> Fusce ultrices fringilla </a></h3>

                        <p><a href="#"> Quisque dictum integer nisl risus, sagittis convallis, rutrum id, congue, and
                                nibh .</a></p>
                    </li>
                    <li>
                        <h3><a href="#"> Nulla luctus eleifend </a></h3>

                        <p><a href="#"> Quisque dictum integer nisl risus, sagittis convallis, rutrum id, congue, and
                                nibh .</a></p>
                    </li>
                </ul>
            </div>
        </div>
    </div>


    <?php require_once "footer.html" ?>
</div>
</body>
</html>