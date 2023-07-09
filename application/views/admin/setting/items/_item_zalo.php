<?php
$item = !empty($item) ? (array)$item : null;
if (isset($_GET['id'])) $id = $_GET['id'];
if (isset($_GET['meta_key'])) $meta_key = $_GET['meta_key'];
?>

<fieldset>
    <div class="tab-pane">
        <div class="col-md-12">
            <div class="form-group">
                <label for="">Số điện thoại</label>
                <?= form_input($meta_key . "[" . $meta_key . $id . "][phone]",
                    !empty($item['phone']) ? $item['phone'] : ''
                    , ['class' => 'form-control', 'placeholder' => 'Số điện thoại']) ?>
            </div>
        </div>

        <ul class="nav nav-tabs">
            <?php foreach ($this->config->item('cms_language') as $lang_code => $lang_name) { ?>
                <li<?php echo ($lang_code == 'vi') ? ' class="active"' : ''; ?>>
                    <a href="#tab_zalo_service_<?=$meta_key?>_<?php echo $lang_code . $id; ?>" data-toggle="tab">
                        <?php echo $lang_name; ?>
                    </a>
                </li>
            <?php } ?>
        </ul>

        <div class="col-md-12 tab-content">
            <?php foreach ($this->config->item('cms_language') as $lang_code => $lang_name) { ?>
            <div class="tab-pane <?php echo ($lang_code == 'vi') ? 'active' : ''; ?>"
                 id="tab_zalo_service_<?=$meta_key?>_<?php echo $lang_code . $id; ?>">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="">Tên</label>
                            <?= form_input($meta_key . "[" . $meta_key . $id . "][$lang_code][name]",
                                !empty($item[$lang_code]['name']) ? $item[$lang_code]['name'] : ''
                                , ['class' => 'form-control', 'placeholder' => 'Tên']) ?>
                        </div>
                    </div>
                </div>
            </div>
            <?php } ?>
        </div>

        <i class="glyphicon glyphicon-trash removeInput" onclick="removeInputImage(this)"></i>
</fieldset>