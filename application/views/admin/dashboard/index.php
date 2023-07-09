<!-- Main content -->
<section class="content">
    <div class="row">

        <?php
        $list_total_field = [
            ['title' => 'Số lượng bài viết', 'id' => 'total_post', 'icon' => 'fa fa-newspaper-o', 'src' => site_url('admin/post')],
            ['title' => 'Số lượng thành viên', 'id' => 'total_user', 'icon' => 'fa fa-user', 'src' => site_url('admin/user')],
            ['title' => 'Số lượng lịch hoạt động', 'id' => 'total_calendar', 'icon' => 'fa fa-calendar', 'src' => site_url('admin/calendar')],
        ];
        ?>

        <?php foreach ($list_total_field as $value) { ?>
            <div class="col-lg-4 col-xs-6">
                <!-- small box -->
                <div class="small-box bg-purple">
                    <div class="inner">
                        <h3 id="<?= $value['id'] ?>"><i class="fa fa-refresh fa-spin"></i></h3>
                        <p><?= $value['title'] ?></p>
                    </div>
                    <div class="icon">
                        <i class="<?= $value['icon'] ?>"></i>
                    </div>
                    <a href="<?= $value['src'] ?>" class="small-box-footer">
                        <?php echo lang('text_more_info'); ?>
                        <i class="fa fa-arrow-circle-right"></i>
                    </a>
                </div>
            </div>
        <?php } ?>

    </div>
    <div class="row">
        <div class="col-xs-12">
            <!-- AREA CHART -->
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">Phân tích dữ liệu website</h3>
                    <div class="box-tools pull-right">
                        <span style="margin-right: 10px">Thời gian:</span>
                        <button type="button" class="btn btn-default btn-box-tool" id="date-range-btn">
                            <span></span>
                            <i class="fa fa-caret-down"></i>
                        </button>
                    </div>
                </div>
                <div class="box-body chart-responsive">
                    <div id="general_data">

                    </div>
                </div>
                <!-- /.box-body -->
            </div>
            <!-- /.box -->
        </div>
        <div class="col-xs-6">
            <div class="box box-info">
                <div class="box-header with-border">
                    <h3 class="box-title">Top Most Visit Pages</h3>

                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i
                                    class="fa fa-minus"></i>
                        </button>
                    </div>
                </div>
                <!-- /.box-header -->
                <div class="box-body" id="top_visited_data">
                    <div class="overlay loading-data">
                        <i class="fa fa-refresh fa-spin"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xs-6">
            <div class="box box-info">
                <div class="box-header with-border">
                    <h3 class="box-title">Top Referrers</h3>

                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i
                                    class="fa fa-minus"></i>
                        </button>
                    </div>
                </div>
                <!-- /.box-header -->
                <div class="box-body" id="top_referrers">
                    <div class="overlay loading-data">
                        <i class="fa fa-refresh fa-spin"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-6">
            <div class="box box-info">
                <div class="box-header with-border">
                    <h3 class="box-title">Top Browser</h3>

                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i
                                    class="fa fa-minus"></i>
                        </button>
                    </div>
                </div>
                <!-- /.box-header -->
                <div class="box-body" id="top_browser">
                    <div class="overlay loading-data">
                        <i class="fa fa-refresh fa-spin"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- /.content -->
<script>
    var url_ajax_total = '<?php echo site_url("admin/{$this->router->fetch_class()}/ajax_total")?>';
    var url_ajax_general_data = '<?php echo site_url("admin/{$this->router->fetch_class()}/general_data")?>';
    var url_ajax_top_visited = '<?php echo site_url("admin/{$this->router->fetch_class()}/get_top_visit_page")?>';
    var url_ajax_top_browser = '<?php echo site_url("admin/{$this->router->fetch_class()}/get_top_browser")?>';
    var url_ajax_top_referrers = '<?php echo site_url("admin/{$this->router->fetch_class()}/get_top_referrers")?>';
</script>
