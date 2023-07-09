<?php

defined('BASEPATH') OR exit('No direct script access allowed');
?>

<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-body">
                    <div class="col-sm-7 col-xs-12">
                        <div class="row">
                            <form action="" id="form_filter" method="post">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <div class="input-group">
                                            <span class="input-group-addon"><i class="fa fa-filter"></i></span>
                                            <select class="form-control select2 item"
                                                    data-place="Tiệc" name="page_id"
                                                    style="max-width: 100%;" tabindex="-1" aria-hidden="true">
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="col-sm-5 col-xs-12 text-right">
                        <?php showButtonExport(BASE_ADMIN_URL . 'contact/export_excel') ?>
                        <?php button_admin(['delete']) ?>
                        <button class="btn btn-default" onclick="reload_table()">
                            <i class="glyphicon glyphicon-refresh"></i> <?= lang('btn_reload'); ?>
                        </button>
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
                                <th><?= lang('text_fullname'); ?></th>
                                <th><?= lang('text_phone'); ?></th>
                                <th><?= lang('text_email'); ?></th>
                                <th >Số lượng người tham dự</th>
                                <th >Ngày tổ chức</th>
                                <th >Thời gian tổ chức</th>
                                <th >Nội dung</th>
                                <th><?= lang('text_created_time'); ?></th>
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
<script>
    var contact_type = '<?php echo !empty($contact_type) ? $contact_type : '' ?>';
    var url_ajax_view = '<?= site_url('admin/contact/ajax_view')?>';
    var url_export_excel = '<?= site_url('admin/contact/export_excel/')?>' + contact_type;
    var url_ajax_page = '<?= site_url('admin/page/ajax_load_child/service_wedding')?>';
</script>