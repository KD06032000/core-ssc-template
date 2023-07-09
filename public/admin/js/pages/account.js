
$(function () {
    init_filter_table();
    init_data_table();
    init_checkbox_table();
});

function add_form() {
    slug_disable = false;
    save_method = 'add';
    $('#modal_form').modal('show');
    $('#image_preview').attr('src', "http://via.placeholder.com/400x200");
    $('#modal_form').trigger("reset");
    $('.modal-title').text('Thêm thành viên');
    $('[name="username"],[name="email"]').attr('disabled', false);
}

//form sua
function edit_form(id) {
    save_method = 'update';
    $('#title-form').text('Sửa thành viên');
    $.ajax({
        url: url_ajax_edit + "/" + id,
        type: "GET",
        dataType: "JSON",
        success: function (data) {
            LOAD.autoFill(data)
            $('#modal_form select[name="active"] > option[value="' + data[0].active + '"]').prop("selected", true);
            $('#modal_form').modal('show');
        },
        error: function (jqXHR, textStatus, errorThrown) {
            $(".modal-body").prepend(box_alert('alert-danger', language['error_try_again']));
        }
    });
}

//ajax luu form
function save() {
    let url = save_method == 'add' ? url_ajax_add : url_ajax_update;
    AJAX.save(url, $('#form'), reload_table);
}