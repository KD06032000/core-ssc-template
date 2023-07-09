<div class="page detail-page" data-url="<?= getUrlPage($main) ?>">

    <?php $this->load->view($this->template_path . '_block/banner_page'); ?>

    <div class="main-page">
        <div class="container">
            <div class="row page-about">
                <div class="col-lg-3">
                    <div class="control-sidebar-wrap wow fadeInUp">

                        <ul class="control-sidebar">

                            <?php if (!empty($types)) foreach ($types as $key => $value) { ?>
                                <li><a class="<?= $main->id == $value->id ? 'active' : '' ?>"
                                       href="<?= getUrlPage($value) ?>"><?= $value->title ?></a></li>
                            <?php } ?>

                        </ul>

                        <?php $this->load->view($this->template_path . '_block/item_contact'); ?>

                    </div>
                </div>
                <div class="col-lg-9">
                    <?php if (!empty($main)) { ?>
                        <div class="wrap-content-about wow fadeInUp delay02">
                            <h3 class="title-pri"><?= $main->title ?></h3>
                            <div class="s-content">
                                <?= $main->content ?>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
</div>