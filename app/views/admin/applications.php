<?php require_once BASE_PATH . '/app/views/layout/header.php'; ?>

<h3>All Applications</h3>

<table class="table table-bordered">
    <tr>
        <th>Applicant</th>
        <th>Job</th>
        <th>Status</th>
        <th>Applied At</th>
    </tr>
    <?php foreach ($applications as $app): ?>
        <tr>
            <td><?= htmlspecialchars($app['applicant']) ?></td>
            <td><?= htmlspecialchars($app['title']) ?></td>
            <td><?= htmlspecialchars($app['status']) ?></td>
            <td><?= htmlspecialchars($app['applied_at']) ?></td>
        </tr>
    <?php endforeach; ?>
</table>

<?php require_once BASE_PATH . '/app/views/layout/footer.php'; ?>
