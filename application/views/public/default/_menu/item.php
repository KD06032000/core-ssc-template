<li class="<?= $item['class'] ?>">
    <a target="<?= $item['type'] == 'other' ? '_blank' : '_self' ?>" href="<?= $item['link'] ?>" title=""><?= $item['name'] ?></a>

    <?php if (!empty($item['children'])) { ?>
        <ul>
            <?php foreach ($item['children'] as $child) { ?>
                <?= $ci->load->view($ci->template_path . '_menu/item', [
                    'item' => $child,
                    'ci' => $ci
                ], TRUE); ?>
            <?php } ?>
        </ul>
    <?php } ?>
</li>