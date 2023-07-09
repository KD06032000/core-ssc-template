<?php
$cms_language = $this->config->item('cms_language');
$list = !empty($list) ? (array)$list : [];
?>

<div class="box-body">
    <div class="row" style="display: flex; flex-wrap: wrap">

        <?php if (!empty($list)) foreach ($list as $key => $item) { ?>

            <div class="<?= !empty($class) ? $class : 'col-md-12' ?>" style="margin-bottom: 10px">
                <?php
                $item['id'] = !empty($item['id']) ? $item['id'] : $item['name'];
                $this->load->view($this->template_path . '_block/input/' . $item['type'], $item);
                ?>
            </div>

        <?php } ?>
    </div>
</div>
