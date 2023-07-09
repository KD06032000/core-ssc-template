<?php
$list_field_langs = [
//    ['title' => 'Tên', 'name' => ['company', 'name'], 'type' => 'input_text'],
    ['title' => 'Địa chỉ', 'name' => ['company', 'address'], 'type' => 'input_text'],
];
$list_field_normal = [
    ['title' => 'Hotline', 'name' => 'phone', 'type' => 'input_text'],
//    ['title' => 'Tel', 'name' => 'tel', 'type' => 'input_text'],
    ['title' => 'Email', 'name' => 'email_site', 'type' => 'input_text'],
];
?>

<div class="tab-pane" id="<?= $target ?>">
    <fieldset class="form-group album-contain">
        <legend>Thông tin chung</legend>
        <div class="box-body">
            <?php if (!empty($list_field_normal)) foreach ($list_field_normal as $key => $item):
                $item['value'] = !empty(${$item['name']}) ? ${$item['name']} : '';
                $this->load->view($this->template_path . 'setting/items/' . $item['type'], $item);
                ?>
            <?php endforeach ?>
        </div>
        <div class="box-body">
            <ul class="nav nav-tabs">
                <?php foreach ($this->config->item('cms_language') as $lang_code => $lang_name) { ?>
                    <li<?php echo ($lang_code == 'vi') ? ' class="active"' : ''; ?>>
                        <a href="#tab_social_<?php echo $lang_code; ?>" data-toggle="tab">
                            <img src="<?php echo $this->templates_assets; ?>/flag/<?php echo $lang_code ?>.png"> <?php echo $lang_name; ?>
                        </a>
                    </li>
                <?php } ?>
            </ul>
            <div class="tab-content">
                <?php foreach ($this->config->item('cms_language') as $lang_code => $lang_name): ?>
                    <div class="tab-pane <?php echo ($lang_code == 'vi') ? 'active' : ''; ?>"
                         id="tab_social_<?php echo $lang_code; ?>">
                        <?php if (!empty($list_field_langs)) foreach ($list_field_langs as $key => $item): ?>
                            <div class="form-group">
                                <label><?= $item['title'] ?></label>
                                <?php
                                $field_name = $item['name'][0];
                                $child_name = @$item['name'][1];
                                if ($child_name) {
                                    $name = "{$field_name}[{$lang_code}][{$child_name}]";
                                    $value = !empty(${$field_name}[$lang_code][$child_name]) ? ${$field_name}[$lang_code][$child_name] : '';
                                } else {
                                    $name = "{$field_name}[{$lang_code}]";
                                    $value = !empty(${$field_name}[$lang_code]) ? ${$field_name}[$lang_code] : '';
                                }
                                echo form_input($name, $value, ['class' => 'form-control', 'placeholder' => $item['title']]);
                                ?>
                            </div>
                        <?php endforeach ?>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </fieldset>
</div>