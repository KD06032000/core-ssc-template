<div class="form-group">
    <label><?= $title ?> <?php if (!empty($required)) showRequiredField(); ?></label>
    <?php $class = !empty($class) ? $class : '' ?>
    <?= form_input($name,'',['class'=>'form-control ' . $class, 'id'=>$id, 'placeholder'=>$title]) ?>
</div>
