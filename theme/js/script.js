function init() {

    var formContainer = document.getElementById('formContainer');
    var containers = document.getElementsByClassName('flipLink');
    for (var i = 0 ; i < containers.length; i++) {
        containers[i].addEventListener('click', function (e) {

            if (formContainer.classList.contains('flipped')) {
                formContainer.classList.remove('flipped');
            } else {
                formContainer.classList.add('flipped');
            }
        }, true);

    }

}