<div class="form-group">
    <label> <?= $title ?> <?php if (!empty($required)) showRequiredField(); ?> </label>
    <div class="input-group">
        <span class="input-group-addon"><?= BASE_URL ?></span>
        <input type="text" name="<?= $name ?>" class="form-control input-link-url">
    </div>
</div>