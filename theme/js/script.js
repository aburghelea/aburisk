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
    map = document.getElementById("map");
    var i;
    svgroot = map.contentDocument;
    svgDoc = map.contentDocument.documentElement;
    var galaxies = {};

    for (i  in planets) {
        var circle = createCircle(planets[i]);
        svgDoc.appendChild(circle);
        var planet = createPlanet(planets[i]);
        svgDoc.appendChild(planet);
        planet.addEventListener("mouseover", enlargeCircle, false);
        planet.addEventListener("mouseout", shrinkCircle, false);

        if (galaxies[planets[i].containing_galaxy_id] == undefined)
            galaxies[planets[i].containing_galaxy_id] = [];
        var x_pos = planets[i].x_pos + planets[i].diameter / 2;
        var y_pos = planets[i].y_pos + planets[i].diameter / 2;
        galaxies[planets[i].containing_galaxy_id].push({x_pos: x_pos, y_pos: y_pos});
    }

    drawGalaxies(galaxies, svgDoc, svgroot);
    drawConnections(connections, planets, svgDoc, svgroot);

}

function drawConnections(connections, planets, svgDoc, svgroot) {
    for (var i in connections) {
        var firstPlanet = getPlanetById(planets, connections[i].first_planet_id);
        var secondPlanet = getPlanetById(planets, connections[i].second_planet_id);
        var connection = createRoute(firstPlanet, secondPlanet);

        svgDoc.insertBefore(connection, svgroot.getElementById("routes"));
    }
}
function drawGalaxies(galaxies, svgDoc, svgroot) {
    galaxies = sortGalaxyPoints(galaxies);
    for (var i in galaxies) {
        var polygon = createPolygon(galaxies[i], i);
        svgDoc.insertBefore(polygon, svgroot.getElementById("galaxies"));
    }
}

function enlargeCircle(e) {
    var planet = e.target;
    circleId = 'circle_' + planet.getAttribute('id');
    var circle = svgDoc.getElementById(circleId);
    console.log(circle);
    var initialRadius = circle.getAttribute('r');
//    <animate attributeName="r" from="0" to="100" dur="3s"/>
    var animate = document.createElementNS("http://www.w3.org/2000/svg", "animate");
    animate.setAttribute('attributeName', 'r');
    animate.setAttribute('dur', '3s');
    animate.setAttribute('fill', 'freeze');
    animate.setAttribute('from', initialRadius);
    animate.setAttribute('to', initialRadius * 1.2);
    circle.appendChild(animate);
    console.log(animate);
}

function shrinkCircle(e) {

}


function createCircle(planetJSON){
    var circle = document.createElementNS("http://www.w3.org/2000/svg", "circle");
    circle.setAttribute('class', "galaxy_"+planetJSON.containing_galaxy_id);
    circle.setAttribute('cx', planetJSON.x_pos + planetJSON.diameter/2);
    circle.setAttribute('cy', planetJSON.y_pos +  + planetJSON.diameter/2);
    circle.setAttribute('r', planetJSON.diameter * 1.2 / 2 );
    circle.setAttribute('id', 'circle_'+planetJSON.id );

    return circle;
}
function createPlanet(planetJSON) {
    var planet = document.createElementNS("http://www.w3.org/2000/svg", "image");
    planet.setAttribute('class', "planet");
    planet.setAttribute('x', planetJSON.x_pos);
    planet.setAttribute('y', planetJSON.y_pos);
    planet.setAttribute('width', planetJSON.diameter);
    planet.setAttribute('height', planetJSON.diameter);
    planet.setAttribute('id', planetJSON.id );
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