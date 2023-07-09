<div class="form-group">
    <label> <?= $title ?><?php if (!empty($required)) showRequiredField(); ?> </label>
    <input type="number"
           placeholder="<?= $title ?>"
           class="form-control"
           value="<?= $value ?? ''?>"
           name="<?= $name ?>">
</div>