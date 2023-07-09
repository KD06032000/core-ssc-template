
// SELECT2
function load_select2_ajax(options, dataSelected) {
    let params_ajax = {
        url: options.url,
        dataType: 'json',
        delay: 250,
        processResults: function (data) {
            return {
                results: data
            };
        },
        cache: true
    };
    if (typeof options.ajax === 'object') {
        params_ajax = Object.assign(params_ajax, options.ajax)
    }
    options.selector.select2({
        allowClear: true,
        placeholder: options.placeholder,
        multiple: options.multiple,
        minimumResultsForSearch: options['hide_search'] ? -1 : 1,
        data: dataSelected,
        ajax: params_ajax
    });
    if (typeof dataSelected !== 'undefined') options.selector.find('> option').prop("selected", "selected").trigger("change");
}

function load_select2_ajax_empty(options) {
    let params_ajax = {
        url: options.url,
        dataType: 'json',
        delay: 250,
        processResults: function (data) {
            return {
                results: data
            };
        },
        cache: true
    };
    if (typeof options.ajax === 'object') {
        params_ajax = Object.assign(params_ajax, options.ajax)
    }
    options.selector.select2({
        allowClear: true,
        placeholder: options.placeholder,
        multiple: options.multiple,
        minimumResultsForSearch: options['hide_search'] ? -1 : 1,
        ajax: params_ajax
    });
}
// SELECT2


// DATATABLE
function filterDatatables(data) {
    dataFilter = data;
    reload_table();
}

function init_checkbox_table() {
    // checkbox check all
    $('#data-table-select-all').on('click', function () {
        var rows = table.rows({'search': 'applied'}).nodes();
        $('input[type="checkbox"]', rows).prop('checked', this.checked);
    });

    $('#data-table tbody').on('change', 'input[type="checkbox"]', function () {
        if (!this.checked) {
            var el = $('#data-table-select-all').get(0);
            if (el && el.checked && ('indeterminate' in el)) {
                el.indeterminate = true;
            }
        }
    });
}

function init_filter_table() {
    $('#form_filter .item').on('change keyup', function () {
        $('#form_filter').trigger('submit');
    })
}

function reload_table() {
    table.ajax.reload(null, false); //reload datatable ajax
}
// DATATABLE


// DELETE TABLE
function swal_delete() {
    return {
        title: language['mess_alert_title'],
        text: language['mess_confirm_delete'],
        type: "warning",
        showCancelButton: true,
        confirmButtonClass: "btn-danger",
        confirmButtonText: language['btn_yes'],
        cancelButtonText: language['btn_no'],
        closeOnConfirm: false
    };
}
// DELETE TABLE


// SEO
function checkSEOTitle(_this) {
    _this = $(_this);
    var c_title = _this.val().length;
    var l_title = $("span.count-title");
    $(l_title).html(c_title);
    if (c_title >= 40 && c_title <= 80) {
        _this.css({"color": colors[2], border: "3px solid" + colors[2]});
        $(l_title).css("color", colors[2])
    } else if (c_title >= 25 && c_title < 40) {
        _this.css({"color": colors[1], border: "3px solid" + colors[1]});
        $(l_title).css("color", colors[1])
    } else {
        _this.css({"color": colors[0], border: "3px solid" + colors[0]});
        $(l_title).css("color", colors[0])
    }
    var seo_title = _this.val();
    $(".gg-title").html(seo_title);
}

function checkSEODesc(_this) {
    _this = $(_this);
    var c_desc = _this.val().length;
    var l_desc = $(".count-desc");
    $(l_desc).html(c_desc);
    if (c_desc >= 120 && c_desc <= 150) {
        _this.css({"color": colors[2], border: "3px solid" + colors[2]});
        $(l_desc).css("color", colors[2])
    } else if (c_desc >= 90 && c_desc < 120) {
        _this.css({"color": colors[1], border: "3px solid" + colors[1]});
        $(l_desc).css("color", colors[1])
    } else {
        _this.css({"color": colors[0], border: "3px solid" + colors[0]});
        $(l_desc).css("color", colors[0])
    }
    var seo_desc = _this.val();
    $(".gg-desc").html(seo_desc);
}

