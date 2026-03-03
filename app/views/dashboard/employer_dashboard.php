<?php require_once BASE_PATH . '/app/views/layout/header.php'; ?>

<section class="hero-panel row align-items-center g-4">
    <div class="col-md-8">
        <h3>Employer Dashboard</h3>
        <p>Publish roles, review candidate flow, and manage hiring activity from one workspace.</p>
    </div>
    <div class="col-md-4">
        <img class="hero-image" src="<?= base_url('../images/Image2.png') ?>" alt="Employer recruitment dashboard">
    </div>
</section>

<div class="row g-3">
    <div class="col-md-4">
        <div class="glass-card metric-card">
            <h5>My Jobs</h5>
            <p class="metric-value"><?= $job_count ?></p>
            <a href="<?= base_url('job/create') ?>" class="btn btn-primary btn-sm">Post Job</a>
            <a href="<?= base_url('job') ?>" class="btn btn-outline-secondary btn-sm mt-2">View All Jobs</a>
        </div>
    </div>

    <div class="col-md-4">
        <div class="glass-card metric-card">
            <h5>Total Applicants</h5>
            <p class="metric-value"><?= $application_count ?></p>
            <a href="<?= base_url('application/employerApplications') ?>" class="btn btn-outline-primary btn-sm">Manage Applications</a>
        </div>
    </div>
</div>

<?php require_once BASE_PATH . '/app/views/layout/footer.php'; ?>
