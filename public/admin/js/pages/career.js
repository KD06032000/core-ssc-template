
$(function () {
    //load lang
    load_lang(controller);
    DATATABLE.init();
    SEO.init();
    TINYMCE.init();
});

//ajax luu form
function save() {
    let url = save_method === 'add' ? url_ajax_add : url_ajax_update;
    AJAX.save(url, $('#form'), reload_table);
}