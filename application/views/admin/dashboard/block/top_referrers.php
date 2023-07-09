<?php if (!empty($top_referrers)): ?>
    <div class="table-responsive">
        <table class="table no-margin">
            <thead>
            <tr>
                <th>#</th>
                <th>Url</th>
                <th>Views</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($top_referrers as $key => $item): ?>
                <tr>
                    <td><?= $key + 1 ?></td>
                    <td><?= $item['url'] ?></a></td>
                    <td><?=$item['pageViews']?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php endif; ?>