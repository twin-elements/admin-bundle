const loaderON = function () {
    'use strict';
    $('#loader-container').addClass('active');
}

const loaderOFF = function () {
    'use strict';
    $('#loader-container').removeClass('active');
}

export {loaderON, loaderOFF};
