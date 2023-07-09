var csrfToken = $('meta[name="csrf-token"]');
var csrfName = csrfToken.attr('data-name');
var csrfValue = csrfToken.attr('content');
$(document).ajaxSend(function (elm, xhr, s) {
    if (typeof s.contentType != 'undefined' && s.contentType !== false) {
        if (typeof s != "undefined") {
            if (typeof s.data != "undefined") {
                if (s.data !== '') {
                    s.data += '&';
                }
                s.data += csrfName + '=' + csrfValue;
            }
        } else {
            s[data] = {csrfName: csrfToken};
        }
    }
});

$(function () {
    HOME.init();
    LIBRARY.init();
    POST.init();
    LOAD.init();
})

var HOME = {
    init: function () {
        if ($('#page_home').length) {
            LOAD.goto_tab();
            Helper._show_album('.media-library');
        }
    }
}

var POST = {
    init: function () {
        if ($('#page_cate_post').length) {

            let categoryId = $('#page_cate_post').data('id') || '';

            let data = {
                category_id: categoryId
            };

            if ($('.page_tags').length) {
                data.category_id = '';
                data.tag_id = categoryId;
            }

            let url = base_url + 'news/ajax_call';
            let list = new AjaxList(url, data, {tag_id: 'select[name="tag_id"]'});


            list.ajax({}, '#list');
            list.filter('#formFilter', true);
            list.loadMore();
        }
    }
}

var LIBRARY = {
    init: function () {
        if ($('#page_library').length > 0) {

            setTimeout(() => {
                let url = window.location.href.split('#');
                if (typeof url[1] !== 'undefined') {
                    $("html, body").stop().animate({scrollTop: $(`#tab_${url[1]}`).offset().top - 100}, 1000);
                    $(`#tab_${url[1]}`).trigger('click');
                }
            }, 200)

            let url = base_url + 'media/ajax_call';
            let categoryId = $('#tabCategory a.active').data('id') || 1;
            let list = new AjaxList(url, {category_id: categoryId});

            list.ajax({}, '#gallery');
            list.tab();
            list.loadMore();

            Helper._show_album('.media-library');
        }
    },
}

var LOAD = {
    init: function () {
        // this.active_menu('.d-nav', 1);
        // this.active_menu('.m-nav', 0);
        let _this = this;
        this.load_font();
        this.lazyload();
        // this.back_to_top();
        $('.goto_tab a').on("click", function () {
            _this.goto_tab();
        });
    },

    goto_tab() {
        setTimeout(() => {
            let url = window.location.href.split('#');
            if (typeof url[1] !== 'undefined') {
                $("html, body").stop().animate({scrollTop: $(`#tab_${url[1]}`).offset().top - 100}, 600);
            }
        }, 200)
    },

    back_to_top: function () {

        var back_top = $('.back-to-top'),
            offset = 800;

        back_top.click(function () {
            $("html, body").animate({scrollTop: 0}, 800);
            return false;
        });

        if (win.scrollTop() > offset) {
            back_top.fadeIn(200);
        }

        win.scroll(function () {
            if (win.scrollTop() > offset) back_top.fadeIn(200);
            else back_top.fadeOut(200);
        });
    },

    active_menu: function (element, activeALlParent, url) {
        if (url === undefined) {
            url = current_url.split('/#')[0];
        }
        let page_detail = $('.detail-page');
        if (page_detail.length) {
            url = page_detail.data('url')
        }
        const menuElementMain = $(element + ' a[href="' + url + '"]');
        if (activeALlParent === 1) {
            menuElementMain.parents('li').addClass('active');
        } else {
            menuElementMain.parent('li').addClass('active').parents('ul').css('display', 'block');
            menuElementMain.parents('li').children('.acd-drop').addClass('active');
            menuElementMain.parents('li').parents('ul').parents('li').addClass('active');
        }
    },

    load_font: function () {
        let path_font = [
            'public/fonts/Fontawesome/all.min.css',
            'public/css/fonts.css'
        ];
        let head = $('head');

        path_font.map(e => {
            let link = `<link rel="stylesheet" href="${base_url + e}" media="all">`
            head.append(link)
        });
    },

    lazyload: function () {
        $(".lazy").lazyload({
            effect: "fadeIn"
        });
    },

    sdkInit: function () {
        window.fbAsyncInit = function () {
            FB.init({
                appId: '276428663519832',
                cookie: true,
                xfbml: true,
                version: 'v11.0'
            });
        };
        (function (d, s, id) {
            var js, fjs = d.getElementsByTagName(s)[0];
            if (d.getElementById(id)) {
                return;
            }
            js = d.createElement(s);
            js.id = id;
            js.src = `https://connect.facebook.net/${lang}/sdk.js`;
            fjs.parentNode.insertBefore(js, fjs);
        }(document, 'script', 'facebook-jssdk'));
    }
};

toastr.options.escapeHtml = false;
toastr.options.closeButton = true;
toastr.options.positionClass = "toast-top-right";
toastr.options.timeOut = 5000;
toastr.options.showMethod = 'fadeIn';
toastr.options.hideMethod = 'fadeOut';

$(function () {
    $(".selectPackage").on("click", function () {
        $("select[name=package] option[data-id=" + $(this).data('package') + "]").prop('selected', true);
    });
    $('.btn-register').click(function(){
        let _this = $(this);
        let name = _this.closest('.item-price').find('.name').text();
        name ='Đăng ký gói sản phẩm ' +name.toUpperCase();
        $('[name="content"]').val(name);
    });

    
    formSubmit();
});


const formSubmit = function(){
    $("#button-register").on("click", function (event) {
        event.preventDefault();
        let button = $(this);
        let _this = $("#form-contact");
        let url = "https://apecsoft.asia/api/contact/landingApecKid";
        let data = _this.serialize();

        button.text('Đang gửi');
        button.prop('disabled', true);

        $.ajax({
            type: "POST",
            url: url,
            data: data,
            contentType: "application/json",
            dataType: "JSON",
            success: function (data) {
                _this.find(".form-group").find("span.text-danger").remove();
                if (data.type == "warning") {
                    $.each(data.validation, function (key, val) {
                        _this.find("input[name=" + key + "]").parent(".form-group").append(val);
                    });
                }
                if (data.type == "success") {
                    _this.trigger("reset");
                }
                toastr.clear();
                toastr[data.type](data.message);
            },
            complete: function (data) {
                button.text('Gửi đăng ký');
                button.prop('disabled', false);

            }
        });
    });
}