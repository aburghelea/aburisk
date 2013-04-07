/**
 * Created with JetBrains PhpStorm.
 * User: iceman
 * Date: 4/7/13
 * Time: 5:51 PM
 * To change this template use File | Settings | File Templates.
 */
ABURISK.game = function() {
    var svgRoot,
        svgDocument;

     var selectForClaim = function(e) {

    };

//    planet.addEventListener("mouseout", selectForClaim, false);

    function claimPlanet(e) {
        $claimInput = document.getElementById("claimIdPlanet");
        var planetId = e.target.getAttribute("id");
        $claimInput.setAttribute("value", planetId);
    }

    return {
        initClaim : function() {
            svgRoot = document.getElementById("mapContainer").contentDocument;
            svgDocument = svgRoot.documentElement;
            var planets = svgDocument.getElementsByClassName("planet");
            for (var i = 0; i < planets.length; i++) {
                planets[i].addEventListener('click', claimPlanet, false);
            }
        }
    }
}();