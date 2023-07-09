
<section class="page-banner">
    <div class="img">
        <img class="lazy" src="<?= getThumbLazy('1920x35') ?>"
             data-src="<?= resizeImage($oneItem->banner, '1920x356') ?>"
             alt="<?= $oneItem->title ?>">
    </div>
    <div class="box-breadcrumb container">
        <h1 class="title wow zoomIn"
            style="visibility: visible; animation-name: zoomIn;"><?= $oneItem->title ?></h1>
        <ol class="breadcrumb wow fadeInUp delay02" style="visibility: visible; animation-name: fadeInUp;">
            <li class="breadcrumb-item"><a href="<?= site_url() ?>"><?= lang('home') ?></a></li>
            <li class="breadcrumb-item" aria-current="page"><?= $oneItem->title ?></li>
        </ol>
    </div>
</section>