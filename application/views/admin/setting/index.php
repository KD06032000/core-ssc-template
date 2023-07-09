<section class="content">
    <?php echo form_open("admin/setting"); ?>
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-body">
                    <div class="col-sm-6">
                    </div>
                    <div class="col-sm-6 text-right">
                        <button class="btn btn-success" type="submit">
                            <i class="glyphicon glyphicon-plus"></i> <?php echo lang('btn_save'); ?>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <?php 
                $list_tab = [
                    ['target'=>'tab_general','name'=>'Tổng quan'],
                    ['target'=>'tab_home','name'=>'Trang chủ'],
                    ['target'=>'tab_about','name'=>'Giới thiệu'],
                    ['target'=>'tab_contact','name'=>'Liên hệ'],
                    ['target'=>'tab_social','name'=>'Mạng xã hội'],
                    // ['target'=>'tab_google_analytics','name'=>'Google Analytics'],
                    // ['target'=>'tab_google_calendar','name'=>'Google Calendar'],
                    ['target'=>'tab_embeb','name'=>'Mã nhúng']
                ];
             ?>
            <div class="nav-tabs-custom">
                <ul class="nav nav-tabs">
                    <?php foreach ($list_tab as $key => $tab): ?>
                    <li class="<?= ($key == 0)?'active':'' ?>"><a class="load_tab_setting" href="#<?= $tab['target'] ?>" data-toggle="tab"><?= $tab['name'] ?></a></li>
                    <?php endforeach ?>
                </ul>
                <div class="tab-content">
                    <?php foreach ($list_tab as $key => $tab): ?>
                        <?php 
                            $this->load->view($this->template_path.'setting/'.$tab['target'],$tab);
                        ?>
                    <?php endforeach ?>
                </div>
                <!-- /.tab-content -->
            </div>
            <!-- nav-tabs-custom -->
        </div>
        <!-- /.col -->
    </div>
    <!-- /.row -->
    <?php echo form_close(); ?>
</section>
<!-- /.content-wrapper -->
<script>
    var url_ajax_backup_db = '<?php echo site_url('admin/setting/ajax_backup_db')?>',
        url_ajax_restore_db = '<?php echo site_url('admin/setting/ajax_restore_db')?>',
        url_ajax_delete_db = '<?php echo site_url('admin/setting/ajax_delete_db')?>';
</script>
