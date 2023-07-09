<div class="form-group">
    <label><?= $title ?></label>
    <?php $class = !empty($tinymce) ? 'tinymce' : '' ?>
    <?= form_textarea($name,$value,['class'=> $class . ' form-control', 'placeholder'=>$title],!empty($row) ? $row : 8) ?>
</div>