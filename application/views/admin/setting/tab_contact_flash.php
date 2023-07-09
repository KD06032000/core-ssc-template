<?php

$list_field_normal = [
    ['title' => 'Hotline', 'name' => 'phone_popup', 'type' => 'input_text'],
    ['title' => 'ID Mess', 'name' => 'id_mess_popup', 'type' => 'input_text'],
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
    </fieldset>
</div>