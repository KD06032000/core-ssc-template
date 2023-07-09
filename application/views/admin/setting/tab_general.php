<?php
$list_field_normal = [
    ['name' => 'logo', 'id' => 'logo', 'title' => 'Logo', 'type' => 'input_media'],
    ['name' => 'logo_ft', 'id' => 'logo_footer', 'title' => 'Logo_footer', 'type' => 'input_media'],
    ['name' => 'favicon', 'id' => 'favicon', 'title' => 'Favicon', 'type' => 'input_media'],
    ['name' => 'image_share_default', 'id' => 'image_share_default', 'title' => 'Hình ảnh chia sẽ mặc định', 'type' => 'input_media'],
];
$list_field_langs = [
    ['name' => ['meta', 'name'], 'title' => 'Tên website', 'type' => 'input_text'],
    ['name' => ['meta', 'description_web'], 'title' => 'Mô tả', 'type' => 'input_editor'],
    ['name' => ['meta', 'title'], 'title' => 'Tiêu đề SEO', 'type' => 'input_text'],
    ['name' => ['meta', 'meta_desc'], 'title' => 'Mô tả SEO', 'type' => 'input_textarea'],
];
?>
<div class="tab-pane active" id="<?= $target ?>">
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
                    <a href="#tab_<?php echo $lang_code; ?>" data-toggle="tab">
                        <img src="<?php echo $this->templates_assets; ?>/flag/<?php echo $lang_code ?>.png"> <?php echo $lang_name; ?>
                    </a>
                </li>
            <?php } ?>
        </ul>
        <div class="tab-content">
            <?php foreach ($this->config->item('cms_language') as $lang_code => $lang_name): ?>
                <div class="tab-pane <?php echo ($lang_code == 'vi') ? 'active' : ''; ?>"
                     id="tab_<?php echo $lang_code; ?>">
                    <?php if (!empty($list_field_langs)) foreach ($list_field_langs as $key => $item): ?>
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

                        $item['name'] = $name;
                        $item['value'] = $value;

                        $this->load->view($this->template_path . 'setting/items/' . $item['type'], $item);

                        ?>
                    <?php endforeach ?>
                </div>
            <?php endforeach; ?>
            <div class="form-group">
                <label>Meta Keyword</label>
                <label for="key"><span class="count-key">0</span>
                    / <?php echo $this->config->item('SEO_keyword_maxlength') ?></label>
                <input value="<?= !empty($meta[$lang_code]['meta_keyword']) ? $meta[$lang_code]['meta_keyword'] : '' ?>"
                       id="meta_keyword_<?php echo $lang_code ?>" name="meta[<?php echo $lang_code ?>][meta_keyword]"
                       placeholder="Meta Keyword" class="form-control tagsinput" data-role="tagsinput" type="text"/>
            </div>
        </div>
    </div>
</div>