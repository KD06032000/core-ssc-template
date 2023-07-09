<?php

defined('BASEPATH') OR exit('No direct script access allowed');
$display_button = !empty($display_button) ? $display_button : [];
$controller = $this->router->fetch_class();
?>
<div class="col-sm-5 col-xs-12 text-right">

    <?php if(in_array($controller, ['redirect'])):?>
        <?php showButtonExport() ?>
    <?php endif;?>

    <?php if($controller =='stock' ):?>
        <button class="btn btn-warning" type="button" onclick="add_stock_in()">
                <i class="glyphicon glyphicon-circle-arrow-left"></i> <?php echo lang('title_stock_in');?>
        </button>
        <button class="btn btn-warning" type="button" onclick="add_stock_out()">
                <i class="glyphicon glyphicon-circle-arrow-right"></i> <?php echo lang('title_stock_out');?>
        </button>
    <?php endif;?>
  <?php button_admin($display_button) ?>
    <?php if(in_array('copy',$display_button)): ?>

        <button class="btn btn-info" type="button" type="button" onclick="copy_multiple()">
            <i class="fa fa-fw fa-copy"></i> <?php echo lang('btn_copy');?>
        </button>
    <?php endif; ?>
    <button class="btn btn-default" type="button" type="button" onclick="reload_table()">
        <i class="glyphicon glyphicon-refresh"></i> <?php echo lang('btn_reload');?>
    </button>
</div>