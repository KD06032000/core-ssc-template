var csrfToken = $('meta[name="csrf-token"]');
var csrfName = csrfToken.attr('data-name');
var csrfValue = csrfToken.attr('content');

var options_select2 = {};
var options_filter_select2 = {};
var load_video = false;
var init_input_multiple = false;
var load_tiny = false;
var load_seo = false;

let prefix = {
    "post" : 'd',
    "tag" : 't',
};

let prefixCat = {
    "post" : 'c',
};

$(document).ajaxSend(function (elm, xhr, s) {
    if (s.data !== 'undefined') {
        s.data += '&';
    }
    s.data += csrfName + '=' + csrfValue;
});

//file js dinh nghia ham dung chung
var save_method = '',
    slug_disable = false,
    table = '',
    limit = 10,
    i = 1,
    dataFilter = {};

$(function () {
    'use strict';
    $(document).ajaxStart(function () {
        Pace.restart();
    });
    //load lang
    load_lang('general');

    LIB.init();
    COMMON.init();
    LOAD.init();
    MODAL.init();
    ACTION.init();

    init_checkbox_table();
    init_filter_table();
});

const INPUT_MULTIPLE = {
    block: '.input_multiple .block-list',
    html: '',
    key: 0,
    field: [],
    root: '',
    init(root, field = []) {
        init_input_multiple = true;
        this.field = field;
        this.root = root;
        this.render();
        // this.append();
        this.remove();
        this.add();
    },

    render() {
        let e = $(document).find(this.block).find('.item');
        this.html = e.html();
        e.remove();
    },

    load(value) {
        _that = this;
        if (value === null) return;
        if (value.length) {
            let obj = JSON.parse(value);
            Object.keys(obj).map(function (key, index) {
                _that.append(obj[key]);
            });
        }
    },

    reload() {
        $(document).find(this.block).find('.item').remove();
        this.key = 0;
    },

    remove() {
        $(document).on("click", ".input_multiple .block-list .item .remove", function () {
            $(this).closest('.item').remove();
        });
    },

    add() {
        let _this = this;
        $(document).on("click", ".input_multiple .add", function () {
            _this.append();
        });
    },

    append(value = {}) {
        let item = this.html;
        let root = this.root;

        this.field.map((e) => {
            item = item.replaceAll(`name="${root}[${e}]"`, `name="${root}[${this.key}][${e}]"`)
                .replaceAll(`"${root}_${e}"`, `${root}_${e}_${this.key}`)
                .replaceAll(`chooseImage('${root}_${e}')`, `chooseImage('${root}_${e}_${this.key}')`)
        });

        $(document).find(this.block).append('<div class="item">' + item + '</div>');

        if (typeof value !== "undefined") {
            this.field.map((e) => {
                $(document).find(this.block).find(`[name="${root}[${this.key}][${e}]"]`).val(value[e]);
                loadImageThumb(value[e], `${root}[${this.key}][${e}]`);
            });
        }

        this.key += 1;
    }

};

