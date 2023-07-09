<?php
defined('BASEPATH') or exit('No direct script access allowed');
$name = !empty($name) ? $name : 'album';
$value = !empty(${$name}) ? ${$name} : [];
$name = $name . '[]';
$box = !empty($box) ? $box : 'gallery';
$placeholder = !empty($placeholder) ? $placeholder : 'Album ảnh';
?>
<div class="form-group">
    <label><?= $placeholder ?></label>
    <div data-id="<?= count($value) ?>" id="<?= $box ?>" class="gallery">

        <?php if (!empty($value)) foreach ($value as $key => $item) { ?>
            <div class="item_gallery item_<?= $key + 1 ?>" data-count="<?= $key + 1 ?>">
                <a href="<?= MEDIA_URL . $item ?>" data-fancybox="gallery">
                    <img src="<?= MEDIA_URL . $item ?>" id="item_1" height="120">
                </a>
                <input type="hidden" name="<?= $name ?>" value="<?= $item ?>">
                <span class="fa fa-times removeInput" onclick="removeInputImage(this)"></span>
            </div>
        <?php } ?>

    </div>
    <div class="clearfix"></div>
    <p class="error-multiple-media"></p>
    <div class="col-md-12">
        <button type="button" class="btn btn-primary btnAddMore"
                onclick="chooseMultipleMediaName('<?= $box ?>','<?php echo $name ?>')">
            <i class="fa fa-plus"> <?php echo lang('btn_add'); ?> </i>
        </button>
    </div>
</div>
