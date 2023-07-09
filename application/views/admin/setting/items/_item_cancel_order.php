<?php
$item = !empty($item) ? (array)$item : null;
if (isset($_GET['id'])) $id = $_GET['id'];
if (isset($_GET['meta_key'])) $meta_key = $_GET['meta_key'];
?>
<fieldset>
  <div class="tab-pane">
    <ul class="nav nav-tabs">
      <?php foreach ($this->config->item('cms_language') as $lang_code => $lang_name) { ?>
        <li<?php echo ($lang_code == 'vi') ? ' class="active"' : ''; ?>>
          <a href="#tab_cancel_order_<?php echo $lang_code . $id; ?>" data-toggle="tab">
            <?php echo $lang_name; ?>
          </a>
        </li>
      <?php } ?>
    </ul>
    <div class="tab-content">
      <?php foreach ($this->config->item('cms_language') as $lang_code => $lang_name) { ?>
        <div class="tab-pane <?php echo ($lang_code == 'vi') ? 'active' : ''; ?>"
             id="tab_cancel_order_<?php echo $lang_code . $id; ?>">
          <fieldset style="" class="">
            <div class="col-md-12">
              <div class="form-group">
                <?= form_input($meta_key . "[" . $meta_key . $id . "][$lang_code]",
                  !empty($item[$lang_code]) ? $item[$lang_code] : ''
                  , ['class' => 'form-control', 'placeholder' => 'Lý do']) ?>
              </div>
            </div>
          </fieldset>
        </div>
      <?php } ?>
    </div>
  </div>
  <i class="glyphicon glyphicon-trash removeInput" onclick="removeInputImage(this)"></i>
</fieldset>