const ACTION = {
    init: function () {
        this.add_form();
        this.edit_form();
        this.delete_form();
        this.export_excel();
    },
    add_form: function () {
        $(document).on("click", '#add_form', function () {
            slug_disable = false;
            save_method = 'add';

            LOAD.select2();

            var title = '';
            switch (controller) {
                case 'category':
                    $('input[name="type"]').val(category_type);
                    title = 'Thêm ' + language['type_' + category_type];
                    break;
                case 'property':
                    $('input[name="type"]').val(property_type);
                    title = 'Thêm ' + language['text_' + property_type];
                    break;
                case 'users':
                    $('[name="username"]').removeAttr('disabled');
                    $('[name="email"]').removeAttr('disabled');
                    title = language['heading_title_add'];
                    break;
                case 'address':
                    loadWards();
                case 'location':
                    _loadCity();
                    loadDistrict();

                    break;
                default:
                    title = language['heading_title_add'];
            }

            if (typeof location_type !== "undefined") {
                switch (location_type) {
                    case "city":
                        modal_title.text('Thêm Tỉnh / Thành phố');
                        break;
                    case "district":
                        modal_title.text('Thêm Quận / Huyện');
                        break;
                    case "ward":
                        modal_title.text('Thêm Phường Xã');
                        break;
                }
            } else {
                if (title === "") {
                    $('.modal-title').text('Thêm mới');
                } else {
                    $('.modal-title').text(title);
                }
            }

            $('#modal_form').modal('show');
            $('#modal_form').trigger("reset");
            if (load_tiny) TINYMCE.reset_data();
        })
    },
    edit_form: function () {
        $(document).on("click", '#edit_form', function () {
            let id = $(this).data('id');
            AJAX.edit(id);
        })
    },
    delete_form: function () {
        // DELETE ONE ITEM
        $(document).on("click", '#delete_form', function () {
            let id = $(this).data('id');
            swal(swal_delete(), function () {
                AJAX.delete(id);
            });
        })

        // DELETE MULTI
        $(document).on("click", '#delete_multi', function () {

            var tmpArr = $('.chk_id:checkbox:checked').map(function () {
                return this.value;
            }).get();

            if (tmpArr.length > 0) {
                swal(swal_delete(), function () {
                    AJAX.delete(0, tmpArr);
                });
                $('#data-table-select-all').prop('checked', false);
            } else {
                toastr['warning']('Vui lòng chọn thông tin cần xóa');
            }
        })
    },
    export_excel: function () {
        $(document).on("click", '#export_table', function () {
            let url = (typeof url_export_excel === "undefined") ? current_url + '/export_excel' : url_export_excel;
            let form_data = $('#form_filter').serializeArray();
            var data = {};
            $.each(form_data, function (index, val) {
                if (val.value !== '') {
                    data[val.name] = val.value;
                }
            });
            let params = $.param(data);
            if (params) url += '?' + params;

            window.location.href = url;
        })
    }
};

const LIB = {
    init: function () {
        this.select2();
        this.fancybox();
        this.datepicker();
        this.tooltip();
        this.bootstrap();
        this.telInput();
        this.money();
        this.colorpicker();
    },
    colorpicker() {
        $('.colorpicker-component').colorpicker();
    },
    select2: function () {
        $('select.select2').map((i, e) => {
            $(e).select2({
                allowClear: true,
                placeholder: $(this).data('placeholder')
            });
        })
    },
    telInput: function () {
        $('.inputTel').intlTelInput({});
    },
    fancybox: function () {
        $('.fancybox').fancybox({
            'overlayOpacity': 0.6,
            'autoScale': false,
            'type': 'iframe'
        });
    },
    datepicker: function () {
        $('.datepicker').datepicker({
            format: "dd/mm/yyyy",
            autoclose: true,
            todayHighlight: true,
        });

        $('.datepicker').attr('readonly', true);

        $('.date-custom').daterangepicker(optionDateRangePickerCustom());

        $('.date-custom-only-time').daterangepicker(optionDateRangePickerCustomOnly(true));

        $(".datepicker-years").datepicker({
            format: "yyyy",
            viewMode: "years",
            minViewMode: "years",
            autoclose: true,
            todayHighlight: true,
        });
    },
    tooltip: function () {
        $('[data-toggle="tooltip"]').tooltip();
    },
    bootstrap: function () {
        $('.bootstrapswitch').bootstrapSwitch();
    },
    money: function () {
        $('body').on('keyup', "[data-type='currency']", function () {
            var str = $(this).val();
            str = str.replace(/\D+/g, '');
            $(this).val(str.replace(/\d(?=(?:\d{3})+(?!\d))/g, '$&,'));
        });
    }

};

