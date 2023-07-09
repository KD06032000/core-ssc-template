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
          <a href="#tab_<?php echo $lang_code . $id; ?>" data-toggle="tab">
            <?php echo $lang_name; ?>
          </a>
        </li>
      <?php } ?>
    </ul>
    <div class="tab-content">
      <?php foreach ($this->config->item('cms_language') as $lang_code => $lang_name) { ?>
        <div class="tab-pane <?php echo ($lang_code == 'vi') ? 'active' : ''; ?>"
             id="tab_<?php echo $lang_code . $id; ?>">
          <fieldset style="" class="">
            <div class="col-md-10">
              <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                    <?= form_input($meta_key . "[" . $meta_key . $id . "][$lang_code][title]",
                      !empty($item[$lang_code]['title']) ? $item[$lang_code]['title'] : ''
                      , ['class' => 'form-control', 'placeholder' => 'Title']) ?>

                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <?= form_input($meta_key . "[" . $meta_key . $id . "][$lang_code][link]",
                      !empty($item[$lang_code]['link']) ? $item[$lang_code]['link'] : ''
                      , ['class' => 'form-control', 'placeholder' => 'link']) ?>
                  </div>
                </div>
                <div class="col-md-12">
                  <?= form_textarea($meta_key . "[" . $meta_key . $id . "][$lang_code][desc]",
                    !empty($item[$lang_code]['desc']) ? $item[$lang_code]['desc'] : ''
                    , ['class' => 'form-control tinymce-more', 'row' => 4, 'placeholder' => 'Mô tả']) ?>
                </div>
              </div>
            </div>
            <div class="col-md-2">
              <input
                  id="<?php echo $meta_key . '_' . $lang_code . '_' . $id; ?>"
                  name="<?php echo $meta_key; ?>[<?php echo $meta_key . $id; ?>][<?php echo $lang_code ?>][img]"
                  placeholder="Choose image..."
                  value="<?php echo !empty($item[$lang_code]['img']) ? $item[$lang_code]['img'] : '' ?>"
                  class="form-control col-md-6" type="hidden"
                  style="width: 50%"/>
              <img onclick="chooseImage('<?php echo $meta_key . '_' . $lang_code . '_' . $id; ?>')"
                   src="<?php echo isset($item[$lang_code]['img']) ? getImageThumb($item[$lang_code]['img']) : 'http://via.placeholder.com/100x50'; ?>"
                   alt="" height="50">
            </div>
          </fieldset>
        </div>
      <?php } ?>
      <div class="col-md-12">
        <?= form_input($meta_key . "[" . $meta_key . $id . "][sales]",
          !empty($item['sales']) ? $item['sales'] : ''
          , ['class' => 'form-control datetimepicker', 'row' => 4, 'placeholder' => 'Thời gian khuyến mại']) ?>
      </div>
    </div>
  </div>
  <i class="glyphicon glyphicon-trash removeInput" onclick="removeInputImage(this)"></i>
</fieldset>