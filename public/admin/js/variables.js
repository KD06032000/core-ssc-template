var optionTinyMCE = {
    height: "250",
    selector: "textarea.tinymce",
    setup: function (ed) {
        ed.on('DblClick', function (e) {
            if (e.target.nodeName === 'IMG') {
                tinyMCE.activeEditor.execCommand('mceImage');
            }
        });
    },
    plugins: [
        "advlist autolink autosave link image lists charmap print preview hr anchor pagebreak spellchecker template",
        "searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking",
        "table contextmenu directionality emoticons template textcolor paste textcolor colorpicker textpattern moxiemanager link image",
    ],

    toolbar1: "newdocument | bold italic underline strikethrough | alignleft aligncenter alignright alignjustify | styleselect formatselect fontselect fontsizeselect template",
    toolbar2: "cut copy paste | searchreplace | bullist numlist | outdent indent blockquote | undo redo | link unlink anchor image media code | insertdatetime preview | forecolor backcolor",
    toolbar3: "table | hr removeformat | subscript superscript | charmap emoticons | print fullscreen | ltr rtl | spellchecker | visualchars visualblocks nonbreaking template pagebreak restoredraft insertfile link image",

    templates: [
        {
            title: 'Textbox',
            description: 'Tạo Textbox',
            url: base_url + 'public/admin/plugins/tinymce/templates/text-box.html'
        }
    ],

    menubar: false,
    element_format: 'html',
    extended_valid_elements: "iframe[src|width|height|name|align], embed[width|height|name|flashvars|src|bgcolor|align|play|loop|quality|allowscriptaccess|type|pluginspage]",
    toolbar_items_size: 'small',
    relative_urls: false,
    remove_script_host: false,
    convert_urls: false,
    verify_html: false,
    style_formats: [
        {title: 'Bold text', inline: 'b'},
        {title: 'Red text', inline: 'span', styles: {color: '#ff0000'}},
        {title: 'Red header', block: 'h1', styles: {color: '#ff0000'}},
        {title: 'Example 1', inline: 'span', classes: 'example1'},
        {title: 'Example 2', inline: 'span', classes: 'example2'},
        {title: 'Table styles'},
        {title: 'Table row 1', selector: 'tr', classes: 'tablerow1'}
    ],

    external_plugins: {
        "moxiemanager": base_url + "/public/admin/plugins/moxiemanager/plugin.min.js"
    }
};

var optionTinyMCEMore = {
    height: "150",
    selector: "textarea.tinymce-more",
    setup: function (ed) {
        ed.on('DblClick', function (e) {
            if (e.target.nodeName === 'IMG') {
                tinyMCE.activeEditor.execCommand('mceImage');
            }
        });
    },
    plugins: [
        "advlist autolink autosave link image lists charmap print preview hr anchor pagebreak spellchecker",
        "  visualblocks visualchars code fullscreen  nonbreaking",
        "table contextmenu directionality emoticons textcolor paste textcolor textpattern link code",
    ],

    toolbar1: "newdocument | bold italic underline strikethrough | alignleft aligncenter alignright alignjustify | code styleselect formatselect fontselect fontsizeselect cut copy paste  | bullist numlist | outdent indent blockquote  | table | hr removeformat | subscript superscript | charmap emoticons |  fullscreen | ltr rtl | spellchecker | link",
    toolbar2: false,
    menubar: false,
    branding: false,
    statusbar: false,
    element_format: 'html',
    extended_valid_elements: "iframe[src|width|height|name|align], embed[width|height|name|flashvars|src|align|play|loop|quality|allowscriptaccess|type|pluginspage]",
    toolbar_items_size: 'small',
    relative_urls: false,
    remove_script_host: false,
    convert_urls: true,
    verify_html: false,
    external_plugins: {
        "moxiemanager": base_url + "/public/admin/plugins/moxiemanager/plugin.min.js"
    }
};

function optionDateRangePickerCustom(timePicker = false) {
    let curYear = new Date().getFullYear();
    return {
        timePicker: timePicker,
        startDate: '01/01/2020',
        endDate: moment(curYear + '-12-01').endOf('month'),
        ranges: {
            'Tất cả': ['01/01/2020', moment(curYear + '-12-01').endOf('month')],
            'Hôm nay': [moment(), moment()],
            'Hôm qua': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
            'Tháng nay': [moment().startOf('month'), moment().endOf('month')],
            'Tháng trước': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
            'Qúy 1': [moment(curYear + '-01-01'), moment(curYear + '-03-01').endOf('month')],
            'Qúy 2': [moment(curYear + '-04-01'), moment(curYear + '-06-01').endOf('month')],
            'Qúy 3': [moment(curYear + '-07-01'), moment(curYear + '-09-01').endOf('month')],
            'Qúy 4': [moment(curYear + '-10-01'), moment(curYear + '-12-01').endOf('month')],
        },
        locale: {
            "format": "DD/MM/YYYY",
            "separator": " - ",
            "applyLabel": "Áp dụng",
            "cancelLabel": "Bỏ qua",
            "fromLabel": "Từ",
            "toLabel": "Đến",
            "customRangeLabel": "Tùy chỉnh",
            "daysOfWeek": ["CN", "T2", "T3", "T4", "T5", "T6", "T7"],
            "monthNames": ["Tháng 1", "Tháng 2", "Tháng 3", "Tháng 4", "Tháng 5", "Tháng 6", "Tháng 7", "Tháng 8", "Tháng 9", "Tháng 10", "Tháng 11", "Tháng 12"],
            "firstDay": 1
        }
    }
}

function optionDateRangePickerCustomOnly(timePicker = false) {
    return {
        timePicker: timePicker,
        locale: {
            "format": "DD/MM/YYYY hh:mm A",
            "separator": " - ",
            "applyLabel": "Áp dụng",
            "cancelLabel": "Bỏ qua",
            "fromLabel": "Từ",
            "toLabel": "Đến",
            "customRangeLabel": "Tùy chỉnh",
            "daysOfWeek": ["CN", "T2", "T3", "T4", "T5", "T6", "T7"],
            "monthNames": ["Tháng 1", "Tháng 2", "Tháng 3", "Tháng 4", "Tháng 5", "Tháng 6", "Tháng 7", "Tháng 8", "Tháng 9", "Tháng 10", "Tháng 11", "Tháng 12"],
            "firstDay": 1
        }
    }
}

var colors = ["#f44336", "#fbc02d", "#4caf50"];