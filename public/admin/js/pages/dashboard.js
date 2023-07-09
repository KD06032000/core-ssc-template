var btn_date_range = $('#date-range-btn');
var data_visitor = {};
var data_country = {};
var visitor_chart = null;
$(function () {
    init_dash();
    general_data();
    init_top_browser();
    init_top_referrers();
    init_top_visited();
    btn_date_range.find('span').html(moment().format('DD/M/YYYY') + ' - ' + moment().format('DD/M/YYYY'))
    btn_date_range.daterangepicker(
        {
            ranges: {
                'Hôm nay': [moment(), moment()],
                'Hôm qua': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                '7 ngày qua': [moment().subtract(6, 'days'), moment()],
                '30 ngày qua': [moment().subtract(29, 'days'), moment()],
                'Tháng này': [moment().startOf('month'), moment().endOf('month')],
                'Tháng trước': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
            },
            locale: {
                format: "DD/MM/YYYY",
                separator: " - ",
                applyLabel: "Áp dụng",
                cancelLabel: "Thoát",
                fromLabel: "Từ",
                toLabel: "Đến",
                customRangeLabel: "Tùy chỉnh",
                daysOfWeek: [
                    "CN",
                    "T2",
                    "T3",
                    "T4",
                    "T5",
                    "T6",
                    "T7"
                ],
                monthNames: [
                    "Tháng 1",
                    "Tháng 2",
                    "Tháng 3",
                    "Tháng 4",
                    "Tháng 5",
                    "Tháng 6",
                    "Tháng 7",
                    "Tháng 8",
                    "Tháng 9",
                    "Tháng 10",
                    "Tháng 11",
                    "Tháng 12"
                ],
                firstDay: 1
            },
            startDate: moment(),
            endDate: moment(),
            maxDate: moment(),
            opens: "left"
        },
        function (start, end) {
            btn_date_range.find('span').html(start.format('DD/M/YYYY') + ' - ' + end.format('DD/M/YYYY'))
        }
    );
    btn_date_range.on('apply.daterangepicker', function (e, picker) {
        let startDate = picker.startDate.format('YYYY-MM-DD');
        let endDate = picker.endDate.format('YYYY-MM-DD');
        general_data(startDate, endDate);
    })
});

function init_dash() {
    $.ajax({
        url: url_ajax_total,
        type: "GET",
        dataType: "JSON",
        success: function (data) {
            // console.log(data);return false;
            $.each(data, function (key, value) {
                $('#' + key).html(value);
            });
        },
        error: function (jqXHR, textStatus, errorThrown) {
            $(".modal-body").prepend(box_alert('alert-danger', language['error_try_again']));
        }
    });
}

function general_data(startDate, endDate) {
    let selector = $('#general_data');
    $.ajax({
        url: url_ajax_general_data,
        type: "GET",
        dataType: "JSON",
        data: {startDate: startDate, endDate: endDate},
        beforeSend: function () {
            selector.empty();
            selector.html('<div class="overlay loading-data"><i class="fa fa-refresh fa-spin"></i></div>')
        },
        success: function (data) {
            selector.empty();
            selector.html(data);
        },
        error: function (jqXHR, textStatus, errorThrown) {
            $(".modal-body").prepend(box_alert('alert-danger', language['error_try_again']));
        }
    });
}

function init_top_visited() {
    $.ajax({
        url: url_ajax_top_visited,
        type: "GET",
        dataType: "JSON",
        success: function (data) {
            $('#top_visited_data').html(data);
        },
        error: function (jqXHR, textStatus, errorThrown) {
            $(".modal-body").prepend(box_alert('alert-danger', language['error_try_again']));
        }
    });
}

function init_top_browser() {
    $.ajax({
        url: url_ajax_top_browser,
        type: "GET",
        dataType: "JSON",
        success: function (data) {
            $('#top_browser').html(data);
        },
        error: function (jqXHR, textStatus, errorThrown) {
            $(".modal-body").prepend(box_alert('alert-danger', language['error_try_again']));
        }
    });
}

function init_top_referrers() {
    $.ajax({
        url: url_ajax_top_referrers,
        type: "GET",
        dataType: "JSON",
        success: function (data) {
            $('#top_referrers').html(data);
        },
        error: function (jqXHR, textStatus, errorThrown) {
            $(".modal-body").prepend(box_alert('alert-danger', language['error_try_again']));
        }
    });
}
