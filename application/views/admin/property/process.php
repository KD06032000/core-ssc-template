<?php
defined('BASEPATH') or exit('No direct script access allowed');

?>
<style type="text/css">

</style>
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
                    <table id="data-table" class="table table-bordered table-hover dataTable" role="grid">
                        <thead>
                        <tr>
                            <th><input type="checkbox" name="select_all" value="1" id="data-table-select-all"></th>
                            <th>ID</th>
                            <th><?php echo lang('text_title'); ?></th>
                            <th><?php echo lang('text_featured'); ?></th>
                            <th><?php echo lang('text_sort'); ?></th>
                            <th><?php echo lang('text_status'); ?></th>
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
    <div class="modal-dialog" style="width: 60%">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                </button>
                <h3 class="modal-title" id="title-form"><?php echo lang('heading_title_add'); ?></h3>
            </div>
            <div class="modal-body form">
                <?php echo form_open('', ['id' => 'form', 'class' => '']) ?>
                <input type="hidden" name="id" value="0">
                <input type="hidden" name="type" value="<?php echo !empty($property_type) ? $property_type : '' ?>">
                <!-- Custom Tabs -->
                <div class="nav-tabs-custom">
                    <ul class="nav nav-tabs">
                        <li class="active"><a href="#tab_language"
                                              data-toggle="tab"><?php echo lang('tab_language'); ?></a></li>
                        <li><a href="#tab_info" data-toggle="tab"><?php echo lang('tab_info'); ?></a></li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane active" id="tab_language">

                            <?php
                            $list_lang = [
                                ['name' => 'title', 'title' => 'Năm', 'type' => 'text', 'required' => true],
                                ['name' => 'description', 'title' => 'Tóm tắt', 'type' => 'textarea', 'required' => false, 'tinymce' => false],
                                ['name' => 'content', 'title' => 'Nội dung', 'type' => 'textarea', 'required' => false, 'tinymce' => true],
                            ];

                            $this->load->view($this->template_path . '_block/_item_form_lang', ['list' => $list_lang, 'seo' => false]);
                            ?>

                        </div>
                        <div class="tab-pane" id="tab_info">

                            <?php
                            $list = [
                                ['name' => 'order', 'title' => 'Sắp xếp', 'class' => '', 'type' => 'number', 'required' => false],
                                ['name' => 'status', 'title' => 'Trạng thái', 'type' => 'status', 'required' => false],
                            ];

                            if (in_array($property_type, array('partner'))) {
                                $list[] = ['name' => 'url', 'title' => 'Link website', 'class' => '', 'type' => 'text', 'required' => false];
                            }

                            $this->load->view($this->template_path . '_block/_item_form', ['list' => $list, 'class' => 'col-md-12']);
                            ?>

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
<script type="text/javascript">
    var property_type = '<?php echo !empty($property_type) ? $property_type : '' ?>';
    var url_ajax_list = '<?php echo base_url('admin/property/ajax_list/') ?>' + property_type;
</script>
