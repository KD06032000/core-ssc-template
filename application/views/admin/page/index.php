<?php

defined('BASEPATH') or exit('No direct script access allowed');
?>
<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-body">
                    <div class="row">
                        <div class="col-sm-7 col-xs-12"></div>
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
                                <th><?php echo lang('text_title'); ?></th>
                                <th><?php echo lang('text_status'); ?></th>
                                <th><?php echo lang('text_created_time'); ?></th>
                                <th><?php echo lang('text_updated_time'); ?></th>
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
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
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
                                ['name' => 'content_more', 'title' => 'Tóm tắt', 'type' => 'textarea', 'required' => false, 'tinymce' => false],
                                ['name' => 'content', 'title' => 'Nội dung', 'type' => 'textarea', 'required' => false, 'tinymce' => true],
                            ];

                            $this->load->view($this->template_path . '_block/_item_form_lang', ['list' => $list_lang, 'seo' => true]);
                            ?>

                        </div>
                        <div class="tab-pane" id="tab_info">
                            <div class="box-body">
                                <div class="form-group">
                                    <label>Layout style</label>
                                    <select name="style" id="" class="form-control">
                                        <?php $list_style = [
                                            '' => 'Trang tĩnh',
                                            'home' => 'trang chủ',
                                            'introduce' => 'giới thiệu',
                                            'price' => 'bảng giá',
                                            'customer' => 'khách hàng',
                                            'hỗ trợ' => 'support',
                                            'gallery' => 'Media',
                                            'news' => 'Tin tức',
                                        ] ?>

                                        <?php if (!empty($list_style)) foreach ($list_style as $key => $value) { ?>
                                            <option value="<?= $key ?>"><?= $value ?></option>
                                        <?php } ?>

                                    </select>
                                </div>

                                <?php $this->load->view($this->template_path . '_block/input_file') ?>

                                <div class="form-group">
                                    <?php showSelectStatus() ?>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane" id="tab_image">
                            <div class="box-body">
                                <?php $this->load->view($this->template_path . '_block/input_media') ?>
                                <?php $this->load->view($this->template_path . '_block/input_media', [
                                    'id_image' => 'form_banner',
                                    'name_image' => 'banner',
                                    'value_image' => '',
                                    'label_image' => 'Banner'
                                ]) ?>
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
    var url_ajax_load_page_service = '<?php echo site_url('admin/page/ajax_load/service') ?>';
</script>