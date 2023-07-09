<div class="col-item ">
    <div class="col-item-inner relative">
        <span class="col-img">
            <img src="<?= resizeImage($item['slug']) ?>" alt="<?= $item['name'] ?>">
        </span>
        <h4><a href="javascript:;" title=""><?= $item['name'] ?></a></h4>
        <ul>
            <?php foreach ($item['children'] as $child) { ?>
                <?= $ci->load->view($ci->template_path . '_menu/item_mega_child_3', [
                    'item' => $child,
                    'ci' => $ci
                ], TRUE); ?>
            <?php } ?>
        </ul>
    </div>
</div>