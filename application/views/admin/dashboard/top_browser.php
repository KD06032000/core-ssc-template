<?php if (!empty($top_browser)): ?>
    <div class="table-responsive">
        <table class="table no-margin">
            <thead>
            <tr>
                <th>#</th>
                <th>Browser</th>
                <th>Session</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($top_browser as $key => $item): ?>
                <tr>
                    <td><?= $key + 1 ?></td>
                    <td><?= $item['browser'] ?></a></td>
                    <td><?=$item['sessions']?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php endif; ?>