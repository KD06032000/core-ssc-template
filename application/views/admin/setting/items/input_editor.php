<div class="form-group">
    <label><?= $title ?></label>
    <?= form_textarea($name,$value,['class'=> 'tinymce form-control', 'placeholder'=>$title],!empty($row) ? $row : 8) ?>
</div>