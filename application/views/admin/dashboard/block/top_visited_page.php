<?php if (!empty($top_visit)): ?>
    <div class="table-responsive">
        <table class="table no-margin">
            <thead>
            <tr>
                <th>#</th>
                <th>Title</th>
                <th>Views</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($top_visit as $key => $item): ?>
                <tr>
                    <td><?= $key + 1 ?></td>
                    <td><a href="<?=$item['url']?>" target="_blank"><?=$item['pageTitle']?></a></td>
                    <td><?=$item['pageViews']?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php endif; ?>