<?php
/**
 * Created by PhpStorm.
 * User: Tinhpx
 * Date: 17/2/2020
 */
defined('BASEPATH') OR exit('No direct script access allowed');

?>

<div class="form-group">
  <label>Banner</label>
  <div class="input-group input-group-lg">
    <span class="input-group-addon" onclick="chooseImage('form_thumbnail1')"><i class="fa fa-fw fa-image"></i><?php echo lang('btn_select_image'); ?></span>
    <input id="form_thumbnail1" onclick="chooseImage('form_thumbnail1')" name="banner" placeholder="Banner" class="form-control" type="text" value="" />
    <span class="input-group-addon" style="padding: 0;"><a href="<?php echo getImageThumb(); ?>" class="fancybox"><img src="<?php echo getImageThumb(); ?>" width="44" height="44"></a></span>
  </div>
</div>