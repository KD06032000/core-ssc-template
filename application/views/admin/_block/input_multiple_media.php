<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$name='album[]';
$box='gallery';
?>
<div class="form-group">
  <fieldset class="form-group album-contain">
    <legend>Album ảnh</legend>
    <div data-id="0" id="<?=$box?>" class="gallery"></div>
    <div class="clearfix"></div>
    <p class="error-multiple-media"></p>
    <div class="col-md-12">
      <button type="button" class="btn btn-primary btnAddMore"
              onclick="chooseMultipleMediaName('<?=$box?>','<?php echo $name ?>')">
        <i class="fa fa-plus"> <?php echo lang('btn_add'); ?> </i>
      </button>
    </div>
  </fieldset>
</div>
