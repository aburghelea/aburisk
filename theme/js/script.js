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
        element = element.parentNode;
        if (element.tagName.toLowerCase() == "form") {
            element.submit();
            return element;
        }
    }
    return 0;
}

var mapContainer, svgRoot, svgDocument;

function initMap(planets, connections) {
    mapContainer = document.getElementById("map");
    svgRoot = mapContainer.contentDocument;
    svgDocument = svgRoot.documentElement;
    var galaxies = drawPlanetsAndComputeGalaxyes(planets);
    drawGalaxies(galaxies);
    drawConnections(connections, planets);

}

function drawPlanetsAndComputeGalaxyes(planets) {
    var i, galaxies = {};
    const atmospheresDelimiter = svgDocument.getElementById('atmospheres');
    const planetsDelimiter = svgDocument.getElementById('planets');

    for (i = 0; i < planets.length; i += 1) {
        var atmosphere = createCircle(planets[i]);
        var planet = createPlanet(planets[i]);
        svgDocument.insertBefore(atmosphere, atmospheresDelimiter);
        svgDocument.appendChild(planet, planetsDelimiter);
        planet.addEventListener("mouseover", enlargeCircle, false);
        planet.addEventListener("mouseout", shrinkCircle, false);

        if (galaxies[planets[i].containing_galaxy_id] == undefined)
            galaxies[planets[i].containing_galaxy_id] = [];
        var x_pos = planets[i].x_pos + planets[i].radius;
        var y_pos = planets[i].y_pos + planets[i].radius;
        galaxies[planets[i].containing_galaxy_id].push({x_pos: x_pos, y_pos: y_pos});
    }

    return galaxies;
}

function drawConnections(connections, planets) {
    const routesDelimiter = svgRoot.getElementById("routes");

    for (var i in connections) {
        var firstPlanet = getPlanetById(planets, connections[i].first_planet_id);
        var secondPlanet = getPlanetById(planets, connections[i].second_planet_id);
        var connection = createRoute(firstPlanet, secondPlanet);

        svgDocument.insertBefore(connection, routesDelimiter);
    }
}

function drawGalaxies(galaxies) {
    const galaxiesDelimiter = svgRoot.getElementById("galaxies");

    galaxies = sortGalaxyPoints(galaxies);
    for (var i in galaxies) {
        var polygon = createPolygon(galaxies[i], i);
        svgDocument.insertBefore(polygon, galaxiesDelimiter);
    }
}

function enlargeCircle(e) {
    id = 'circle_' + e.target.getAttribute('id');
    var circle = svgDocument.getElementById(id);
    var radius = circle.getAttribute('r');
    circle.setAttribute('r', Number(radius) + 5);
}

function shrinkCircle(e) {
    id = 'circle_' + e.target.getAttribute('id');
    var circle = svgDocument.getElementById(id);
    var radius = circle.getAttribute('r');
    circle.setAttribute('r', Number(radius) - 5);
}

function createCircle(planetJSON) {
    var circle = document.createElementNS("http://www.w3.org/2000/svg", "circle");
    circle.setAttribute('class', "galaxy_" + planetJSON.containing_galaxy_id);
    circle.setAttribute('cx', planetJSON.x_pos + planetJSON.radius);
    circle.setAttribute('cy', planetJSON.y_pos + +planetJSON.radius);
    circle.setAttribute('r', planetJSON.radius * 1.2);
    circle.setAttribute('id', 'circle_' + planetJSON.id);

    return circle;
}

function createPlanet(planetJSON) {
    var planet = document.createElementNS("http://www.w3.org/2000/svg", "image");
    planet.setAttribute('class', "planet");
    planet.setAttribute('x', planetJSON.x_pos);
    planet.setAttribute('y', planetJSON.y_pos);
    planet.setAttribute('width', planetJSON.radius * 2);
    planet.setAttribute('height', planetJSON.radius * 2);
    planet.setAttribute('id', planetJSON.id);
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
    polygon.setAttribute("x1", first_planet.x_pos + first_planet.radius);
    polygon.setAttribute("y1", first_planet.y_pos + first_planet.radius);
    polygon.setAttribute("x2", second_planet.x_pos + second_planet.radius);
    polygon.setAttribute("y2", second_planet.y_pos + second_planet.radius);
    polygon.setAttribute("class", "connection");

    return polygon;
}

function pointComparator(center) {
    var _center = center;
    return function (a, b) {
        var angle_a = getAngle(a, _center),
            angle_b = getAngle(b, _center);
        return angle_a < angle_b;
    }
}

function sortGalaxyPoints(galaxies) {
    var i;
    for (i in galaxies) {

        var center = getCenter(galaxies[i]);
        galaxies[i].sort(pointComparator(center));
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
    var i;
    for (i = 0; i < planets.length; i++) {
        if (id == planets[i].id)
            return planets[i];
    }

    return null;
}