<?php
$name = 'family';
?>

<div class="tab-pane row" id="tab_<?=$name?>">

    <div class="col-md-2">
        <ul class="nav nav-pills nav-stacked custom">
            <li role="presentation" class="active">
                <a data-toggle="tab" href="#tab_family_1">Mở đầu</a>
            </li>
            <li role="presentation">
                <a data-toggle="tab" href="#tab_family_2">Tâm thư của thầy</a>
            </li>
            <li role="presentation">
                <a data-toggle="tab" href="#tab_family_3">Định hướng</a>
            </li>
            <li role="presentation">
                <a data-toggle="tab" href="#tab_family_4">Đồng hành cùng GNH</a>
            </li>
        </ul>
    </div>

    <div class="col-md-10">
        <div class="tab-content">
            <div class="tab-pane active" id="tab_family_1">
                <fieldset class="form-group album-contain">
                    <legend>Mở đầu</legend>

                    <?php
                    $list_field_normal = [
                        ['name' => 'family_title_1', 'id' => 'family_title_1', 'title' => 'Tiều đê 1', 'type' => 'input_text'],
                        ['name' => 'family_title_2', 'id' => 'family_title_2', 'title' => 'Tiều đê 2', 'type' => 'input_text'],
                        ['name' => 'family_slogan', 'id' => 'family_slogan', 'title' => 'Slogan', 'type' => 'input_text'],
                        ['name' => 'description', 'id' => 'family_description', 'title' => 'Mô tả', 'type' => 'input_textarea', 'tinymce' => true]
                    ];
                    ?>

                    <div class="box-body">
                        <?php if (!empty($list_field_normal)) foreach ($list_field_normal as $key => $item):
                            $item['value'] = !empty(${$item['name']}) ? ${$item['name']} : '';
                            $this->load->view($this->template_path . 'setting/items/' . $item['type'], $item);
                            ?>
                        <?php endforeach ?>
                    </div>
                </fieldset>

            </div>

            <div class="tab-pane" id="tab_family_2">
                <fieldset class="form-group album-contain">
                    <legend>Tâm thư của thầy</legend>

                    <?php
                    $list_field_normal = [
                        ['name' => 'family_letter_author', 'id' => 'family_letter_author', 'title' => 'Ảnh đại diện', 'type' => 'input_media'],
                        ['name' => 'family_letter', 'id' => 'family_letter', 'title' => 'Tâm thư', 'type' => 'input_textarea', 'tinymce' => true],
                    ];
                    ?>

                    <div class="box-body">
                        <?php if (!empty($list_field_normal)) foreach ($list_field_normal as $key => $item):
                            $item['value'] = !empty(${$item['name']}) ? ${$item['name']} : '';
                            $this->load->view($this->template_path . 'setting/items/' . $item['type'], $item);
                            ?>
                        <?php endforeach ?>
                    </div>
                </fieldset>

            </div>

            <div class="tab-pane" id="tab_family_3">
                <fieldset class="form-group album-contain">
                    <legend>Định hướng</legend>

                    <?php $this->load->view($this->template_path . 'setting/items/input_multi', ['id' => 'orientation_family', 'layout' => 'home']); ?>
                </fieldset>

            </div>

            <div class="tab-pane" id="tab_family_4">
                <fieldset class="form-group album-contain">
                    <legend>Đồng hành cùng GNH</legend>

                    <?php
                    $list_field_normal = [
                        ['name' => 'family_companion_image', 'id' => 'family_companion_image', 'title' => 'Ảnh đại diện', 'type' => 'input_media'],
                        ['name' => 'family_companion_content', 'id' => 'family_companion_content', 'title' => 'Nội dung', 'type' => 'input_textarea', 'tinymce' => true],
                        ['name' => 'family_companion_link', 'id' => 'family_companion_link', 'title' => 'Link "lên xe ngay"', 'type' => 'input_text'],
                    ];
                    ?>

                    <div class="box-body">
                        <?php if (!empty($list_field_normal)) foreach ($list_field_normal as $key => $item):
                            $item['value'] = !empty(${$item['name']}) ? ${$item['name']} : '';
                            $this->load->view($this->template_path . 'setting/items/' . $item['type'], $item);
                            ?>
                        <?php endforeach ?>
                    </div>
                </fieldset>

            </div>

        </div>
    </div>

</div>