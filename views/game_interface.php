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
$planetHandler = "function(){}";
if (GameManager::getGame() && GameManager::getGame()->state === 'PLANET_CLAIM' && GameManager::isLoggedInPlayersTurn())
    $planetHandler = "ABURISK.game.initClaim";
?>

<body>

<div id="wrapper">
    <?php require_once "header.php" ?>
    <div id="page">
        <div id="content">
            <?php if (isset($game)) { ?>
                <object id='mapContainer' onload='ABURISK.map.init(<?php echo $planetsJSON ?>,<?php echo $connectiosJSON ?>, <?php echo $planetHandler ?> )'
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
                    <h2>Game <?php echo $game->id; ?> </h2>

                    <ul class="style2">
                        <li class="first">
                            <h3>Statistics </h3>

                            <p>State : <a href="javascript:void(0)"><?php echo $game->state; ?></a></p>
                            <?php if (!GameManager::needsMorePlayers()) { ?>
                                <p> Current player: <a target="_blank"
                                                       href="/aburisk/profile.php?id=<?php echo GameManager::getCurrentPlayerId(); ?>">
                                        <?php echo GameManager::getCurrentPlayerUsername(); ?>
                                    </a></p>
                                <p>
                                    Is your turn?  <?php echo GameManager::isLoggedInPlayersTurn() ? "Yes" : "No" ?>
                                </p>
                            <?php } else { ?>
                                <p>Needed players: <a href="javascript:void(0)"> <?php echo $game->noplayers ?></a></p>
                                <p>Joined players: <a href="javascript:void(0)">
                                        <?php echo GameManager::getJoinedPlayersNumber(); ?></a></p>

                            <?php }  ?>
                            <form action="scripts/end-game.php" method="post">
                                <input type="hidden" name='idGame' value="<?php echo $game->id ?>"/>
                                <a href="javascript:void(0);" class="button-style" onclick="submitForm(this)">End
                                    game</a>
                            </form>
                        </li>
                        <?php if (!GameManager::needsMorePlayers() && GameManager::isLoggedInPlayersTurn()) { ?>
                            <li>
                                <h3>
                                    Players
                                </h3>
                                <?php foreach (GameManager::getPlayers() as $player) { ?>
                                    <p>
                                        <a target="_blank"
                                           href="/aburisk/profile.php?id=<?php echo $player->getId() ?>">
                                            <?php echo $player->getUsername() ?>
                                        </a>
                                    </p>
                                <?php } ?>
                            </li>
                            <li>
                                <h3>
                                    Actions
                                </h3>

                                <div>
                                    <form action="scripts/claim-planet.php" method="post">
                                        <p>
                                            Selected planet
                                            <input type="text" id="claimIdPlanet" style="width: 30px" name="idPlanet" value="3">
                                        </p>
                                        <input type="hidden" name="idUser"
                                               value="<?php echo AuthManager::getLoggedInUserId() ?>"/>
                                        <input type="hidden" name="idGame"
                                               value="<?php echo $game->id ?>"/>
                                        <!--                                    <div class="clearfix"></div>-->
                                        <a href="javascript:void(0);" class="button-style"
                                           onclick="submitForm(this)">Claim</a>

                                    </form>
                                </div>
                            </li>
                        <?php } ?>


                    </ul>
                <?php } ?>
            </div>
        </div>
    </div>

    <?php require_once "footer.html" ?>
</div>
</body>
</html>