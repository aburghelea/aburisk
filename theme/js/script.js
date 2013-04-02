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


function initMap(planets) {
    var map = document.getElementById("map");
    var i;
    var svgroot = map.contentDocument;
    var svgDoc = map.contentDocument.documentElement;
    var galaxies = {};
    for (i = 0; i < planets.length; i++) {
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
        var el = svgroot.getElementById("galaxies");
        console.log(svgDoc);
        svgDoc.insertBefore(polygon, el);
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
        points += galaxy[i].x_pos+","+galaxy[i].y_pos+" ";
    }
    var polygon = document.createElementNS("http://www.w3.org/2000/svg", "polygon");;
    polygon.setAttribute("points", points);
    polygon.setAttribute("class", "galaxy_"+cn);

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

/**
 * Function : dump()
 * Arguments: The data - array,hash(associative array),object
 *    The level - OPTIONAL
 * Returns  : The textual representation of the array.
 * This function was inspired by the print_r function of PHP.
 * This will accept some data as the argument and return a
 * text that will be a more readable version of the
 * array/hash/object that is given.
 * Docs: http://www.openjs.com/scripts/others/dump_function_php_print_r.php
 */
function var_dump(arr, level) {
    var dumped_text = "";
    if (!level) level = 0;

    //The padding given at the beginning of the line.
    var level_padding = "";
    for (var j = 0; j < level + 1; j++) level_padding += "    ";

    if (typeof(arr) == 'object') { //Array/Hashes/Objects
        for (var item in arr) {
            var value = arr[item];

            if (typeof(value) == 'object') { //If it is an array,
                dumped_text += level_padding + "'" + item + "' ...\n";
                dumped_text += var_dump(value, level + 1);
            } else {
                dumped_text += level_padding + "'" + item + "' => \"" + value + "\"\n";
            }
        }
    } else { //Stings/Chars/Numbers etc.
        dumped_text = "===>" + arr + "<===(" + typeof(arr) + ")";
    }
    return dumped_text;
}