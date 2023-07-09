<div class="form-group">
    <?php $class = !empty($class) ? $class : '' ?>
    <label><?= $title ?> <?php if (!empty($required)) showRequiredField(); ?></label>
    <div class="input-group colorpicker-component">
        <?= form_input($name,'#ffffff',['class'=>'form-control ' . $class, 'id'=>$id, 'placeholder'=>$title]) ?>
        <span class="input-group-addon"><i></i></span>
    </div>
</div>
