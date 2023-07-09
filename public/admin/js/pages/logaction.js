
options_filter_select2 = {
    user_id: {
        selector: $('select[name="user_id"]'),
        placeholder: 'Thành viên',
        multiple: false,
        hide_search: true,
        url: url_load_users
    },
};

$(function () {
    //load lang
    // load_lang(controller);
    DATATABLE.init();
    //load slug
    $('select.user_id').on('change', function () {
        var id = this.value;
        var user_id = {user_id:id};
        filterDatatables(user_id);
    });
});