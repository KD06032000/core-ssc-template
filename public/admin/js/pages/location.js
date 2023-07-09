let modal_form = $('#modal_form');
let modal_title = $('.modal-title');
let btn_save = $('#btnSave');

$(function () {
    DATATABLE.init();
    // init_checkbox_table();

    $(document).on('click', ".btnUpdateStatusCountry", function () {
        let status = $(this).data('value');
        let statusValue = 0;
        switch (status) {
            case 0:
                statusValue = 1;
                break;
            case 1:
                statusValue = 0;
                break;
            default:
                statusValue = 1;
        }
        updateField($(this).parent().parent().find('[name="id[]"]').val(), 'status', statusValue);
    });
    _loadFilterCity();
    loadFilterDistrict();
});

function add_form() {
    slug_disable = false;
    save_method = 'add';
    modal_form.modal('show');
    modal_form.trigger("reset");
    _loadCity();
    switch (location_type) {
        case "city":
            modal_title.text('Thêm Tỉnh / Thành phố');
            loadCountry();
            break;
        case "district":
            modal_title.text('Thêm Quận / Huyện');
            break;
        case "country":
            modal_title.text('Thêm quốc gia');
            break;
        default:
            modal_title.text('Thêm Phường / Xã');
    }
    $('#tab_image img').attr('src', 'http://via.placeholder.com/400x200');
}

//form sua
function edit_form(id) {
    save_method = 'update';
    //Ajax Load data from ajax
    $.ajax({
        url: url_ajax_edit + "/" + id,
        type: "GET",
        dataType: "JSON",
        success: function (data) {
            $.each(data.data, function (key, value) {
                $('[name="' + key + '"]').val(value);
            });
            if (data.city) {
                _loadCity(data.city);
                loadDistrict(data.city[0].id, data.district);
            }
            switch (location_type) {
                case "city":
                    modal_title.text('Sửa Tỉnh / Thành phố');
                    loadCountry(data.country);
                    break;
                case "district":
                    modal_title.text('Sửa Quận / Huyện');
                    break;
                case "country":
                    modal_title.text('Sửa quốc gia');
                    break;
                default:
                    modal_title.text('Sửa Phường / Xã');
            }
            modal_form.modal('show');
        },
        error: function (jqXHR, textStatus, errorThrown) {
            $(".modal-body").prepend(box_alert('alert-danger', language['error_try_again']));
        }
    });
}

//ajax luu form
function save() {
    btn_save.text(language['btn_saving']); //change button text
    btn_save.attr('disabled', true); //set button disable
    let url;

    if (save_method == 'add') {
        url = url_ajax_add;
    } else {
        url = url_ajax_update;
    }

    // ajax adding data to database
    $.ajax({
        url: url,
        type: "POST",
        data: $('#form').serialize(),
        dataType: "JSON",
        success: function (data) {
            toastr[data.type](data.message);
            if (data.type === "warning") {
                $('span.text-danger').remove();
                $.each(data.validation, function (i, val) {
                    $('[name="' + i + '"]').closest('.form-group').append(val);
                })
            } else {
                modal_form.modal('hide');
                reload_table();
            }
            btn_save.text(language['btn_save']);
            btn_save.attr('disabled', false);
        },
        error: function (jqXHR, textStatus, errorThrown) {
            $(".modal-body").prepend(box_alert('alert-danger', language['error_try_again']));
            btn_save.text(language['btn_save']); //change button text
            btn_save.attr('disabled', false); //set button enable

        }
    });
}

function import_excel() {
    let selector = $('[name="importExcel"]');
    selector.click();
    selector.change(function () {
        let inputEle = $(this);
        let file_data = inputEle.prop("files")[0];
        let form_data = new FormData();
        form_data.append('file', file_data);
        $.ajax({
            type: "POST",
            url: url_ajax_import_excel,
            processData: !1,
            contentType: !1,
            data: form_data,
            dataType: "JSON",
            beforeSend: function () {
                inputEle.parent().find('.fa-spinner').show();
                toastr['info']("Hệ thống đang tự động import vui lòng chờ ...");
            },
            success: function (response) {
                toastr[response.type](response.message);
                reload_table();
                inputEle.parent().find('.fa-spinner').hide();
            }
        });
    });
}

