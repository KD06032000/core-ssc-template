<li class="item-megamenu">
    <a href="" title=""> <?= $item['name'] ?> </a>

    <?php if (!empty($item['children'])) { ?>

        <div class="megamenu">
            <div class="container">
                <div class="nav-flex flex">

                    <?php foreach ($item['children'] as $child) { ?>
                        <?= $ci->load->view($ci->template_path . '_menu/item_mega_child', [
                            'item' => $child,
                            'ci' => $ci
                        ], TRUE); ?>
                    <?php } ?>

                </div>
            </div>
        </div>

    <?php } ?>

</li>
