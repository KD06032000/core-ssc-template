<div class="form-group">
    <label><?= $title ?></label>
    <select class="form-control " name="<?= $name ?>"
            style="width: 100%;" tabindex="-1" aria-hidden="true">
        <?php if (!empty($options)) foreach ($options as $key => $value):  ?>
        <?php $selected = $value->id == ${$name} ? 'selected' : '' ?>
            <option <?= $selected ?> value="<?= $value->id ?>"><?= $value->title ?></option>
        <?php endforeach ?>
    </select>
</div>
