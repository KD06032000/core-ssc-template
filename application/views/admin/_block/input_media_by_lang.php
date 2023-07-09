<ul class="nav nav-pills">
  <?php if (!empty($this->config->item('cms_language'))) foreach ($this->config->item('cms_language') as $lang_code => $lang_name) { ?>
    <li<?php echo ($lang_code == 'vi') ? ' class="active"' : ''; ?>><a
          href="#tab_<?php echo $id_image ?>_<?php echo $lang_code; ?>"
          data-toggle="tab"><?php echo $lang_name; ?></a>
    </li>
  <?php } ?>
</ul>
<div class="tab-content">
  <?php if (!empty($this->config->item('cms_language'))) foreach ($this->config->item('cms_language') as $lang_code => $lang_name) { ?>
    <div class="tab-pane <?php echo ($lang_code == 'vi') ? 'active' : ''; ?>"
         id="tab_<?php echo $id_image ?>_<?php echo $lang_code; ?>">
      <div class="box-body">
        <div class="row">
          <?php
          foreach ($list as $item) {
            $this->load->view($this->template_path . '_block/input_media', [
              'id_image' => $item['id_image'] . '_' . $lang_code,
              'name_image' => $item['name_image'] . '[' . $lang_code . ']',
              'label_image' => $item['label_image'],
              'value_image' => $item['value_image'],
            ]);
          }
          ?>
        </div>
      </div>
    </div>
  <?php } ?>

</div>