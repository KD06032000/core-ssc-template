$(function () {
    optionTinyMCEMore.height = 300;//Đặt lại height
    optionTinyMCEMore.plugins = [
        "advlist autolink autosave link image lists charmap print preview hr anchor pagebreak spellchecker",
        "  visualblocks visualchars code fullscreen  nonbreaking",
        "table contextmenu directionality emoticons textcolor paste textcolor textpattern link code fullpage",
    ]; //Thêm plugin fullpage
    $("#form_template").addClass('hidden');//Ẩn form
    $("#fileName").addClass('hidden');//Ẩn input file name
    tree_template_mail();
    
});

//Đọc nội dung từ file template ra tinycme
function load_file_template_mail(fileName) {
    $("#fileName").addClass('hidden');//Ẩn input file name
    $("#form_template").removeClass('hidden');//Hiện form
    if(tinyMCE.activeEditor == null) {
        tinymce.init(optionTinyMCEMore);//Thực thi tinymce
    }
    $.ajax({
        url : url_ajax_load_template_mail + '/' + fileName,
        type: 'GET',
        dataType : "JSON",
        success: function (response) {
            tinyMCE.activeEditor.setContent(response);//Đặt nội dung cho tinycme
            $("#fileName").find('input').val(fileName);//Đặt gán tên form là tên file chọn
        }
    });
}

var countNewTemplate = 0;//Sử dụng như biến global
//Thêm một input nhập từ khóa mới
function add_new_key_content() {
    countNewTemplate ++;
    var row = "<div class=\"box box-info\" id='keyword_" + countNewTemplate +"'>" +
        "           <div class=\"box-body\">" +
    "                   <div class=\"col-md-4 form-group\">\n" +
    "                       <input type=\"text\" class=\"form-control\" name='keyword_" + countNewTemplate +"' placeholder=\"Từ khóa\">\n" +
    "                   </div>\n" +
    "                   <div class=\"col-md-1 form-group text-center\"> - " +
    "                   </div>\n" +
    "                   <div class=\"col-md-6 form-group\">\n" +
    "                       <input type=\"text\" class=\"form-control\" name='content_keyword_"+ countNewTemplate +"' placeholder=\"Nội dung từ khóa\">\n" +
    "                   </div>\n" +
    "                   <div class=\"col-md-1 form-group\">\n" +
    "                       <a href='javascript:void(0)' onclick=\"removeRow(" + "'keyword_" + countNewTemplate + "'" + ")\"><i class=\"fa fa-times\" aria-hidden=\"true\"></i></a>\n" +
    "                   </div>\n" +
    "           </div>" +
    "       </div>" +
    "   </div>";
    $("#form_template").append(row);
}

//Xóa một dòng từ khóa mới
function removeRow(name) {
    $("#" + name).remove();//Xóa box chứa dòng thêm từ khóa
}

//Thay đổi nội dung của template
function tree_template_mail() {

    $.ajax({
        url : url_ajax_load_tree,
        type: 'GET',
        dataType : "JSON",
        success: function (response) {
            var tree = "<ul class=\"tree_template_mail\">";
            tree += "<li style=\"font-size:23px;\"><i class=\"fa fa-folder-o\" aria-hidden=\"true\"></i><small>Danh sách file template</small></li>";
            for (let itemTree of response){
                tree += "<li style='padding-left: 15px;'>";
                tree += " ----- ";
                tree += "<a href='javascript:void(0)' onclick=load_file_template_mail(" + "'" + itemTree + "'" + ")>" + itemTree + "</a>";
                tree += "<a href='javascript:void(0)' id=\"delete-file-template\" onclick=remove_file_template_mail(" + "'" + itemTree + "'" + ")> <i class=\"fa fa-times\" aria-hidden=\"true\"></i></a>";
                tree += "</li>";
            }
            tree += "</ul>";
            $(".tree-template-mail").html(tree);
        }
    });
}

var addNew = false;//Tạo biến check thêm mới template - Sử dụng như biến global
//Thêm mới template
function add_new_template() {
    if(tinymce.activeEditor == null) {
        tinymce.init(optionTinyMCEMore);//Thực thi tinymce
    }
    setTimeout(function(){ tinymce.activeEditor.setContent(''); }, 1000);//Đặt rỗng tinymce
    $("#form_template").removeClass('hidden');//Hiện form
    $("#fileName").removeClass('hidden');//Hiện file name input
    $("#fileName").find('input').val('');//Đặt rỗng file name
    addNew = true;
}

//Xóa 1 tree file
function remove_file_template_mail(fileName) {
    $.ajax({
        url : url_ajax_remove_template_mail + '/' + fileName,
        type: 'GET',
        dataType : "JSON",
        success: function (response) {
            toastr[response.message.type](response.message.message);
            tree_template_mail();//Load lại tree file
            tinymce.activeEditor.setContent('');//Đặt rỗng tinymce
        }
    });
}

//Thay đổi nội dung template mail
function changeTemplateMail() {
    let form_id = "#form_template";//Lấy id form
    let template_file_name = $("#fileName").find('input').val() ? $("#fileName").find('input').val() : "null";//Lấy tên file
    $("#content_template").val(tinymce.activeEditor.getContent());//Set nội dung trong tinycme vào textarea
    
    let url = url_ajax_update + "/" + template_file_name;
    if(addNew == true){//Kiểm tra thêm mới template
        url += "/addNew";
    }
    $.ajax({
        url : url,
        type: 'POST',
        dataType : "JSON",
        data: $(form_id).serialize(),
        success: function (response) {
            toastr[response.message.type](response.message.message);
            load_file_template_mail(template_file_name);//Load lại file template
            tree_template_mail();//Load lại tree file
        }
    });
}