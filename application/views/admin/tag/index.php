<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

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
                    <form action="" id="form-table" method="post">
                        <input type="hidden" value="0" name="msg"/>
                        <table id="data-table" class="table table-bordered table-striped">
                            <thead>
                            <tr>
                                <th width="60"><input type="checkbox" name="select_all" value="1" id="data-table-select-all"></th>
                                <th width="60">ID</th>
                                <th><?php echo lang('text_title'); ?></th>
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
                        <li class="active"><a href="#tab_language" data-toggle="tab"><?php echo lang('tab_language'); ?></a></li>
                        <li><a href="#tab_info" data-toggle="tab"><?php echo lang('tab_info'); ?></a></li>
                        <li>
                            <a href="#tab_image" data-toggle="tab"><?php echo lang('tab_image'); ?></a>
                        </li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane active" id="tab_language">
                            <ul class="nav nav-pills">
                                <?php if (!empty($this->config->item('cms_language'))) foreach ($this->config->item('cms_language') as $lang_code => $lang_name) { ?>
                                    <li<?php echo ($lang_code == 'vi') ? ' class="active"' : ''; ?>><a
                                            href="#tab_<?php echo $lang_code; ?>" data-toggle="tab"><?php echo $lang_name; ?></a></li>
                                <?php } ?>
                            </ul>
                            <div class="tab-content">
                                <?php if (!empty($this->config->item('cms_language'))) foreach ($this->config->item('cms_language') as $lang_code => $lang_name) { ?>
                                    <div class="tab-pane <?php echo ($lang_code == 'vi') ? 'active' : ''; ?>"
                                         id="tab_<?php echo $lang_code; ?>">
                                        <div class="box-body">
                                            <div class="row">
                                                <div class="col-sm-6 col-xs-12">
                                                    <div class="form-group">
                                                        <label>Tiêu đề <?php showRequiredField($lang_code); ?></label>
                                                        <input id="title_<?php echo $lang_code; ?>" name="title[<?php echo $lang_code; ?>]"
                                                               placeholder="Tiêu đề" class="form-control" type="text"/>
                                                    </div>

                                                    <div class="form-group">
                                                        <label><?php echo lang('form_description'); ?></label>
                                                        <textarea id="description_<?php echo $lang_code; ?>"
                                                                  name="description[<?php echo $lang_code; ?>]"
                                                                  placeholder="<?php echo lang('form_description'); ?>"
                                                                  class="form-control"></textarea>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6 col-xs-12">
                                                    <?php $this->load->view($this->template_path . '_block/seo_meta', ['lang_code' => $lang_code]) ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                        <div class="tab-pane" id="tab_info">
                            <div class="box-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="">Ngày hiển thị</label>
                                            <div class="input-group">
                                                <div class="input-group-addon">
                                                    <i class="fa fa-calendar"></i>
                                                </div>
                                                <input name="displayed_time" placeholder="Ngày hiển thị" class="form-control datepicker"
                                                       id="displayed_time" type="text"/>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="">Sắp xếp</label>
                                            <input name="order" placeholder="Sắp xếp" class="form-control" type="text"/>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <?php showSelectStatus() ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane" id="tab_image">

                            <div class="box-body">
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
    var url_ajax_load_tag = '<?php echo site_url('admin/tag/ajax_load') ?>';
</script>
