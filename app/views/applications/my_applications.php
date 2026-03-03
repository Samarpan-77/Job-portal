<?php require_once BASE_PATH . '/app/views/layout/header.php'; ?>

<h3>My Applications</h3>

<a href="<?= base_url('job') ?>" class="btn btn-outline-primary btn-sm mb-3">Browse Jobs</a>

<?php if (!empty($_SESSION['flash_success'])): ?>
    <div class="alert alert-success" role="alert">
        <?= htmlspecialchars($_SESSION['flash_success'], ENT_QUOTES, 'UTF-8') ?>
    </div>
    <?php unset($_SESSION['flash_success']); ?>
<?php endif; ?>

<?php if (!empty($_SESSION['flash_info'])): ?>
    <div class="alert alert-info" role="alert">
        <?= htmlspecialchars($_SESSION['flash_info'], ENT_QUOTES, 'UTF-8') ?>
    </div>
    <?php unset($_SESSION['flash_info']); ?>
<?php endif; ?>

<?php if (!empty($_SESSION['flash_error'])): ?>
    <div class="alert alert-danger" role="alert">
        <?= htmlspecialchars($_SESSION['flash_error'], ENT_QUOTES, 'UTF-8') ?>
    </div>
    <?php unset($_SESSION['flash_error']); ?>
<?php endif; ?>

<table class="table table-bordered">
    <tr>
        <th>Job</th>
        <th>Status</th>
        <th>Date</th>
        <th>Action</th>
    </tr>

    <?php foreach ($applications as $app): ?>
        <tr>
            <td><?= htmlspecialchars($app['title']) ?></td>
            <td>
                <?php
                    $status = strtolower((string)$app['status']);
                    $badgeClass = $status === 'shortlisted' ? 'bg-success' : ($status === 'rejected' ? 'bg-danger' : 'bg-warning text-dark');
                    $label = $status === 'shortlisted' ? 'Shortlisted' : ($status === 'rejected' ? 'Rejected' : 'Under Review');
                ?>
                <span class="badge <?= $badgeClass ?>"><?= htmlspecialchars($label, ENT_QUOTES, 'UTF-8') ?></span>
            </td>
            <td><?= htmlspecialchars($app['applied_at'], ENT_QUOTES, 'UTF-8') ?></td>
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
