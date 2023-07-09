<?php if (!empty($cate_ecosystem)) { ?>
    <section class="ecosystem" id="eco">
        <div class="ecosystem-title">
            <div class="container">
                <div class="title-page text-left">
                    <span class="text-blue">Hệ sinh thái</span>
                    <span class="text-brown"> GNH</span>
                </div>
            </div>
        </div>

        <?php
        $count = count($cate_ecosystem);
        $countRight = intval($count / 2);
        $countLeft = $count - $countRight;

        $left = array_slice($cate_ecosystem, 0, $countLeft);
        $right = array_slice($cate_ecosystem, $countLeft);
        $s = 0;
        ?>

        <div class="homepage-ecosystem">
            <div class="container">
                <div class="ec-main" id="ecosystem">
                    <div class="ec-left">

                        <?php if (!empty($left)) foreach ($left as $key => $item) { ?>
                            <?php $s += 1 ?>

                            <div class="ec__item__left ec__item__left<?= $key + 1 ?> ec__item__wrap" id="tab_<?= $s ?>">
                                <span class="ec-item-line"><img class="lazy" src="<?= getThumbLazy() ?>" data-src="public/images/line-left.png" alt="line-left"></span>
                                <div class="ec__item">
                                    <a href="javascript:;" title="" class="ec-item-icon">
                                        <img class="lazy" src="<?= getThumbLazy() ?>" data-src="<?= resizeImage($item->thumbnail) ?>" alt="<?= $item->title ?>">
                                    </a>
                                    <h3 class="ec-item-title">
                                        <span class="ec-sub-title"><?= str_replace($item->title_highlight, ' ', $item->title) ?></span>
                                        <?= $item->title_highlight ?>
                                    </h3>
                                    <ul class="ec-item-list">

                                        <?php
                                        $ecosystem = getEcosystemByCategory($item->id);
                                        ?>
                                        <?php if (!empty($ecosystem)) foreach ($ecosystem as $k => $i) { ?>
                                            <li class="ec-item-content">
                                                <a target="_blank" href="<?= $i->url ?>" title="" class="detail-icon">
                                                    <img class="lazy" src="<?= getThumbLazy() ?>" data-src="<?= resizeImage($i->thumbnail) ?>" alt="<?= $i->title ?>">
                                                </a>
                                                <div class="ec-item-detail">
                                                    <a target="_blank" href="<?= $i->url ?>" title="" class="detail-title"><?= $i->title ?></a>
                                                    <div class="detail-content"><?= $i->content ?></div>
                                                </div>
                                            </li>
                                        <?php } ?>

                                    </ul>
                                </div>
                            </div>

                        <?php } ?>

                    </div>
                    <div class="ec-right">

                        <?php if (!empty($right)) foreach ($right as $key => $item) { ?>
                            <?php $s += 1 ?>
                            <div class="ec__item__right ec__item__righta ec__item__wrap" id="tab_<?= $s ?>">
                                <span class="ec-item-line"><img class="lazy" src="<?= getThumbLazy() ?>" data-src="public/images/line-right.png" alt="line-right"></span>
                                <div class="ec__item">
                                    <a href="javascript:;" title="" class="ec-item-icon">
                                        <img class="lazy" src="<?= getThumbLazy() ?>" data-src="<?= resizeImage($item->thumbnail) ?>" alt="<?= $item->title ?>">
                                    </a>
                                    <h3 class="ec-item-title">
                                        <span class="ec-sub-title"><?= str_replace($item->title_highlight, ' ', $item->title) ?></span>
                                        <?= $item->title_highlight ?>
                                    </h3>
                                    <ul class="ec-item-list">
                                        <?php
                                        $ecosystem = getEcosystemByCategory($item->id);
                                        ?>
                                        <?php if (!empty($ecosystem)) foreach ($ecosystem as $k => $i) { ?>
                                            <li class="ec-item-content">
                                                <a target="_blank" href="<?= $i->url ?>" title="" class="detail-icon">
                                                    <img class="lazy" src="<?= getThumbLazy() ?>" data-src="<?= resizeImage($i->thumbnail) ?>" alt="<?= $i->title ?>">
                                                </a>
                                                <div class="ec-item-detail">
                                                    <a target="_blank" href="<?= $i->url ?>" title="" class="detail-title"><?= $i->title ?></a>
                                                    <div class="detail-content"><?= $i->content ?></div>
                                                </div>
                                            </li>
                                        <?php } ?>

                                    </ul>
                                </div>
                            </div>
                        <?php } ?>

                    </div>
                </div>
            </div>
        </div>
    </section>
<?php } ?>