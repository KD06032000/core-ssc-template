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
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <div class="input-group">
                                                <span class="input-group-addon"><i class="fa fa-filter"></i></span>
                                                <select class="form-control item select2" name="store_id"
                                                        style="max-width: 100%;" tabindex="-1" aria-hidden="true">
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <div class="input-group">
                                                <span class="input-group-addon"><i class="fa fa-filter"></i></span>
                                                <select class="form-control item select2" name="employee_id"
                                                        style="max-width: 100%;" tabindex="-1" aria-hidden="true">
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <div class="input-group">
                                                <span class="input-group-addon"><i class="fa fa-filter"></i></span>
                                                <input type="text" readonly class="form-control item date-custom"
                                                       placeholder="Chọn ngày" name="date"/>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <div class="input-group">
                                                <span class="input-group-addon"><i class="fa fa-filter"></i></span>
                                                <select name="payments"
                                                        placeholder="Giờ bắt đầu" class="item form-control" type="text">
                                                    <option value="">Chọn hình thức thanh toán</option>
                                                    <option value="1">Quet thẻ</option>
                                                    <option value="2">Tiền mặt</option>
                                                    <option value="3">Chuyển khoản</option>
                                                    <option value="4">Thanh toán online</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <div class="input-group">
                                                <span class="input-group-addon"><i class="fa fa-filter"></i></span>
                                                <select name="is_status"
                                                        placeholder="Giờ bắt đầu" class="item form-control" type="text">
                                                    <option value="">Chọn trạng thái đơn hàng</option>
                                                    <option value="1">Đã đặt lịch</option>
                                                    <option value="2">Khách đã đến</option>
                                                    <option value="3">Đã hoàn thành</option>
                                                    <option value="4">Hủy</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <div class="col-sm-5 col-xs-12 text-right">
                            <div class="row">
                                <div class="col-md-6"></div>
                                <div class="col-md-6">
                                    <div id="total-booking">
                                        Tổng doanh thu : <?= number_format($total_price) ?>
                                    </div>
                                </div>
                            </div>
                            <button class="btn btn-info" onclick="export_excel()">
                                <i class="fa fa-file-excel-o"></i> Xuất Excel
                            </button>
                        </div>
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
                                <th width="35px">ID</th>
                                <th>ID</th>
                                <th>Tên khách hàng</th>
                                <th>Email</th>
                                <th>Điện thoại</th>
                                <th>Mã đơn hàng</th>
                                <th>Tiệm</th>
                                <th>Thợ</th>
                                <th>Số tiền</th>
                                <th>Trạng thái đơn hàng</th>
                                <th>Hình thức thanh toán</th>
                                <th>Ngày đơn hàng</th>
                                <th>Ghi chú</th>
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
    var url_ajax_load_store = '<?php echo site_url('admin/store/ajax_load') ?>';
    var url_ajax_load_employee = '<?php echo site_url('admin/employee/ajax_load') ?>';
    var url_ajax_export_excel = '<?php echo site_url('admin/report/export_excel') ?>';
    var url_ajax_total_revenue = '<?php echo site_url('admin/report/total_revenue') ?>';
</script>
