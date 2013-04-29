/**
 * Created with JetBrains PhpStorm.
 * User: iceman
 * Date: 4/7/13
 * Time: 5:51 PM
 * To change this template use File | Settings | File Templates.
 */
ABURISK.game = function () {
    var svgRoot,
        svgDocument,
        defenderPlaner,
        attackerPlanet,
        connections,
        arrowDelimiter;

    function selectPlanet(e, inputId) {
        var planetId = e.target.getAttribute("id");
        var url = "scripts/claim-planet.php";
        var success = function (xhr) {
            console.log(xhr.responseText);
            var response = JSON.parse(xhr.responseText);
            console.log(response);
            if (response.status == "SUCCESS") {
                var owner_class = "player_" + ABURISK.players.index(response.owner);
                var circle = svgDocument.getElementById("circle_" + planetId);
                var text = svgDocument.getElementById("ships_" + planetId);
                circle.classList.add(owner_class);
                text.classList.add(owner_class);
                text.textContent = 1;
            }
        };
        var fail = function () {
            console.log("epic fail");
        };
        postCall(url, success, fail, {"idPlanet": planetId});
    }

    function placeShip(e, inputId) {
        var claimInput = document.getElementById(inputId);
        var planetId = e.target.getAttribute("id");
        claimInput.setAttribute("value", planetId);
        var url = "scripts/deploy-ship.php";
        var success = function (xhr) {
            console.log(xhr.responseText);
            var response = JSON.parse(xhr.responseText);
            console.log(response);
            if (response.status == "SUCCESS") {
                var text = svgDocument.getElementById("ships_" + planetId);
                text.textContent = parseInt(text.textContent) + 1;
            }
        };
        var fail = function () {
            console.log("epic fail");
        };
        postCall(url, success, fail, {"idPlanet": planetId});
    }

    function selectMaxShips(e, inputId) {
        var shipsInput = document.getElementById(inputId);
        var planetId = e.target.getAttribute("id");
        var shipsId = "ships_" + planetId;
        var noShips = svgDocument.getElementById(shipsId).textContent;
        noShips = Math.min(noShips, 3);
        shipsInput.setAttribute("value", noShips);
    }

    function resetPlanets() {
        if (svgDocument == undefined) {
            svgRoot = document.getElementById("mapContainer").contentDocument;
            svgDocument = svgRoot.documentElement;
            arrowDelimiter = svgDocument.getElementById("arrow-route");
        }

        var planets = svgDocument.getElementsByClassName("planet");
        for (var i = 0; i < planets.length; i++) {
            var clone = planets[i].cloneNode();
            planets[i].parentNode.replaceChild(clone, planets[i]);
            planets[i] = clone;
        }
        var arrow = svgDocument.getElementById("arrow_attack");
        if (arrow != undefined)
            svgDocument.removeChild(arrow);
    }

    function init() {
        svgRoot = document.getElementById("mapContainer").contentDocument;
        svgDocument = svgRoot.documentElement;
        arrowDelimiter = svgDocument.getElementById("arrow-route");

        resetPlanets();
    }

    function canBeAttacked(defender) {
        for (var i = 0; i < connections.length; i++) {
            if (connections[i].first_planet_id == defender && connections[i].second_planet_id == attackerPlanet)
                return true;
            if (connections[i].second_planet_id == defender && connections[i].first_planet_id == attackerPlanet)
                return true;
        }

        return false;
    }


    function updateArrow(e) {
        console.log(e);
        var arrow = svgDocument.getElementById("arrow_attack");
        arrow.setAttribute("x2", e.clientX);
        arrow.setAttribute("y2", e.clientY);
    }

    function initArrow(e) {
        var circle = svgDocument.getElementById("circle_" + attackerPlanet);
        var cx = circle.getAttribute("cx");
        var cy = circle.getAttribute("cy");
        console.log(circle.getAttribute("cy"));
        var arrow = svgDocument.getElementById("arrow_attack");
        if (arrow == undefined) {
            arrow = document.createElementNS("http://www.w3.org/2000/svg", "line");
            arrow.setAttribute("id", "arrow_attack");
            arrow.setAttribute("class", "arrow");
            svgDocument.insertBefore(arrow, arrowDelimiter);
        }

        arrow.setAttribute("x1", cx);
        arrow.setAttribute("y1", cy);


        updateArrow(e);
        svgDocument.addEventListener("mousemove", updateArrow);

    }

    function selectAttackerPlanet(e, inputId) {
        var claimInput = document.getElementById(inputId);
        attackerPlanet = e.target.getAttribute("id");
        var shipsNo = parseInt(svgDocument.getElementById("ships_" + attackerPlanet).textContent);
        if (shipsNo < 2)
            return;

        claimInput.setAttribute("value", attackerPlanet);
        selectMaxShips(e, "noShips");

        console.log(e);
        initArrow(e);
    }

    function selectDefendingPlanet(e, inputId) {
        var claimInput = document.getElementById(inputId);
        var defender = e.target.getAttribute("id");
        if (canBeAttacked(defender)) {
            defenderPlaner = defender;
            claimInput.setAttribute("value", defenderPlaner);

            updateArrow(e);
            svgDocument.removeEventListener("mousemove", updateArrow);
        }

    }

    return {
        resetPlanets: resetPlanets,
        initClaim: function () {
            init();
            var planets = svgDocument.getElementsByClassName("planet");
            for (var i = 0; i < planets.length; i++) {
                planets[i].addEventListener('click', function (e) {
                    selectPlanet(e, "idPlanet")
                }, false);
            }
        },

        initPlacing: function () {
            init();
            url = 'scripts/get-info.php?about=planets_games';
            success = function (data) {
                var planetsJSON = JSON.parse(data.responseText);
                for (var i in planetsJSON) {
                    ABURISK.players.index(planetsJSON.owner_id);
                    if (planetsJSON[i].owner_id == ABURISK.players.getCurrent()) {
                        var planet = svgDocument.getElementById(planetsJSON[i].planet_id);
                        planet.addEventListener('click', function (e) {
                            placeShip(e, "idPlanet")
                        }, true);
                    }
                }
            };

            fail = function () {
                console.log("Nu s-au obtinut informatii despre planete in stadiul de initializare deply");
            };

            postCall(url, success, fail);
        },

        initAttack: function () {
            init();

            console.log('Initializing attack');
            url = 'scripts/get-info.php?about=all';
            success = function (data) {
                var responsJSON = JSON.parse(data.responseText);
                var planetsGamesJSON = responsJSON.planetsGames;
                connections = responsJSON.connections;
                for (var i in planetsGamesJSON) {
                    ABURISK.players.index(planetsGamesJSON.owner_id);
                    var planet = svgDocument.getElementById(planetsGamesJSON[i].planet_id);
                    if (planetsGamesJSON[i].owner_id == ABURISK.players.getCurrent()) {
                        planet.addEventListener('click', function (e) {
                            selectAttackerPlanet(e, "idPlanet1");

                        }, true);
                    } else {
                        planet.addEventListener('click', function (e) {
                            selectDefendingPlanet(e, "idPlanet2")
                        }, true);
                    }
                }
            };

            fail = function () {
                console.log("Nu s-au obtinut informatii despre planete in stadiul de initializare attack");
            };

            postCall(url, success, fail);
        }


    }
}();