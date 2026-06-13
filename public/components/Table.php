<?php function Table(array $dataSource, ?callable $renderActions = null, ?callable $renderEmpty = null)
{
    if (empty($dataSource)) {
        echo $renderEmpty ? $renderEmpty() : '<div class="alert alert-info">Aucune donnée disponible.</div>';
        return;
    }
    $headers = array_keys($dataSource[0]);
    ?>
    <table class="table align-middle border-top mb-0">
        <thead>
            <tr class="text-muted small">
                <?php foreach ($headers as $header): ?>
                    <th><?php if ($header !== 'id')
                        echo $header ?></th>
                <?php endforeach; ?>
                <?php if ($renderActions): ?>
                    <th class="text-end">Actions</th>
                <?php endif; ?>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($dataSource as $row): ?>
                <tr>
                    <?php foreach ($row as $key => $value): ?>
                        <td>
                            <?php
                            if (strtolower($key) === 'image') {
                                echo '<img src="' . htmlspecialchars($value) . '" class="rounded object-fit-cover" style="width: 60px; height: 45px;">';
                            } else if ($key === 'id') {
                                continue;
                            } else {
                                echo htmlspecialchars($value);
                            }
                            ?>
                        </td>
                    <?php endforeach; ?>

                    <?php if ($renderActions): ?>
                        <td class="text-end">
                            <div class="btn-group btn-group-sm">
                                <?php $renderActions($row); ?>
                            </div>
                        </td>
                    <?php endif; ?>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

<?php } ?>