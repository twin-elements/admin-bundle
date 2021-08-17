import {loaderON, loaderOFF} from './utils'

const Menu = {
    module: function () {
        return '#app_adminbundle_menu_module'
    },
    getModule: function () {
        return $(this.module());
    },
    getModuleId: function () {
        return this.getModule().val();
    },
    isModuleChecked: function () {
        if ($.isNumeric(this.getModuleId())) {
            return true;
        }

        return false;
    },
    objectClass: function () {
        return '#app_adminbundle_menu_objectClass';
    },
    getObjectClass: function () {
        return $(this.objectClass());
    },
    foreignKey: function () {
        return '#app_adminbundle_menu_foreignKey';
    },
    getForeignKey: function () {
        return $(this.foreignKey());
    },
    getForeignKeyValue: function () {
        return this.getForeignKey().val()
    },
    hasForeignKey: function () {
        if ($.isNumeric(this.getForeignKey().val())) {
            return true;
        }

        return false;
    },
    itemList: function () {
        return '#itemList';
    },
    cleanRoute: function () {
        return $('#app_adminbundle_menu_route').val('');
    },
    compileFetchData: function () {
        let data = new FormData;

        if (this.isModuleChecked()) {
            data.append('moduleId', this.getModuleId());
        }

        if (this.hasForeignKey()) {
            data.append('foreignKey', this.getForeignKeyValue());
        }

        return data;
    }
}

$(document).ready(function () {
    if (Menu.isModuleChecked() && Menu.hasForeignKey()) {
        loaderON();

        fetch(Routing.generate('menu_get_module_items'), {
            method: 'post',
            body: Menu.compileFetchData(),
            credentials: 'same-origin'
        }).then(function (response) {
            if (!response.ok) {
                throw new Error(response.statusText);
            }

            return response.json();
        }).then(function (json) {
            $(json.list).insertAfter(Menu.getModule());
            loaderOFF();
        })
    }

    var moduleSelect = $("#app_adminbundle_menu_module");
    moduleSelect.on('change', function () {
        // var moduleId = $(this).val();
        //
        // var data = new FormData;
        // data.append('moduleId', moduleId);

        fetch(Routing.generate('menu_get_module_items'), {
            method: 'post',
            body: Menu.compileFetchData(),
            credentials: 'same-origin'
        }).then(function (response) {
            if (!response.ok) {
                throw new Error(response.statusText);
            }

            return response.json();

        }).then(function (json) {
            if ($('#itemList').length) {
                $('#itemList').replaceWith(json.list)
            } else {
                $(json.list).insertAfter(moduleSelect);
            }
            Menu.getObjectClass().val(json.objectClass);

        }).catch(function (error) {

            alert(error);

        })

    })

    $(document).on('change', Menu.itemList(), function () {
        var foreignKey = $(this).val();
        Menu.getForeignKey().val(foreignKey);
        Menu.cleanRoute();
    })
})