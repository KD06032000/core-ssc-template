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
        <div class="tab-content">
            <?php foreach ($this->config->item('cms_language') as $lang_code => $lang_name) { ?>
                <div class="tab-pane <?php echo ($lang_code == 'vi') ? 'active' : ''; ?>"
                     id="tab_<?=$meta_key?>_<?php echo $lang_code . $id; ?>">
                    <fieldset style="" class="">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <?= form_input($meta_key . "[" . $meta_key . $id . "][$lang_code][title]",
                                        !empty($item[$lang_code]['title']) ? $item[$lang_code]['title'] : ''
                                        , ['class' => 'form-control', 'placeholder' => 'Điểm nổi bật']) ?>
                                </div>
                            </div>
                        </div>
                    </fieldset>
                </div>
            <?php }?>
        </div>
    </div>
    <i class="glyphicon glyphicon-trash removeInput" onclick="removeInputImage(this)"></i>
</fieldset>