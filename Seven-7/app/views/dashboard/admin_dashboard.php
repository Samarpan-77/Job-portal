<?php require_once BASE_PATH . '/app/views/layout/header.php'; ?>

<h3>Admin Dashboard</h3>

<div class="row">

    <div class="col-md-3">
        <div class="card p-3 bg-light">
            <h5>Total Users</h5>
            <p><?= $user_count ?></p>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card p-3 bg-light">
            <h5>Total Jobs</h5>
            <p><?= $job_count ?></p>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card p-3 bg-light">
            <h5>Total Applications</h5>
            <p><?= $application_count ?></p>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card p-3 bg-light">
            <h5>Interview Sessions</h5>
            <p><?= $interview_count ?></p>
        </div>
    </div>

</div>

<div class="mt-3 d-flex gap-2">
    <a href="<?= base_url('admin/users') ?>" class="btn btn-outline-primary btn-sm">Manage Users</a>
    <a href="<?= base_url('admin/applications') ?>" class="btn btn-outline-primary btn-sm">All Applications</a>
    <a href="<?= base_url('admin/interviewAnalytics') ?>" class="btn btn-outline-primary btn-sm">Interview Analytics</a>
</div>

<?php require_once BASE_PATH . '/app/views/layout/footer.php'; ?>
