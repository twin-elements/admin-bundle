import 'bootstrap/js/dist/tooltip';

export class ChooseLinkModal {

    constructor(modalId) {
        this._modal = document.getElementById(modalId);
        this._$modal = $(this._modal);
        this._$modalLinks = this._$modal.find('a.select-link');
        this._$customHrefInput = this._$modal.find('.custom-href-input');
        this._$hiddenInput = this._$modal.siblings('input');
        this._valueToInput = this._modal.dataset.inputValue;
        this._$acceptBtn = this._$modal.find('.accept-link-btn');
        this._$cancelBtn = this._$modal.find('.cancel-btn');
        this._$clearBtn = this._$modal.siblings().find('.reset-module-link');
        this._$linkPlaceholder = this._$modal.siblings().find('.link-placeholder');
        this._$modalLinks.on('click', (e) => {
            this.chooseModuleLink(e.currentTarget);
        });

        this._$customHrefInput.on('focusout change', () => {
            if(this._$customHrefInput.val()){
                this.clearChosenLink();
                this._valueToInput = this._$customHrefInput.val();
            }
        });

        this._$acceptBtn.on('click', () => {
            this.acceptChanges();
        })

        this._$modal.on('show.bs.modal', () => {
            this.prepareModal();
        });

        this._$modal.on('hide.bs.modal', () => {
            this._$customHrefInput.attr('disabled', true);
        })

        this._$clearBtn.on('click', () => {
            this.clearAll();
        })

    }

    chooseModuleLink(linkRef){

        const _$linkRef = $(linkRef);
        this._$customHrefInput.val('');
        _$linkRef.toggleClass('bg-primary text-white');
        _$linkRef.siblings('.select-link.bg-primary').removeClass('bg-primary text-white');
        _$linkRef.parents('.single-module-container').siblings().find('a.select-link.bg-primary').removeClass('bg-primary text-white');
        const moduleId = _$linkRef.parents('.collapse').attr('id');
        const linkId = linkRef.dataset.linkId;

        const linkObj = {
            moduleId: moduleId,
            linkId: linkId
        };

        this._valueToInput = JSON.stringify(linkObj);
    }

    clearChosenLink(){
        this._$modalLinks.removeClass('bg-primary text-white');
    }

    generateLinkPlaceholder(){

        const moduleDesc = this.parseValueToInput();
        if(typeof moduleDesc === 'object'){
            const chosenLink = this._$modal.find('.select-link.bg-primary');
            const isNotTranslated = !!chosenLink.children('span.no-translation').length;
            const parentModule = $(`#${moduleDesc.moduleId}`).prev().html();
            const arrayModuleDesc = [parentModule, chosenLink.html()];
            this._$linkPlaceholder.html(`<div class="pr-4"><span><strong>${arrayModuleDesc[0]}: </strong>${arrayModuleDesc[1]}</span></div>`);
            if(isNotTranslated){
                this._$linkPlaceholder.children().children().attr('title', chosenLink.attr('title'));
            }
        } else if (typeof moduleDesc === 'string'){
            this._$linkPlaceholder.html(`<div class="pr-4"><strong>Własny odnośnik</strong>: <span style="max-width: 310px;" class="text-truncate d-inline-block align-bottom" title="${moduleDesc}">${moduleDesc}</span></div>`);
        }

    }

    parseValueToInput(){
        try {
            return JSON.parse(this._valueToInput);
        } catch (e) {
            return this._valueToInput;
        }
    }

    acceptChanges(){
        if(this.checkCustomHrefValidity()){
            this._$hiddenInput.val(this._valueToInput);
            this._$modal.modal('hide');
            this._$clearBtn.removeClass('d-none');
            this._modal.dataset.inputValue = this._valueToInput;
            this.generateLinkPlaceholder();
            this.toggleTriggerBtnName();
        }
    }

    prepareModal(){

        const preset = this.parseValueToInput();
        this._$customHrefInput.removeAttr('disabled');
        if(typeof preset === 'object'){

            const module = this._$modal.find(`#${preset.moduleId}`);
            module.collapse('show');
            module.find(`.select-link[data-link-id=${preset.linkId}]`).addClass('bg-primary text-white');
            this._$customHrefInput.val('');

        } else if (typeof preset === 'string'){
            this.clearChosenLink();
            this._$customHrefInput.val(preset);
        }

    }

    clearAll(){
        this._modal.dataset.inputValue = ''
        this._$linkPlaceholder.html('');
        this._valueToInput = '';
        this._$hiddenInput.val('');
        this._$clearBtn.addClass('d-none');
        this.toggleTriggerBtnName();
    }

    toggleTriggerBtnName(){
        const triggerBtn = this._$modal.siblings().find('.choose-link-btn');
        if(this._valueToInput){
            triggerBtn.html('Zmień link');
        } else {
            triggerBtn.html('Wybierz link');
        }
    }

    checkCustomHrefValidity(){
        try {
            new URL(window.location.origin + this._$customHrefInput.val());
            return true;
        } catch (e) {

            if(this._$customHrefInput[0].checkValidity()){
                return true;
            }

            this._$customHrefInput.tooltip({trigger: 'manual', title: 'Wprowadź poprawny link URL' });
            this._$customHrefInput.tooltip('show');

            setTimeout(() => {
                this._$customHrefInput.tooltip('dispose');
            }, 2000);
            return false
        }

    }

}
