class Ajax {

    constructor(hasFile = false) {
        this.hasFile = hasFile;
    }

    call(url, data, type = 'POST') {
        return new Promise((resolve, reject) => {

            let options = {
                url: url,
                type: type,
                data: data,
                datatype: 'JSON',
                beforeSend: function () {

                },
                success: function (data) {
                    resolve(data);
                },
                error: function (xhr) {
                    reject(xhr);
                },
                complete: function () {

                },
            };

            if (this.hasFile) {
                options = {
                    ...options, ...{
                        cache: false,
                        processData: false,
                        contentType: false,
                    }
                }
            }

            $.ajax(options);
        })
    }
}

class Form {

    static getData(form) {
        return $(form).serializeArray().reduce(function (obj, item) {
            obj[item.name] = item.value;
            return obj;
        }, {});
    }

    static fill(boxForm, data = {}, optionsSelect2 = {}) {

        return new Promise((resolve => {
            Object.keys(data).map(function (key, index) {

                let boxInput = $(boxForm).find(`[name="${key}"]`);

                switch (boxInput.attr('type')) {
                    case 'checkbox':
                        boxInput[0].checked = data[key];
                        break;
                    default:
                        boxInput.val(data[key]);
                        break;
                }

                $(boxForm).find(`[name="${key}"]`).val(data[key]);

                if (optionsSelect2 && optionsSelect2[key]) {

                    data[key].id = data[key]._id
                    Select.call(optionsSelect2[key], [data[key]]);

                }

            });

            resolve(data);
        }));

    }

    static validate(form, err) {

        // use Toast JS
        Message.show(err.responseJSON.message || '');

        // Ren err HTML
        $(form).find('.form-group .invalid-feedback').hide();
        let errors = err.responseJSON.errors;

        Object.keys(errors).map(function (key, index) {
            let htmlError = `<p> ${errors[key][0]} </p>`
            $(form)
                .find(`[name="${key}"]`)
                .closest('.form-group')
                .find('.invalid-feedback')
                .show()
                .html(htmlError);
        });
    }

    static reset(form) {
        $(form)[0].reset();
        $(form).find('.invalid-feedback').html('');
    }

    static post(form, callback) {

        let _this = this;

        if (!$(document).find(form).length) return;

        $(document).on('submit', form, function (event) {
            event.preventDefault();

            let data;
            let hasFile = false;
            let url = $(this).attr('action');
            let _form = $(this);

            _form.find('button[type="submit"]').attr('disabled', 'disabled')

            if ($(this).attr('enctype') === 'multipart/form-data') {
                data = new FormData(this);
                hasFile = true
            } else {
                data = Form.getData(this);
            }

            new Ajax(hasFile).call(url, data)
                .then((res) => {
                    _form.find('button[type="submit"]').removeAttr('disabled');
                    //user toast
                    if (typeof callback === 'function') {
                        callback(res, _this);
                    } else {
                        Message.show(data.message || '', false);
                    }
                })
                .catch(err => {
                    _form.find('button[type="submit"]').removeAttr('disabled');
                    Form.validate(form, err);
                });
        });
    }
}

class Message {

    static init() {
        toastr.clear();

        toastr.options = {
            closeButton: true,
            positionClass: 'toast-bottom-right',
            onclick: null,
            showDuration: 1000,
            hideDuration: 1000,
            timeOut: 10000,
            extendedTimeOut: 1000,
            showEasing: 'swing',
            hideEasing: 'linear',
            showMethod: 'fadeIn',
            hideMethod: 'fadeOut'
        };
    }

    static show(message, error = true, type = '') {
        Message.init();

        let messageType = type != '' ? type : (error ? 'error' : 'success');

        toastr[messageType](message);
    }
}

class Select {

    static init() {
        $('.select2').select2({
            allowClear: true,
            minimumResultsForSearch: 1,
            placeholder: $(this).data("placeholder") || '',
        });

        $('.select2_not_search').select2({
            allowClear: true,
            minimumResultsForSearch: -1,
        });
    }

    static load(options, lang) {

        Object.keys(options).map(function (key, index) {
            Select.call(options[key], [], lang);
        });
    }

    static call(options, dataSelected, lang) {

        options.selector.select2({
            allowClear: true,
            placeholder: options.placeholder || options.selector.data("placeholder"),
            multiple: options.multiple,
            data: dataSelected,
            minimumResultsForSearch: options.hideSearch ? -1 : 1,
            language: lang,
            ajax: {
                url: options.url,
                dataType: 'json',
                delay: 250,
                processResults: function (data) {
                    return {
                        results: data
                    };
                },
                cache: true
            }
        }).on('select2:close', function (e) {
            // $(this).trigger('focus');
        })

        if (typeof dataSelected !== 'undefined') options.selector.find('> option').prop("selected", "selected").trigger("change");
    }

}

