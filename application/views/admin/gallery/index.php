<?php

defined('BASEPATH') or exit('No direct script access allowed');

?>
<!-- Main content -->
<section class="content tpxaps">
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
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <div class="input-group">
                                                <span class="input-group-addon"><i class="fa fa-filter"></i></span>
                                                <input class="datepicker-years form-control item"
                                                        placeholder="Năm" name="year">
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
                                <th width="60"><input type="checkbox" name="select_all" value="1"
                                                      id="data-table-select-all"></th>
                                <th width="60">ID</th>
                                <th><?php echo lang('text_title'); ?></th>
                                <th><?php echo lang('text_sort'); ?></th>
                                <th class="no-sort">Ảnh đại diện</th>
                                <th><?php echo lang('text_status'); ?></th>
                                <th><?php echo lang('text_created_time'); ?></th>
                                <?php showColumnAction(); ?>
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

<!-- Bootstrap modal -->
<div class="modal fade" id="modal_form" role="dialog">
    <div class="modal-dialog" style="width: 90%">
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
                        <li class="active"><a href="#tab_language"
                                              data-toggle="tab"><?php echo lang('tab_language'); ?></a></li>
                        <li><a href="#tab_info" data-toggle="tab"><?php echo lang('tab_info'); ?></a></li>
                        <li><a href="#tab_image" data-toggle="tab"><?php echo lang('tab_image'); ?></a></li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane active" id="tab_language">
                            <?php
                            $list_lang = [
                                ['name' => 'title', 'title' => 'Tiêu đề', 'type' => 'text', 'required' => true],
                                ['name' => 'description', 'title' => 'Tóm tắt', 'type' => 'textarea', 'required' => false, 'tinymce' => false],
                            ];

                            $this->load->view($this->template_path . '_block/_item_form_lang', ['list' => $list_lang, 'seo' => false]);
                            ?>
                        </div>
                        <div class="tab-pane" id="tab_info">
                            <?php
                            $list = [
                                ['name' => 'displayed_time', 'title' => 'Ngày hiển thị', 'class' => 'datepicker', 'type' => 'text', 'required' => false],
                                ['name' => 'status', 'title' => 'Trạng thái', 'class' => '', 'type' => 'status', 'required' => false],
                            ];

                            $this->load->view($this->template_path . '_block/_item_form', ['list' => $list, 'class' => 'col-md-12']);
                            ?>
                        </div>
                        <div class="tab-pane" id="tab_image">
                            <div class="box-body">
                                <?php $this->load->view($this->template_path . '_block/input_media') ?>
                                <?php $this->load->view($this->template_path . '_block/input_multiple_media') ?>
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