
options_filter_select2 = {
    category_filter: {
        selector: $('select[name="category_id"]'),
        placeholder: 'Danh mục bài viết',
        multiple: false,
        hide_search: true,
        url: url_ajax_load_category
    },
};

options_select2 = {
    category_id: {
        selector: $('select[name="category_id[]"]'),
        placeholder: 'chọn danh mục',
        multiple: false,
        hide_search: true,
        url: url_ajax_load_category
    },
    tag_id: {
        selector: $('select[name="tag_id[]"]'),
        placeholder: 'chọn tag',
        multiple: true,
        hide_search: true,
        url: url_ajax_load_tag
    }
}

$(function () {
    //load lang
    load_lang(controller);
    DATATABLE.init();
    TINYMCE.init();
    SEO.init();
});

//ajax luu form
function save() {
    let url = save_method === 'add' ? url_ajax_add : url_ajax_update;
    AJAX.save(url, $('#form'), reload_table);
}
