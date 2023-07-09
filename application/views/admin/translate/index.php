<?php
defined('BASEPATH') or exit('No direct script access allowed'); ?>
<!-- Main content -->
<style>
    .disabled {
        cursor: not-allowed;
        pointer-events: none;
        opacity: 1;
        background: #d2d2d2;
        font-weight: 700;
    }

    #overlay {
        position: fixed;
        display: none;
        width: 100%;
        height: 100%;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: rgba(0, 0, 0, 0.5);
        z-index: 2;
        cursor: pointer;
    }

    #text {
        position: absolute;
        top: 50%;
        left: 50%;
        font-size: 50px;
        color: white;
        transform: translate(-50%, -50%);
        -ms-transform: translate(-50%, -50%);
        text-align: center;
    }
</style>
<div id="overlay">
    <div id="text">Đang xử lý dữ liệu ... <br> Vui lòng không đóng tab!</div>
</div>
<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-body">
                    <div class="row">
                        <div class="col-sm-7 col-xs-12"> </div>
                        <div class="col-sm-5 col-xs-12 text-right"> &nbsp;
                            <button type="button" id="btnSave" onclick="save()" class="btn btn-primary ">Lưu</button>
                            <button class="btn btn-default" type="button">
                                <i class="glyphicon glyphicon-refresh"></i> Làm mới
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
                <?php echo form_open('', ['id' => 'form', 'class' => 'form-horizontal']) ?>
                <div class="box-body">
                    <table id="data-table" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th style="text-align: left; width: 50%; font-size: 20px;">Tiếng Việt</th>
                                <th class="data-country" style="text-align: left;">
                                    <select id="language" class="language form-control " data-code="" style="color: black; font-size: 20px; min-height: 40px;">
                                        <option value="" selected disabled>Chọn ngôn ngữ</option>
                                    </select>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
                <!-- /.box-body -->
                <?php echo form_close() ?>
            </div>
            <!-- /.box -->
        </div>
        <!-- /.col -->
    </div>
    <!-- /.row -->
</section>
<!-- /.content -->