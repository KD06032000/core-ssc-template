<?php if (!empty($oneItem)) { ?>
    <div class="wrap-content-about wow fadeInUp delay02">
        <h3 class="title-pri"><?= $oneItem->title ?></h3>
        <div class="s-content">
            <?= $oneItem->content ?>
        </div>
        <div class="group-main-action">

            <?php $action = getDataProperty('action'); ?>

            <?php if (!empty($action)) foreach ($action as $value) { ?>

                <div class="item">
                    <div class="img">
                        <img class="lazy" src="<?= getThumbLazy('427x427') ?>"
                             data-src="<?= resizeImage($value->thumbnail, '427x427') ?>" alt="<?= $value->title ?>">
                    </div>
                    <div class="content">
                        <h4 class="title fz-text"><?= $value->title ?></h4>
                        <div class="s-content">
                            <?= $value->content ?>
                        </div>
                    </div>
                </div>

            <?php } ?>
        </div>
    </div>
<?php } ?>