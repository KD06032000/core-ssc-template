<?php
$id = !empty($id) ? $id : '';
$name = !empty($name) ? $name : '';
$placeholder = !empty($placeholder) ? $placeholder : 'Chọn ảnh';
$value = isset($value) ? $value : '';

?>
<?php if (!empty($label)): ?> <label for=""><?= $label ?></label><?php endif; ?>
<div class="form-group">
    <?php if (!empty($title)) { ?> <label><?= $title ?></label> <?php } ?>
    <div class="input-group input-group-lg">
        <span class="input-group-addon" onclick="chooseImage('<?= $id ?>')"
              data-toggle="tooltip" title="<?php echo lang('btn_select_image'); ?>">
            <i class="fa fa-fw fa-image"></i>
        </span>
        <input id="<?php echo $id ?>" name="<?php echo $name ?>" value="<?php echo $value; ?>"
               placeholder="<?= $placeholder ?>" class="form-control"
               type="text"/>
        <span class="input-group-addon">
    <a class="fancybox" href="<?php echo getImageThumb($value) ?>"
       title="Click để xem ảnh">
      <img src="<?php echo getImageThumb($value, 64, 45) ?>"
           width="30" height="22px"> </a></span>
    </div>
</div>