/**
 * Created with JetBrains PhpStorm.
 * User: iceman
 * Date: 4/7/13
 * Time: 3:57 PM
 * To change this template use File | Settings | File Templates.
 */


ABURISK.map = function () {

    var svgRoot,
        svgDocument,
        galaxiesDelimiter,
        atmospheresDelimiter,
        planetsDelimiter,
        routesDelimiter;

    var getAngle = function (point_a, point_b) {
        var deltaX = point_a.x_pos - point_b.x_pos;
        var deltaY = point_a.y_pos - point_b.y_pos;

        return (Math.atan2(deltaX, deltaY) * 180 / Math.PI + 360) % 360;
    };

    var getPlanetById = function (planets, id) {
        var i;
        for (i = 0; i < planets.length; i++) {
            if (id == planets[i].id)
                return planets[i];
        }

        return null;
    };

    var getCenterOfPlanets = function (points) {
        var x_pos = 0, y_pos = 0;
        for (var i = 0; i < points.length; i++) {
            x_pos += points[i].x_pos;
            y_pos += points[i].y_pos;
        }

        x_pos /= points.length;
        y_pos /= points.length;

        return {x_pos: x_pos, y_pos: y_pos};
    };

    var pointComparator = function (center) {
        var _center = center;
        return function (a, b) {
            var angle_a = getAngle(a, _center),
                angle_b = getAngle(b, _center);
            return angle_a < angle_b;
        }
    };

    var sortPlanetsCounterClockWise = function (galaxies) {
        var i;
        for (i in galaxies) {
            if (galaxies.hasOwnProperty(i)) {
                var center = getCenterOfPlanets(galaxies[i]);
                galaxies[i].sort(pointComparator(center));
            }
        }
        return galaxies;
    };

    var createRoute = function (first_planet, second_planet) {
        var polygon = document.createElementNS("http://www.w3.org/2000/svg", "line");
        polygon.setAttribute("x1", first_planet.x_pos + first_planet.radius);
        polygon.setAttribute("y1", first_planet.y_pos + first_planet.radius);
        polygon.setAttribute("x2", second_planet.x_pos + second_planet.radius);
        polygon.setAttribute("y2", second_planet.y_pos + second_planet.radius);
        polygon.setAttribute("class", "connection");

        return polygon;
    };

    function isOwnerByCurrentPlayer(circle) {
        var owner = 0;
        for (var i = 0; i < circle.classList.length; i++) {
            var _class = circle.classList[i];
            if (_class.indexOf("player_") !== -1) {
                owner = _class.substr(_class.indexOf("player_") + 7);
            }
            else
                owner = -1;
        }
        return owner == ABURISK.players.getCurrent() || owner == 0;
    }

    var enlargeAtmosphere = function enlargeAtmosphere(e) {
        console.log("enlarge");
        var owner_id = e.target.getAttribute('id');
        id = 'circle_' + owner_id;
        var circle = svgDocument.getElementById(id);

        if (isOwnerByCurrentPlayer(circle)) {
            var radius = circle.getAttribute('r');
            circle.setAttribute('r', Number(radius) * 1.1);
        }
    };

    var shrinkAtmoshpere = function shrinkAtmoshpere(e) {
        var id = 'circle_' + e.target.getAttribute('id');
        var circle = svgDocument.getElementById(id);
        if (isOwnerByCurrentPlayer(circle)) {
            var radius = circle.getAttribute('r');
            circle.setAttribute('r', Number(radius) / 1.1);
        }
    };


    var createAtmosphere = function (planetJSON) {
        var circle = document.createElementNS("http://www.w3.org/2000/svg", "circle");
        circle.setAttribute('class', "galaxy_" + planetJSON.containing_galaxy_id);
        circle.setAttribute('cx', planetJSON.x_pos + planetJSON.radius);
        circle.setAttribute('cy', planetJSON.y_pos + +planetJSON.radius);
        circle.setAttribute('r', planetJSON.radius * 1.1);
        circle.setAttribute('id', 'circle_' + planetJSON.id);

        return circle;
    };

    var createPlanet = function (planetJSON) {
        var planet = document.createElementNS("http://www.w3.org/2000/svg", "image");
        planet.setAttribute('class', "planet");
        planet.setAttribute('x', planetJSON.x_pos);
        planet.setAttribute('y', planetJSON.y_pos);
        planet.setAttribute('width', planetJSON.radius * 2);
        planet.setAttribute('height', planetJSON.radius * 2);
        planet.setAttribute('id', planetJSON.id);
        planet.setAttributeNS('http://www.w3.org/1999/xlink', 'xlink:href', "/aburisk/resources/plantes/" + planetJSON.image);
        planet.addEventListener("mouseover", enlargeAtmosphere, false);
        planet.addEventListener("mouseout", shrinkAtmoshpere, false);
        return planet;
    };

    var createShipNo = function (planplanetJSON) {
        var circle = svgDocument.getElementById("circle_" + planplanetJSON.planet_id);
        circle.className = "player_" + ABURISK.players.index(planplanetJSON.owner_id);
        for (var i = 0; i < 10; i++) {
            if (circle.classList.contains("player_" + i))
                circle.classList.remove("player_" + i);
        }
        circle.classList.add("player_" + ABURISK.players.index(planplanetJSON.owner_id));

        var x = circle.getAttribute('cx');
        var y = circle.getAttribute('cy');
        var text = svgDocument.getElementById("ships_" + planplanetJSON.planet_id);
        if (text == undefined) {
            text = document.createElementNS("http://www.w3.org/2000/svg", "text");
            text.setAttribute('x', x);
            text.setAttribute('y', y);
            text.setAttribute('id', "ships_" + planplanetJSON.planet_id);
        }
        text.setAttribute('class', "text player_" + ABURISK.players.index(planplanetJSON.owner_id));
        text.textContent = planplanetJSON.noships;

        return text == undefined ? false : text;
    };

    var createPolygon = function (galaxy, cn) {
        var points = "";
        for (var i in galaxy) {
            if (galaxy.hasOwnProperty(i))
                points += galaxy[i].x_pos + "," + galaxy[i].y_pos + " ";
        }
        var polygon = document.createElementNS("http://www.w3.org/2000/svg", "polygon");
        polygon.setAttribute("points", points);
        polygon.setAttribute("class", "galaxy_" + cn);

        return polygon;
    };

    return {
        enlarge: enlargeAtmosphere,
        shrink: shrinkAtmoshpere,
        init: function (planetHandler) {
            var url = 'scripts/get-info.php?about=planet_connections';
            var _this = this;
            var success = function (data) {
                var jsonData = JSON.parse(data.responseText);
                var planets = jsonData.planets;
                var connections = jsonData.connections;
                svgRoot = document.getElementById("mapContainer").contentDocument;
                svgDocument = svgRoot.documentElement;
                galaxiesDelimiter = svgRoot.getElementById("galaxies");
                atmospheresDelimiter = svgDocument.getElementById('atmospheres');
                planetsDelimiter = svgDocument.getElementById('planets');
                routesDelimiter = svgRoot.getElementById("routes");

                _this.drawPlanets(planets);
                _this.drawGalaxies(planets);
                _this.drawConnections(connections, planets);
                _this.setPlanetInfo();

                planetHandler();
            };

            var fail = function () {
                console.log("Nu s-au obtinut informatii despre planete in stadiul de initializare harta");
            };
            postCall(url, success, fail);


        },
        setPlanetInfo: function () {
            var url = 'scripts/get-info.php?about=planets_games';
            console.log("Uplating planet info");
            var success = function (data) {
                var planetsJSON = JSON.parse(data.responseText);
                for (var i in planetsJSON) {
                    ABURISK.players.index(planetsJSON.owner_id);
                    var shipNumber = createShipNo(planetsJSON[i]);
                    if (shipNumber != false)
                        svgDocument.appendChild(shipNumber);
                }
            };

            var fail = function () {
                console.log("Nu s-au obtinut informatii despre planete in stadiul de afisare a informatiilor despre planete");
            };

            postCall(url, success, fail);
        },
        drawPlanets: function (planets) {
            var i, galaxies = {};

            for (i = 0; i < planets.length; i += 1) {
                var atmosphere = createAtmosphere(planets[i]);
                var planet = createPlanet(planets[i]);
                svgDocument.insertBefore(atmosphere, atmospheresDelimiter);
                svgDocument.appendChild(planet, planetsDelimiter);
            }

            return galaxies;
        },

        drawConnections: function (connections, planets) {

            for (var i = 0; i < connections.length; i++) {
                var firstPlanet = getPlanetById(planets, connections[i].first_planet_id);
                var secondPlanet = getPlanetById(planets, connections[i].second_planet_id);
                var connection = createRoute(firstPlanet, secondPlanet);

                svgDocument.insertBefore(connection, routesDelimiter);
            }
        },

        drawGalaxies: function (planets) {
            var galaxies = {};
            for (i = 0; i < planets.length; i += 1) {
                if (galaxies[planets[i].containing_galaxy_id] == undefined)
                    galaxies[planets[i].containing_galaxy_id] = [];
                var x_pos = planets[i].x_pos + planets[i].radius;
                var y_pos = planets[i].y_pos + planets[i].radius;
                galaxies[planets[i].containing_galaxy_id].push({x_pos: x_pos, y_pos: y_pos});
            }


            galaxies = sortPlanetsCounterClockWise(galaxies);
            for (var i in galaxies) {
                if (galaxies.hasOwnProperty(i)) {
                    var polygon = createPolygon(galaxies[i], i);
                    svgDocument.insertBefore(polygon, galaxiesDelimiter);
                }
            }
        }
    }

}();