function checkSEOKeyword(_this) {
    _this = $(_this);
    var c_key = _this.val().length;
    var l_key = $("span.count-key");
    $(l_key).html(c_key);
    if (c_key >= 10 && c_key <= 15) {
        _this.css({"color": colors[2], border: "3px solid" + colors[2]});
        $(l_key).css("color", colors[2])
    } else if (c_key >= 6 && c_key < 10) {
        _this.css({"color": colors[1], border: "3px solid" + colors[1]});
        $(l_key).css("color", colors[1])
    } else {
        _this.css({"color": colors[0], border: "3px solid" + colors[0]});
        $(l_key).css("color", colors[0])
    }
    var seo_key = _this.val();
    $(".gg-result").val(seo_key);
}
// SEO


// SLUG
function create_slug(title, ele) {
    if (slug_disable) {
        return;
    }
    $(ele).val(ren_slug(title));
}

function ren_slug(title) {
    slug = title.toLowerCase();
    slug = slug.replace(/á|à|ả|ạ|ã|ă|ắ|ằ|ẳ|ẵ|ặ|â|ấ|ầ|ẩ|ẫ|ậ/gi, 'a');
    slug = slug.replace(/é|è|ẻ|ẽ|ẹ|ê|ế|ề|ể|ễ|ệ/gi, 'e');
    slug = slug.replace(/i|í|ì|ỉ|ĩ|ị/gi, 'i');
    slug = slug.replace(/ó|ò|ỏ|õ|ọ|ô|ố|ồ|ổ|ỗ|ộ|ơ|ớ|ờ|ở|ỡ|ợ/gi, 'o');
    slug = slug.replace(/ú|ù|ủ|ũ|ụ|ư|ứ|ừ|ử|ữ|ự/gi, 'u');
    slug = slug.replace(/ý|ỳ|ỷ|ỹ|ỵ/gi, 'y');
    slug = slug.replace(/đ/gi, 'd');
    //slug = slug.replace(/\`|\~|\!|\@|\#|\||\$|\%|\^|\&|\*|\(|\)|\+|\=|\,|\.|\/|\?|\>|\<|\'|\"|\:|\;|_/gi, '');
    slug = slug.replace(/[^a-zA-Z0-9 ]/g, "");
    slug = slug.replace(/ /gi, "-");
    slug = slug.replace(/\-\-\-\-\-/gi, '-');
    slug = slug.replace(/\-\-\-\-/gi, '-');
    slug = slug.replace(/\-\-\-/gi, '-');
    slug = slug.replace(/\-\-/gi, '-');
    slug = '@' + slug + '@';
    slug = slug.replace(/\@\-|\-\@|\@/gi, '');
    return slug;
}

function init_slug(listen, target) {
    $.each(lang_cnf, function (code) {
        let id = $('#' + listen + '_' + code);
        let meta_title = $('#meta_title_' + code);
        //su kien paste
        id.on('paste', function () {
            setTimeout(function () {
                create_slug(id.val(), '#' + target + '_' + code);
                if (!slug_disable) {
                    meta_title.val(id.val());
                    checkSEOTitle(meta_title);
                }
            }, 500);
        });
        //su kien keyup
        id.on('keyup', function () {
            create_slug(id.val(), '#' + target + '_' + code);
            if (!slug_disable) {
                meta_title.val(id.val());
                checkSEOTitle(meta_title);
            }
        });
    });
}
// SLUG



function getFormattedDate(date, element) {
    if (date !== '' && date !== '0000-00-00') {
        if (typeof element == 'undefined') element = $('.datepicker');
        else element = $('[name="' + element + '"]');
        element.datepicker("update", new Date(date));
        date = new Date(date);
        let year = date.getFullYear();
        let month = (1 + date.getMonth()).toString().padStart(2, '0');
        let day = date.getDate().toString().padStart(2, '0');
        return day + '/' + month + '/' + year;
    } else {
        return '';
    }
}

function btnFly() {
    $("body").append('<style>.modal-footer-top-button{position:fixed;z-index:999999;top:0;right:0px;}</style>');
    var diaLogScroll = $('#modal_form'),
        diaLogScrollHeight = diaLogScroll.find('.modal-header').height(),
        diaLogScrollFooter = diaLogScroll.find('.modal-footer');
    diaLogScroll.find('.modal-footer').addClass('modal-footer-top-button');
    diaLogScroll.scroll(function () {
        if (diaLogScroll.scrollTop() <= diaLogScrollHeight + 35) {
            diaLogScrollFooter.addClass('modal-footer-top-button');
        } else {
            diaLogScrollFooter.removeClass('modal-footer-top-button');
        }
    });
}
