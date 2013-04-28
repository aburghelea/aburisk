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

if (GameManager::getGame() && GameManager::getGame()->state === 'SHIP_PLACING' && GameManager::isLoggedInPlayersTurn())
    $planetHandler = "ABURISK.game.initPlacing";

if (GameManager::getGame() && GameManager::getGame()->state === 'ATTACK' && GameManager::isLoggedInPlayersTurn())
    $planetHandler = "ABURISK.game.initAttack";
if (GameManager::getGame())
    $winner = GameManager::getWinner();

?>

<body>

<div id="wrapper">
    <?php require_once "header.php" ?>
    <div id="page">
        <div id="content">
            <?php
                if (GameManager::getGame() && GameManager::getGame()->state === 'FINISHED') { ?>
                <h2>Game over</h2>
                <h3>Winner is
                    <a target="_blank"
                       href="/aburisk/profile.php?id=<?php echo $winner->getId() ?>">
                        <?php echo $winner->username ?>
                    </a>
                </h3>
            <?php } else if (isset($game)) { ?>
                <object id='mapContainer'
                        onload='ABURISK.map.init(<?php echo $planetsJSON ?>,<?php echo $connectiosJSON ?>, <?php echo $planetHandler ?> )'
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
                            <p>State : <a href="javascript:void(0)"><?php echo $game->state; ?></a></p>
                            <?php if (!GameManager::needsMorePlayers()) { ?>
                                <p id="currentPlayer"> Current player:
                                    <a target="_blank"  id="currentUserName"
                                       href="/aburisk/profile.php?id=<?php echo GameManager::getCurrentPlayerId(); ?>">
                                        <?php echo GameManager::getCurrentPlayerUsername(); ?>
                                    </a></p>

                                <p>
                                    Is your turn?  <?php echo GameManager::isLoggedInPlayersTurn() ? "Yes" : "No" ?>
                                </p>

                                <p>
                                    <?php echo GameManager::getRemainingShips() ?> ship(s) left
                                </p>
                            <?php } else { ?>
                                <p>Needed players: <a href="javascript:void(0)"> <?php echo $game->noplayers ?></a></p>

                                <p>Joined players: <a href="javascript:void(0)">
                                        <?php echo GameManager::getJoinedPlayersNumber(); ?></a></p>

                            <?php }  ?>
                            <form action="scripts/end-game.php" method="post">
                                <input type="hidden" name='idGame' value="<?php echo $game->id ?>"/>
                                <a href="javascript:void(0);" class="button-style" onclick="submitForm(this)">End
                                    game <?php echo $game->id ?></a>
                            </form>

                        </li>
                        <?php if (!GameManager::needsMorePlayers()) { ?>
                            <li id="userlist">
                                <h3>
                                    Players
                                </h3>
                                <?php foreach (GameManager::getPlayers() as $player) { ?>
                                    <p name="<?php echo $player->getId() ?>">

                                        <a target="_blank"
                                           href="/aburisk/profile.php?id=<?php echo $player->getId() ?>">
                                            <?php echo $player->getUsername() ?>
                                        </a>
                                    </p>
                                <?php } ?>
                                <script>
                                    var links = document.getElementById("userlist").getElementsByTagName("p");
                                    for (var i = 0; i < links.length; i++) {
                                        var name = links[i].getAttribute("name");
                                        var class_name = "player_" + ABURISK.players.index(name);
                                        var element = links[i].classList;
                                        links[i].classList.add(class_name);
                                    }
                                    var current = document.getElementById('currentPlayer').getElementsByTagName('a');
                                    var currentUsername = current[0].innerHTML.replace(/\s+/g, ' ') + ".";

                                    document.getElementById('currentPlayer').classList.add("player_" + ABURISK.players.getCurrent())
                                </script>
                            </li>
                            <?php if (GameManager::isLoggedInPlayersTurn()) { ?>
                                <li>
                                    <h3>
                                        Actions
                                    </h3>

                                    <div>
                                        <?php
//                                        if (GameManager::getGame()->state == 'PLANET_CLAIM') {
//                                            require_once dirname(__FILE__) . "/partials/planet-claimer.php";
//                                        }

                                        if (GameManager::getGame()->state == 'SHIP_PLACING') {
                                            require_once dirname(__FILE__) . "/partials/ship-placer.php";
                                        }

                                        if (GameManager::getGame()->state == 'ATTACK') {
                                            require_once dirname(__FILE__) . "/partials/planet-attacker.php";
                                        }
                                        ?>
                                    </div>
                                </li>
                            <?php } ?>
                        <?php } ?>


                    </ul>
                <?php } ?>
            </div>
        </div>
    </div>

    <?php require_once "footer.html" ?>
    <script>
        source = new EventSource('sse/sse.php');
        source.addEventListener('message', function (e) {
            console.log(e.data);
        }, false);
    </script>
</div>
</body>
</html>