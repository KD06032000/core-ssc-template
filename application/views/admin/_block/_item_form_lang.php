
<?php
    $cms_language = $this->config->item('cms_language');
    $list = !empty($list) ? (array)$list : [];
    $seo = !empty($seo);
?>

<ul class="nav nav-pills">
    <?php if (count($cms_language) > 1) foreach ($cms_language as $lang_code => $lang_name) { ?>
        <li<?= ($lang_code == $this->_lang_default) ? ' class="active"' : ''; ?>><a
                href="#tab_<?= $lang_code; ?>"
                data-toggle="tab"><?= $lang_name; ?></a></li>
    <?php } ?>
</ul>

<div class="tab-content">
    <?php if (!empty($cms_language)) foreach ($cms_language as $lang_code => $lang_name) { ?>
        <div class="tab-pane <?= ($lang_code == $this->_lang_default) ? 'active' : ''; ?>"
             id="tab_<?= $lang_code; ?>">
            <div class="box-body">
                <div class="row">

                    <div class="<?= $seo ? 'col-sm-6' : 'col-sm-12' ?> col-xs-12">

                        <?php if (!empty($list)) foreach ($list as $key => $item) {
                            $item['id'] = $item['name'] . "_{$lang_code}";
                            $item['name'] = $item['name'] . "[{$lang_code}]";
                            if ($lang_code != $this->_lang_default) $item['required'] = false;
                            $this->load->view($this->template_path . '_block/input/' . $item['type'], $item);
                        } ?>

                    </div>

                    <?php if ($seo) { ?>
                        <div class="col-sm-6 col-xs-12">
                            <?php $this->load->view($this->template_path . '_block/seo_meta', ['lang_code' => $lang_code]) ?>
                        </div>
                    <?php } ?>

                </div>
            </div>
        </div>
    <?php } ?>
</div>