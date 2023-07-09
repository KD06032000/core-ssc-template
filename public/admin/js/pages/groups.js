$(function () {
    //load lang
    load_lang(controller);
    DATATABLE.init();
});

function save() {
    let url = save_method === 'add' ? url_ajax_add : url_ajax_update;
    AJAX.save(url, $('#form'), reload_table);
}

function load_permission(id, data) {
    if (id === 1) {
        check_all_per();
    } else {
        if (data.permission) {
            //uncheck_all_per();
            $.each(JSON.parse(data.permission), function (key, value) {
                if ($.inArray(key, cms_custom_per) !== -1) {
                    $.each(value, function (per, val) {
                        if (typeof val == 'object') {
                            $.each(val, function (k, v) {
                                $('#per_' + key + '_' + per + '_' + k).prop('checked', true);
                            });
                        } else {
                            console.log(val);
                        }
                    });
                }
                $.each(value, function (per, val) {
                    $('#per_' + key + '_' + per).prop('checked', true);
                });
            });
        }
    }
}

function check_all_per() {
    $('#tbl_per :checkbox').prop('checked', true);
}

function uncheck_all_per() {
    $('#tbl_per :checkbox').prop('checked', false);
}