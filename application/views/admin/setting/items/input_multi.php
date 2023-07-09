<?php

$id = !empty($id) ? $id : '';
$layout = !empty($layout) ? $layout : $id;
$total = 0;
if (!empty(${$id})):
    $total = getNumberics(${$id});
endif;
?>
<div data-id="<?php echo $total ?>" id="<?= $id ?>">
    <?php
    if (!empty(${$id})) foreach (${$id} as $key => $item):
        $this->load->view($this->template_path . 'setting/items/_item_' . $layout, ['item' => $item, 'meta_key' => $id, 'id' => preg_replace('/[^0-9]/', '', $key)]);
    endforeach;
    ?>
</div>
<button type="button" class="btn btn-primary btn-sm btnAddMore"
        onclick="addInputElementSettings('<?= $id ?>', document.getElementById('<?= $id ?>').getAttribute('data-id'), null, '<?= $layout ?>', 'ajax_load_item', true)">
    <i class="fa fa-plus"> <?php echo lang('btn_add'); ?> </i>
</button>