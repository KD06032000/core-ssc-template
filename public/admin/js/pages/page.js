
options_select2 = {
    parent_id: {
        selector: $('select[name="parent_id"]'),
        placeholder: 'Chọn trang cha',
        multiple: false,
        hide_search: true,
        url: url_ajax_load_page_service
    }
}

$(function () {
    //load lang
    load_lang(controller);
    DATATABLE.init();
    TINYMCE.init();
    SEO.init();
});

function save() {
    let url = save_method === 'add' ? url_ajax_add : url_ajax_update;
    AJAX.save(url, $('#form'), reload_table);
}