const COMMON = {
    init: function () {

        // ACTIVE MENUS
        this.active_menus();
        this.row_checkbox();

        // Filter form
        $('#form_filter').submit(function (e) {
            e.preventDefault();
            let form_data = $(this).serializeArray();
            let filter = {};
            $.each(form_data, function (index, val) {
                if (val.value !== '') {
                    filter[val.name] = val.value;
                }
            });

            filterDatatables(filter);
        });

        // Update Status
        $(document).on('click', ".btnUpdateStatus", function () {
            let status = $(this).data('value');
            let statusValue;
            switch (status) {
                case 1:
                case 2:
                    statusValue = 0;
                    break;
                default:
                    statusValue = 1;
            }
            AJAX.update_field($(this).closest('tr').find('[name="id[]"]').val(), 'is_status', statusValue);
        });

        // Update Status không có nháp
        $(document).on('click', ".btnUpdateStatusnotdarf", function () {
            let status = $(this).data('value');
            let statusValue;
            switch (status) {
                case 1:
                    statusValue = 0;
                    break;
                default:
                    statusValue = 1;
            }
            AJAX.update_field($(this).closest('tr').find('[name="id[]"]').val(), 'is_status', statusValue);
        });

        // Update Featured
        $(document).on('click', ".btnUpdateFeatured", function () {
            let value = $(this).data('value');
            let language_code = $('.filter_language_code').val();
            let updateValue;
            switch (value) {
                case 1:
                    updateValue = 0;
                    break;
                default:
                    updateValue = 1;
            }
            AJAX.update_field($(this).closest('tr').find('[name="id[]"]').val(), 'is_featured', updateValue, language_code);
            return false;
        });

        // Update Order
        $('#data-table').on('change', '.change_order', function () {
            if ($(this).val() < 0) {
                toastr['warning']('Giá trị không đươc nhỏ hơn 0');
                reload_table();
            } else {
                AJAX.update_field($(this).attr('data-id'), 'order', $(this).val());
            }
        });

        $(document).on('change', '.update_single_field', function () {
            let _this = $(this);

            let id = _this.attr('data-id');
            let field = _this.attr('name');
            let value = _this.val();
            AJAX.update_field(id, field, value);
        });
    },
    row_checkbox: function () {
        $(document).on("click", '.chk_id', function () {
            var check = false;
            let checkbox = $('.chk_id');
            for (let i = 0; i < checkbox.length; ++i) {
                let item = $($('.chk_id')[i]);
                if (item.prop('checked')) {
                    check = true;
                } else {
                    check = false;
                    break;
                }
            }
            $('#data-table-select-all').prop('checked', check);
        });
    },
    active_menus: function () {
        let menuElement = $('a[href="' + document.URL + '"]');

        menuElement.parent().addClass('active');
        menuElement.parent().parent().show();
        menuElement.parent().parent().parent().addClass('menu-open');
    }
};

