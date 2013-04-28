/**
 * Created with JetBrains PhpStorm.
 * User: iceman
 * Date: 4/7/13
 * Time: 5:51 PM
 * To change this template use File | Settings | File Templates.
 */
ABURISK.game = function () {
    var svgRoot,
        svgDocument;

    function selectPlanet(e, inputId) {
//        resetPlanets();
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
    }

    function selectMaxShips(e, inputId) {
        var shipsInput = document.getElementById(inputId);
        var planetId = e.target.getAttribute("id");
        var shipsId = "ships_" + planetId;
        var noShips = svgDocument.getElementById(shipsId).textContent;
        shipsInput.setAttribute("value", noShips);
    }

    function resetPlanets() {
        var planets = svgDocument.getElementsByClassName("planet");
        for (var i = 0; i < planets.length; i++) {
            var clone = planets[i].cloneNode();
            planets[i].parentNode.replaceChild(clone, planets[i]);
            planets[i] = clone;
        }
    }

    function init() {
        svgRoot = document.getElementById("mapContainer").contentDocument;
        svgDocument = svgRoot.documentElement;
        resetPlanets();
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
            url = 'scripts/get-info.php?about=planets';
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
            url = 'scripts/get-info.php?about=planets';
            success = function (data) {
                var planetsJSON = JSON.parse(data.responseText);
                for (var i in planetsJSON) {
                    ABURISK.players.index(planetsJSON.owner_id);
                    var planet = svgDocument.getElementById(planetsJSON[i].planet_id);
                    if (planetsJSON[i].owner_id == ABURISK.players.getCurrent()) {
                        planet.addEventListener('click', function (e) {
                            selectPlanet(e, "idPlanet1");
                            selectMaxShips(e, "noShips");
                        }, true);
                    } else {
                        planet.addEventListener('click', function (e) {
                            selectPlanet(e, "idPlanet2")
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