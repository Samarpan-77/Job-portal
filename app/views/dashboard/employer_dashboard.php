<?php require_once BASE_PATH . '/app/views/layout/header.php'; ?>

<h3>Employer Dashboard</h3>

<div class="row">
    <div class="col-md-4">
        <div class="card p-3">
            <h5>My Jobs</h5>
            <p><?= $job_count ?></p>
            <a href="<?= base_url('job/create') ?>" class="btn btn-primary btn-sm">Post Job</a>
            <a href="<?= base_url('job') ?>" class="btn btn-outline-secondary btn-sm mt-2">View All Jobs</a>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card p-3">
            <h5>Total Applicants</h5>
            <p><?= $application_count ?></p>
            <a href="<?= base_url('application/employerApplications') ?>" class="btn btn-outline-primary btn-sm">Manage Applications</a>
        </div>
    </div>
</div>

<?php require_once BASE_PATH . '/app/views/layout/footer.php'; ?>
