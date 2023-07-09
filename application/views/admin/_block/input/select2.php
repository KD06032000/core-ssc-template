<div class="form-group">
    <label> <?= $title ?> <?php if (!empty($required)) showRequiredField(); ?> </label>
    <select data-placeholder="<?= $title ?>"
            class="form-control select2"
            name="<?= $name ?>" style="width: 100%;" tabindex="-1"
            aria-hidden="true">
    </select>
</div>