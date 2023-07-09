<?php
$list_fieldset = [
    [
        'title' => 'Facebook',
        'data' => [
            ['name' => 'social_facebook_name', 'title' => 'Tên', 'type' => 'input_text'],
            ['name' => 'social_facebook_sub', 'title' => 'Sub-', 'type' => 'input_text'],
            ['name' => 'social_facebook_link', 'title' => 'Link', 'type' => 'input_text'],
        ]
    ],
    [
        'title' => 'Youtube',
        'data' => [
            ['name' => 'social_youtube_name', 'title' => 'Tên', 'type' => 'input_text'],
            ['name' => 'social_youtube_sub', 'title' => 'Sub-', 'type' => 'input_text'],
            ['name' => 'social_youtube_link', 'title' => 'Link', 'type' => 'input_text'],
        ]
    ],
    [
        'title' => 'Tiktok',
        'data' => [
            ['name' => 'social_tiktok_name', 'title' => 'Tên', 'type' => 'input_text'],
            ['name' => 'social_tiktok_sub', 'title' => 'Sub-', 'type' => 'input_text'],
            ['name' => 'social_tiktok_link', 'title' => 'Link', 'type' => 'input_text'],
        ]
    ],
    [
        'title' => 'Zalo',
        'data' => [
            ['name' => 'social_zalo_name', 'title' => 'Tên', 'type' => 'input_text'],
            ['name' => 'social_zalo_sub', 'title' => 'Sub-', 'type' => 'input_text'],
            ['name' => 'social_zalo_link', 'title' => 'Link', 'type' => 'input_text'],
        ]
    ]
];
?>
<div class="tab-pane" id="<?= $target ?>">
    <?php if (!empty($list_fieldset)) foreach ($list_fieldset as $field): ?>
        <fieldset class="form-group album-contain">
            <legend><?= $field['title'] ?></legend>
            <div class="box-body">
                <?php if (!empty($field['data'])) foreach ($field['data'] as $key => $item):
                    $item['value'] = !empty(${$item['name']}) ? ${$item['name']} : '';
                    $this->load->view($this->template_path . 'setting/items/' . $item['type'], $item);
                    ?>
                <?php endforeach ?>
            </div>

        </fieldset>
    <?php endforeach ?>
</div>