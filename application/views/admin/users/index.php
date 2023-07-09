<?php
defined('BASEPATH') or exit('No direct script access allowed'); ?>
<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-7"></div>
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
                            <th>Tên tài khoản</th>
                            <th><?php echo lang('text_email'); ?></th>
                            <th><?php echo lang('text_phone'); ?></th>
                            <th class="no-sort"><?php echo lang('text_role'); ?></th>
                            <th><?php echo lang('text_fullname'); ?></th>
                            <th><?php echo lang('text_status'); ?></th>
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
    <div class="modal-dialog" style="width: 70%;">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                </button>
                <h3 class="modal-title" id="title-form"><?php echo lang('heading_title_add'); ?></h3>
            </div>
            <div class="modal-body form" style="padding: 10px;">
                <?php echo form_open('', ['id' => 'form']) ?>
                <input type="hidden" name="id" value="5">

                <div class="box-body">
                    <div class="col-xs-6">
                        <div class="form-group">
                            <label>Tên tài khoản <?php showRequiredField() ?></label>
                            <input name="username" autocomplete="off" placeholder="<?php echo lang('form_username'); ?>"
                                   class="form-control" type="text"/>
                        </div>
                    </div>
                    <div class="col-xs-6">
                        <div class="form-group">
                            <label><?php echo lang('form_email'); ?>*</label>
                            <input name="email" autocomplete="off" placeholder="<?php echo lang('form_email'); ?>" class="form-control"
                                   type="text"/>
                        </div>
                    </div>
                    <div class="col-xs-6">
                        <div class="form-group">
                            <label><?php echo lang('form_full_name'); ?></label>
                            <input name="full_name" autocomplete="off" placeholder="<?php echo lang('form_full_name'); ?>"
                                   class="form-control" type="text"/>
                        </div>
                    </div>
                    <div class="col-xs-6">
                        <div class="form-group">
                            <label><?php echo lang('form_phone'); ?> <?= showRequiredField() ?></label>
                            <input name="phone" autocomplete="off" placeholder="<?php echo lang('form_phone'); ?>" class="form-control"
                                   type="text"/>
                        </div>
                    </div>
                    <div id="div-password">
                        <div class="col-xs-6">
                            <div class="form-group">
                                <label><?php echo lang('form_password'); ?><?= showRequiredField() ?></label>
                                <input name="password" autocomplete="off" placeholder="<?php echo lang('form_password'); ?>"
                                       class="form-control" type="password"/>
                            </div>
                        </div>
                        <div class="col-xs-6">
                            <div class="form-group">
                                <label><?php echo lang('form_repassword'); ?>*</label>
                                <input name="repassword" autocomplete="off" placeholder="<?php echo lang('form_repassword'); ?>"
                                       class="form-control" type="password"/>
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-6">
                        <div class="form-group">
                            <label>Trạng thái</label>
                            <div>
                                <select name="active" class="form-control">
                                    <option value="1">Đang hoạt động</option>
                                    <option value="0">Ngừng hoạt động</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-6">
                        <div class="form-group">
                            <label>Vai trò</label>
                            <select name="group_id"
                                    class="form-control" <?php if ($this->session->userdata['user_id'] == 1 || $this->session->userdata['admin_group_id'] == 1) echo ''; else echo 'disabled'; ?>>
                                <?php
                                $selected = '';
                                if (!empty($group_user)) foreach ($group_user as $item) {
                                    echo "<option value='" . $item->id . "'>" . $item->name . "</option>";
                                }
                                ?>
                            </select>
                            <?php
                            if ($this->session->userdata['user_id'] != 1) {
                                ?>
                                <input type="hidden" name="group_id" value="">
                                <?php
                            } ?>
                        </div>
                    </div>
                </div>
                <?php echo form_close() ?>
            </div>
            <div class="modal-footer v2">
                <?php $this->load->view($this->template_path . '_block/form_button') ?>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<!-- End Bootstrap modal -->
<script>
    var url_ajax_load_store = '<?php echo site_url('admin/store/ajax_load') ?>';
</script>