<div class="main-content-new">
    <div class="list-document">
        <div class="row">
            <div class="col-md-6">
                <h3 class="title-pri mt-2"><?= lang('document') ?></h3>
            </div>
            <div class="col-md-6">

                <?= form_open("room/ajax_call", array('id' => 'form-search-document', 'class' => 'form-search-document form_submit')) ?>
                <input type="text" name="keyword" data-old="<?= $keyword ?? '' ?>" value="<?= $keyword ?? '' ?>"
                       class="search-year input" placeholder="Tìm kiểm ...">

                <?php $type_price = !empty($type_price) ? $type_price : '' ?>
                <select class="search-year input" name="type" type="text" data-old="<?= $type_price ?? '' ?>" value="<?= $type_price ?? '' ?>">
                    <option value="">Tất cả</option>
                    <option <?= (int)$type_price === 1 ? 'selected' : '' ?> value="1">Có phí</option>
                    <option <?= (int)$type_price === 2 ? 'selected' : '' ?> value="2">Miễn phí</option>
                </select>
                <?= form_close() ?>

            </div>
        </div>

        <?php if (!empty($data)) foreach ($data as $key => $value) { ?>
            <div class="item">
                <div class="content"><i class="fal fa-file-alt"></i>
                    <div class="title-wrap">
                        <p>
                            <?php $allowSee = in_array(substr($value->file, -3), ['pdf', 'png', 'jpg', 'jpeg', 'gif']); ?>
                            <a class="title smooth" data-id="<?= $value->id ?>">
                                <?= $value->title ?>
                            </a>
                        </p>
                        <span class="time">
                                <i class="far fa-calendar mr-2"></i><?= formatDate($value->displayed_time, 'd.m.Y') ?>
                            </span>
                    </div>
                </div>
                <?php if ((int)$value->price) { ?>
                    <a class="btn-more pay_doc" href="#" data-id="<?= $value->id ?>">
                        <?= lang('download') ?><i class="fas fa-lock-alt ml-2"></i>
                    </a>
                <?php } else { ?>
                    <a class="btn-more" href="<?= MEDIA_URL . $value->file ?>" download="">
                        <?= lang('download') ?><i class="fas fa-arrow-alt-circle-down ml-2"></i>
                    </a>
                <?php } ?>

            </div>
        <?php } else { ?>
            <div class="text-center no-find-result empty-result">
                <p class="text-center"><?= lang('no_find_result') ?></p>
            </div>
        <?php } ?>


        <?= $pagination ?>

    </div>
</div>
