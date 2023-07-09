<div class="pd-60">
    <div class="row">

        <?php if (!empty($cate_left)) { ?>
            <div class="col-md-8">
                <div class="news-8">
                    <div class="title-page text-left">
                        <a href="<?= getUrlCateNews($cate_left) ?>" title="<?= $cate_left->title ?>">
                            <span class="text-blue"><?= str_replace($cate_left->title_highlight, ' ', $cate_left->title) ?></span>
                            <span class="text-grown"><?= $cate_left->title_highlight ?></span>
                        </a>
                    </div>

                    <?php
                    if (!empty($blog_cate_left)) {
                        $first = $blog_cate_left[0];
                        $tagFirst = getDataTagByBlogId($first->id);
                        $last = array_slice($blog_cate_left, 1);
                        ?>

                        <div class="news-item news-item-hot">
                            <div class="news-date border-radius-1011">
                                <p><?= date('d', strtotime($first->displayed_time)) ?></p>
                                <p> <?= date('m • Y', strtotime($first->displayed_time)) ?></p>
                            </div>
                            <div class="news-inner">
                                <div class="row">
                                    <div class="col-md-7">
                                        <div class="news-image">
                                            <a href="<?= getUrlNews($first) ?>" title="<?= $first->title ?>"
                                               class="zoom">
                                                <img class="lazy" src="<?= getThumbLazy('619x348') ?>"
                                                     data-src="<?= resizeImage($first->thumbnail, '619x348') ?>"
                                                     alt="<?= $first->title ?>">
                                            </a>
                                        </div>
                                    </div>
                                    <div class="col-md-5">
                                        <div class="news-text">
                                            <h4><a href="<?= getUrlNews($first) ?>"
                                                   title=<?= $first->title ?>""><?= $first->title ?></a>
                                            </h4>
                                            <p class="desc"><?= $first->description ?></p>
                                            <div class="news-action flex-center-between">
                                                <div class="tags">
                                                    <?php if (!empty($tagFirst)) foreach ($tagFirst as $item) { ?>
                                                        <a href="<?= getUrlTag($item) ?>"
                                                           title="<?= $item->title ?>" class="tag">
                                                            <i class="fas fa-tag"></i><?= $item->title ?>
                                                        </a>
                                                    <?php } ?>
                                                </div>
                                                <a href="<?= getUrlNews($first) ?>"
                                                   title="<?= $first->title ?>" class="read">Chi tiết</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <?php if (!empty($last)) foreach ($last as $item) { ?>
                            <div class="news-item news-item-nor">
                                <div class="news-date border-radius-1011">
                                    <p><?= date('d', strtotime($item->displayed_time)) ?></p>
                                    <p> <?= date('m • Y', strtotime($item->displayed_time)) ?></p>
                                </div>
                                <div class="news-inner">
                                    <div class="news-text">
                                        <h4><a href="<?= getUrlNews($item) ?>"
                                               title=""><?= $item->title ?></a></h4>
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
                                               class="read">Chi tiết</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>

                    <?php } ?>

                </div>
            </div>
        <?php } ?>


        <?php if (!empty($cate_right)) { ?>
            <div class="col-md-4">
                <div class="title-page text-left">
                    <a href="<?= getUrlCateNews($cate_right) ?>" title="<?= $cate_right->title ?>">
                        <span class="text-blue"><?= str_replace($cate_right->title_highlight, ' ', $cate_right->title) ?></span>
                        <span class="text-grown"><?= $cate_right->title_highlight ?></span>
                    </a>
                </div>
                <?php if (!empty($blog_cate_right)) { ?>
                    <div class="news-4">
                        <div class="flex-mb">
                            <?php foreach ($blog_cate_right as $item) { ?>
                                <div class="news-item news-item-hot">
                                    <div class="news-inner">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="news-image">
                                                    <a href="<?= getUrlNews($item) ?>"
                                                       title="<?= $item->title ?>" class="zoom">
                                                        <img class="lazy" src="<?= getThumbLazy('131x131') ?>"
                                                             data-src="<?= resizeImage($item->thumbnail, '131x131') ?>"
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
                        <div class="flex-center-end">
                            <a href="<?= getUrlCateNews($cate_right) ?>" title="" class="read">Xem tất
                                cả</a>
                        </div>
                    </div>
                <?php } ?>
            </div>
        <?php } ?>

    </div>
</div>