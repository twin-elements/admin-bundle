import {ChooseLinkModal} from './ChooseLinkModal'
import getTinymceSimpleConfig from "./tinymce-simple-config";
require('symfony-collection/jquery.collection');

$.each($('.core-admin-bundle-tecollection-type'), (collectionIndex, collection) => {
    const _$collection = $(collection);
    const minCount = _$collection.attr('data-min-count');
    const maxCount = _$collection.attr('data-max-count');
    const allowChangePosition = +_$collection.attr('data-allow-change-position') !== 0;
    _$collection.collection({
        up: '<button type="button" class="collection-up btn btn-outline-secondary" title="Do góry"><span class="jcon jcon-arrow-up"></span></button>',
        down: '<button type="button" class="collection-down btn btn-outline-secondary" title="Na dół"><span class="jcon jcon-arrow-down"></span></button>',
        add: '<button type="button" class="collection-add btn btn-outline-secondary" title="Dodaj"><span class="jcon jcon-add"></span></button>',
        remove: '<button type="button" class="collection-remove btn btn-danger" title="Usuń"><span class="jcon jcon-trash"></span></button>',
        init_with_n_elements: Number(minCount),
        min: minCount,
        max: maxCount,
        drag_drop: true,
        allow_up: allowChangePosition,
        allow_down: allowChangePosition,
        before_add: (collection, element) => {
            if(collection.find('.tinymce-simple').length) {
                const tinymceTextareas = Array.from(collection[0].querySelectorAll('.tinymce-simple'));
                tinymceTextareas.forEach((textarea) => {
                    tinymce.remove(`.tinymce-simple`);
                });
            }
        },
        after_add: (collection, element) => {
            element.children('.collapse').collapse('show');
            if (element.find('.select2').length) {
                element.find('.select2').select2({theme: 'bootstrap4'});
            }
            if (element.find('.choose-link-modal').length) {
                const modals = Array.from(element[0].querySelectorAll('.choose-link-modal'));
                modals.map((modal) => new ChooseLinkModal(modal.id));
            }
            if(collection.find('.tinymce-simple').length) {
                const tinymceTextareas = Array.from(collection[0].querySelectorAll('.tinymce-simple'));
                tinymceTextareas.forEach((textarea) => {
                    tinymce.init(getTinymceSimpleConfig(`#${textarea.id}`));
                });
            }
        },
        elements_selector: '.collection-child-actions-container',
    })

});

const disbaleCollectionRow = function (event) {
    if(event.target.parentElement.classList.contains('disable-collection-element') || event.target.classList.contains('disable-collection-element')){
        const clickedBtn = event.target.nodeName === 'BUTTON' ? event.target : event.target.parentElement;
        const clickedIcon = event.target.nodeName === 'BUTTON' ? event.firstChild : event.target;
        const inputId = clickedBtn.dataset.inputId;
        const input = document.getElementById(inputId);
        const entityInputs = Array.from(clickedBtn.previousElementSibling.children).map((col) => {return col.querySelector('input');});
        clickedBtn.previousElementSibling.classList.toggle('disabled', !!+input.value);
        entityInputs.forEach((rowInput) => rowInput.required = !+input.value);
        input.value = !+input.value ? 1 : 0;
        if(!!+input.value){
            clickedIcon.classList.replace('jcon-switch-off', 'jcon-switch-on');
            clickedBtn.classList.replace('text-danger', 'text-success');
        } else {
            clickedIcon.classList.replace('jcon-switch-on', 'jcon-switch-off');
            clickedBtn.classList.replace('text-success', 'text-danger');
        }
    }
};

const disbaleCollectionRowBtns = Array.from(document.querySelectorAll('.te-collection-type-container'));
disbaleCollectionRowBtns.forEach((btn) => btn.addEventListener('click', disbaleCollectionRow));

// $('.core-admin-bundle-tecollection-type').collection({
//     up: '<button type="button" class="collection-up btn btn-outline-secondary" title="Do góry"><span class="jcon jcon-arrow-up"></span></button>',
//     down: '<button type="button" class="collection-down btn btn-outline-secondary" title="Na dół"><span class="jcon jcon-arrow-down"></span></button>',
//     add: '<button type="button" class="collection-add btn btn-outline-secondary" title="Dodaj"><span class="jcon jcon-add"></span></button>',
//     remove: '<button type="button" class="collection-remove btn btn-danger" title="Usuń"><span class="jcon jcon-trash"></span></button>',
//     init_with_n_elements: 1,
//     // init_with_n_elements: +($('.core-admin-bundle-tecollection-type').attr('data-children-count')),
//     drag_drop: true,
//     after_add: (collection, element) => {
//         element.children('.collapse').collapse('show');
//     },
//     elements_selector: '.collection-child-actions-container',
//
// });