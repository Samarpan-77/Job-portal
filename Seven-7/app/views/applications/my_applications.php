<?php require_once BASE_PATH . '/app/views/layout/header.php'; ?>

<h3>My Applications</h3>

<a href="<?= base_url('job') ?>" class="btn btn-outline-primary btn-sm mb-3">Browse Jobs</a>

<table class="table table-bordered">
    <tr>
        <th>Job</th>
        <th>Status</th>
        <th>Date</th>
    </tr>

    <?php foreach ($applications as $app): ?>
        <tr>
            <td><?= htmlspecialchars($app['title']) ?></td>
            <td>
                <span class="badge bg-info"><?= $app['status'] ?></span>
            </td>
            <td><?= $app['applied_at'] ?></td>
        </tr>
    <?php endforeach; ?>

</table>

<?php require_once BASE_PATH . '/app/views/layout/footer.php'; ?>