function loadCountry(dataSelected) {

    $('select[name="country_id"]').select2({
        allowClear: true,
        data: dataSelected,
        placeholder: 'Quốc gia',
        multiple: false,
        ajax: {
            url: url_ajax_load_country,
            dataType: 'json',
            delay: 250,
            processResults: function (data) {
                return {
                    results: data
                };
            },
            cache: true
        }
    });
    if (typeof dataSelected !== 'undefined') {
        $('select[name="country_id"] > option').prop("selected", "selected").trigger("change");
    }
}

function loadParentCategory() {
    $('select[name="parent_id"]').select2({
        allowClear: true,
        placeholder: language['text_category'],
        ajax: {
            url: url_ajax_load,
            data: {id: $('input[name="id"]').val()},
            dataType: 'json',
            delay: 250,
            processResults: function (data) {
                return {
                    results: data
                };
            },
            cache: true
        }
    });
}

function _loadFilterCity(dataSelected) {
    if ($('select[name="filter_city_id"]').length > 0) {
        $('select[name="filter_city_id"]').select2({
            allowClear: true,
            placeholder: "Chọn tỉnh/thành phố",
            data: dataSelected,
            ajax: {
                url: url_ajax_city,
                dataType: 'json',
                delay: 250,
                processResults: function (data) {
                    return {
                        results: data
                    };
                },
                cache: true
            }
        });
        $('select[name="filter_city_id"]').change(function () {
            getDataformFilterLocation();
            var city_id = $(this).val();
            loadFilterDistrict(city_id);
        });
    }
}

function _loadCity(dataSelected) {
    let selector = $('select[name="city_id"]');
    if (selector.length > 0) {
        selector.select2({
            allowClear: true,
            placeholder: "Chọn tỉnh/thành phố",
            data: dataSelected,
            ajax: {
                url: url_ajax_city,
                dataType: 'json',
                delay: 250,
                processResults: function (data) {
                    return {
                        results: data
                    };
                },
                cache: true
            }
        });
        if (typeof dataSelected !== 'undefined') selector.find('> option').prop("selected", "selected").trigger("change");
        $('select[name="city_id"]').change(function () {
            var city_id = $(this).val();
            loadDistrict(city_id);
        });

    }
}

function loadDistrict(city_id, dataSelected) {
    let selector = $('select[name="district_id"]');
    if (selector.length > 0) {
        selector.select2({
            allowClear: true,
            placeholder: "Chọn quận huyện",
            data: dataSelected,
            ajax: {
                type: "POST",
                url: url_ajax_district + '/' + city_id,
                dataType: 'json',
                delay: 250,
                processResults: function (data) {
                    return {
                        results: data
                    };
                },
                cache: true
            }
        });
        selector.find('option').prop("selected", "selected").trigger("change");
    }
}

function loadFilterDistrict(city_id, dataSelected) {
    let selector = $('select[name="filter_district_id"]');
    if (selector.length > 0) {
        selector.select2({
            allowClear: true,
            placeholder: "Chọn quận huyện",
            data: dataSelected,
            ajax: {
                type: "POST",
                url: url_ajax_district + '/' + city_id,
                dataType: 'json',
                delay: 250,
                processResults: function (data) {
                    return {
                        results: data
                    };
                },
                cache: true
            }
        });
        selector.change(function () {
            getDataformFilterLocation();
        });
    }
}

function getDataformFilterLocation() {
    var filter_city_id = $("select.filter_city_id").val();
    var filter_district_id = $(".filter_district_id").val();
    var dataFilter = {
        filter_city_id: filter_city_id,
        filter_district_id: filter_district_id,
    };


    filterDatatables(dataFilter);

}