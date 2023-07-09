<section class="homepage-banner">
    <div class="banner-slider">
        <div class="cas-home"> 
            <?php if (!empty($banner)) foreach ($banner as $item) { ?>
                <div class="item">
                    <div class="bg-banner img">
                        <a  href="" title="">
                            <img class="lazy" src="<?= getThumbLazy() ?>" data-src="<?= resizeImage(SiteSettings::item('bg_banner')) ?>" alt="">
                        </a>
                    </div>
                    <div class="ct">
                        <div class="container">
                            <div class="row">
                                <div class="col-xl-7 col-lg-5">
                                    <div class="logo-container">
                                            <a href="" title="" class="logo-banner" data-animation="animated fadeInDown delay08">
                                                <img class="lazy" src="<?= getThumbLazy() ?>" data-src="<?= resizeImage(SiteSettings::item('logo_container')) ?>" alt="">
                                            </a>
                                    </div>
                                    <div class="head-banner">
                                        <p class="fz22"  data-animation="animated fadeInRight delay12"><?=  $item->title ?></p>
                                        <h2 class="title fz58"  data-animation="animated fadeInLeft delay16"><?= $item->description ?></h2>
                                    </div>
                                    <div class="view-second" data-animation="animated fadeInUp delay16">
                                        <a href="#id2" class="title btn-scroll">Dùng thử miễn phí</a>
                                    </div>
                                    <ul class="download-app">
                                        <li data-animation="animated fadeInLeft delay18"><a href="https://apps.apple.com/vn/app/apeckids/id1487188413?l=vi" title="Appstore" target="_blank"><img src="public/media/appstore.png" alt=""></a></li>
                                        <li data-animation="animated fadeInRight delay18"><a href="https://play.google.com/store/apps/details?id=com.apecsoft.apeckids" title="Google Play" target="_blank"><img src="public/media/googleplay.png" alt=""></a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="banner-home img-abs" data-animation="animated fadeInUp delay06"><img class="lazy" src="<?= getThumbLazy() ?>" data-src="<?= resizeImage(SiteSettings::item('banner_home')) ?>" alt=""></div>
                </div>
                <div class="item">
                    <div class="bg-banner img">
                        <a href="" title="">
                            <img class="lazy" src="<?= getThumbLazy() ?>" data-src="<?= resizeImage(SiteSettings::item('bg_banner')) ?>" alt="">
                        </a>
                    </div>
                    <div class="ct">
                        <div class="container">
                            <div class="row">
                                <div class="col-xl-7 col-lg-5">
                                    <div class="logo-container">
                                            <a href="" title="" class="logo-banner" data-animation="animated fadeInDown delay08">
                                                <img class="lazy" src="<?= getThumbLazy() ?>" data-src="<?= resizeImage(SiteSettings::item('logo_container')) ?>" alt="">
                                            </a>
                                    </div>
                                    <div class="head-banner">
                                        <p class="fz22" data-animation="animated fadeInRight delay12"><?=  $item->title ?></p>
                                        <h2 class="title fz58" data-animation="animated fadeInLeft delay16"><?= $item->description ?></h2>
                                    </div>
                                    <div class="view-second" data-animation="animated fadeInUp delay16">
                                        <a href="#id2" class="title btn-scroll">Dùng thử miễn phí</a>
                                    </div>
                                    <ul class="download-app">
                                        <li data-animation="animated fadeInLeft delay18"><a href="https://apps.apple.com/vn/app/apeckids/id1487188413?l=vi" title="Appstore" target="_blank"><img src="public/media/appstore.png" alt=""></a></li>
                                        <li data-animation="animated fadeInRight delay18"><a href="https://play.google.com/store/apps/details?id=com.apecsoft.apeckids" title="Google Play" target="_blank"><img src="public/media/googleplay.png" alt=""></a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="banner-home img-abs" data-animation="animated fadeInUp delay06"><img class="lazy" src="<?= getThumbLazy() ?>" data-src="<?= resizeImage(SiteSettings::item('banner_home')) ?>" alt=""></div>
                </div>
            <?php } ?>
        </div>
    </div>
</section>