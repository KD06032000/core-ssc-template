$(function () {
    //load lang
    load_lang('system_menu');
    loadParentMenu();
    var iconPickerOptions = {searchText: 'Search icon...'};
    var iconClass = 'fa';
    var sortableListOptions = {
        placeholderCss: {'background-color': '#fff'}
    };
    var editor = new MenuEditor('myEditor', {listOptions: sortableListOptions, iconPicker: iconPickerOptions, iconClass: iconClass, labelEdit: 'Edit'});
    editor.setForm($('#frmEdit'));
    editor.setUpdateButton($('#btnUpdate'));
    load_menu(editor);

    $("#btnUpdate").click(function(){
        let data = {};
        $('#frmEdit').find('.item-menu').each(function(){
            data[$(this).attr('name')] = $(this).val();
        });
        save(data, 'update');
        editor.resetForm();
        $('#icon').val('');
        load_menu(editor);

    });
    
    $('#btnReset').click(function () {
        editor.resetForm();
        $('#icon').val('');
        loadParentMenu();
    });

    $('#btnAdd').click(function(){
        let data = {};
        $('#frmEdit').find('.item-menu').each(function(){
            data[$(this).attr('name')] = $(this).val();
        });
        save(data, 'add');
        editor.resetForm();
        $('#icon').val('');
        loadParentMenu();
        load_menu(editor);
    });
    $(document).on('click', '.btnRemove', function () {
        let itemEditing = $(this).closest('li');
        editor.setFormData(itemEditing);
        let id = $('input[name=id]').val();
        swal({
            title: language['mess_alert_title'],
            text: language['mess_confirm_delete'],
            type: "warning",
            showCancelButton: true,
            confirmButtonClass: "btn-danger",
            confirmButtonText: language['btn_yes'],
            cancelButtonText: language['btn_no'],
            closeOnConfirm: true
        }, function(isConfirm){
            if(isConfirm) {
                delete_item_menu(id);
                editor.remove();
                $('#icon').val('');
                load_menu(editor);
            } else {
                editor.resetForm();
                $('#icon').val('');
            }
        });
    });
});

function load_menu(editor) {
    $.ajax({
        url : url_ajax_list,
        type: "GET",
        dataType: "JSON",
        success: function(data) {
            (function filter(obj) {
                $.each(obj, function(key, value){
                    if (value == "" && key === 'children'){
                        delete obj[key];
                    } else if (Object.prototype.toString.call(value) === '[object Object]') {
                        filter(value);
                    } else if ($.isArray(value)) {
                        $.each(value, function (k,v) { filter(v); });
                    }
                });
            })(data);
            editor.update();
            editor.setData(data);
        },
        error: function (jqXHR, textStatus, errorThrown)
        {
            console.log(jqXHR);
        }
    });
}

//ajax luu form
function save(data, method){
    //ajax adding data to database
    let url = null;
    if (method === 'add') {
        url = url_ajax_add;
    } else {
        url = url_ajax_update;
    }
    $.ajax({
        url : url,
        type: "POST",
        data: data,
        dataType: "JSON",
        success: function(data){
            toastr[data.type](data.message);
            if(data.type === "warning"){
                $('span.text-danger').remove();
                $.each(data.validation, function (i, val) {
                    $('[name="' + i + '"]').closest('.form-group .col-sm-10').addClass('has-error').append(val);
                })
            }
        }, error: function (jqXHR, textStatus, errorThrown) {
            console.log(jqXHR);
            console.log(textStatus);
            console.log(errorThrown);
        }
    });
}

function delete_item_menu(id) {
    var csrfName = csrfToken.attr('data-name');
    var csrfValue = csrfToken.attr('content');
    $.ajax({
        url : url_ajax_delete+"/"+id,
        type: "POST",
        dataType: "JSON",
        data:{csrfName:csrfValue},
        success: function(data) {
            if(data.type){
                toastr[data.type](data.message);
            }
            $('#frmEdit').find('input[name=id]').val('');
        },
        error: function (jqXHR, textStatus, errorThrown)
        {
            alert(textStatus);
            console.log(jqXHR);
        }
    });
}

function loadParentMenu() {
    let select = $('#parent_id');
    $.ajax({
        url: url_ajax_load_parent_menu,
    }).then(function(options) {
        let obj = JSON.parse(options);
        $.each(obj, function(val, text) {
            select.append(new Option(text.text, text.id));
        });
    });
}
