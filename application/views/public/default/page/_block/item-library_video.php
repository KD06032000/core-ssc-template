<div class="main-content-new">
    <div class="list-pri wow fadeInUp delay02">
        <div class="row mt-2">
            <div class="col-md-6">
                <h3 class="title-pri mt-2">Video</h3>
            </div>
            <div class="col-md-6">
                <input type="text" id="datepicker-years" data-old="<?= $year ?? '1' ?>" value="<?= $year ?? '' ?>"
                       class="datepicker-years search-year" placeholder="Năm">
            </div>
        </div>
        <div class="row">

            <?php if (!empty($data)) foreach ($data as $key => $value) { ?>
                <div class="col-md-4">
                    <div class="item">
                        <div class="img">
                            <img class="lazy" src="<?= getThumbLazy('442x246', '210x117') ?>"
                                 data-src="<?= imageWaterMark($value->thumbnail, SiteSettings::item('water_mark'), '442x246', '210x117'); ?>"
                                 alt="<?= $value->title ?>">
                            <span class="time"><?= !empty($value->duration) ? $value->duration : '00:00' ?></span>
                            <i class="fal fa-play-circle"></i>
                        </div>
                        <div class="content">
                            <h4 class="title"><?= $value->title ?></h4>
                        </div>
                        <a class="link media-library" data-id="<?= $value->id ?>" href="javascript:;"></a>
                    </div>
                </div>
                <script>
                    album['<?= $value->id ?>'] = "<?=  $value->linkvideo ?>"
                </script>
            <?php } else { ?>
                <div class="text-center no-find-result empty-result">
                    <p class="text-center"><?= lang('no_find_result') ?></p>
                </div>
            <?php } ?>

        </div>

        <?= $pagination ?>

    </div>
</div>
