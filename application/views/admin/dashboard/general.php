<div class="row">
    <div class="col-xs-8">
        <div class="chart" id="stats-chart" style="height: 300px;"></div>
    </div>
    <div class="col-xs-4">
        <div class="chart" id="world-map" style="height: 300px;"></div>
    </div>
</div>
<div class="row">
    <?php if (!empty($general_total)): ?>
        <div class="col-lg-3 col-md-4 col-sm-6">
            <div class="info-box">
                <span class="info-box-icon bg-orange"><i class="fa fa-eye"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Sessions</span>
                    <span class="info-box-number"><?= $general_total['ga:sessions'] ?></span>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-4 col-sm-6">
            <div class="info-box">
                <span class="info-box-icon bg-blue"><i class="fa fa-users"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Visitors</span>
                    <span class="info-box-number"><?= $general_total['ga:users'] ?></span>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-4 col-sm-6">
            <div class="info-box">
                <span class="info-box-icon bg-aqua"><i class="fa fa-search"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Page Views</span>
                    <span class="info-box-number"><?= $general_total['ga:pageviews'] ?></span>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-4 col-sm-6">
            <div class="info-box">
                <span class="info-box-icon bg-yellow"><i class="fa fa-line-chart"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Percent new session</span>
                    <span class="info-box-number"><?= round($general_total['ga:percentNewSessions'], 2) ?>%</span>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-4 col-sm-6">
            <div class="info-box">
                <span class="info-box-icon bg-purple"><i class="fa fa-pie-chart"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Bounce Rate</span>
                    <span class="info-box-number"><?= round($general_total['ga:bounceRate'], 2) ?>%</span>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-4 col-sm-6">
            <div class="info-box">
                <span class="info-box-icon bg-teal"><i class="fa fa-globe"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Pages/Session</span>
                    <span class="info-box-number"><?= round($general_total['ga:pageviewsPerVisit'], 2) ?></span>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-4 col-sm-6">
            <div class="info-box">
                <span class="info-box-icon bg-green"><i class="fa fa-clock-o"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Avg. Duration</span>
                    <span class="info-box-number"><?= gmdate('H:i:s', $general_total['ga:avgSessionDuration']) ?></span>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-4 col-sm-6">
            <div class="info-box">
                <span class="info-box-icon bg-fuchsia"><i class="fa fa-user-plus"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">New Visitors</span>
                    <span class="info-box-number"><?= $general_total['ga:newUsers'] ?></span>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>
<script>
    data_visitor = '<?= !empty($visitor_stats) ? json_encode($visitor_stats) : '[]'?>';
    data_visitor = JSON.parse(data_visitor);
    $('#stats-chart').empty();
    visitor_chart = new Morris.Area({
        element: 'stats-chart',
        resize: true,
        data: data_visitor,
        xkey: 'axis',
        ykeys: ['visitors', 'pageViews'],
        labels: ['Visitors', 'Page Views'],
        lineColors: ['#DD4D37', '#3c8dbc'],
        hideHover: 'auto',
        parseTime: false
    });
    data_country = '<?= !empty($country_stats) ? json_encode($country_stats) : '[]'?>';
    data_country = JSON.parse(data_country);
    $('#world-map').vectorMap({
        map: 'world_mill',
        backgroundColor: 'transparent',
        regionStyle: {
            initial: {
                fill: '#e4e4e4',
                'fill-opacity': 1,
                stroke: 'none',
                'stroke-width': 0,
                'stroke-opacity': 1
            }
        },
        series: {
            regions: [{
                values: data_country,
                scale: ['#C64333', '#dd4b39'],
                normalizeFunction: 'polynomial'
            }]
        },
        onRegionTipShow: function (e, el, code) {
            if (typeof data_country[code] !== 'undefined') {
                el.html(el.html() + ': ' + data_country[code]);
            }
        }
    });
</script>

