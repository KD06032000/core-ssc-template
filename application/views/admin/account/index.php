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
                                        <div class="form-group">
                                            <div class="input-group">
                                                <span class="input-group-addon"><i class="fa fa-filter"></i></span>
                                                <select class="form-control item" name="month"
                                                        style="width: 100%;" tabindex="-1" aria-hidden="true">
                                                    <option value="">Chọn tháng sinh</option>
                                                    <?php $i = 2;
                                                    while ($i <= 13) { ?>
                                                        <option value="<?= $i ?>">Tháng <?= $i - 1 ?></option>
                                                        <?php $i++;
                                                    } ?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <div class="input-group">
                                                <span class="input-group-addon"><i class="fa fa-filter"></i></span>
                                                <select class="form-control item" name="is_status"
                                                        style="width: 100%;" tabindex="-1" aria-hidden="true">
                                                    <option value="">Chọn trạng thái</option>
                                                    <option value="2">Đang hoạt động</option>
                                                    <option value="1">Ngừng hoạt động</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <?php $this->load->view($this->template_path . "_block/button", ['display_button' => ['add']]) ?>
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
                            <th class="no-sort"><?php echo lang('text_id'); ?></th>
                            <th class="no-sort">Tên thành viên</th>
                            <th class="no-sort"><?php echo lang('text_email'); ?></th>
                            <th class="no-sort">Số điện thoại</th>
                            <th class="no-sort">Ngày sinh</th>
                            <th class="no-sort"><?php echo lang('text_status'); ?></th>
                            <th class="no-sort">Ngày đăng ký</th>
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
    <div class="modal-dialog" style="width: 70%">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                <h3 class="modal-title" id="title-form"><?php echo lang('heading_title_add'); ?></h3>
            </div>
            <div class="modal-body form">
                <?php echo form_open('', array('id' => 'form')) ?>
                <input type="hidden" name="id" value="0">
                <div class="box-body">
                    <div class="">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label>Email</label>
                                <input name="email" placeholder="Email" class="form-control" type="text"/>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label>Họ và tên <?php showRequiredField(); ?></label>
                                <input name="full_name" placeholder="Họ và tên" class="form-control" type="text"/>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label>Số điện thoại <?php showRequiredField(); ?></label>
                                <input name="phone" placeholder="Số điện thoại liên hệ" class="inputTel form-control"
                                       type="text"/>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label>Ngày sinh</label>
                                <input type="text" name="birthday" class="form-control datepicker"
                                       placeholder="Ngày sinh"
                                       autocomplete="off">
                            </div>
                        </div>
                        <div id="div-password">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label>Mật khẩu <?php showRequiredField(); ?></label>
                                    <input name="password" placeholder="Mật khẩu" class="form-control" type="password"/>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label>Xác nhận lại mật khẩu <?php showRequiredField(); ?></label>
                                    <input name="repassword" placeholder="Nhập lại mật khẩu" class="form-control"
                                           type="password"/>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label>Trạng thái</label>
                                <select class="form-control" name="active">
                                    <option value="0">Ngừng hoạt động</option>
                                    <option value="1" selected="">Đang hoạt động</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <?php $this->load->view($this->template_path . '_block/input_media') ?>
                        </div>
                    </div>

                </div>
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
    var url_ajax_list = '<?php echo site_url("admin/account/ajax_list");?>';
</script>
