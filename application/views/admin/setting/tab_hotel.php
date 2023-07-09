<?php
$name = 'hotel';
?>

<div class="tab-pane" id="tab_<?=$name?>">

    <fieldset class="form-group album-contain">
        <legend>Thông tin chung</legend>
        <?php
        $list_field_content = [
            ['name' => ['room', 'banner'] , 'title' => 'Hình nền', 'type' => 'input_media'],
            ['name' => ['room', 'num1'] , 'title' => 'Số phòng', 'type' => 'input_text'],
            ['name' => ['room', 'num2'] , 'title' => 'Số tầng', 'type' => 'input_text'],
            ['name' => ['room', 'num3'] , 'title' => 'Số nhân viên', 'type' => 'input_text'],
            ['name' => ['room', 'num4'] , 'title' => 'Số năm kinh doanh', 'type' => 'input_text'],
        ];
        $list_field_langs = [
            ['name' => ['room_' . $name, 'desc'], 'title' => 'Mô tả', 'type' => 'input_textarea']
        ];
        ?>
        <?= config_field_settings($name, $list_field_content) ?>
        <?php $this->load->view($this->template_path . 'setting/items/_item_lang', ['tab' => $name . '_about', 'list' => $list_field_langs]); ?>
    </fieldset>

</div>