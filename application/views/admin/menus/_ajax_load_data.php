<div class="nav-tabs-custom">
    <ul class="nav nav-tabs">
        <?php if(!empty($list_tabs)) foreach($list_tabs as $key=>$tab): ?>
            <li class="<?= ($key == 0) ? 'active' :'' ?>">
                <a href="#<?= $tab['id'] ?>" data-toggle="tab"><?= $tab['name'] ?></a>
            </li>
        <?php endforeach ?>
    </ul>
    <div id="listDataItem" class="tab-content">
        <?php if(!empty($list_tabs)) foreach($list_tabs as $key=>$tab): ?>
        <?php $helper_convert = !empty($tab['convert_link']) ? $tab['convert_link'] : ''; ?>
        <div class="tab-pane <?= ($key == 0) ? 'active' :'' ?>" id="<?= $tab['id'] ?>">
            <input type="hidden" value="page" name="type">
            <select class="form-control select2" data-placeholder="Chọn link <?= $tab['name'] ?>"   style="width: 100%;" tabindex="-1" aria-hidden="true">
            <?php if(!empty($tab['data_select'])) foreach($tab['data_select'] as $k=>$item): ?>
            <?php 
                $item = (object)$item;
                $link_page = !empty($helper_convert) ? str_replace(base_url(),'',$helper_convert($item)) : $item->slug;
            ?>
                <option value="<?= $link_page ?>"><?= $item->title ?></option>
            <?php endforeach ?>
            </select>
        </div>
        <?php endforeach ?>
        <!-- /.tab-pane -->
    </div>
    <!-- /.tab-content -->
    <button type="button" class="btn btn-success addtonavmenu"><i class="glyphicon glyphicon-plus"></i> Thêm vào menu</button>
</div>
<!-- nav-tabs-custom -->
