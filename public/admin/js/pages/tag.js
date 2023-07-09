
$(function () {
    //load lang
    load_lang(controller);
    DATATABLE.init();
    // TINYMCE.init();
    SEO.init();
});

function save() {
    let url = save_method === 'add' ? url_ajax_add : url_ajax_update;
    AJAX.save(url, $('#form'), reload_table);
}

//
// $(function () {
//     //load lang
//     load_lang('tag');
//     //load slug
//     init_slug('title', 'slug');
//     //load table ajax
//     init_data_table();
//     //bind checkbox table
//     $('.filter_language_code').on('change', function () {
//         filterDatatables({
//             filter_language_code: $('.filter_language_code').val(),
//         });
//     });
// });
//
// //form them moi
// function add_form() {
//     slug_disable = false;
//     save_method = 'add';
//     $('#modal_form').modal('show');
//     $('.modal-title').text('Thêm thẻ mới');
//     $('#modal_form').trigger("reset");
// }
//
// //form sua
// function edit_form(id) {
//     slug_disable = true;
//     save_method = 'update';
//     $('.tuyendung_').css('display', 'none');
//     //Ajax Load data from ajax
//     $.ajax({
//         url: url_ajax_edit + "/" + id,
//         type: "GET",
//         dataType: "JSON",
//         success: function (data) {
//             $.each(data, function (key, value) {
//                 $.each(value, function (k, v) {
//                     if ($.inArray(k, ['title', 'meta_title', 'description', 'meta_description', 'slug', 'meta_keyword']) !== -1) {
//                         $('[name="' + k + '[' + value.language_code + ']"]').val(v);
//                         if (tinymce.get(k + '_' + value.language_code)) tinymce.get(k + '_' + value.language_code).setContent(v);
//                     } else {
//                         if (k == 'view_mode' || k == 'type_user') {
//                             $('[name="' + k + '"][value="'+v+'"]').prop('checked',true);
//                             view_mode();
//                         } else {
//                             if (k == 'displayed_time') v = getFormattedDate(v);
//                             $('[name="' + k + '"]').val(v);
//                         }
//                     }
//                 });
//                 $('[name="meta_keyword[' + value.language_code + ']"]').tagsinput('add', value.meta_keyword);
//             });
//             loadImageThumb(data[0].thumbnail);
//             $('#modal_form').modal('show');
//             $('.modal-title').text('Sửa bài viết');
//
//         },
//         error: function (jqXHR, textStatus, errorThrown) {
//             alert(textStatus);
//             console.log(jqXHR);
//         }
//     });
// }
//
// //ajax luu form
// function save() {
//     $('#btnSave').text(language['btn_saving']); //change button text
//     $('#btnSave').attr('disabled', true); //set button disable
//     var url;
//
//     if (save_method == 'add') {
//         url = url_ajax_add;
//     } else {
//         url = url_ajax_update;
//     }
//
//     // ajax adding data to database
//     $.ajax({
//         url: url,
//         type: "POST",
//         data: $('#form').serialize(),
//         dataType: "JSON",
//         success: function (data) {
//             toastr[data.type](data.message);
//             if (data.type === "warning") {
//                 $('span.text-danger').remove();
//                 $.each(data.validation, function (i, val) {
//                     $('[name="' + i + '"]').closest('.form-group').append(val);
//                 })
//             } else {
//                 $('#modal_form').modal('hide');
//                 reload_table();
//             }
//             $('#btnSave').text(language['btn_save']);
//             $('#btnSave').attr('disabled', false);
//         }, error: function (jqXHR, textStatus, errorThrown) {
//             $('#btnSave').text(language['btn_save']);
//             $('#btnSave').attr('disabled', false);
//         }
//     });
// }