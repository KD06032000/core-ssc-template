$(function () {
    //load data country ajax
    get_data_country();
    $("select").change(function () {
        var code = $(this).find(':selected').attr('data-code');
        $('#language').attr('disabled', true);
        action_lang(code);
    });
});

//reset data
function custom_translate(code) {
    var source_text = {};
    $('.text_translate').each(function () {
        _index = $(this).data('index');
        _text = $(this).text();
        translate_index = $('input[name="' + _index + '"]').val();

        if (translate_index == '') {
            source_text[_index] = _text;
        }
    });
    if (!jQuery.isEmptyObject(source_text)) {
        ajax_translate(code, source_text);
    }
    else $('#language').attr('disabled', false);
}

function ajax_translate(code, source_text) {
    var country = $('#language').val();
    country = country.trim().split(" ").join("_");
    country = country.split(/[^A-Za-z0-9-_]+/).join("");
    $('#overlay').show();
    $.ajax({
        url: current_url + '/translation/' + code + '/' + country,
        type: "POST",
        data: {
            'text': source_text,
            csrfName: csrfValue
        },
        dataType: "JSON",
        success: function (data) {
            if (data.type == "success") {
                if (typeof (data.data) != "undefined" && data.data != null) {
                    for (const [key, value] of Object.entries(data.data)) {
                        $('input[name="' + key + '"]').val(value);
                    }
                }
            }
            $('#language').attr('disabled', false);
            $('#overlay').hide();
            toastr[data.type](data.message);
        },
        error: function (jqXHR, textStatus, errorThrown) {
            $('#language').attr('disabled', false);
            $('#overlay').hide();
            toastr['error'](language['error_try_again']);
            alert(textStatus);
            console.log(jqXHR);
        }
    });
}

//get data country
function get_data_country() {
    //Ajax Load data from ajax
    $.ajax({
        url: url_ajax_list + "_country",
        type: "GET",
        dataType: "JSON",
        success: function (data) {
            // console.log(data);
            // $('.language').children().remove();
            $('.language').append(data);
        },
        error: function (jqXHR, textStatus, errorThrown) {
            alert(textStatus);
            console.log(jqXHR);
        }
    });
}

// get data with country
function action_lang(code) {
    var country = $('#language').val();
    country = country.trim().split(" ").join("_");
    country = country.split(/[^A-Za-z0-9-_]+/).join("");

    //Ajax Load data from ajax
    $.ajax({
        url: current_url + "/get_data_file/" + country,
        type: "GET",
        dataType: "JSON",
        success: function (data) {
            $('#data-table tbody').children().remove();
            $('#data-table tbody').append(data.lang_default);
            if (data.lang_country != null) {
                for (const [key, value] of Object.entries(data.lang_country)) {
                    $('input[name="' + key + '"]').val(value);
                }
            }
            custom_translate(code);
        },
        error: function (jqXHR, textStatus, errorThrown) {
            $('#language').attr('disabled', false);
            toastr['error'](language['error_try_again']);
        }
    });
}

function save() {
    var country = $('#language').val();
    country = country.trim().split(" ").join("_");
    country = country.split(/[^A-Za-z0-9-_]+/).join("");
    $('#language').attr('disabled', true);
    $('#overlay').show();
    // ajax adding data to database
    $.ajax({
        url: current_url + "/create_lang_file_for_country/" + country,
        type: "POST",
        data: $('#form').serialize(),
        dataType: "JSON",
        success: function (data) {
            toastr[data.type](data.message);
            if (data.type != "success") {
                $('span.text-danger').remove();
                $.each(data.validation, function (i, val) {
                    $('[name="' + i + '"]').after(val);
                })
            } else {
                $('.text-danger').empty();
                $('#language').attr('disabled', false);
                $('#overlay').hide();
            }
            $('#btnSave').text(language['btn_save']); //change button text
            $('#btnSave').attr('disabled', false); //set button enable
            $('#token').val(data.token);
        },
        error: function (jqXHR, textStatus, errorThrown) {
            toastr['error'](language['error_try_again']);
            $('#btnSave').text(language['btn_save']); //change button text
            $('#btnSave').attr('disabled', false); //set button enable
            $('#language').attr('disabled', false);
            $('#overlay').hide();
        }
    });
}