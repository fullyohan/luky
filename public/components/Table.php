<?php function Table(array $dataSource, ?callable $renderActions = null, ?callable $renderEmpty = null)
{
    if (empty($dataSource)) {
        echo $renderEmpty ? $renderEmpty() : '<div class="alert alert-info border-0 shadow-sm">Aucune donnée disponible.</div>';
        return;
    }
    $headers = array_keys($dataSource[0]);
    ?>
    <div class="table-responsive rounded-3 border bg-white shadow-sm">
        <table class="table table-hover table-striped table-borderless align-middle mb-0">
            <thead class="table-light border-bottom text-uppercase fs-7 text-muted fw-semibold">
                <tr>
                    <?php foreach ($headers as $header): ?>
                        <?php if ($header === 'id') continue;?>
                        <th class="py-3 px-4"><?= htmlspecialchars($header) ?></th>
                    <?php endforeach; ?>
                    
                    <?php if ($renderActions): ?>
                        <th class="py-3 px-4 text-end" style="width: 120px;">Actions</th>
                    <?php endif; ?>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($dataSource as $row): ?>
                    <tr style="transition: background-color 0.15s ease-in-out;">
                        <?php foreach ($row as $key => $value): ?>
                            <?php if ($key === 'id') continue; ?>
                            <td class="py-3 px-4">
                                <?php
                                if (strtolower($key) === 'image') {
                                    echo '<img src="' . htmlspecialchars($value) . '" class="rounded shadow-sm border object-fit-cover" style="width: 55px; height: 40px;">';
                                } else {
                                    echo htmlspecialchars($value);
                                }
                                ?>
                            </td>
                        <?php endforeach; ?>

                        <?php if ($renderActions): ?>
                            <td class="py-3 px-4 text-end">
                                <div class="btn-group gap-1 justify-content-end">
                                    <?php $renderActions($row); ?>
                                </div>
                            </td>
                        <?php endif; ?>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

<?php } ?>