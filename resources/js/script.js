function init() {

    var formContainer = document.getElementById('formContainer');
    var containers = document.getElementsByClassName('flipLink');
    for (var i = 0; i < containers.length; i++) {
        containers[i].addEventListener('click', function () {
            var captcha = document.getElementById("recaptcha_container");
            var captcha_flipped = document.getElementById("captcha_flipped");
            var captcha_unflipped = document.getElementById("captcha_unflipped");

            if (formContainer.classList.contains('flipped')) {
                formContainer.classList.remove('flipped');
                captcha_unflipped.appendChild(captcha);
            } else {
                formContainer.classList.add('flipped');
                captcha_flipped.appendChild(captcha);
            }
        }, true);

    }

}

function submitForm(element) {
    while (element) {
        element = element.parentNode;
        if (element.tagName.toLowerCase() == "form") {
            element.submit();
            return element;
        }
    }
    return 0;
}


function prepareParams(json) {
    var dataArray = [];
    for (var i in json) {
        var encodedData = encodeURIComponent(i);
        encodedData += "=";
        encodedData += encodeURIComponent(json[i]);
        dataArray.push(encodedData);
    }
    return dataArray.join("&");
}

function postCall(url, success, fail, params) {
    var xhr = new XMLHttpRequest;

    xhr.open('POST', url, true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    if (params !== undefined) {
        console.log(prepareParams(params));
        xhr.send(prepareParams(params));
    }
    else
        xhr.send("");
    xhr.onreadystatechange = function () {
        if (xhr.readyState === 4) {
            if (xhr.status === 200) {
                success(xhr);
            } else {
                fail()
            }
        } else {
            //still loading
        }
    };
}

function clearContent(container) {
    while (container.firstChild) {
        container.removeChild(container.firstChild);
    }
}


function makePlayerName(player) {
    var anchor = document.createElement("a");
    anchor.setAttribute("target", "_black");
    anchor.setAttribute("href", "profile.php?id=" + player.id);
    anchor.appendChild(document.createTextNode("[" + player.username + "] "));
    return anchor;
}
function makePlayerFleet(player) {
    var planetsShips = document.createElement("span");
    planetsShips.appendChild(document.createTextNode(player.ships + " ships / " + player.planets + " planets"));
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
        ptag.className = "player_" + ABURISK.players.index(player.id);

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

function manageWinner(responseJSON) {
    var mapcontainer = document.getElementById("mapContainer");
    mapcontainer.parentNode.removeChild(mapcontainer);
    alert("Game won by " + responseJSON.winner.username);
    window.location = "/aburisk/gameslist.php";
    var url = "scripts/end-game.php";
    var callback;
    callback = function () {
    };
    postCall(url, callback, callback);
}

function timeDisplayer(_time) {
    var timeToAct = document.getElementById("timeToAct");
    clearContent(timeToAct);
    timeToAct.appendChild(document.createTextNode(_time));
}

function timeSignaler() {
    var timeToAct = document.getElementById("timeToAct");
    clearContent(timeToAct);
    timeToAct.appendChild(document.createTextNode(0));

    console.log("Signaling turn change");

    var url = "scripts/change-player.php";
    var callback;
    callback = function () {
    };
    postCall(url, callback, callback);
}

function gameMapInit(responseJSON, actions) {
    if (responseJSON.action == "NONE")
        ABURISK.timer.disarm();
    if (responseJSON.action == "PLANET_CLAIM") {
        ABURISK.game.initClaim();
        ABURISK.timer.disarm();

        ABURISK.timer.init(timeDisplayer, timeSignaler);

    }
    if (responseJSON.action == "SHIP_PLACING") {
        ABURISK.game.initPlacing();
        actions.style.display = 'block';
        clearContent(actions);
        actions.appendChild(document.createTextNode("Start attacking"));
        ABURISK.timer.init(timeDisplayer, timeSignaler);
    }
    if (responseJSON.action == "ATTACK") {
        ABURISK.game.initAttack();
        actions.style.display = 'block';
        clearContent(actions);
        actions.appendChild(document.createTextNode("Next player"));
        ABURISK.timer.init(timeDisplayer, timeSignaler);
    }
}
function updateAnimation(responseJSON) {
    var info = responseJSON.animation_info;
    if (info.to != 0 && info.to != undefined && info.to != null)
        ABURISK.game.doAnimation(info.to, info.from, info.with);
}
function updateInterface(responseJSON) {

    if (responseJSON.winner != undefined && responseJSON.winner != null) {
        manageWinner(responseJSON);
    }
    updateAnimation(responseJSON);
    updatePlayerList(responseJSON);
    updateState(responseJSON);
    updateCurrentPlayer(responseJSON);
    updateRemainingShips(responseJSON);
    updatePlayersNo(responseJSON);
}
function initGameSSE() {
    source = new EventSource('sse/GameSSE.php');
    source.addEventListener('message', function (e) {
        var actions = document.getElementById("actions");

        var responseJSON = JSON.parse(e.data);
        console.log(responseJSON);
        if (responseJSON.status == "EXIT") {
            window.location = "/aburisk/gameslist.php";
        }
        if (responseJSON.status == "UPDATE") {

            ABURISK.game.resetPlanets();
            actions.style.display = 'none';
            gameMapInit(responseJSON, actions);

            updateInterface(responseJSON);
            ABURISK.map.setPlanetInfo();

        }
    }, false);
}


function removeAfterTime(id, time) {
    time = time == undefined ? 3000 : time;
    function remove() {
        var element = document.getElementById(id);
        console.log(element);
        if (element != undefined && element != null)
            element.parentNode.removeChild(element);
    }

    setTimeout(remove, time);
}
ABURISK = {};

