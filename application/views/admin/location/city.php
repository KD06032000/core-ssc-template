<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-body">
                  <div class="row">
                    <div class="col-sm-7 col-xs-12" id="table-buttons"></div>
                    <?php $this->load->view($this->template_path."_block/button", ['display_button' => [ 'add', 'delete']]) ?>
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
                                <th>Tên tỉnh/Thành phố</th>
                                <th>Loại</th>
                                <th>Tên đầy đủ</th>
                                <th>Vĩ độ</th>
                                <th>Kinh độ</th>
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
    <div class="modal-dialog" style="width: 70%">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                    aria-hidden="true">&times;</span></button>
                    <h3 class="modal-title" id="title-form"><?php echo lang('heading_title_add'); ?></h3>
                </div>
                <div class="modal-body form" style="padding-right: 15px;padding-left: 15px;">
                    <?php echo form_open('', ['id' => 'form', 'class' => '']) ?>
                    <input type="hidden" name="id" value="0">
                    <!-- Custom Tabs -->
                    <div class="nav-tabs-custom">
                        <div class="tab-content">
                            <div class="row">
                                <div class="col-xs-12">
                                    <div class="form-group">
                                        <label>Tên <?php showRequiredField(); ?></label>
                                        <input id="title" name="title" class="form-control" type="text" placeholder="Tên">
                                    </div>
                                  <div class="form-group">
                                    <label>Địa chỉ đẩy đủ</label>
                                    <input  name="name_with_type" class="form-control" type="text" placeholder="Địa chỉ đẩy đủ">
                                  </div>
                                    <div class="form-group">
                                        <label>Miền</label>
                                      <select name="region" class="form-control">
                                        <option value="1">Miền Bắc</option>
                                        <option value="2">Miền Trung</option>
                                        <option value="3">Miền Nam</option>
                                      </select>
                                    </div>
                                  <div class="form-group">
                                        <label>Loại</label>
                                      <select name="type" class="form-control">
                                        <option value="Tỉnh">Tỉnh</option>
                                        <option value="Thành phố">Thành phố</option>
                                      </select>
                                    </div>
                                  <div class="form-group">
                                    <label>Vĩ độ <?php showRequiredField(); ?></label>
                                    <input type="text" name="latitude" class="form-control" placeholder="VD: 8.9624099">
                                  </div>
                                  <div class="form-group">
                                    <label>Kinh độ <?php showRequiredField(); ?></label>
                                    <input type="text" name="longitude" class="form-control" placeholder="VD: 105.1258955">
                                  </div>
                                  <div class="form-group">
                                    <label>Sắp xếp</label>
                                    <input type="number" name="order" class="form-control" value="0">
                                  </div>
                                </div>
                            </div>
                        </div>
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
        var url_ajax_import_excel = '<?php echo site_url('admin/location/ajax_import_excel_city') ?>';
        var url_ajax_load_country = '<?php echo site_url('admin/location/ajax_load_country') ?>';
        var location_type='city';
    </script>