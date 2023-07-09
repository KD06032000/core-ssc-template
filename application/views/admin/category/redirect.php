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
                        <div class="col-sm-7 col-xs-12">
                            <div class="row">
                                <form action="" id="form_filter" method="post">
                                    <div class="col-md-6">
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
                    <form action="" id="form-table" method="post" onsubmit="return false;">
                        <input type="hidden" value="0" name="msg"/>
                        <table id="data-table" class="table table-bordered table-hover dataTable" role="grid">
                            <thead>
                            <tr>
                                <th class="no-sort"><input type="checkbox" name="select_all" value="1"
                                                           id="data-table-select-all"></th>
                                <th>ID</th>
                                <th><?php echo lang('text_title'); ?></th>
                                <th class="no-sort"><?php echo lang('text_featured'); ?></th>
                                <th class="no-sort"><?php echo lang('text_sort'); ?></th>
                                <th class="no-sort"><?php echo lang('text_status'); ?></th>
                                <th class="no-sort"><?php echo lang('text_created_time'); ?></th>
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
    <div class="modal-dialog" style="width: 80%">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span>
                </button>
                <h3 class="modal-title" id="title-form"><?php echo lang('heading_title_add'); ?></h3>
            </div>
            <div class="modal-body form">
                <?php echo form_open('', ['id' => 'form', 'class' => '']) ?>
                <input type="hidden" name="id" value="0">
                <input type="hidden" name="type" value="<?php echo !empty($category_type) ? $category_type : '' ?>">
                <!-- Custom Tabs -->
                <div class="nav-tabs-custom">

                    <ul class="nav nav-tabs">
                        <li class="active">
                            <a href="#tab_language" data-toggle="tab"><?php echo lang('tab_language'); ?></a>
                        </li>
                    </ul>

                    <div class="tab-content">
                        <div class="tab-pane active" id="tab_language">

                            <?php
                            $seo = false;
                            $list_lang = [
                                ['name' => 'title', 'title' => 'Tiêu đề', 'type' => 'text', 'required' => true],
                            ];

                            $this->load->view($this->template_path . '_block/_item_form_lang', ['list' => $list_lang, 'seo' => $seo]);
                            ?>

                            <?php
                            $list = [
                                ['name' => 'status', 'title' => 'Trạng thái', 'type' => 'status', 'required' => false,],
                            ];

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
    var category_type = '<?php echo !empty($category_type) ? $category_type : '' ?>';
    url_ajax_list = '<?php echo base_url('admin/category/ajax_list/')?>' + category_type;
    url_ajax_load = '<?php echo base_url('admin/category/ajax_load/') ?>' + category_type;
    url_ajax_delete = '<?php echo base_url('admin/category/ajax_delete/') ?>' + category_type;
</script>
