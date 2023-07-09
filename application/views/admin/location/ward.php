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
                                <div class="col-sm-6">
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="fa fa-filter"></i></span>
                                        <select class="form-control select2 filter_city_id" title="filter_city_id"
                                                name="filter_city_id"
                                                style="width: 100%;" tabindex="-1" aria-hidden="true">
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="fa fa-filter"></i></span>
                                        <select class="form-control select2 filter_district_id"
                                                title="filter_district_id" name="filter_district_id"
                                                style="width: 100%;" tabindex="-1" aria-hidden="true">
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php $this->load->view($this->template_path . "_block/button", ['display_button' => ['add', 'delete']]) ?>

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
                                <th>Phường / Xã</th>
                                <th>Tên Quận/Huyện</th>
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
            <div class="modal-body form">
                <?php echo form_open('', ['id' => 'form', 'class' => '']) ?>
                <input type="hidden" name="id" value="0">
                <!-- Custom Tabs -->
                <div class="nav-tabs-custom">
                    <div class="tab-content">
                        <div class="row">
                            <div class="col-xs-12">
                                <div class="form-group">
                                    <label>Tên <?php showRequiredField(); ?></label>
                                    <input name="title" class="form-control" type="text" placeholder="Tên">
                                </div>
                                <div class="form-group">
                                    <label>Địa chỉ đầy đủ</label>
                                    <input name="name_with_type" class="form-control" type="text"
                                           placeholder="Địa chỉ đầy đủ">
                                </div>
                                <div class="form-group">
                                    <label>Loại</label>
                                    <select name="type" class="form-control">
                                        <option value="Xã">Xã</option>
                                        <option value="Thị trấn">Thị trấn</option>
                                        <option value="Phường">Phường</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Tỉnh / Thành phố <?php showRequiredField(); ?></label>
                                    <select class="form-control select2 city_" name="city_id" style="width: 100%;"
                                            tabindex="-1"
                                            aria-hidden="true"></select>
                                </div>
                                <div class="form-group">
                                    <label>Quận/Huyện <?php showRequiredField(); ?></label>
                                    <select class="form-control select2 " name="district_id" style="width: 100%;"
                                            tabindex="-1"
                                            aria-hidden="true"></select>
                                </div>

                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label>Vĩ độ <?php showRequiredField(); ?></label>
                                    <input id="latitude" name="latitude" class="form-control" placeholder="Vĩ độ">
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label>Kinh độ <?php showRequiredField(); ?></label>
                                    <input id="longitude" name="longitude" class="form-control" placeholder="Kinh độ">
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
    var location_type = 'ward';
    var url_ajax_city = '<?php echo site_url('admin/location/ajax_load_city') ?>';
    var url_ajax_district = '<?php echo site_url('admin/location/ajax_load_district') ?>';
</script>