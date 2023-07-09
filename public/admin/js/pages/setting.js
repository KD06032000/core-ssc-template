$(function () {
    tinymce.init(optionTinyMCE);
    tinymce.init(optionTinyMCEMore);
    $("[name=is_cache]").bootstrapSwitch();
    $('a.btnClearCache').click(function () {
        var elment = $(this);
        $.ajax({
            type: 'POST',
            url: url_ajax_clear_cache,
            data: {key: 'a'},
            dataType: 'json',
            beforeSend: function () {
                elment.find('.fa-spinner').show();
            },
            success: function (response) {
                console.log(response);
                elment.find('.fa-spinner').hide();
                toastr.success('Clear cache thành công !');
            },
            error: function (xhr, ajaxOptions, thrownError) {
                alert(xhr.status);
                console.log(thrownError);
            }
        });
        return false;
    });

    $('button.btn-ajax-generate-db').click(function () {
        var elment = $(this);
        $.ajax({
            type: 'POST',
            url: url_ajax_backup_db,
            dataType: 'json',
            beforeSend: function () {
                elment.find('.fa-spinner').show();
            },
            success: function (response) {
                console.log(response);
                elment.find('.fa-spinner').hide();
                location.reload();
            },
            error: function (xhr, ajaxOptions, thrownError) {
                alert(xhr.status);
                console.log(thrownError);
            }
        });
        return false;
    });

    $('.btn-ajax-restore-db').click(function () {
        var elment = $(this);
        var db_name = $(this).parent().parent().find('td.file-name').text();
        $.ajax({
            type: 'POST',
            url: url_ajax_restore_db,
            data: {db_name: db_name},
            dataType: 'json',
            beforeSend: function () {
                elment.find('.fa-spinner').show();
            },
            success: function (response) {
                console.log(response);
                if (response.status === 0) {
                    $('.alert').addClass('alert-danger');
                    $('.alert .content-msg').html(response.msg);
                }
                elment.find('.fa-spinner').hide();
            },
            error: function (xhr, ajaxOptions, thrownError) {
                alert(xhr.status);
                console.log(thrownError);
            }
        });
        return false;
    });

    $('.btn-ajax-delete-db').click(function () {
        var elment = $(this);
        var db_name = $(this).parent().parent().find('td.file-name').text();
        swal({
            title: language['alert_title'],
            text: language['confirm_delete'],
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: language['btn_yes'],
            cancelButtonText: language['btn_no'],
            closeOnConfirm: false,
            showLoaderOnConfirm: true,
        }, function () {
            $.ajax({
                type: 'POST',
                url: url_ajax_delete_db,
                data: {db_name: db_name},
                dataType: 'json',
                beforeSend: function () {
                    elment.find('.fa-spinner').show();
                },
                success: function (response) {
                    console.log(response);
                    elment.find('.fa-spinner').hide();
                    swal.close();
                    location.reload();
                },
                error: function (xhr, ajaxOptions, thrownError) {
                    alert(xhr.status);
                    console.log(thrownError);
                }
            })
        });
        return false;
    })
});

$('#check_token_gg_calendar').on("click", function () {
    $.ajax({
        type: "GET",
        url: base_url + "admin/calendar/check_token",
        dataType: "JSON",
        success: function (data) {
            if (!data.error) {
                toastr['success'](data.message);
                $('#google_calendar_call_success').html(data.view);
            } else {
                toastr['error'](data.message);
            }
        }
    });
});

$(document).on("click", "#google_calendar_apply_code", function () {

    let val = $(document).find('#google_calendar_code_value').val();

    $.ajax({
        type: "POST",
        url: base_url + "admin/calendar/apply_token",
        data: {'data' : val},
        dataType: "JSON",
        success: function (data) {
            if (!data.error) {
                toastr['success'](data.message);
                $('#google_calendar_call_success').html(data.view);
            } else {
                toastr['error'](data.message);
            }
        }
    });
});

function addInputElementSettings(idElement, i, value, file, url, addTinymce) {
    var element = $('#' + idElement);
    i = parseInt(i) + 1;
    $.ajax({
        type: "POST",
        url: base_url + "admin/setting/" + url,
        data: {i: i, item: value, file: file, meta_key: idElement, id: i},
        dataType: "html",
        success: function (inputImage) {
            element.append(inputImage);
            element.attr('data-id', i);

            if (typeof addTinymce != 'undefined' && addTinymce != '') {
                setTimeout(function () {
                    tinymce.init(optionTinyMCE);
                }, 100);
                $('.datetimepicker').datetimepicker({
                    format: "yyyy-mm-dd HH:ii:ss",
                    autoclose: true,
                });
            }
        }
    });
}