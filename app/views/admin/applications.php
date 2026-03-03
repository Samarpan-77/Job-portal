<?php require_once BASE_PATH . '/app/views/layout/header.php'; ?>

<h3>All Applications</h3>

<?php if (!empty($_SESSION['flash_success'])): ?>
    <div class="alert alert-success" role="alert">
        <?= htmlspecialchars($_SESSION['flash_success'], ENT_QUOTES, 'UTF-8') ?>
    </div>
    <?php unset($_SESSION['flash_success']); ?>
<?php endif; ?>

<?php if (!empty($_SESSION['flash_error'])): ?>
    <div class="alert alert-danger" role="alert">
        <?= htmlspecialchars($_SESSION['flash_error'], ENT_QUOTES, 'UTF-8') ?>
    </div>
    <?php unset($_SESSION['flash_error']); ?>
<?php endif; ?>

<table class="table table-bordered">
    <tr>
        <th>Applicant</th>
        <th>Job</th>
        <th>Status</th>
        <th>Applied At</th>
        <th>Action</th>
    </tr>
    <?php foreach ($applications as $app): ?>
        <tr>
            <td><?= htmlspecialchars($app['applicant']) ?></td>
            <td><?= htmlspecialchars($app['title']) ?></td>
            <td><?= htmlspecialchars($app['status']) ?></td>
            <td><?= htmlspecialchars($app['applied_at']) ?></td>
            <td>
                <a
                    href="<?= base_url('application/delete/' . $app['id']) ?>"
                    class="btn btn-sm btn-outline-danger"
                    onclick="return confirm('Delete this application?');"
                >Delete</a>
            </td>
        </tr>
    <?php endforeach; ?>
</table>

<?php require_once BASE_PATH . '/app/views/layout/footer.php'; ?>
