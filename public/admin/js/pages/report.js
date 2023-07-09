
let params_story = '';

options_filter_select2 = {
    store_filter: {
        selector: $('select[name="store_id"]'),
        placeholder: 'Chọn tiệm',
        multiple: false,
        hide_search: true,
        url: url_ajax_load_store
    },
    employee_filter: {
        selector: $('select[name="employee_id"]'),
        placeholder: 'Chọn thợ',
        multiple: false,
        hide_search: true,
        url: url_ajax_load_employee
    },
};

// Load select2 employee
$('select[name="store_id"]').change(function () {
    params_story = $(this).val() || '';
    load_select2_employee();
});

function load_select2_employee() {
    options_filter_select2.employee_filter.url = url_ajax_load_employee + "?story_id=" + params_story;
    load_select2_ajax_empty(options_filter_select2.employee_filter, '');
}
// Load select2 employee

$(function () {
    DATATABLE.init();
});

function export_excel() {

    let urlExport = url_ajax_export_excel;
    var form_data = $('#form_filter').serializeArray();
    var data = {};
    $.each(form_data, function (index, val) {
        if (val.value !== '') {
            data[val.name] = val.value;
        }
    });
    var params = $.param(data);

    if (params) {
        urlExport = urlExport + '?' + params;
    }
    window.location.href = urlExport;
}

function total_revenue(data) {
    $.ajax({
        type: "POST",
        url: url_ajax_total_revenue,
        data: data,
        dataType: "JSON",
        success: function (data) {
            $('#total-booking').html(`Tổng doanh thu : ${data.total}`)
        }
    });
}