class AjaxList {

    constructor(url, data = {}, configFilter = {}, dataType = 'GET') {
        this.data = {
            'category_id': (typeof categoryId != 'undefined' ? categoryId : 0),
        }
        if (typeof data.category_id !== "undefined") {
            delete this.data.category_id
        }
        this.data = {...this.data, ...data};
        this.url = url;
        this.configInput = configFilter;
        this.dataType = dataType;
        this.old = {};
    }

    run(boxList = '') {

        if (typeof boxList === "string" && boxList.length > 0) {
            this.boxList = boxList;
        }

        this.ajax(this.data);
        this.pagination();
        this.tab();
        this.filter();
    }

    runDone(data) {
    }

    done(func) {
        this.runDone = func;
    }

    ajax(dataAjax = {}, boxList = '') {
        let _this = this;

        if (jQuery.isEmptyObject(dataAjax)) {
            dataAjax = _this.data;
        }

        if (typeof boxList === "string" && boxList.length > 0) {
            _this.boxList = boxList;
        }

        let key_old = Object.entries(_this.data).map(([key, val]) => `${key}=${val}`).join('&');

        if (typeof _this.old[key_old] !== "undefined") {
            $(_this.boxList).html(_this.old[key_old] || '');
            _this.runDone([]);
            return;
        }

        return new Ajax()
            .call(_this.url, dataAjax, _this.dataType)
            .then((res) => {
                res = JSON.parse(res);
                _this.old[key_old] = res.data.html;
                if (typeof _this.boxList !== "undefined") {
                    $(_this.boxList).html(res.data.html || '');
                }
                _this.runDone(res);
                return res;
            })
    }

    pagination() {
        let _this = this;
        $(document).on("click", ".pagination a", function (event) {
            event.preventDefault();
            _this.data.page = $(this).attr('href').split('page=')[1] || 0;

            _this.ajax(_this.data);
        });
    }

    // block TAB required id "tabCategory"
    tab(idBox = '#tabCategory') {
        let _this = this;
        $(idBox).on("click", "a", function (event) {
            event.preventDefault();
            let id = $(this).data('id');
            if (parseInt(_this.data.category_id) !== parseInt(id)) {
                $(this).closest(idBox).find('a').removeClass('active');
                $(this).addClass('active');
                delete _this.data.page;
                _this.data.category_id = id;
                _this.ajax(_this.data);
            }
        });
    }

    // block FORM required id "formFilter"
    filter(idBox = '#formFilter', isAuto = true) {
        let _this = this;

        if (isAuto) {

            if (!jQuery.isEmptyObject(_this.configInput)) {

                Object.keys(_this.configInput).map(function (key, index) {

                    $(idBox).on("change", _this.configInput[key], function (event) {

                        delete _this.data.page;
                        _this.data[key] = $(this).val();
                        _this.ajax(_this.data);
                    });
                });
            }
        } else {

            $(document).on("submit", idBox, function (event) {
                event.preventDefault();

                let data = $(this).serializeArray();

                new Promise((resolve => {
                    data.map((e, i) => {
                        _this.data[e.name] = e.value;
                    });
                    delete _this.data.page;
                    resolve(_this.data);
                })).then((res) => {
                    _this.ajax(res);
                });

            })

        }
    }

    // block BUTTON required id "buttonLoadMore"
    loadMore(idBox = '#buttonLoadMore') {
        let _this = this;

        $(document).on("click", idBox, function (event) {
            event.preventDefault();
            _this.data.page = $(this).data('page');
            _this.ajax(_this.data);
        })

    }
}

class Helper {
    static buildQueryString(url, parameters) {
        Object.keys(parameters).forEach((key) => {
            (parameters[key] == null || parameters[key].length <= 0) && delete parameters[key]
        });
        let esc = encodeURIComponent;
        let queryString = Object.keys(parameters)
            .map(k => esc(k) + '=' + esc(parameters[k]))
            .join('&');
        if (queryString.length > 0) {
            url = url + "?" + queryString;
        }
        return url;
    }

    static _show_album(id) {
        $(document).on('click', id, function (e) {
            e.preventDefault();
            let id = $(this).data('id');
            console.log(album[id])
            var data = [];
            if (typeof album[id] === "object") {
                album[id].map((e) => {
                    var obj = {
                        src: media_url + e,
                    }
                    data.push(obj);
                });
            } else {
                if (album[id].length > 0) {
                    data.push({
                        src: album[id]
                    })
                }
            }

            $.fancybox.open(data, {
                loop: true,
                thumbs: {autoStart: false},
                height: 600,
                autoSize: false
            });
        })
    }
}
