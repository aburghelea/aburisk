function init() {

    var formContainer = document.getElementById('formContainer');
    var containers = document.getElementsByClassName('flipLink');
    for (var i = 0; i < containers.length; i++) {
        containers[i].addEventListener('click', function () {

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


function prepareParams(json) {
    var dataArray = [];
    for (var i in json) {
        var encodedData = encodeURIComponent(i);
        encodedData += "=";
        encodedData += encodeURIComponent(json[i]);
        dataArray.push(encodedData);
    }
    return dataArray.join("&");
}

function postCall(url, success, fail, params) {
    var xhr = new XMLHttpRequest;

    xhr.open('POST', url, true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    if (params !== undefined) {
        console.log(prepareParams(params));
        xhr.send(prepareParams(params));
    }
    else
        xhr.send("");
    xhr.onreadystatechange = function () {
        if (xhr.readyState === 4) {
            if (xhr.status === 200) {
                success(xhr);
            } else {
                fail()
            }
        } else {
            //still loading
        }
    };
}

ABURISK = {};

