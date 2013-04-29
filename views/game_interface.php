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
GameManager::setModified(true);
$game = GameManager::getGame();
if (GameManager::getGame())
    $winner = GameManager::getWinner();

?>

<script>

    function makePlayerName(player) {
        var anchor = document.createElement("a");
        anchor.setAttribute("target", "_black");
        anchor.setAttribute("href", "profile.php?id=" + player.id);
        anchor.appendChild(document.createTextNode(player.username));
        return anchor;
    }
    function makePlayerFleet(player) {
        var planetsShips = document.createElement("span");
        planetsShips.appendChild(document.createTextNode(player.ships + " ships on " + player.planets + " planets"));
        planetsShips.setAttribute("style", "float:right;");
        return planetsShips;
    }

    function makePlayerScore(player) {
        var score = document.createElement("span");
        score.appendChild(document.createTextNode("(" + player.score + ")"));
        return score;
    }
    function updatePlayerList(responseJSON) {
        var list = document.getElementById("player_list");
        var parent = list.parentNode;
        var newlist = document.createElement("div");
        newlist.setAttribute("id", "player_list");

        for (var i in responseJSON.player_list) {
            var player = responseJSON.player_list[i];
            var ptag = document.createElement("p");
            ptag.setAttribute('name', player.id);
            ptag.classList.add("player_" + ABURISK.players.index(player.id));

            var score = makePlayerScore(player);
            var anchor = makePlayerName(player);
            var planetsShips = makePlayerFleet(player);

            ptag.appendChild(score);
            ptag.appendChild(anchor);
            ptag.appendChild(planetsShips);
            newlist.appendChild(ptag);
        }
        parent.replaceChild(newlist, list);
    }

    function updateState(responseJSON) {
        var state = document.getElementById("state");
        clearContent(state);
        state.appendChild(document.createTextNode(responseJSON.state));
    }

    function updateCurrentPlayer(responseJSON) {

        var currentPlayer = document.getElementById("currentPlayer");
        clearContent(currentPlayer);
        var anchor = document.createElement('a');
        anchor.setAttribute("target", "_blank");
        var jsonCurrent = responseJSON.currentPlayer;
        anchor.setAttribute("href", "profile.php?id=" + jsonCurrent.id);
        anchor.appendChild(document.createTextNode(jsonCurrent.username));
        currentPlayer.appendChild(anchor);
        currentPlayer.parentNode.setAttribute("class", "player_" + ABURISK.players.index(jsonCurrent.id))
    }
    function initGame() {
//        var handler = function () {
//            ABURISK.game.initClaim();
//            initSSE();
//        };
        ABURISK.map.init(initSSE);
    }
    function initSSE() {
        source = new EventSource('sse/sse.php');
        source.addEventListener('message', function (e) {
            var actions = document.getElementById("actions");

            console.log(e.data);
            var responseJSON = JSON.parse(e.data);
            if (responseJSON.status == "NONE") {
                ABURISK.game.resetPlanets();
            }
            if (responseJSON.status == "UPDATE") {
                ABURISK.game.resetPlanets();
                if (responseJSON.action != "NONE")
                    actions.style.display = 'block';
                else
                    actions.style.display = 'none';
                if (responseJSON.action == "PLANET_CLAIM") {
                    ABURISK.game.initClaim();
                }
                if (responseJSON.action == "SHIP_PLACING") {
                    ABURISK.game.initPlacing();
                }
                if (responseJSON.action == "ATTACK") {

                    console.log("preparring attack");
                    ABURISK.game.initAttack();
                }

                console.log(e.data);
                updatePlayerList(responseJSON);
                updateState(responseJSON);
                updateCurrentPlayer(responseJSON);
                ABURISK.map.setPlanetInfo();

            }
        }, false);
    }
</script>


<body>

<div id="wrapper">
    <?php require_once "header.php" ?>
    <div id="page">
        <div id="content">
            <?php
            if (GameManager::getGame() && GameManager::getGame()->state === 'FINISHED') {
                ?>
                <h2>Game over</h2>
                <h3>Winner is
                    <a target="_blank"
                       href="/aburisk/profile.php?id=<?php echo $winner->getId() ?>">
                        <?php echo $winner->username ?>
                    </a>
                </h3>
            <?php } else if (isset($game)) { ?>
                <object id='mapContainer'
                        onload='initGame()'
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
                            <p>State: <span id='state'></span></p>

                            <p> Current player: <span id="currentPlayer"></span></p>

                            <p>
                                <?php if (!GameManager::needsMorePlayers()) { ?>
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
                        <li id="userlist">
                            <h3>
                                Players
                            </h3>

                            <div id='player_list'>
                            </div>
                        </li>
                        <!--                        --><?php //if (!GameManager::needsMorePlayers()) { ?>
                        <!--                            --><?php //if (GameManager::isLoggedInPlayersTurn()) { ?>
                        <li id="actions">
                            <h3>
                                Actions
                            </h3>

                            <div>
                                <p>
                                    <a href="javascript:void(0);" class="button-style"
                                       onclick="ABURISK.game.changeInnerState();"
                                        >
                                        <!--                                       style="margin-top: 0 !important;"-->
                                        &gt;&gt;Change inner turn</a>
                                </p>
                            </div>
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