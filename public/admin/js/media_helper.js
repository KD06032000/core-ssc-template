
function loadImageThumb(url, name) {

    if (typeof name === 'undefined' || name === '') {
        name = 'thumbnail';
    }
    let imageThumbnail = $(document).find('[name="' + name + '"]');

    if (!imageThumbnail.length) return;

    let link;
    if (url === '' || url == null) {
        link = '//via.placeholder.com/400x200';
    } else {
        link = media_url + url;
    }
    imageThumbnail.parent().find('a').attr('href', link);
    imageThumbnail.parent().find('img').attr('src', link);
}

function loadMultipleMedia(data) {
    if (data !== null && (data).length > 0) {
        $.each(JSON.parse(data), function (i, v) {
            $('#gallery').append(itemGallery(i + 1, v));
        });
    }
}

function loadMultipleMediaByName(name, element, data) {
    if (data !== null && (data).length > 0) {
        data = $.isArray(data) ? data : JSON.parse(data);
        $.each(data, function (i, v) {
            $('#' + element).append(itemGallery_by_name(name, i + 1, v));
        });
    }
}

function chooseImage(idElement) {
    moxman.browse({
        view: 'thumbs',
        fields: idElement,
        // extensions: 'jpg,jpeg,png,ico, gif',
        no_host: true,
        oninsert: function (args) {
            let url = args.focusedFile.url;
            let urlImageResponse = url.replace(script_name + media_name, '');
            let image = args.focusedFile.thumbnailUrl;
            $('#' + idElement).val(urlImageResponse).parent().find('img').attr('src', image);
        }
    });

}

function chooseMultipleMedia(idElement) {
    let count = parseInt($('#' + idElement).attr('data-id'));
    moxman.browse({
        view: 'thumbs',
        multiple: true,
        extensions: 'jpg,jpeg,gif,png,ico,pdf,doc,docx,xls,xlsx',
        no_host: true,
        oninsert: function (args) {
            $.each(args['files'], function (i, value) {
                count = count + 1;
                let url = value.url;
                let urlImageResponse = url.replace(script_name + media_name, '');
                let html = itemGallery(count, urlImageResponse);
                $('#' + idElement).append(html);
            });
            $('#' + idElement).attr('data-id', $('#' + idElement + ' .item_gallery:last').data('count'));
        }
    });
}

function chooseMultipleMediaName(idElement, name) {
    let count = parseInt($('#' + idElement).attr('data-id'));
    moxman.browse({
        view: 'thumbs',
        multiple: true,
        extensions: 'JPG,PNG,JPEG,jpg,jpeg,gif,png,ico,pdf,doc,docx,xls,xlsx',
        no_host: true,
        upload_auto_close: true,
        oninsert: function (args) {
            $.each(args['files'], function (i, value) {
                count = count + 1;
                let url = value.url;
                let urlImageResponse = url.replace(script_name + media_name, '');
                let html = itemGallery_by_name(name, count, urlImageResponse);
                $('#' + idElement).append(html);
            });
            $('#' + idElement).attr('data-id', $('#' + idElement + ' .item_gallery:last').data('count'));
        }
    });
}

function chooseFiles(idElement) {

    moxman.browse({
        path: '/media/files',
        view: 'thumbs',
        extensions: 'pdf,doc,docx,xls,xlsx, html',
        no_host: true,
        oninsert: function (args) {
            let url = args.focusedFile.url;
            let urlImageResponse = url.replace(script_name + media_name, '');
            $('#' + idElement).val(urlImageResponse);
        }
    });
}

function chooseMultipleFiles(idElement) {

    moxman.browse({
        path: '/media/brochure',
        view: 'thumbs',
        multiple: true,
        extensions: 'jpg,jpeg,gif,png,ico,pdf,doc,docx,xls,xlsx',
        no_host: true,
        oninsert: function (args) {
            let arrImage = [];
            $.each(args.files, function (i, val) {
                arrImage[i] = "brochure/" + val.name;
            });
            $('#' + idElement).val(JSON.stringify(arrImage))
        }
    });
}

function itemGallery(count, urlImageResponse) {
    return html = "<div class='item_gallery item_" + count + "' data-count='" + count + "'>" +
        "<img src='" + media_url + "/" + urlImageResponse + "' id='item_" + count + "' height='120' alt='image'>" +
        "<input type='hidden' name='album[]' value='" + urlImageResponse + "' >" +
        "<span class='fa fa-times removeInput' onclick='removeInputImage(this)'></span></div>";
}

function itemGallery_by_name(name, count, urlImageResponse) {
    if ($('[name="' + name + '"][value="' + urlImageResponse + '"]').length === 0) {
        return html = "<div class='item_gallery item_" + count + "' data-count='" + count + "'>" +
            "<a href='" + media_url + "/" + urlImageResponse + "' data-fancybox='gallery'>" +
            "<img src='" + media_url + "/" + urlImageResponse + "' id='item_" + count + "' height='120' alt='image' />" +
            "</a>" +
            "<input type='hidden' name='" + name + "' value='" + urlImageResponse + "' value='\" + urlImageResponse + \"'  >" +
            "<span class='fa fa-times removeInput' onclick='removeInputImage(this)'></span></div>";
    }
}

function addInputImage(idElement, i, value) {
    let element = $('#' + idElement);
    i = parseInt(i);
    i += 1;
    $.ajax({
        type: "POST",
        url: base_url + "admin/setting/ajax_load_item_album",
        data: {i: i, item: value},
        dataType: "html",
        success: function (inputImage) {
            element.append(inputImage);
            element.attr('data-id', i + 1);
            $('.fancybox').fancybox();
        }
    });
}

function removeInputImage(_this) {
    $(_this).parent().remove();
}