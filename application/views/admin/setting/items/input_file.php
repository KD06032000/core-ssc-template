<div class="form-group">
    <label><?= $title ?></label>
    <div class="input-group input-group-lg">
        <span class="input-group-addon" onclick="chooseFiles('<?= $name ?>')"><i class="fa fa-fw fa-file"></i>Chọn file</span>
        <input id="<?= $name ?>" onclick="chooseFiles('<?= $name ?>')" name="<?= $name ?>"
               placeholder="Chọn file brochure" class="form-control" type="text"
               value="<?= $value ?>"/>
    </div>
</div>