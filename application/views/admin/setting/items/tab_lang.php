<ul class="nav nav-tabs">
  <?php foreach ($this->config->item('cms_language') as $lang_code => $lang_name) { ?>
    <li<?php echo ($lang_code == 'vi') ? ' class="active"' : ''; ?>>
      <a href="#<?php echo $id ?>_<?php echo $lang_code ; ?>" data-toggle="tab">
        <?php echo $lang_name; ?>
      </a>
    </li>
  <?php } ?>
</ul>