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

    function selectPlanet(e) {
        $claimInput = document.getElementById("idPlanet");
        var planetId = e.target.getAttribute("id");
        $claimInput.setAttribute("value", planetId);
    }

    function init() {
        svgRoot = document.getElementById("mapContainer").contentDocument;
        svgDocument = svgRoot.documentElement;
    }

    return {
        initClaim: function () {
            init();
            var planets = svgDocument.getElementsByClassName("planet");
            for (var i = 0; i < planets.length; i++) {
                planets[i].addEventListener('click', selectPlanet, false);
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
                        planet.addEventListener('click', selectPlanet, true);
                    }
                }
            };

            fail = function () {
                console.log("nasol");
            };

            postCall(url, success, fail);
        }
    }
}();