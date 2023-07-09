if ($('select[name="page_id"]').length) {
    options_filter_select2 = {
        category_filter: {
            selector: $('select[name="page_id"]'),
            placeholder: 'Thế Loại',
            multiple: false,
            hide_search: true,
            url: url_ajax_page
        },
    };
}

$(function () {
    //load lang
    load_lang(controller);
    DATATABLE.init();
});

function view_item(id) {
    $.ajax({
        url: url_ajax_view + '/' + id,
        type: "GET",
        dataType: "JSON",
        success: function (data) {
            $('#modal_form').modal('show');
            $('.modal-title').text(language['heading_title_view']);
            $.each(data, function (k, v) {
                $('#' + k + '').val(v);
            })

        },
        error: function (jqXHR, textStatus, errorThrown) {
            $(".modal-body").prepend(box_alert('alert-danger', language['error_try_again']));
        }
    });
}