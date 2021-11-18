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
import './tinymce-extended-code-editor-plugin.js';
import getTinymceSimpleConfig from "./tinymce-full-config";

getTinymceSimpleConfig().forced_root_block = false;

document.getElementById('textarea-to-tinymce').addEventListener('click', (e) => {
    let textareaInputs = document.querySelectorAll('form textarea');

    textareaInputs.forEach(textareaInput => {
        textareaInput.classList.add('tinymce');
    });
    tinymce.init(getTinymceSimpleConfig());

    e.target.parentElement.remove();
});



