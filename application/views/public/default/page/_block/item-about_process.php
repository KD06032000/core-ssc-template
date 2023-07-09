<?php if (!empty($oneItem)) { ?>
    <div class="wrap-content-about wow fadeInUp delay02">
        <h3 class="title-pri"><?= $oneItem->title ?></h3>
        <div class="s-content">
            <?= $oneItem->content ?>
        </div>
        <div class="map-history">

            <?php $process = getDataProperty('process'); ?>

            <?php if (!empty($process)) foreach ($process as $value) { ?>
                <div class="item">
                    <p class="year-map fz-24"><?= $value->title ?></p>
                    <span class="content"><?= $value->description ?></span>
                    <div class="s-content mt-3">
                        <?= $value->content ?>
                    </div>
                </div>
            <?php } ?>

        </div>
    </div>
<?php } ?>