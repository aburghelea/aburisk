<!DOCTYPE HTML>
<html>
<?php

require_once "head.php";

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
        currentPlayer.parentNode.setAttribute("class", "player_" + ABURISK.players.index(jsonCurrent.id));
    }

    function updateRemainingShips(responseJSON) {
        var remainingShips = document.getElementById("remainingShips");
        clearContent(remainingShips);
        remainingShips.appendChild(document.createTextNode(responseJSON.ships));
    }
    function updatePlayersNo(responseJSON) {
        var container = document.getElementById("neededPlayers");
        clearContent(container);
        container.appendChild(document.createTextNode(responseJSON.neededPlayers));

        container = document.getElementById("joinedPlayers");
        clearContent(container);
        container.appendChild(document.createTextNode(responseJSON.joinedPlayers));
    }
    function initGame() {
        ABURISK.map.init(initSSE);
    }

    function manageWinner(responseJSON) {
        var mapcontainer = document.getElementById("mapContainer");
        mapcontainer.parentNode.removeChild(mapcontainer);
        alert("Game won by " + responseJSON.winner.username);
        window.location = "/aburisk/games_list.php";
        var url = "scripts/end-game.php";
        var callback;
        callback = function () {
        };
        postCall(url, callback, callback);
    }
    function initSSE() {
        source = new EventSource('sse/sse.php');
        source.addEventListener('message', function (e) {
            var actions = document.getElementById("actions");

            var responseJSON = JSON.parse(e.data);
            console.log(responseJSON);
            if (responseJSON.status == "NONE") {
                ABURISK.game.resetPlanets();
            }
            if (responseJSON.status == "UPDATE") {
                ABURISK.game.resetPlanets();
                actions.style.display = 'none';

                if (responseJSON.action == "PLANET_CLAIM") {
                    ABURISK.game.initClaim();
                }
                if (responseJSON.action == "SHIP_PLACING") {
                    ABURISK.game.initPlacing();
                    actions.style.display = 'block';
                    clearContent(actions);
                    actions.appendChild(document.createTextNode("Start attacking"));
                }
                if (responseJSON.action == "ATTACK") {

                    ABURISK.game.initAttack();
                    actions.style.display = 'block';
                    clearContent(actions);
                    actions.appendChild(document.createTextNode("Next player"));
                }

                if (responseJSON.winner != undefined && responseJSON.winner != null) {
                    manageWinner(responseJSON);
                }

                updatePlayerList(responseJSON);
                updateState(responseJSON);
                updateCurrentPlayer(responseJSON);
                updateRemainingShips(responseJSON);
                updatePlayersNo(responseJSON);

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
            <?php if (isset($game)) { ?>
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
                            <a id="actions"
                               href="javascript:void(0);" class="button-style"
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