const LOAD = {
    loaded: [],
    datetime: ['displayed_time', 'deadline', 'birthday'],
    images: ['thumbnail', 'banner', 'mobile_thumbnail'],
    video: ['linkvideo', 'url_video', 'link', 'video', 'url'],
    color: ['color'],
    multiple: [],
    price: ['price', 'price_old', 'price_sale'],
    init: function () {
        this.select2();
        this.filter_select2();
    },
    select2: function (data) {
        data = typeof data === 'object' ? data : {};
        $.each(options_select2, function (key, option) {
            let dataSelected = typeof data[key] !== 'undefined' ? data[key] : '';
            load_select2_ajax(option, dataSelected);
        });
    },
    filter_select2: function () {
        $.each(options_filter_select2, function (key, option) {
            load_select2_ajax(option, '');
        });
    },
    autoFill: function (data, form) {
        if (init_input_multiple) {
            this.multiple = [INPUT_MULTIPLE.root];
        }

        let that = this;
        form = typeof form !== 'undefined' ? form : '#form';
        that.loaded = [];
        if (typeof data === 'object') {

            $.each(data, function (index, dataItem) {
                if (typeof dataItem === 'object' && dataItem) {


                    let lang_code = dataItem.language_code;
                    $.each(dataItem, function (name, value) {

                        if (name === 'id') {
                            that.prefixUrl(value);
                        }

                        let elements = $(form + ' [name="' + name + '"]');
                        if (elements.length === 0 && $.inArray(name, that.multiple) === -1) {
                            // Đa ngôn ngữ
                            let name_lang = name + '[' + lang_code + ']';
                            if ($.inArray(name_lang, that.loaded) === -1) {
                                elements = $(form + ' [name="' + name_lang + '"]');
                                elements.val(value);

                                if (tinymce.get(name + '_' + lang_code)) {
                                    tinymce.get(name + '_' + lang_code).setContent(value);
                                }
                                if (name === 'meta_keyword') {
                                    elements.tagsinput('add', value);
                                }
                                if (name === 'slug') {

                                    let urlPrefix = '';
                                    if (save_method !== 'add') {
                                        let id = $('[name="id"]').val();
                                        if (typeof prefix[controller] !== "undefined") {
                                            urlPrefix = '-' + prefix[controller] + id;
                                        } else if (controller === 'category') {
                                            if (typeof prefixCat[category_type] !== "undefined") {
                                                urlPrefix = '-' + prefixCat[category_type] + id;
                                            }
                                        }
                                    }

                                    $('.gg-url').html(base_url + value + urlPrefix)
                                }
                                if (name === 'meta_description') {

                                    $('.gg-desc').html(value)
                                }
                                that.loaded.push(name_lang);
                            }

                            if ($.inArray(name, that.loaded) === -1) {
                                if (name === 'album') {
                                    loadMultipleMedia(value);
                                    that.loaded.push(name);
                                }
                            }

                        } else {
                            that.fillItemNormal(name, value, elements);
                        }
                    });
                } else {
                    if (index === 'id') that.prefixUrl(dataItem);
                    let elements = $(form + ' [name="' + index + '"]');
                    that.fillItemNormal(index, dataItem, elements);
                }

            })
        } else {
            this.valueFormControl(name, data);
        }
    },
    fillItemNormal: function (name, value, elements) {
        let that = this;
        if ($.inArray(name, that.loaded) === -1) {
            if ($.inArray(name, that.multiple) !== -1) {
                INPUT_MULTIPLE.load(value);
            } else if ($.inArray(name, that.datetime) !== -1) {
                value = getFormattedDate(value, name);
            } else if ($.inArray(name, that.color) !== -1) {
                elements.val(value);
                elements.trigger('change');
            } else if ($.inArray(name, that.images) !== -1) {
                loadImageThumb(value, name);
            } else if ($.inArray(name, that.video) !== -1) {
                VIDEO.load(value);
            } else if ($.inArray(name, that.price) !== -1) {
                value = value.replace(/\d(?=(?:\d{3})+(?!\d))/g, '$&,');
            }

            elements.val(value);
            that.loaded.push(name);
        }
    },
    valueFormControl: function (name, value, form) {
        form = typeof form !== 'undefined' ? form : '#form';
        $(form + '[name="' + name + '"]').val(value);
    },
    prefixUrl (id) {
        if (typeof prefix[controller] !== "undefined") {
            $('#prefix-slug').html('-' + prefix[controller] + id);
        } else if (controller === 'category') {
            if (typeof prefixCat[category_type] !== "undefined") {
                $('#prefix-slug').html('-' + prefixCat[category_type] + id);
            }
        }
    },
};

const MODAL = {
    init: function () {
        //Event modal
        let modalCms = $('.modal');
        modalCms.modal({backdrop: 'static', keyboard: false, show: false});
        //Event close modal
        modalCms.on('hidden.bs.modal', function () {
            window.onbeforeunload = null;
            $(this).find('form').not('input[name=' + csrfName + ']').trigger('reset');
            $(this).find('input[type=hidden]').not('input[name=' + csrfName + ']').val(0);
            $('.form-group span.text-danger').remove();
            $("#form .select2").empty().trigger('change');
            $("select.load_city").select2("val", "");
            $('.gallery').html('');
            $('.reset_html').html('').attr('data-id', 0);
            $('.alert').remove();
            $("input.tagsinput").tagsinput('removeAll');
            $('input[name="is_featured"]').bootstrapSwitch('state', false);
            $('#tab_image .fancybox img').attr('src', '//via.placeholder.com/400x200');
            $(this).find('.nav-tabs a:first,.nav-pills a:first').tab('show');
            $('.colorpicker-component input').val('#ffffff');
            $('.colorpicker-component input').trigger('change');

            $('#prefix-slug').html('');
            $('.gg-url').html(base_url)
            $('.gg-desc').html('')

            if (load_tiny) TINYMCE.reset_data();

            if (load_seo) SEO.reload();

            if (load_video) VIDEO.reload();

            if (init_input_multiple) INPUT_MULTIPLE.reload();
        });

        //Event open modal
        modalCms.on('shown.bs.modal', function () {
            btnFly();
        });
    }
};

