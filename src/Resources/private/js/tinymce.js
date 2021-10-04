import tinymce from 'tinymce';
import 'tinymce/icons/default';
import 'tinymce/themes/silver';

// Plugins
import 'tinymce/plugins/paste';
import 'tinymce/plugins/link';
import 'tinymce/plugins/autoresize';
import 'tinymce/plugins/advlist';
import 'tinymce/plugins/autolink';
import 'tinymce/plugins/lists';
import 'tinymce/plugins/image';
import 'tinymce/plugins/imagetools';
import 'tinymce/plugins/charmap';
import 'tinymce/plugins/hr';
import 'tinymce/plugins/anchor';
import 'tinymce/plugins/pagebreak';
import 'tinymce/plugins/searchreplace';
import 'tinymce/plugins/wordcount';
import 'tinymce/plugins/visualblocks';
import 'tinymce/plugins/visualchars';
import 'tinymce/plugins/insertdatetime';
import 'tinymce/plugins/media';
import 'tinymce/plugins/nonbreaking';
import 'tinymce/plugins/save';
import 'tinymce/plugins/table';
import 'tinymce/plugins/contextmenu';
import 'tinymce/plugins/directionality';
import 'tinymce/plugins/paste';
import 'tinymce/plugins/template';

import './tinymce-langs/pl';
import getTinymceSimpleConfig from "./tinymce-simple-config";

import './tinymce-extended-code-editor-plugin.js';

function TEFileBrowser(callback, value, meta) {

    var type = meta.filetype;
    var cmsURL = Routing.generate('file_manager', {module: 'tiny', conf: 'button'});
    console.log(cmsURL);
    if (cmsURL.indexOf("?") < 0) {
        cmsURL = cmsURL + "?type=" + type;
    } else {
        cmsURL = cmsURL + "&type=" + type;
    }

    var windowManagerCSS = '<style type="text/css">' +
        '.tox-dialog {max-width: 100%!important; width:97.5%!important; overflow: hidden; height:95%!important; border-radius:0.25em;}' +
        '.tox-dialog__body { padding: 0!important; }' +
        '.tox-dialog__body-content > div { height: 100%!important; overflow:hidden}' +
        '.tox-form__group { height: 100%!important; overflow:hidden}' +
        '</style > ';

    window.tinymceCallBackURL = '';
    window.tinymceWindowManager = tinymce.activeEditor.windowManager;
    tinymceWindowManager.open({
        title: 'File Manager',
        body: {
            type: 'panel',
            items: [{
                type: 'htmlpanel',
                html: windowManagerCSS + '<iframe src="' + cmsURL + '"  frameborder="0" style="width:100%; height:100%"></iframe>'
            }]
        },
        buttons: [],
        onClose: function () {
            if (tinymceCallBackURL != '')
                callback(tinymceCallBackURL, {}); //to set selected file path
        }
    });
}

if (typeof (base_url) == "undefined") {
    var base_url = location.protocol + '//' + location.host + '/';

    let hostname = window.location.hostname;
    if(hostname.match(/jellinekserwer.pl/g)){
        tbpKey = 'lRCcNZ6M8h8eU8n5n2W1/8bRZAPhZ+h6r8+vTM5PEu4afEETV7yvT5FQHrH6F1KVf4T8R9mTU6qNDhJAieQz0+pWvXSft7qB/WQlckVE/lc=';
    }else{
        if (window.location.hostname === TINYMCE_BOOTSTRAP_HOSTNAME_DEV) {
            var tbpKey = TINYMCE_BOOTSTRAP_KEY_DEV;
        } else if (window.location.hostname === TINYMCE_BOOTSTRAP_HOSTNAME_PROD) {
            var tbpKey = TINYMCE_BOOTSTRAP_KEY_PROD;
        }
    }
}

// Initialize
tinymce.init({
    selector: 'textarea.tinymce',
    relative_urls: false,
    schema: "html5",
    language: "pl",
    convert_urls: false,
    verify_html: true,
    height: 450,
    min_width: 320,
    plugins: [
        "bootstrap advlist autolink lists link image imagetools charmap hr anchor pagebreak",
        "searchreplace wordcount visualblocks visualchars code",
        "insertdatetime media nonbreaking save table directionality",
        "template paste"
    ],
    toolbar: [
        'bootstrap',
        "styleselect | bold italic strikethrough forecolor backcolor | " +
        "link image ImgPen media | alignleft aligncenter alignright alignjustify | " +
        "numlist bullist | outdent indent removeformat | code"
    ],
    contextmenu: "bootstrap",
    file_picker_callback: TEFileBrowser,
    body_id: 'tinymcebody',
    body_class: 'tinymce',
    content_css: TINYMCE_CONTENT_CSS,
    setup: function (editor) {
        editor.shortcuts.add('meta+m', 'superscript', 'superscript');
    },
    style_formats: [
        { title: 'Span', inline: 'span' }
    ],
    browser_spellcheck: true,
    imagetools_toolbar: "rotateleft rotateright | flipv fliph | editimage imageoptions ",
    extended_valid_elements: "iframe[*]",
    visualblocks_default_state: true,
    bootstrapConfig: {
        url: base_url + 'build/admin/plugins/bootstrap/',
        imagesPath: '/uploads',
        bootstrapCss: TINYMCE_CONTENT_CSS,
        key: tbpKey,
        language: 'pl'
    }
});

tinymce.init(getTinymceSimpleConfig());
