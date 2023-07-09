<?php

defined('BASEPATH') or exit('No direct script access allowed');
$controller = $this->router->fetch_class();
$method = $this->router->fetch_method();
?>

<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-body">
                    <div class="row">
                        <div class="col-sm-7 col-xs-12">
                            <div class="row">
                                <form action="" id="form_filter" method="post">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <div class="input-group">
                                                <span class="input-group-addon"><i class="fa fa-filter"></i></span>
                                                <select class="form-control select2 item" name="tag_id[]"
                                                        style="max-width: 100%;" tabindex="-1" aria-hidden="true">
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <?php $this->load->view($this->template_path . "_block/button") ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-body">
                    <form action="" id="form-table" method="post">
                        <input type="hidden" value="0" name="msg"/>
                        <table id="data-table" class="table table-bordered table-striped">
                            <thead>
                            <tr>
                                <th><input type="checkbox" name="select_all" value="1" id="data-table-select-all"></th>
                                <th>ID</th>
                                <th>Link hiện tại</th>
                                <th>Link chuyển hướng</th>
                                <th>Tags</th>
                                <th>Diễn giải</th>
                                <th>Ảnh đại diện</th>
                                <th><?php echo lang('text_action'); ?></th>
                            </tr>
                            </thead>
                        </table>
                    </form>
                </div>
                <!-- /.box-body -->
            </div>
            <!-- /.box -->
        </div>
        <!-- /.col -->
    </div>
    <!-- /.row -->
</section>
<!-- /.content -->


<div class="modal fade" id="modal_form" role="dialog">
    <div class="modal-dialog" style="width: 60%">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                </button>
                <h3 class="modal-title" id="title-form"></h3>
            </div>
            <div class="modal-body form">
                <?php echo form_open('', ['id' => 'form', 'class' => '']) ?>
                <input type="hidden" name="id" value="0">
                <!-- Custom Tabs -->
                <div class="nav-tabs-custom">
                    <ul class="nav nav-tabs">
                        <li class="active">
                            <a href="#tab_common" data-toggle="tab">Thông tin chung</a>
                        </li>
                        <li>
                            <a href="#tab_other" data-toggle="tab">Meta SEO</a>
                        </li>
                        <li>
                            <a href="#tab_image" data-toggle="tab"><?php echo lang('tab_image'); ?></a>
                        </li>
                    </ul>
                    <div class="tab-content">

                        <div class="tab-pane active" id="tab_common">
                            <?php
                            $list = [
                                ['name' => 'category_id[]', 'title' => 'Tag liên kết', 'type' => 'select2', 'required' => false],
                                ['name' => 'redirect_link', 'title' => 'Link chuyển hướng', 'type' => 'text', 'required' => true,],
                                ['name' => 'link_prefix', 'title' => 'Link rút gọn', 'type' => 'link', 'required' => true],
                                ['name' => 'description', 'title' => 'Diễn giải', 'type' => 'textarea', 'required' => false, 'tinymce' => false],
                                ['name' => 'time', 'title' => 'Thời gian chuyển trang', 'type' => 'number', 'required' => false, 'value' => 1],
                            ];

                            $this->load->view($this->template_path . '_block/_item_form', ['list' => $list, 'class' => 'col-md-12']);
                            ?>
                        </div>
                        <div class="tab-pane" id="tab_other">
                            <?php
                            $list = [
                                ['name' => 'meta_title', 'title' => 'Meta Title', 'type' => 'text', 'required' => false,],
                                ['name' => 'meta_description', 'title' => 'Meta Description', 'type' => 'textarea', 'required' => false, 'tinymce' => false],
                            ];

                            $this->load->view($this->template_path . '_block/_item_form', ['list' => $list, 'class' => 'col-md-12']);
                            ?>
                        </div>
                        <div class="tab-pane" id="tab_image">
                            <div class="box-body">
                                <?php $this->load->view($this->template_path . '_block/input_media') ?>
                                <?php $this->load->view($this->template_path . '_block/input_media', [
                                        'id_image' => 'form_banner',
                                        'name_image' => 'banner',
                                        'value_image' => '',
                                        'label_image' => 'Hình ảnh quảng cáo',
                                ]) ?>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- nav-tabs-custom -->
                <?php echo form_close() ?>
            </div>
            <div class="modal-footer">
                <button type="button" id="btnSave" onclick="save()"
                        class="btn btn-primary pull-left"><?php echo lang('btn_save'); ?></button>
                <button type="button" class="btn btn-danger"
                        data-dismiss="modal"><?php echo lang('btn_cancel'); ?></button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>

<script>
    var url_ajax_load_category = '<?= site_url('admin/category/ajax_load/redirect') ?>';
    var url_ajax_load = '<?= site_url('admin/category/ajax_load/redirect') ?>';
</script>