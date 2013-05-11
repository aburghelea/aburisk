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
        defenderPlanet,
        attackerPlanet,
        connections,
        arrowDelimiter,
        noShips;

    function selectPlanet(e, inputId) {
        console.log("Select handler");
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
                circle.className = owner_class;
                text.className = owner_class;
                text.textContent = 1;
            }
        };
        var fail = function () {
            console.log("epic fail");
        };
        postCall(url, success, fail, {"idPlanet": planetId});
    }

    function placeShip(e, inputId) {
        var planetId = e.target.getAttribute("id");
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
        noShips = svgDocument.getElementById(shipsId).textContent;
        noShips = Math.min(noShips, 3);
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
        console.log("canBeAttacked " + defender + " by " + attackerPlanet);
        for (var i = 0; i < connections.length; i++) {
            if (connections[i].first_planet_id == defender && connections[i].second_planet_id == attackerPlanet)
                return true;
            if (connections[i].second_planet_id == defender && connections[i].first_planet_id == attackerPlanet)
                return true;
        }

        return false;
    }


    function updateArrow(e) {
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

        selectMaxShips(e, "noShips");

        console.log(e);
        initArrow(e);
    }

    var STEPS = 10;
    var MILIS = 1000;

    function moveAnimation(dx, dy, ex, ey) {
        var text = svgDocument.getElementById("animation");
        var circle = svgDocument.getElementById("circle_animation");
        var cx = text.getAttribute("x");
        var cy = text.getAttribute("y");
        var xx = parseInt(ex) - parseInt(cx);
        var yy = parseInt(ey) - parseInt(cy);
        var dist = Math.sqrt(xx * xx + yy * yy);
        if (dist < 10) {
            svgDocument.removeChild(text);
            svgDocument.removeChild(circle);
        } else {
            text.setAttribute("x", parseInt(cx) + parseInt(dx));
            text.setAttribute("y", parseInt(cy) + parseInt(dy));
            circle.setAttribute("cx", parseInt(cx) + parseInt(dx));
            circle.setAttribute("cy", parseInt(cy) + parseInt(dy));
            setTimeout(function () {
                moveAnimation(dx, dy, ex, ey)
            }, MILIS / STEPS);
        }

    }

    function doAnimation(ap, dp, ns) {
        ap = ap == undefined ? attackerPlanet : ap;
        dp = dp == undefined ? defenderPlanet : dp;
        ns = ns == undefined ? noShips : ns;

        var start = svgDocument.getElementById("circle_" + ap);
        var end = svgDocument.getElementById("circle_" + dp);

        var sx = start.getAttribute('cx');
        var sy = start.getAttribute('cy');
        var ex = end.getAttribute('cx');
        var ey = end.getAttribute('cy');

        var dx = (ex - sx) / STEPS;
        var dy = (ey - sy) / STEPS;
        console.log("Animating from " + dx + "  " + dy);
        var text = document.createElementNS("http://www.w3.org/2000/svg", "text");

        text.setAttribute('x', sx);
        text.setAttribute('y', sy);
        text.setAttribute('id', "animation");
        text.setAttribute('class', "player_6");
        text.textContent = ns;

        var circle = document.createElementNS("http://www.w3.org/2000/svg", "circle");

        circle.setAttribute('cx', sx);
        circle.setAttribute('cy', sy);
        circle.setAttribute('r', 20);
        circle.setAttribute('id', "circle_animation");
        circle.setAttribute('class', "galaxy_5");
        svgDocument.appendChild(circle);
        svgDocument.appendChild(text);

        moveAnimation(dx, dy, ex, ey);
    }

    function selectDefendingPlanet(e, inputId) {
        var defender = e.target.getAttribute("id");
        if (canBeAttacked(defender)) {
            defenderPlanet = defender;
            svgDocument.removeEventListener("mousemove", updateArrow);
            doAnimation();
            doAttack();
        }


    }

    function doAttack() {
        var url = "scripts/attack.php";
        var success = function (data) {
            console.log(data.responseText);
            var json = JSON.parse(data.responseText);
            console.log("Attack finished");
        };
        var fail = function () {
            console.log("Nu s-au putut obtine informatii despre attack");
        };

        var params = {idPlanet1: attackerPlanet, idPlanet2: defenderPlanet, noShips: noShips};
        postCall(url, success, fail, params);
    }

    return {
        resetPlanets: resetPlanets,
        initClaim: function () {
            console.log("Initializing claim");
            init();
            var planets = svgDocument.getElementsByClassName("planet");
            for (var i = 0; i < planets.length; i++) {
                console.log(i);
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
        },

        doAttack: doAttack,
        doAnimation: doAnimation,
        changeInnerState: function () {
            var url = "scripts/change-inner-state.php";
            var success = function (data) {
                console.log(data.responseText);
                var json = JSON.parse(data.responseText);
                console.log("Changed turn");
                console.log(json);
            };
            var fail = function () {
                console.log("Nu s-au putut obtine informatii despre schimbarea starii");
            };

            postCall(url, success, fail);
        }

    }
}();