const AJAX = {
    edit: function (id) {
        slug_disable = true;
        save_method = 'update';
        $.ajax({
            url: url_ajax_edit + "/" + id,
            type: "GET",
            dataType: "JSON",
            success: function (data) {
                LOAD.autoFill(data);
                LOAD.select2(data);

                if (controller === 'groups') {
                    load_permission(id, data);
                }

                if (controller === 'address') {
                    if (data.city) {
                        _loadCity(data.city);
                        loadDistrict(data.city[0].id, data.district);
                        loadWards(data.district[0].id, data.ward);
                    }
                }

                if (controller === 'location') {
                    if (data.city) {
                        _loadCity(data.city);
                        loadDistrict(data.city[0].id, data.district);
                    }

                    switch (location_type) {
                        case "city":
                            modal_title.text('Sửa Tỉnh / Thành phố');
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
                }

                if (controller === 'property') {
                    console.log(property_type);
                    if (['chart_1', 'chart_2', 'chart_3'].indexOf(property_type) !== -1) {
                        let nums = data[0].data;
                        Object.keys(nums).map(function (key, index) {
                            $('input[name="data[' + key + ']"]').val(nums[key]);
                        });
                    }
                }

                if (controller === 'users') {
                    $('[name="username"]').attr('disabled', true);
                    $('[name="email"]').attr('disabled', true);
                }

                $('#modal_form').modal('show');
                $('.modal-title').text(language['heading_title_edit']);
            },
            error: function (jqXHR, textStatus, errorThrown) {
            }
        });
    },
    save: function (url, form, callback) {
        let btnSave = $('#btnSave');
        btnSave.text(language['btn_saving']);
        btnSave.attr('disabled', true);

        if (load_tiny) TINYMCE.create_data();

        $.ajax({
            url: url,
            type: "POST",
            data: form.serialize(),
            dataType: "JSON",
            success: function (data) {
                toastr[data.type](data.message);
                if (data.type === "warning") {
                    $('span.text-danger').remove();
                    $.each(data.validation, function (i, val) {
                        $('[name="' + i + '"]').closest('.form-group').append(val);
                    })
                } else {
                    $('.modal').modal('hide');
                    if (typeof callback !== 'undefined') {
                        callback();
                    }
                }
                btnSave.text(language['btn_save']);
                btnSave.attr('disabled', false);
            }, error: function (jqXHR, textStatus, errorThrown) {
                btnSave.text(language['btn_save']);
                btnSave.attr('disabled', false);
            }
        });
    },
    update_field: function (id, field, value, language_code = '') {
        $.ajax({
            type: "POST",
            url: url_ajax_update_field,
            data: {id: id, field: field, language_code: language_code, value: value},
            dataType: "JSON",
            success: function (response) {
                toastr[response.type](response.message);
                reload_table();
            }
        });
    },
    delete: function (i, arr) {

        let id = typeof arr !== "undefined" ? arr[i] : i;

        $.ajax({
            url: url_ajax_delete + "/" + id,
            type: "GET",
            dataType: "JSON",
            success: function (data) {

                if (typeof arr !== "undefined") {
                    i++;
                    if (i >= arr.length) {
                        $('#data-table-select-all').attr('checked', false);
                        $('.chk_id').attr('checked', false);

                        swal.close();
                        reload_table();
                        toastr[data.type](data.message);

                    } else {
                        AJAX.delete(i, arr);
                    }
                } else {
                    swal.close();
                    reload_table();
                    toastr[data.type](data.message);
                }
            }
        });
    }
};

const VIDEO = {
    init: function () {
        load_video = true;

        $('#linkvideo').on('keyup', function () {
            let link = $(this).val();
            if (link.trim() !== '') {
                $(`#linkIframeVideo`).css("height", "200px");
            } else {
                $(`#linkIframeVideo`).css("height", "0");
            }
            let url = `https://www.youtube.com/embed/${link.split('=')[1].split('&')[0]}`;
            $(`#linkIframeVideo`).attr('src', function () {
                return url;
            });
        })
    },
    load: function (value) {
        if (load_video) {
            $(`#linkvideo`).val(value);

            let link = '';
            if (value) link = value.split('v=')[1].split('&')[0];

            $(`#linkIframeVideo`).css("height", "200px");
            let url = `https://www.youtube.com/embed/${link}`;
            $(`#linkIframeVideo`).attr('src', function () {
                return url;
            });
        }
    },
    reload: function () {
        $(`#linkIframeVideo`).css("height", "0");
        $(`#linkIframeVideo`).attr('src', '');
    }
};

const TINYMCE = {
    init: function () {
        load_tiny = true;
        tinymce.init(optionTinyMCE);
    },
    create_data: function () {
        for (let j = 0; j < tinyMCE.editors.length; j++) {
            let content = tinymce.get(tinyMCE.editors[j].id).getContent();
            $('#' + tinyMCE.editors[j].id).val(content);
        }
    },
    reset_data: function () {
        for (let j = 0; j < tinyMCE.editors.length; j++) {
            tinymce.get(tinyMCE.editors[j].id).setContent('');
        }
    }
};

const SEO = {
    init: function () {
        load_seo = true;
        this.check();
        this.style();
        init_slug('name', 'slug');
    },
    reload: function () {
        $("span.count-title").text(0);
        $("span.count-desc").text(0);
        $("span.count-key").text(0);
    },
    check: function () {
        //  For Title
        $("input[name^='meta_title']").keyup(function () {
            checkSEOTitle($(this));
        });
        //  For Slug
        $("input[name^='slug']").keyup(function () {

            let urlPrefix = '';
            if (save_method !== 'add') {
                let id = $('[name="id"]').val();
                if (typeof prefix[controller] !== "undefined") {
                    urlPrefix = '-' + prefix[controller] + id;
                } else if (controller === 'category') {
                    if (typeof prefixCat[category_type] !== "undefined") {
                        urlPrefix = '-' + prefixCat[category_type] + id;
                    }
                }
            }

            $(".gg-url").html(base_url + $(this).val() + urlPrefix);
        });
        //  For Focus Keywords
        $(".bootstrap-tagsinput input").keyup(function () {
            checkSEOKeyword($(this));
        });
        //  For Decriptions
        $("textarea[name^='meta_description']").keyup(function () {
            checkSEODesc($(this));
        });

        //Check SEO
        $(".gg-url").html(base_url + $("input[name^='slug']").val());
        if ($("input[name^='meta_title']").length) checkSEOTitle("input[name^='meta_title']");
        if ($("input[name^='meta_description']").length) checkSEODesc("textarea[name^='meta_description']");
    },
    style: function () {
        let gg = $(".gg_1");
        var cgg = gg.text().split("").join("</span><span>");
        gg.html(cgg);
    }
};

const DATATABLE = {
    //init datatable
    init: function (limit, order) {
        if (typeof order == 'undefined') order = 1;
        if (typeof limit == 'undefined' || limit == '') limit = 10;
        //load table ajax
        var element = $('#data-table');
        table = element.DataTable({
            'ajax': {
                type: "POST",
                url: url_ajax_list,
                data: function (d) {
                    return $.extend({}, d, dataFilter);
                }
            },
            fixedHeader: true,
            'bProcessing': true,
            'serverSide': true,
            'buttons': [],
            "language": {
                "url": "//cdn.datatables.net/plug-ins/1.10.19/i18n/Vietnamese.json"
            },
            'columnDefs': [
                {
                    'targets': 'no-sort',
                    "orderable": false,
                    'className': 'text-center'
                },
                {
                    'targets': 0,
                    'visible': element.hasClass("no_check_all") ? false : true,
                    'searchable': false,
                    'orderable': false,
                    'className': 'dt-body-center',
                    'render': function (data, type, full, meta) {
                        return '<input type="checkbox" class="chk_id" name="id[]" value="' + $('<div/>').text(data).html() + '">';
                    }
                },
                {
                    'targets': -1,
                    'searchable': false,
                    'orderable': false
                }
            ],
            'order': [[order, 'desc']],
            'bLengthChange': true,
            "fnDrawCallback": function () {
                $("a.fancybox").fancybox();
                $("#data-table-select-all").prop('checked', false);
            }
        });
    },

    init_default: function (element, url, options) {
        var element = $('#data-table');
        element.submit(function (e) {
            e.preventDefault();
        })
        table = element.DataTable({
            'ajax': {
                type: "POST",
                url: url_ajax_list,
                data: function (d) {
                    d[csrfName] = csrfValue;
                    return $.extend({}, d, dataFilter);
                }
            },
            fixedHeader: true,
            'dom': 'Bfrtip',
            // 'serverSide': true,
            'buttons': [],
            'columnDefs': [
                {
                    'targets': 'no-sort',
                    "orderable": false,
                    'className': 'text-center'
                },
                {
                    'targets': 0,
                    'visible': element.hasClass("no_check_all") ? false : true,
                    'searchable': false,
                    'orderable': false,
                    'className': 'dt-body-center',
                    'render': function (data, type, full, meta) {
                        return '<input type="checkbox" class="chk_id" name="id[]" value="' + $('<div/>').text(data).html() + '">';
                    }
                },
                {
                    'targets': -1,
                    'searchable': false,
                    'orderable': false
                }
            ],
            "columns": [
                null,
                null,
                {"orderDataType": "dom-text-numeric"},
                null,
                null,
                null,
            ],
            "search": {
                "regex": false
            },
            "language": {
                "url": "//cdn.datatables.net/plug-ins/1.10.19/i18n/Vietnamese.json"
            },
            "fnDrawCallback": function () {
                $("a.fancybox").fancybox();
                $('.dataTables_filter input')
                    .off()
                    .on('keyup', function () {
                        let value = this.value;
                        table.search(value.trim(), false, false).draw();
                    });
            }
        });

        /* Create an array with the values of all the input boxes in a column, parsed as numbers */
        $.fn.dataTable.ext.order['dom-text'] = function (settings, col) {
            return this.api().column(col, {order: 'index'}).nodes().map(function (td, i) {
                return $('input', td).val();
            });
        }
        /* Create an array with the values of all the input boxes in a column, parsed as numbers */
        $.fn.dataTable.ext.order['dom-text-numeric'] = function (settings, col) {
            return this.api().column(col, {order: 'index'}).nodes().map(function (td, i) {
                return $('input', td).val() * 1;
            });
        }
    }
};

function load_lang(name) {
    let s = document.createElement("script");
    s.type = "text/javascript";
    s.src = base_url + "lang/load/" + name;
    $("head").append(s);
}

function box_alert(className, content) {
    $('#error-box').remove();
    let html = ' <div class="alert ' + className + ' alert-dismissible" id="error-box">';
    html += '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>';
    html += '<h4><i class="icon fa fa-ban"></i> Thông báo</h4>'
    html += content;
    html += '</div>';
    return html;
}

function strip_tags(str, allowed_tags) {
    let key = '', allowed = false;
    let matches = [];
    let allowed_array = [];
    let allowed_tag = '';
    let i = 0;
    let k = '';
    let html = '';
    let replacer = function (search, replace, str) {
        return str.split(search).join(replace);
    };
    if (allowed_tags) {
        allowed_array = allowed_tags.match(/([a-zA-Z0-9]+)/gi);
    }
    str += '';
    matches = str.match(/(<\/?[\S][^>]*>)/gi);
    for (key in matches) {
        if (isNaN(key)) {
            continue;
        }
        html = matches[key].toString();
        allowed = false;

        for (k in allowed_array) {
            allowed_tag = allowed_array[k];
            i = -1;
            if (i != 0) {
                i = html.toLowerCase().indexOf('<' + allowed_tag + '>');
            }
            if (i != 0) {
                i = html.toLowerCase().indexOf('<' + allowed_tag + ' ');
            }
            if (i != 0) {
                i = html.toLowerCase().indexOf('</' + allowed_tag);
            }
            if (i == 0) {
                allowed = true;
                break;
            }
        }
        if (!allowed) {
            str = replacer(html, "", str);
        }
    }
    return str;
}

function number_format(number, decimals, dec_point, thousands_sep) {
    var n = number, c = isNaN(decimals = Math.abs(decimals)) ? 0 : decimals;
    var d = dec_point == undefined ? "." : dec_point;
    var t = thousands_sep == undefined ? "," : thousands_sep, s = n < 0 ? "-" : "";
    var i = parseInt(n = Math.abs(+n || 0).toFixed(c)) + "", j = (j = i.length) > 3 ? j % 3 : 0;

    return s + (j ? i.substr(0, j) + t : "") + i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + t) + (c ? d + Math.abs(n - i).toFixed(c).slice(2) : "");
}