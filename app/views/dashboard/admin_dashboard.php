<?php require_once BASE_PATH . '/app/views/layout/header.php'; ?>

<section class="hero-panel row align-items-center g-4">
    <div class="col-md-8">
        <h3>Admin Dashboard</h3>
        <p>Get high-level visibility into users, jobs, applications, and interview activity.</p>
    </div>
    <div class="col-md-4">
        <img class="hero-image" src="<?= base_url('../images/logo.png') ?>" alt="Admin overview">
    </div>
</section>

<div class="row g-3">
    <div class="col-md-3">
        <div class="glass-card metric-card">
            <h5>Total Users</h5>
            <p class="metric-value"><?= $user_count ?></p>
        </div>
    </div>

    <div class="col-md-3">
        <div class="glass-card metric-card">
            <h5>Total Jobs</h5>
            <p class="metric-value"><?= $job_count ?></p>
        </div>
    </div>

    <div class="col-md-3">
        <div class="glass-card metric-card">
            <h5>Total Applications</h5>
            <p class="metric-value"><?= $application_count ?></p>
        </div>
    </div>

    <div class="col-md-3">
        <div class="glass-card metric-card">
            <h5>Interview Sessions</h5>
            <p class="metric-value"><?= $interview_count ?></p>
        </div>
    </div>
</div>

<div class="mt-3 d-flex flex-wrap gap-2">
    <a href="<?= base_url('admin/users') ?>" class="btn btn-outline-primary btn-sm">Manage Users</a>
    <a href="<?= base_url('admin/applications') ?>" class="btn btn-outline-primary btn-sm">All Applications</a>
    <a href="<?= base_url('admin/interviewAnalytics') ?>" class="btn btn-outline-primary btn-sm">Interview Analytics</a>
</div>

<?php require_once BASE_PATH . '/app/views/layout/footer.php'; ?>
