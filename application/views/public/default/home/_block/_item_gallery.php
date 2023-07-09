<section class="video-index">
    <div class="container">
        <div class="row">
            <div class="col-md-6">
                <div class="video-6">
                    <div class="title-page text-left">
                        <a href="<?= !empty($page_media) ? getUrlPage($page_media) . '#video' : 'javascript;' ?>" title="">
                            <span class="text-blue">Thư viện</span>
                            <span class="text-brown"> video</span>
                        </a>
                    </div>

                    <?php if (!empty($video)) { ?>
                        <div class="video-item relative">
                            <a data-fancybox="" href="<?= $video->linkvideo ?>" title=""
                               class="video-link flex-center-center"><span class="inflex-center-center"><i
                                            class="fas fa-play"></i> </span></a>
                            <div class="video-image">
                                <img class="lazy" src="<?= getThumbLazy('690x446') ?>" data-src="<?= resizeImage($video->thumbnail, '690x446') ?>" alt="<?= $video->title ?>">
                            </div>
                            <div class="video-title flex-center-between">
                                <h4><?= $video->title ?></h4>
                                <span><?= $video->duration ?></span>
                            </div>
                        </div>
                    <?php } ?>

                </div>
            </div>
            <div class="col-md-6">
                <div class="video-3">

                    <div class="title-page text-left">
                        <a href="<?= !empty($page_media) ? getUrlPage($page_media) . '#gallery' : 'javascript;' ?>" title="">
                            <span class="text-blue">Thư viện</span>
                            <span class="text-brown"> hình ảnh</span>
                        </a>
                    </div>

                    <div class="row">

                        <?php if (!empty($gallery)) foreach ($gallery as $item) { ?>
                            <div class="col-md-6">
                                <div class="video-item relative">
                                    <a href="#" title="<?= $item->title ?>"
                                       class="video-link flex-center-center media-library"
                                       data-id="<?= $item->id ?>"></a>
                                    <div class="video-image">
                                        <img class="lazy" src="<?= getThumbLazy('660x426') ?>" data-src="<?= resizeImage($item->thumbnail, '660x426') ?>" alt="<?= $item->title ?>">
                                    </div>
                                    <div class="video-title flex-center-between">
                                        <h4><?= $item->title ?></h4>
                                        <i class="far fa-images"></i>
                                    </div>
                                </div>
                                <script>
                                    album['<?= $item->id ?>'] = <?= $item->album ?>;
                                </script>
                            </div>
                        <?php } ?>

                    </div>
                </div>
            </div>
        </div>
    </div>
</section>