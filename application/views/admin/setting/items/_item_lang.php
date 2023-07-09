
<?php
$list_lang = !empty($list) ? (array)$list : [];
$tab = !empty($tab) ? $tab : 'item_lang';
?>

<div class="box-body">
    <ul class="nav nav-tabs">
        <?php foreach ($this->config->item('cms_language') as $lang_code => $lang_name) { ?>
            <li<?php echo ($lang_code == 'vi') ? ' class="active"' : ''; ?>>
                <a href="#tab_<?=$tab?>_<?php echo $lang_code; ?>" data-toggle="tab"> <?php echo $lang_name; ?></a>
            </li>
        <?php } ?>
    </ul>
    <div class="tab-content">
        <?php foreach ($this->config->item('cms_language') as $lang_code => $lang_name) : ?>
            <div class="tab-pane <?php echo ($lang_code == 'vi') ? 'active' : ''; ?>" id="tab_<?=$tab?>_<?php echo $lang_code; ?>">
                <?php if (!empty($list_lang)) foreach ($list_lang as $key => $item) : ?>
                    <div class="form-group">
                        <?php
                        $field_name = $item['name'][0];
                        $child_name = @$item['name'][1];
                        if ($child_name) {
                            $item['name'] = "{$field_name}[{$lang_code}][{$child_name}]";
                            $item['value'] = !empty(${$field_name}[$lang_code][$child_name]) ? ${$field_name}[$lang_code][$child_name] : '';
                        } else {
                            $item['name'] = "{$field_name}[{$lang_code}]";
                            $item['value'] = !empty(${$field_name}[$lang_code]) ? ${$field_name}[$lang_code] : '';
                        }
                        $this->load->view($this->template_path . 'setting/items/' . $item['type'], $item);
                        ?>
                    </div>
                <?php endforeach ?>
            </div>
        <?php endforeach; ?>
    </div>
</div>