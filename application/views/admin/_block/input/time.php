<div class="form-group">
    <label> <?= $title ?> <?php if (!empty($required)) showRequiredField(); ?> </label>
    <input type="time" name="<?= $name ?>" class="form-control">
</div>