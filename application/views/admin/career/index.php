<?php

defined('BASEPATH') or exit('No direct script access allowed'); ?>
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
                                        <?php $this->load->view($this->template_path . "_block/where_status") ?>
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
                    <table id="data-table" class="table table-bordered table-striped">
                        <thead>
                        <tr>
                            <th><input type="checkbox" name="select_all" value="1" id="data-table-select-all"></th>
                            <th><?php echo lang('text_id'); ?></th>
                            <th>Tên  Trường</th>
                            <th>Trạng thái</th>
                            <th>Ngày hết hạn</th>
                            <th><?php echo lang('text_created_time'); ?></th>
                            <?php showColumnAction(); ?>
                        </tr>
                        </thead>
                    </table>
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
<!-- Bootstrap modal -->
<div class="modal fade" id="modal_form" role="dialog">
    <div class="modal-dialog" style="width: 80%">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                </button>
                <h3 class="modal-title" id="title-form"><?php echo lang('heading_title_add'); ?></h3>
            </div>
            <div class="modal-body form">
                <?php echo form_open('', ['id' => 'form', 'class' => '']) ?>
                <input type="hidden" name="id" value="0">
                <!-- Custom Tabs -->
                <div class="nav-tabs-custom">

                    <ul class="nav nav-tabs">
                        <li class="active"><a href="#tab_language" data-toggle="tab">Ngôn ngữ</a></li>
                        <li><a href="#tab_info" data-toggle="tab">Thông tin</a></li>
                        <li><a href="#tab_image" data-toggle="tab"><?php echo lang('tab_image'); ?></a></li>
                    </ul>

                    <div class="tab-content">
                        <div class="tab-pane active" id="tab_language">

                            <?php
                            $list_lang = [
                                ['name' => 'title', 'title' => 'Vị trí tuyển dụng', 'type' => 'text', 'required' => true],
                                ['name' => 'address', 'title' => 'Địa chỉ', 'type' => 'text', 'required' => true],
                                ['name' => 'content', 'title' => 'Nội dung', 'type' => 'textarea', 'required' => false, 'tinymce' => true],
                            ];

                            $this->load->view($this->template_path . '_block/_item_form_lang', ['list' => $list_lang, 'seo' => true]);
                            ?>

                        </div>
                        <div class="tab-pane" id="tab_info">

                            <?php
                            $list = [
                                ['name' => 'deadline', 'title' => 'Hạn nộp hồ sơ',  'class' => 'datepicker', 'type' => 'text', 'required' => true],
                                ['name' => 'number', 'title' => 'Số lượng',  'class' => '', 'type' => 'text', 'required' => true],
                                ['name' => 'status', 'title' => 'Trạng thái', 'class' => '', 'type' => 'status', 'required' => false],
                            ];

                            $this->load->view($this->template_path . '_block/_item_form', ['list' => $list, 'class' => 'col-md-12']);
                            ?>

                        </div>
                        <div class="tab-pane" id="tab_image">
                            <div class="box-body">
                                <?php $this->load->view($this->template_path . '_block/input_media') ?>
                            </div>
                        </div>
                    </div>

                    <!-- /.tab-content -->
                </div>
                <!-- nav-tabs-custom -->
                <?php echo form_close() ?>
            </div>
            <div class="modal-footer">
                <?php $this->load->view($this->template_path . '_block/form_button') ?>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<!-- End Bootstrap modal -->
<script>
    var url_ajax_load_category = '<?php echo site_url('admin/category/ajax_load/career') ?>';
    var url_ajax_load = '<?php echo site_url('admin/category/ajax_load/career') ?>';
    var url_ajax_load_location = '<?php echo site_url('admin/property/ajax_load/location') ?>';
    var url_ajax_get_auth = '<?php echo site_url("admin/{$this->router->fetch_class()}/ajax_get_auth") ?>';
</script>