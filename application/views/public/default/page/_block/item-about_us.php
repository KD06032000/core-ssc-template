<?php if (!empty($oneItem)) { ?>
    <div class="wrap-content-about">
        <div class="about-top wow fadeInUp delay02">
            <div class="ab-top-left">
                <h3 class="title-pri"><?= $oneItem->title ?></h3>
                <div class="s-content">
                    <?= $oneItem->content ?>
                </div>
            </div>
            <div class="ab-top-right">
                <span class="num" style="background-image: url(public/images/bg-num.jpg);"><?= (int)SiteSettings::item('year_develop') ?></span>
                <p class="text-center fz-title"><?= lang('num_years_active_and_dev') ?></p>
            </div>
        </div>
        <div class="about-bottom">
            <div class="left wow fadeInUp">
                <div class="count-overview v2">
                    <div class="item">
                        <div class="img"><img src="public/images/human.png" alt=""></div>
                        <p class="num hc-counter"><?= SiteSettings::item('home', ['about', 'num1']); ?></p>
                        <p class="desc"><?= lang('num_people_consulter') ?></p>
                    </div>
                    <div class="item">
                        <div class="img"><img src="public/images/woman.png" alt=""></div>
                        <p class="num hc-counter"><?= SiteSettings::item('home', ['about', 'num2']); ?></p>
                        <p class="desc"><?= lang('num_people_consultations') ?></p>
                    </div>
                    <div class="item">
                        <div class="img"><img src="public/images/love.png" alt=""></div>
                        <p class="num hc-counter"><?= SiteSettings::item('home', ['about', 'num3']); ?></p>
                        <p class="desc"><?= lang('num_temporary_residents') ?></p>
                    </div>
                    <div class="item">
                        <div class="img"><img src="public/images/waiter.png" alt=""></div>
                        <p class="num hc-counter"><?= SiteSettings::item('home', ['about', 'num4']); ?></p>
                        <p class="desc"><?= lang('num_people_serving') ?></p>
                    </div>
                    <div class="item">
                        <div class="img"><img src="public/images/calendar.png" alt=""></div>
                        <p class="num hc-counter"><?= SiteSettings::item('home', ['about', 'num5']); ?></p>
                        <p class="desc"><?= lang('num_consultations') ?></p>
                    </div>
                </div>
            </div>
            <div class="right wow fadeInUp">
                <img class="lazy" src="<?= getThumbLazy('488x362') ?>"
                     data-src="<?= resizeImage($oneItem->thumbnail, '488x362') ?>"
                     alt="<?= $oneItem->thumbnail . $oneItem->title ?>">
            </div>
        </div>
    </div>
<?php } ?>