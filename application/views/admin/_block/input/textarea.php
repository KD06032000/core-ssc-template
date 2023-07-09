<div class="form-group">
    <label><?= $title ?> <?php if (!empty($required)) showRequiredField(); ?></label>
    <?php $class = !empty($tinymce) ? 'tinymce' : '' ?>
    <?= form_textarea($name,'',['class'=> $class . ' form-control', 'id'=>$id, 'placeholder'=>$title],!empty($row) ? $row : 4) ?>
</div>