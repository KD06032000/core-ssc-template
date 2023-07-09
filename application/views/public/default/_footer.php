<footer >
    <div class="container">
        <div class="row">
            <div class="col-lg-2 wow zoomIn delay01">
                <div class="logo-ft">
                    <a href="" title="">
                        <img class="lazy" src="<?= getThumbLazy() ?>" data-src="<?= resizeImage(SiteSettings::item('logo_ft')) ?>" alt="ghn">
                    </a>
                </div>
            </div>
            <div class="col-lg-7">
                <?= menuFooter1('goto_tab') ?>
                <div class="info">
                    <h3 class=" title fz24 wow fadeInUp delay01" >CÔNG TY CP PHẦN MỀM CHÂU Á THÁI BÌNH DƯƠNG</h3>
                    <p class="wow fadeInUp delay02"> <?= SiteSettings::item_lang('company', $this->lang_code, ['address']) ?></p>
                    <ul class="wow fadeInUp delay04">
                        <li class="mr-5"><span>Hotline: </span><a href="tel:0969001511" title=""><?= SiteSettings::item('phone') ?></a></li>
                        <li><span>Email: </span><a href="mailto:contact@apecsoft.asia" title=""><?= SiteSettings::item('email_site') ?></a></li>
                    </ul>
                </div>
            </div>
            
            <div class="col-lg-3">
                <div class="connect wow fadeInUp delay02">
                    <h3 class="title fz24" >KẾT NỐI VỚI CHÚNG TÔI</h3>
                    <div class="ft-social">
                        <a target="_blank" href="<?= SiteSettings::item('social_facebook_link') ?>" title=""><img
                                                    class="lazy" src="<?= getThumbLazy() ?>" data-src="public/media/logo-32x32.png" alt="facebook"> </a>
                        <a target="_blank" href="<?= SiteSettings::item('social_youtube_link') ?>" title=""><img
                                                    class="lazy" src="<?= getThumbLazy() ?>" data-src="public/media/logo-32x32.png" alt="youtube"> </a>
                        <a target="_blank" href="<?= SiteSettings::item('social_tiktok_link') ?>" title=""><img
                                                    class="lazy" src="<?= getThumbLazy() ?>" data-src="public/media/logo-32x32.png" alt="tiktok"> </a>
                        <a target="_blank" href="<?= SiteSettings::item('social_zalo_link') ?>" title=""><img
                                                    class="lazy" src="<?= getThumbLazy() ?>" data-src="public/media/logo-32x32.png" alt="zalo"> </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</footer>

