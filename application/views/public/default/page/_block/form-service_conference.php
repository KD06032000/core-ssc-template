<h3 class="title-pri mt-5 mb-3"><?= lang('register_receive') ?></h3>
<?= form_open("contact/register_conference", array('class' => 'form_conference form_submit form-pri')) ?>
<input type="hidden" name="page_id" value="<?= $data->id ?>">
<div class="form-group">
    <label for=""><?= lang('full_name') ?></label>
    <input class="form-control" type="text" placeholder="<?= lang('full_name') ?>" name="fullname">
</div>
<div class="form-group">
    <label for="">Email</label>
    <input class="form-control" type="text" placeholder="Email" name="email">
</div>
<div class="form-group">
    <label for=""><?= lang('phone_number') ?></label>
    <input class="form-control" type="text" placeholder="<?= lang('phone_number') ?>" name="phone">
</div>
<div class="form-group">
    <label for=""><?= lang('number_of_attendees') ?></label>
    <input class="form-control" type="text" placeholder="<?= lang('number_of_attendees') ?>" name="humans">
</div>
<div class="form-group">
    <label for=""><?= lang('organization_date') ?></label>
    <input class="form-control datepicker" type="text" placeholder="<?= lang('organization_date') ?>"
           name="date_start" value="<?= date('d/m/Y') ?>">
    <i class="fas fa-calendar-alt"></i>
</div>
<div class="form-group">
    <label for=""><?= lang('hold_time') ?></label>
    <input class="form-control time-range-picker" type="text" value="00:00 - 23:59"
           placeholder="<?= lang('hold_time') ?>" name="time_range">
    <i class="fas fa-clock"></i>
</div>
<div class="form-group input-message">
    <label for=""><?= lang('content') ?></label>
    <textarea class="form-control" rows="" cols="" placeholder="<?= lang('content') ?>"
              name="content"></textarea>
</div>
<div class="wrap-submit">
    <button class="submit btn-more" type="submit"><?= lang('register_now') ?></button>
</div>
<?= form_close() ?>