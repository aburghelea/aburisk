function init() {

    var formContainer = document.getElementById('formContainer');
    var containers = document.getElementsByClassName('flipLink');
    for (var i = 0; i < containers.length; i++) {
        containers[i].addEventListener('click', function (e) {

            if (formContainer.classList.contains('flipped')) {
                formContainer.classList.remove('flipped');
            } else {
                formContainer.classList.add('flipped');
            }
        }, true);

    }

}

function submitForm(element) {
    while (element) {
        element = element.parentNode
        if (element.tagName.toLowerCase() == "form") {
            element.submit();
            return element;
        }
    }
    return 0;
}


function initMap(planets, connections) {
        var map = document.getElementById("map");
    var i;
    var svgroot = map.contentDocument;
    var svgDoc = map.contentDocument.documentElement;
    var galaxies = {};
    for (i  in planets) {
        var planet = createPlanet(planets[i]);
        svgDoc.appendChild(planet);
        if (galaxies[planets[i].containing_galaxy_id] == undefined)
            galaxies[planets[i].containing_galaxy_id] = [];
        var x_pos = planets[i].x_pos + planets[i].diameter / 2;
        var y_pos = planets[i].y_pos + planets[i].diameter / 2;
        galaxies[planets[i].containing_galaxy_id].push({x_pos: x_pos, y_pos: y_pos});
    }

    galaxies = sortGalaxyPoints(galaxies);
    for (i in galaxies) {
        var polygon = createPolygon(galaxies[i], i);
        svgDoc.insertBefore(polygon, svgroot.getElementById("galaxies"));
    }

    for (i in connections) {
        var firstPlanet = getPlanetById(planets, connections[i].first_planet_id);
        var secondPlanet = getPlanetById(planets, connections[i].second_planet_id);
        var connection = createRoute(firstPlanet, secondPlanet);

        svgDoc.insertBefore(connection, svgroot.getElementById("routes"));
    }

}

function createPlanet(planetJSON) {
    var planet = document.createElementNS("http://www.w3.org/2000/svg", "image");
    planet.setAttribute('class', "planet");
    planet.setAttribute('x', planetJSON.x_pos);
    planet.setAttribute('y', planetJSON.y_pos);
    planet.setAttribute('width', planetJSON.diameter);
    planet.setAttribute('height', planetJSON.diameter);
    planet.setAttributeNS('http://www.w3.org/1999/xlink', 'xlink:href', "/aburisk/theme/plantes/" + planetJSON.image);

    return planet;
}

function createPolygon(galaxy, cn) {
    var points = "";
    for (var i in galaxy) {
        points += galaxy[i].x_pos + "," + galaxy[i].y_pos + " ";
    }
    var polygon = document.createElementNS("http://www.w3.org/2000/svg", "polygon");
    polygon.setAttribute("points", points);
    polygon.setAttribute("class", "galaxy_" + cn);

    return polygon;
}

function createRoute(first_planet, second_planet) {
    var polygon = document.createElementNS("http://www.w3.org/2000/svg", "line");
    polygon.setAttribute("x1", first_planet.x_pos + first_planet.diameter / 2);
    polygon.setAttribute("y1", first_planet.y_pos + first_planet.diameter / 2);
    polygon.setAttribute("x2", second_planet.x_pos + second_planet.diameter / 2);
    polygon.setAttribute("y2", second_planet.y_pos + second_planet.diameter / 2);
    polygon.setAttribute("class", "connection");

    return polygon;
}
function sortGalaxyPoints(galaxies) {
    var i;
    for (i in galaxies) {


        var center = getCenter(galaxies[i]);
        var comparator = function (a, b) {
            angle_a = getAngle(a, center);
            angle_b = getAngle(b, center);
            return angle_a < angle_b;
        };
        galaxies[i].sort(comparator);
    }
    return galaxies;
}

function getCenter(points) {
    var x_pos = 0, y_pos = 0, radius = Number.MIN_VALUE;
    for (var i = 0; i < points.length; i++) {
        x_pos += points[i].x_pos;
        y_pos += points[i].y_pos;
    }

    x_pos /= points.length;
    y_pos /= points.length;

    return {x_pos: x_pos, y_pos: y_pos};
}

function getAngle(point_a, point_b) {
    var deltaX = point_a.x_pos - point_b.x_pos;
    var deltaY = point_a.y_pos - point_b.y_pos;

    return (Math.atan2(deltaX, deltaY) * 180 / Math.PI + 360) % 360;
}

function getPlanetById(planets, id) {
    for (i in planets) {
        if (id == planets[i].id)
            return planets[i];
    }

    return null;
}