<fieldset class="form-group input_multiple">
    <legend> <?= $title ?> </legend>

    <div class="block-list">
        <div class="item">
            <div class="remove">
                <button class="btn btn-danger remove" type="button">x</button>
            </div>
            <?php if (!empty($field)) foreach ($field as $key => $item) {
                $item['id'] = $name .'_'. $item['name'];
                $item['name'] = $name. '['. $item['name'] .']';
                $field[$key] = $item;
            } ?>
            <?php $this->load->view($this->template_path . '_block/_item_form', ['list' => $field, 'class' => 'col-md-12']);; ?>
        </div>
    </div>

    <div class="text-center">
        <button type="button" class="btn btn-success add">Thêm mới</button>
    </div>
    <br>

</fieldset>