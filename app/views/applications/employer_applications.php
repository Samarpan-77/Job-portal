<?php require_once BASE_PATH . '/app/views/layout/header.php'; ?>

<h3>Applicants</h3>

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
        <th>Name</th>
        <th>Email</th>
        <th>Status</th>
        <th>Action</th>
    </tr>

    <?php foreach ($applications as $app): ?>
        <tr>
            <td><?= htmlspecialchars($app['job_title'], ENT_QUOTES, 'UTF-8') ?></td>
            <td>
                <a href="<?= base_url('profile/view/' . $app['user_id']) ?>" class="job-company-link">
                    <?= htmlspecialchars($app['name'], ENT_QUOTES, 'UTF-8') ?>
                </a>
            </td>
            <td><?= htmlspecialchars($app['email'], ENT_QUOTES, 'UTF-8') ?></td>
            <td>
                <?php
                    $status = strtolower((string)$app['status']);
                    $badgeClass = $status === 'shortlisted' ? 'bg-success' : ($status === 'rejected' ? 'bg-danger' : 'bg-warning text-dark');
                    $label = $status === 'shortlisted' ? 'Shortlisted' : ($status === 'rejected' ? 'Rejected' : 'Pending');
                ?>
                <span class="badge <?= $badgeClass ?>"><?= htmlspecialchars($label, ENT_QUOTES, 'UTF-8') ?></span>
            </td>
            <td class="d-flex gap-1 flex-wrap">
                <a href="<?= base_url('application/viewResume/' . $app['id']) ?>" class="btn btn-outline-primary btn-sm">View Resume</a>
                <a href="<?= base_url('application/update/' . $app['id'] . '/shortlisted') ?>" class="btn btn-success btn-sm">Shortlist</a>
                <a href="<?= base_url('application/update/' . $app['id'] . '/rejected') ?>" class="btn btn-danger btn-sm">Reject</a>
                <a href="<?= base_url('application/update/' . $app['id'] . '/pending') ?>" class="btn btn-secondary btn-sm">Set Pending</a>
                <a
                    href="<?= base_url('application/delete/' . $app['id']) ?>"
                    class="btn btn-outline-danger btn-sm"
                    onclick="return confirm('Delete this application?');"
                >Delete</a>
            </td>
        </tr>
    <?php endforeach; ?>

</table>

<?php require_once BASE_PATH . '/app/views/layout/footer.php'; ?>
