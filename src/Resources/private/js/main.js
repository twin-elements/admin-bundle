const FileManagerModal = require("twin-elements/admin/src/templates/te-upload-type-modal-window.html.twig");
import ClipboardJS from "clipboard";

$(".file-manager-delete-button").on("click", function () {
    const _inputId = $(this).attr("id").split("-").reverse().slice(2).reverse().join("-");
    const _$input = $(`#${_inputId}`);
    _$input.attr("value", null).val("").trigger("change");
    _$input.trigger("change");
});

$(document).ready(function () {
    const clipboardBtns = new ClipboardJS(".filemanager-input-copy");

    $("body").on("change", "input.filemanager-input", function () {
        console.log("fff");
        const _$self = $(this);
        const _id = _$self.attr("id");
        const _$deleteButton = $(`#${_id}-delete-button`);
        const _$copyButton = $(`#${_id}-copy-button`);
        const _$thumbnail = $(`#${_id}-thumbnail`);
        const _fileType = _$self.attr("data-file-type");
        console.log(_fileType);
        if (_$self.val()) {
            _$deleteButton.removeAttr("disabled");
            if (_fileType === "image") {
                if (_$copyButton.length) {
                    _$copyButton.replaceWith(
                        $("<a>", {
                            href: _$self.val(),
                            "data-lightbox": _id,
                            class: "teuploadtype-thumbnail",
                        }).css("background-image", `url('${_$self.val()}')`)
                    );
                    return false;
                }
                _$thumbnail
                    .attr("href", _$self.val())
                    .css("background-image", `url('${_$self.val()}')`);
            }
            _$deleteButton.removeAttr("disabled");
            _$copyButton.removeAttr("disabled");
            return false;
        }
        _$deleteButton.attr("disabled", true);
        if (_$thumbnail.length) {
            _$thumbnail.replaceWith(
                $("<button>", {
                    type: "button",
                    id: `${_id}-copy-button`,
                    "data-clipboard-target": `#${_id}`,
                    class: "btn btn-outline-secondary filemanager-input-copy",
                    disabled: true,
                }).append('<i class="jcon jcon-sheet"></i>')
            );
            return false;
        }
        _$copyButton.attr("disabled", true);
        return false;
    });

    $(".filemanager-input").on("keydown paste", function (event) {
        event.preventDefault();
    });

    $('[data-toggle="datetimepicker"]').datetimepicker({
        locale: "pl",
        icons: {
            time: "jcon jcon-clock",
            date: "jcon jcon-calendar",
            up: "jcon jcon-up",
            down: "jcon jcon-down",
            previous: "jcon jcon-left",
            next: "jcon jcon-right",
            today: "jcon jcon-speedometer",
            clear: "jcon jcon-refresh",
            close: "jcon fa-close",
        },
    });

    $("form").submit(function (e) {
        if (
            $(this).find('input[name="_method"]').length &&
            $(this).find('input[name="_method"]').val() == "DELETE"
        ) {
            if (!confirm("Potwierdź akcję kasowania")) {
                e.preventDefault();
            }
        }
    });

    $(".select2").select2({
        theme: "bootstrap4",
    });

    $("body").on("click", ".open-file-manager-btn", function () {
        const _self = this;
        const _$self = $(_self);
        const inputId = _$self.attr("data-input-id");
        const _$parentGroup = _$self.parents(".input-group").first();
        let _$modalInstance = _$parentGroup.children(".modal").first();
        if (!_$modalInstance.length) {
            _$modalInstance = $(
                FileManagerModal({
                    input_id: inputId,
                    iframe_src: _$self.attr("data-iframe-src"),
                    modal_title: _$self.attr("data-modal-title"),
                })
            );
            _$parentGroup.append(_$modalInstance);
        }
        _$modalInstance.modal();
        _$modalInstance.find("iframe").on("load", function () {
            const _iframe = this;
            const _$iframe = $(_iframe);
            _$iframe.contents().on("click", ".select", function () {
                const dataPath = $(this).attr("data-path");
                _$parentGroup.find(".filemanager-input").val(dataPath).trigger("change");
                _$modalInstance.modal("hide");
            });
        });
    });
    clipboardBtns.on("success", function (e) {
        const $trigger = $(e.trigger);
        $trigger.tooltip({ placement: "bottom", trigger: "manual", title: "Skopiowano" });
        $trigger.tooltip("show");
        setTimeout(() => {
            $trigger.tooltip("dispose");
        }, 2000);
        e.clearSelection();
    });
});
