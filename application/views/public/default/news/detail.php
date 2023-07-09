<main id="page_post">
    <section class="page-breadcrum page-detail relative">
        <div class="breadcrumbs breadcrumbs-detail flex-center-end">
            <div class="banner-detail-image relative">
                <div class="news-date border-radius-1011 absolute">
                    <p><?= date('d', strtotime($main->displayed_time)) ?></p>
                    <p> <?= date('m • Y', strtotime($main->displayed_time)) ?></p>
                </div>
                <img src="<?= resizeImage($main->thumbnail, '1083x443') ?>" alt="<?= $main->title ?>">
            </div>
        </div>
        <div class="text-abs">
            <div class="container">
                <div class="row">
                    <div class="col-md-5">
                        <div class="breadcrumb-text">
                            <p class="cate-name">
                                <a href="<?= getUrlCateNews($cate) ?>" title=""><?= $cate->title ?></a>
                            </p>
                            <h1><?= $main->title ?></h1>
                            <div class="news-action flex-center-between">

                                <?php if (!empty($tagFirst)) foreach ($tagFirst as $item) { ?>
                                    <a href="<?= getUrlTag($item) ?>"
                                       title="<?= $item->title ?>" class="tag">
                                        <i class="fas fa-tag"></i><?= $item->title ?>
                                    </a>
                                <?php } ?>

                                <?php $this->load->view($this->template_path . '_block/button_share'); ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="news-detail">
        <div class="container">
            <div class="row">
                <div class="col-md-8">
                    <div class="article-detail">
                        <div class="desc"><?= $main->description ?></div>
                        <?= $main->content ?>
                    </div>
                    <div class="news-action mgt-20 flex-center-between">

                        <?php if (!empty($tagFirst)) foreach ($tagFirst as $item) { ?>
                            <a href="<?= getUrlTag($item) ?>"
                               title="<?= $item->title ?>" class="tag">
                                <i class="fas fa-tag"></i><?= $item->title ?>
                            </a>
                        <?php } ?>

                        <?php $this->load->view($this->template_path . '_block/button_share'); ?>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="sticky-top news-sticky">
                        <div class="title-page text-left">
                            <a href="" title="">
                                <span class="text-blue">Tin</span>
                                <span class="text-brown">liên quan</span>
                            </a>
                        </div>
                        <div class="news-4">
                            <div class="flex-mb">
                                <?php if (!empty($list_related)) foreach ($list_related as $item) { ?>
                                    <div class="news-item news-item-hot">
                                        <div class="news-inner">
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <div class="news-image">
                                                        <a href="<?= getUrlNews($item) ?>"
                                                           title="<?= $item->title ?>" class="zoom">
                                                            <img src="<?= resizeImage($item->thumbnail, '131x131') ?>"
                                                                 alt="<?= $item->title ?>">
                                                        </a>
                                                    </div>
                                                </div>
                                                <div class="col-md-8">
                                                    <div class="news-text">
                                                        <h4><a href="<?= getUrlNews($item) ?>"
                                                               title="<?= $item->title ?>"><?= $item->title ?></a>
                                                        </h4>
                                                        <div class="news-action">
                                                            <?php
                                                            $tags = getDataTagByBlogId($item->id);
                                                            if (!empty($tags)) foreach ($tags as $tag) { ?>
                                                                <a href="<?= getUrlTag($tag) ?>"
                                                                   title="<?= $tag->title ?>"
                                                                   class="tag">
                                                                    <i class="fas fa-tag"></i><?= $tag->title ?>
                                                                </a>
                                                            <?php } ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php } ?>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="news-related">
        <div class="container">
            <div class="title-page text-left">
                <a href="" title="">
                    <span class="text-blue">Tin</span>
                    <span class="text-brown"> mới nhất</span>
                </a>
            </div>
            <div class="news-slider cate-list ">
                <?php if (!empty($list_new)) foreach ($list_new as $item) { ?>
                    <div class="news-wrap">
                        <div class="news-item news-item-hot">
                            <div class="news-date border-radius-1011">
                                <p><?= date('d', strtotime($item->displayed_time)) ?></p>
                                <p> <?= date('m • Y', strtotime($item->displayed_time)) ?></p>
                            </div>
                            <div class="news-inner">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="news-image">
                                            <a href="<?= getUrlNews($item) ?>" title="<?= $item->title ?>" class="zoom">
                                                <img src="<?= resizeImage($item->thumbnail, '619x348') ?>"
                                                     alt="<?= $item->title ?>">
                                            </a>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="news-text">
                                            <h4><a href="<?= getUrlNews($item) ?>"
                                                   title=""><?= $item->title ?></a></h4>
                                            <p class="desc"><?= $item->description ?></p>
                                            <div class="news-action flex-center-between">
                                                <div class="tags">
                                                    <?php
                                                    $tags = getDataTagByBlogId($item->id);
                                                    if (!empty($tags)) foreach ($tags as $tag) { ?>
                                                        <a href="<?= getUrlTag($tag) ?>" title="<?= $tag->title ?>"
                                                           class="tag">
                                                            <i class="fas fa-tag"></i><?= $tag->title ?>
                                                        </a>
                                                    <?php } ?>
                                                </div>
                                                <a href="<?= getUrlNews($item) ?>" title="<?= $item->title ?>"
                                                   class="read">Chi
                                                    tiết</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php } ?>
            </div>
        </div>
    </section>
</main>