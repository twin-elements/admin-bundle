import * as monaco from 'monaco-editor/esm/vs/editor/editor.api';
import 'bootstrap/js/src/modal';
import editorModal from './code-editor-modal.html.twig';

(function () {
    var code = (function () {
        'use strict';

        var global = tinymce.util.Tools.resolve('tinymce.PluginManager');

        var global$1 = tinymce.util.Tools.resolve('tinymce.dom.DOMUtils');

        var getMinWidth = function (editor) {
            return editor.getParam('code_dialog_width', 600);
        };
        var getMinHeight = function (editor) {
            return editor.getParam('code_dialog_height', Math.min(global$1.DOM.getViewPort().h - 200, 500));
        };
        var Settings = {
            getMinWidth: getMinWidth,
            getMinHeight: getMinHeight
        };

        var setContent = function (editor, html) {
            editor.focus();
            editor.undoManager.transact(function () {
                editor.setContent(html);
            });
            editor.selection.setCursorLocation();
            editor.nodeChanged();
        };
        var getContent = function (editor) {
            return editor.getContent({ source_view: true });
        };
        var Content = {
            setContent: setContent,
            getContent: getContent
        };

        var open = function (editor) {
            const modalContainer = document.createElement('div');
            modalContainer.innerHTML = editorModal();
            document.body.appendChild(modalContainer);
            $('#editor-modal').modal('show');
            const codeEditor = monaco.editor.create(modalContainer.querySelector('#editor'), {
                value: Content.getContent(editor),
                language: 'html',
                theme: 'vs-dark',
                fontFamily: 'Source Code Pro',
                fontWeight: '200',
                minimap: {
                    enabled: false
                },
                wordWrap: 'wordWrapColumn',
                wordWrapColumn: 120,
                autoClosingOvertype: "always",
                formatOnType: true,
                formatOnPaste: true,
                matchBrackets: false,
            });

            setTimeout(() => {
                codeEditor.trigger('dfdfd', 'editor.action.formatDocument');
            }, 300)
            const saveButton = modalContainer.querySelector('#code-editor-save');
            saveButton.addEventListener('click', () => {
                setContent(editor, codeEditor.getValue());
                $('#editor-modal').modal('hide');
            });
            $('#editor-modal').on('hidden.bs.modal', () => {
                const modalParent = document.getElementById('editor-modal').parentNode;
                document.body.removeChild(modalParent);
                codeEditor.dispose();
            })

        };
        var Dialog = { open: open };

        var register = function (editor) {
            editor.addCommand('mceCodeEditor', function () {
                Dialog.open(editor);
            });
        };
        var Commands = { register: register };

        var register$1 = function (editor) {
            editor.ui.registry.addButton('code', {
                icon: 'code',
                tooltip: 'Source code',
                onAction: function () {
                    Dialog.open(editor);
                }
            });
            editor.ui.registry.addMenuItem('code', {
                icon: 'code',
                text: 'Source code',
                onAction: function () {
                    Dialog.open(editor);
                }
            });
        };
        var Buttons = { register: register$1 };

        global.add('code', function (editor) {
            Commands.register(editor);
            Buttons.register(editor);
            return {};
        });
        function Plugin () {
        }

        return Plugin;

    }());
})();

