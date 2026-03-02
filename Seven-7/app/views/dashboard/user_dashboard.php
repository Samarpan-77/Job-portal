<?php require_once BASE_PATH . '/app/views/layout/header.php'; ?>

<h3>User Dashboard</h3>

<div class="row">

    <div class="col-md-4">
        <div class="card p-3">
            <h5>Applied Jobs</h5>
            <p><?= $application_count ?></p>
            <a href="<?= base_url('application/myApplications') ?>" class="btn btn-outline-primary btn-sm">My Applications</a>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card p-3">
            <h5>Resumes</h5>
            <p><?= $resume_count ?></p>
            <a href="<?= base_url('resume') ?>" class="btn btn-outline-primary btn-sm">Resume Builder</a>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card p-3">
            <h5>Interview Sessions</h5>
            <p><?= $interview_count ?></p>
            <a href="<?= base_url('interview/start') ?>" class="btn btn-outline-primary btn-sm">Practice Interview</a>
        </div>
    </div>

</div>

<?php require_once BASE_PATH . '/app/views/layout/footer.php'; ?>
