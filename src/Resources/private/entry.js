window.$ = $;

require('./scss/style.scss');

import 'bootstrap/dist/js/bootstrap';
import 'eonasdan-bootstrap-datetimepicker';
require('moment');
require('pc-bootstrap4-datetimepicker/build/js/bootstrap-datetimepicker.min');
require('lightbox2');
require('select2');
require('./js/main');
require('cropper/dist/cropper.min');
require('./js/collection-type');
import './js/modules-links';

if(typeof IS_SORTABLE_ENABLED !== "undefined" && IS_SORTABLE_ENABLED ){
    import(/*webpackChunkName: "sortable-module"*/'./js/sortable');
}

if(typeof IS_TRANSLATIONS_PAGE !== "undefined" && IS_TRANSLATIONS_PAGE){
    import(/*webpackChunkName: "translations-page-module"*/'./js/translationsPage');
}
