

$(function () {

    load_lang(controller);
    DATATABLE.init();
    //bind checkbox table
    $('[name="username"]').keypress(function (e) {
        var txt = String.fromCharCode(e.which);
        if (!txt.match(/[^&\/\\#,+()^!`$~%'":*?<>{} àáạảãâầấậẩẫăằắặẳẵèéẹẻẽêềếệểễìíịỉĩòóọỏõôồốộổỗơờớợởỡùúụủũưừứựửữỳýỵỷỹđ]/g, '_')) {
            return false;
        }
    });
});

//ajax luu form
function save()
{
    let url = save_method === 'add' ? url_ajax_add : url_ajax_update;
    AJAX.save(url, $('#form'), reload_table);
}