<ul class="nav nav-pills">
  <?php if(!empty($this->config->item('cms_language'))) foreach ($this->config->item('cms_language') as $lang_code => $lang_name){ ?>
    <li<?php echo ($lang_code == 'vi') ? ' class="active"' : '';?>><a href="#tab_gllery_<?php echo $lang_code;?>" data-toggle="tab"><?php echo $lang_name;?></a></li>
  <?php } ?>
</ul>
<div class="tab-content">
  <?php if(!empty($this->config->item('cms_language')))  foreach ($this->config->item('cms_language') as $lang_code => $lang_name){ ?>
    <div class="tab-pane <?php echo ($lang_code == 'vi') ? 'active' : '';?>" id="tab_gllery_<?php echo $lang_code;?>">
      <div class="box-body">
        <div class="row">
          <?php $this->load->view($this->template_path . '_block/input_multiple_media',['name'=>'album['.$lang_code.'][]','box'=>'gallery_'.$lang_code]) ?>
        </div>
      </div>
    </div>
  <?php } ?>
</div>