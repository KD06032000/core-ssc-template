<?php
$item = !empty($item) ? (array)$item : null;
if (isset($_GET['id'])) $id = $_GET['id'];
if (isset($_GET['meta_key'])) $meta_key = $_GET['meta_key'];
?>
<fieldset>
    <div class="tab-pane">
        <ul class="nav nav-tabs">
            <?php foreach ($this->config->item('cms_language') as $lang_code => $lang_name) { ?>
                <li<?php echo ($lang_code == 'vi') ? ' class="active"' : ''; ?>>
                    <a href="#tab_<?=$meta_key?>_<?php echo $lang_code . $id; ?>" data-toggle="tab">
                        <?php echo $lang_name; ?>
                    </a>
                </li>
            <?php } ?>
        </ul>
        <div class="col-md-10 tab-content">
            <?php foreach ($this->config->item('cms_language') as $lang_code => $lang_name) { ?>
                <div class="tab-pane <?php echo ($lang_code == 'vi') ? 'active' : ''; ?>"
                     id="tab_<?=$meta_key?>_<?php echo $lang_code . $id; ?>">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <?= form_input($meta_key . "[" . $meta_key . $id . "][$lang_code][title]",
                                    !empty($item[$lang_code]['title']) ? $item[$lang_code]['title'] : ''
                                    , ['class' => 'form-control', 'placeholder' => 'Title']) ?>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <?= form_textarea($meta_key . "[" . $meta_key . $id . "][$lang_code][desc]",
                                !empty($item[$lang_code]['desc']) ? $item[$lang_code]['desc'] : ''
                                , ['class' => 'form-control', 'row' => 8, 'placeholder' => 'Mô tả']) ?>
                        </div>
                    </div>
                </div>
            <?php } ?>
        </div>
        <div class="col-md-12">
            <div class="form-group">
                <fieldset class="form-group album-contain">
                    <legend>Album ảnh</legend>
                    <div data-id="0" id="<?= $meta_key . '_' . $id; ?>" class="gallery">
                        <?php if (!empty($item['album'])) foreach ($item['album'] as $key => $value) { ?>
                            <div class="item_gallery item_<?= $key+1 ?>" data-count="<?= $key+1 ?>">
                                <a href="<?= getImageThumb($value)?>" data-fancybox="gallery">
                                    <img src="<?= getImageThumb($value)?>" id="item_<?= $key+1 ?>" height="120">
                                </a>
                                <input type="hidden" name="<?php echo $meta_key; ?>[<?php echo $meta_key . $id; ?>][album][]" value="<?= $value?>">
                                <span class="fa fa-times removeInput" onclick="removeInputImage(this)"></span>
                            </div>
                        <?php } ?>
                    </div>
                    <div class="clearfix"></div>
                    <p class="error-multiple-media"></p>
                    <div class="col-md-12">
                        <button type="button" class="btn btn-primary btnAddMore"
                                onclick="chooseMultipleMediaName('<?= $meta_key . '_' . $id; ?>','<?php echo $meta_key; ?>[<?php echo $meta_key . $id; ?>][album][]')">
                            <i class="fa fa-plus"> <?php echo lang('btn_add'); ?> </i>
                        </button>
                    </div>
                </fieldset>
            </div>
        </div>
    </div>
    <i class="glyphicon glyphicon-trash removeInput" onclick="removeInputImage(this)"></i>
</fieldset>