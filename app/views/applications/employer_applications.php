<?php require_once BASE_PATH . '/app/views/layout/header.php'; ?>

<h3>Applicants</h3>

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
            <td><?= htmlspecialchars($app['job_title']) ?></td>
            <td><?= htmlspecialchars($app['name']) ?></td>
            <td><?= htmlspecialchars($app['email']) ?></td>
            <td><span class="badge bg-secondary"><?= htmlspecialchars($app['status']) ?></span></td>
            <td>
                <a href="<?= base_url('application/viewResume/' . $app['id']) ?>" class="btn btn-outline-primary btn-sm">View Resume</a>
                <a href="<?= base_url('application/update/' . $app['id'] . '/shortlisted') ?>" class="btn btn-success btn-sm">Shortlist</a>
                <a href="<?= base_url('application/update/' . $app['id'] . '/rejected') ?>" class="btn btn-danger btn-sm">Reject</a>
            </td>
        </tr>
    <?php endforeach; ?>

</table>

<?php require_once BASE_PATH . '/app/views/layout/footer.php'; ?>
