<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<style>
    .tree-template-mail{
        height: 374px;
    }

    #delete-file-template{
        float: right;
    }

    #delete-file-template i{
        color: #B22222;
    }
    .tree_template_mail {
        color: #F39C12;
    }

    .tree_template_mail a {
        color: #555;
        font-weight: bold;
    }
</style>
<section class="content">
    <div class="row">
        <div class="col-md-3">
            <div class="box box-primary">
                <div class="box-body">
                    <div class="tree-template-mail"></div>
                </div>
                <div class="box-footer">
                    <a class="btn btn-primary" href='javascript:void(0)' onclick='add_new_template()'>
                        <i class="fa fa-plus-square-o" aria-hidden="true"></i> Thêm mới template
                    </a>
                </div>
            </div>
        </div>
        <div class="col-md-9">
            <?=form_open('', ['id' => 'form_template', 'class' => '']);?>
                <div class="box box-info">
                    <div class="box-body">
                        <div class="form-group">
                            <textarea id="content_template" name="content_template" placeholder="Nội dung" class="tinymce-more form-control content_post" rows="10"></textarea>
                        </div>
                    
                        <div class="box-footer">
                            <a href="javascript:void(0)" class="btn btn-info" onclick="add_new_key_content()">Thêm mới từ khóa</a>
                            <a href="javascript:void(0)" class="btn btn-success pull-right" onclick="changeTemplateMail()">Lưu</a>
                        </div>
                    </div>
                </div>
                <div class="box box-info" id="fileName">
                    <div class="box-body">
                        <div class="form-group">
                            <label>Tên file: </label>
                            <input type="text" name="fileName" class="form-control">
                        </div>
                    </div>
                </div>
            <?=form_close();?>
        </div>
</section>
<script>
    var url_ajax_load_category = '<?php echo site_url('admin/category/ajax_load/post') ?>';
    var url_ajax_load = '<?php echo site_url('admin/category/ajax_load/post') ?>';
    var url_ajax_load_users = '<?php echo site_url('admin/users/ajax_load') ?>';
    var url_ajax_load_tag = '<?php echo site_url('admin/tag/ajax_load') ?>';
    var url_ajax_load_tree = '<?php echo site_url('admin/template_mail/tree_template_mail') ?>';
    var url_ajax_load_template_mail = '<?php echo site_url('admin/template_mail/url_ajax_load_template_mail') ?>';
    var url_ajax_remove_template_mail = '<?php echo site_url('admin/template_mail/url_ajax_remove_template_mail') ?>';
</script>