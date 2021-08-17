let TinymceSimpleOptions = {
  selector: 'textarea.tinymce-simple',
  skin: false,
  // theme: "modern",
  browser_spellcheck: true,
  fontsize_formats: '8px 10px 12px 13px 14px 16px 18px 20px 24px 28px 32px 36px 40px 46px 52px 60px 68px 78px',
  relative_urls: false,
  height: 250,
  min_width: 320,
  resize: 'both',
  forced_root_block: false,
  schema: "html5",
  language: "pl",
  remove_script_host: true,
  convert_urls: false,
  valid_children: '+body[link],+body[style]',
  invalid_styles: { 'table': 'width height', 'tr' : 'width height', 'th' : 'width height', 'td' : 'width height' },
  verify_html: true,
  menubar: false,
  plugins: [
    "lists link",
    "visualblocks visualchars code",
    "nonbreaking",
    "paste table"
  ],
  visualblocks_default_state: true,
  toolbar1: "link | bold italic underline | fontsizeselect | alignleft aligncenter alignright alignjustify | bullist numlist | backcolor styleselect | table | code",
  style_formats_merge: true,
  style_formats: [
    {title: 'Regularna grubość czcionki', inline: 'b', wrapper: true},
    {
      title: 'Kolory czcionek', items: [
        {title: 'Kolor 1', inline: 'span', classes: 'text-primary', wrapper: true},
        {title: 'Kolor 2', inline: 'span', classes: 'text-warning', wrapper: true},
        {title: 'Kolor 3', inline: 'span', classes: 'text-info', wrapper: true},
        {title: 'Kolor 4', inline: 'span', classes: 'text-success', wrapper: true},
        {title: 'Kolor 5', inline: 'span', classes: 'text-danger', wrapper: true},
        {title: 'Domyślny kolor', inline: 'span', classes: 'text-body', wrapper: true},
      ]
    }
  ],
  body_id: 'tinymcebody',
  body_class: 'tinymce',
  content_css: TINYMCE_CONTENT_CSS,
  setup: function (editor) {
    editor.shortcuts.add('meta+m', 'superscript', 'superscript');
  }
}

const getTinymceSimpleConfig = (selector = 'textarea.tinymce-simple') => {
  TinymceSimpleOptions.selector = selector;
  return TinymceSimpleOptions;
}

export default getTinymceSimpleConfig;
