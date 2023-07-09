<?php

defined('BASEPATH') OR exit('No direct script access allowed');
$id_file = !empty($id_image)?$id_image:'form_file';
$name_file = !empty($name_image)?$name_image:'file';
$value_file = !empty($value_image)?$value_file:'';
$label_file = !empty($label_file)?$label_file:'Tài liệu';

?>
<div class="form-group">
    <label><?php echo $label_file ?></label>
    <div class="input-group input-group-lg">
        <span class="input-group-addon" onclick="chooseFiles('<?php echo $id_file ?>')"><i class="fa fa-fw fa-file"></i><?php echo lang('btn_select_document');?></span>
        <input id="<?php echo $id_file ?>" onclick="chooseFiles('<?php echo $id_file ?>')" name="<?php echo $name_file ?>" placeholder="<?=$label_file?>" class="form-control" type="text" value="<?php echo $value_file; ?>"/>
    </div>
</div>
