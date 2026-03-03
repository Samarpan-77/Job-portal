<?php require_once BASE_PATH . '/app/views/layout/header.php'; ?>

<section class="hero-panel row align-items-center g-4">
    <div class="col-md-8">
        <h3>User Dashboard</h3>
        <p>Monitor your application pipeline, improve your resume, and keep interview practice on track.</p>
    </div>
    <div class="col-md-4">
        <img class="hero-image" src="<?= base_url('../images/Image1.png') ?>" alt="Job seeker dashboard">
    </div>
</section>

<div class="row g-3">
    <div class="col-md-4">
        <div class="glass-card metric-card">
            <h5>Applied Jobs</h5>
            <p class="metric-value"><?= $application_count ?></p>
            <a href="<?= base_url('application/myApplications') ?>" class="btn btn-outline-primary btn-sm">My Applications</a>
        </div>
    </div>

    <div class="col-md-4">
        <div class="glass-card metric-card">
            <h5>Resumes</h5>
            <p class="metric-value"><?= $resume_count ?></p>
            <a href="<?= base_url('resume') ?>" class="btn btn-outline-primary btn-sm">Resume Builder</a>
        </div>
    </div>

    <div class="col-md-4">
        <div class="glass-card metric-card">
            <h5>Interview Sessions</h5>
            <p class="metric-value"><?= $interview_count ?></p>
            <a href="<?= base_url('interview/start') ?>" class="btn btn-outline-primary btn-sm">Practice Interview</a>
        </div>
    </div>
</div>

<?php require_once BASE_PATH . '/app/views/layout/footer.php'; ?>
