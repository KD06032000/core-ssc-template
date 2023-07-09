<section class="philosophy">
    <div class="container">
        <div class="title-page">
            <span class="text-blue">Triết lý &</span>
            <span class="text-brown">Sứ mệnh</span>
        </div>
        <div class="philosophy-content">
            <div class="row">

                <?php if (!empty($philosophy)) foreach ($philosophy as $item) { ?>
                    <div class="col-md-4">
                        <a href="javascript:;" title="" class="philosophy-item">
                            <div class="philo-title">
                                <?= $item[$this->lang_code]['title'] ?>
                            </div>
                            <div class="desc">
                                <?= $item[$this->lang_code]['desc'] ?>
                            </div>
                            <div class="philo-image">
                                <img src="<?= getThumbLazy() ?>" data-src="<?= resizeImage($item['img']) ?>"
                                     alt="<?= $item[$this->lang_code]['title'] ?>" class="lazy img-hover">
                                <img src="<?= getThumbLazy() ?>" data-src="<?= resizeImage($item['img']) ?>"
                                     alt="<?= $item[$this->lang_code]['title'] ?>" class="lazy img-normal">
                            </div>
                        </a>
                    </div>
                <?php } ?>

            </div>
        </div>

        <?php $this->load->view($this->template_path . 'home/_block/_item_blog'); ?>

    </div>
</section>