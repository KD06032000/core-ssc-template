
options_filter_select2 = {
    category_filter: {
        selector: $('select[name="tag_id[]"]'),
        placeholder: 'Tag liên kết',
        multiple: true,
        hide_search: true,
        url: url_ajax_load_category
    },
};

options_select2 = {
    category_id: {
        selector: $('select[name="category_id[]"]'),
        placeholder: 'chọn tag liên kết',
        multiple: true,
        hide_search: true,
        url: url_ajax_load_category
    }
}

$(function () {
    //load lang
    load_lang(controller);
    DATATABLE.init();
});

//ajax luu form
function save() {
    let url = save_method === 'add' ? url_ajax_add : url_ajax_update;
    AJAX.save(url, $('#form'), reload_table);
}
