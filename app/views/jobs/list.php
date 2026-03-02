<?php require_once BASE_PATH . '/app/views/layout/header.php'; ?>

<h3>Available Jobs</h3>

<?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'employer'): ?>
    <a href="<?= base_url('job/create') ?>" class="btn btn-primary btn-sm mb-3">Post New Job</a>
<?php endif; ?>

<table class="table table-bordered">
    <tr>
        <th>Title</th>
        <th>Location</th>
        <th>Salary</th>
        <th>Action</th>
    </tr>

    <?php foreach ($jobs as $job): ?>
        <tr>
            <td><?= htmlspecialchars($job['title']) ?></td>
            <td><?= htmlspecialchars($job['location']) ?></td>
            <td><?= htmlspecialchars($job['salary']) ?></td>
            <td>
                <a href="<?= base_url('job/view/' . $job['id']) ?>" class="btn btn-sm btn-info">View</a>
            </td>
        </tr>
    <?php endforeach; ?>

</table>

<?php require_once BASE_PATH . '/app/views/layout/footer.php'; ?>
