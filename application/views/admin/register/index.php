<?php

defined('BASEPATH') OR exit('No direct script access allowed');
?>

<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-body">
                    <div class="col-sm-7 col-xs-12"></div>
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
