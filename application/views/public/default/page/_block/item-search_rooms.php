<?php
    $id_form = !empty($id) ? $id : 'form_search_room';
?>
<?= form_open("room/ajax_call", array('id' => $id_form, 'class' => 'form_search_room form_submit')) ?>
    <div class="box-search-prj">
        <div class="form-group key">
            <span class="head"><?= lang('day_arrival') ?></span>
            <div class="input-group date">
                <input name="day_arrival" readonly class="form-control datepicker" type="text" placeholder="<?= date('d/m/Y') ?>">
                <div class="input-group-addon"><span class="glyphicon glyphicon-th"></span>
                </div>
            </div>
        </div>
        <div class="form-group key">
            <span class="head"><?= lang('day_go') ?></span>
            <div class="input-group date">
                <input name="day_go" readonly class="form-control datepicker" type="text" placeholder="<?= date('d/m/Y') ?>">
                <div class="input-group-addon"><span class="glyphicon glyphicon-th"></span>
                </div>
            </div>
        </div>
        <div class="form-group classify">
            <span class="head"><?= lang('adults') ?></span>
            <input class="form-control" name="adults"  type="number" min="0" value="0" placeholder="<?= lang('adults') ?>">
        </div>
        <div class="form-group classify">
            <span class="head"><?= lang('child') ?></span>
            <input class="form-control" name="child" type="number" min="0" value="0" placeholder="<?= lang('child') ?>">
        </div>
        <div class="form-group btn-search">
            <button class="btn-pri"><?= lang('find_room') ?></button>
        </div>
    </div>
<?= form_close() ?>