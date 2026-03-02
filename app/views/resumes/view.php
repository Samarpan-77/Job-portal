<?php require_once BASE_PATH . '/app/views/layout/header.php'; ?>

<?php
$title = $metaTitle ?? 'Resume';
$fullName = $resumeData['full_name'] ?? '';
$headline = $resumeData['headline'] ?? '';
$backPath = (($_SESSION['role'] ?? '') === 'employer') ? 'application/employerApplications' : 'resume';
?>

<h3><?= htmlspecialchars($title, ENT_QUOTES, 'UTF-8') ?></h3>

<style>
@media print {
    nav,
    .btn,
    .resume-actions {
        display: none !important;
    }
}
</style>

<div class="card mb-3">
    <div class="card-body">
        <h4 class="mb-1"><?= htmlspecialchars($fullName, ENT_QUOTES, 'UTF-8') ?></h4>
        <?php if ($headline !== ''): ?>
            <p class="text-muted mb-2"><?= htmlspecialchars($headline, ENT_QUOTES, 'UTF-8') ?></p>
        <?php endif; ?>

        <?php if (!empty($application['applicant_name'])): ?>
            <p class="mb-1"><strong>Applicant:</strong> <?= htmlspecialchars($application['applicant_name'], ENT_QUOTES, 'UTF-8') ?></p>
            <p class="mb-1"><strong>Applied For:</strong> <?= htmlspecialchars($application['job_title'] ?? '', ENT_QUOTES, 'UTF-8') ?></p>
            <p class="mb-0"><strong>Applied At:</strong> <?= htmlspecialchars($application['applied_at'] ?? '', ENT_QUOTES, 'UTF-8') ?></p>
        <?php endif; ?>
    </div>
</div>

<div class="row g-3 mb-3">
    <?php if (($resumeData['email'] ?? '') !== ''): ?>
        <div class="col-md-4"><strong>Email:</strong><br><?= htmlspecialchars($resumeData['email'], ENT_QUOTES, 'UTF-8') ?></div>
    <?php endif; ?>
    <?php if (($resumeData['phone'] ?? '') !== ''): ?>
        <div class="col-md-4"><strong>Phone:</strong><br><?= htmlspecialchars($resumeData['phone'], ENT_QUOTES, 'UTF-8') ?></div>
    <?php endif; ?>
    <?php if (($resumeData['address'] ?? '') !== ''): ?>
        <div class="col-md-4"><strong>Address:</strong><br><?= htmlspecialchars($resumeData['address'], ENT_QUOTES, 'UTF-8') ?></div>
    <?php endif; ?>
</div>

<?php if (($resumeData['summary'] ?? '') !== ''): ?>
    <h5>Professional Summary</h5>
    <p><?= nl2br(htmlspecialchars($resumeData['summary'], ENT_QUOTES, 'UTF-8')) ?></p>
<?php endif; ?>

<?php if (!empty($resumeData['skills'])): ?>
    <h5>Skills</h5>
    <ul>
        <?php foreach ($resumeData['skills'] as $item): ?>
            <li><?= htmlspecialchars($item, ENT_QUOTES, 'UTF-8') ?></li>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>

<?php if (!empty($resumeData['experience_items'])): ?>
    <h5>Work Experience</h5>
    <ul>
        <?php foreach ($resumeData['experience_items'] as $item): ?>
            <li><?= htmlspecialchars($item, ENT_QUOTES, 'UTF-8') ?></li>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>

<?php if (!empty($resumeData['education_items'])): ?>
    <h5>Education</h5>
    <ul>
        <?php foreach ($resumeData['education_items'] as $item): ?>
            <li><?= htmlspecialchars($item, ENT_QUOTES, 'UTF-8') ?></li>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>

<?php if (!empty($resumeData['projects'])): ?>
    <h5>Projects</h5>
    <ul>
        <?php foreach ($resumeData['projects'] as $item): ?>
            <li><?= htmlspecialchars($item, ENT_QUOTES, 'UTF-8') ?></li>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>

<?php if (!empty($resumeData['certifications'])): ?>
    <h5>Certifications</h5>
    <ul>
        <?php foreach ($resumeData['certifications'] as $item): ?>
            <li><?= htmlspecialchars($item, ENT_QUOTES, 'UTF-8') ?></li>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>

<div class="resume-actions mt-3">
    <button type="button" class="btn btn-outline-dark btn-sm" onclick="window.print()">Download PDF</button>
    <a href="<?= base_url($backPath) ?>" class="btn btn-link btn-sm">Back</a>
</div>

<?php require_once BASE_PATH . '/app/views/layout/footer.php'; ?>
