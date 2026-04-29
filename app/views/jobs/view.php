<?php require_once BASE_PATH . '/app/views/layout/header.php'; ?>

<?php
$canManageImage = isset($_SESSION['user_id'], $_SESSION['role']) &&
    ($_SESSION['role'] === 'employer') &&
    ((int)$job['employer_id'] === (int)$_SESSION['user_id']);
$isDeadlinePassed = !empty($job['application_deadline']) && $job['application_deadline'] < date('Y-m-d');
?>

<?php if (!empty($_SESSION['flash_success'])): ?>
    <div class="alert alert-success" role="alert">
        <?= htmlspecialchars($_SESSION['flash_success'], ENT_QUOTES, 'UTF-8') ?>
    </div>
    <?php unset($_SESSION['flash_success']); ?>
<?php endif; ?>

<?php if (!empty($_SESSION['flash_error'])): ?>
    <div class="alert alert-danger" role="alert">
        <?= htmlspecialchars($_SESSION['flash_error'], ENT_QUOTES, 'UTF-8') ?>
    </div>
    <?php unset($_SESSION['flash_error']); ?>
<?php endif; ?>

<h3><?= htmlspecialchars($job['title']) ?></h3>

<?php if (!empty($job['image_path'])): ?>
    <div class="job-image-panel mb-3">
        <img
            src="<?= base_url($job['image_path']) ?>"
            alt="<?= htmlspecialchars($job['title'], ENT_QUOTES, 'UTF-8') ?>"
            class="job-detail-image"
        >
    </div>
<?php endif; ?>

<p><strong>Location:</strong> <?= htmlspecialchars($job['location']) ?></p>
<p><strong>Salary:</strong> <?= htmlspecialchars($job['salary']) ?></p>
<p>
    <strong>Posted On:</strong>
    <?= !empty($job['created_at']) ? htmlspecialchars(date('F j, Y', strtotime((string)$job['created_at'])), ENT_QUOTES, 'UTF-8') : '<span class="text-muted">N/A</span>' ?>
</p>
<p>
    <strong>Application Deadline:</strong>
    <?php if (!empty($job['application_deadline'])): ?>
        <?= htmlspecialchars($job['application_deadline'], ENT_QUOTES, 'UTF-8') ?>
        <?php if ($isDeadlinePassed): ?>
            <span class="text-danger">(Closed)</span>
        <?php endif; ?>
    <?php else: ?>
        <span class="text-muted">No deadline set</span>
    <?php endif; ?>
</p>
<p>
    <strong>Company:</strong>
    <a href="<?= base_url('profile/company/' . $job['employer_id']) ?>" class="job-company-link">
        <?= htmlspecialchars((string)$job['employer_display_name'], ENT_QUOTES, 'UTF-8') ?>
    </a>
</p>
<p><?= nl2br(htmlspecialchars($job['description'])) ?></p>

<?php if ($canManageImage): ?>
    <div class="glass-card p-3 mb-3">
        <h5 class="mb-3">Manage Job Image</h5>
        <form method="POST" action="<?= base_url('job/uploadImage/' . $job['id']) ?>" enctype="multipart/form-data" class="mb-2">
            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
            <input type="file" name="image" class="form-control mb-2" accept=".jpg,.jpeg,.png,.webp,.gif,image/*" required>
            <button class="btn btn-primary btn-sm"><?= !empty($job['image_path']) ? 'Replace Image' : 'Upload Image' ?></button>
        </form>

        <?php if (!empty($job['image_path'])): ?>
            <form method="POST" action="<?= base_url('job/removeImage/' . $job['id']) ?>">
                <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                <button class="btn btn-outline-danger btn-sm" onclick="return confirm('Remove this job image?');">Remove Image</button>
            </form>
        <?php endif; ?>
    </div>
<?php endif; ?>

<?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'user'): ?>
    <div class="mb-3 d-flex gap-2">
        <?php if (!empty($isSaved)): ?>
            <form method="POST" action="<?= base_url('job/unsave/' . $job['id']) ?>">
                <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                <input type="hidden" name="return_to" value="<?= 'job/view/' . $job['id'] ?>">
                <button class="btn btn-outline-secondary btn-sm">Remove from Saved</button>
            </form>
        <?php else: ?>
            <form method="POST" action="<?= base_url('job/save/' . $job['id']) ?>">
                <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                <input type="hidden" name="return_to" value="<?= 'job/view/' . $job['id'] ?>">
                <button class="btn btn-outline-primary btn-sm">Save Job</button>
            </form>
        <?php endif; ?>
        <a href="<?= base_url('job/saved') ?>" class="btn btn-outline-dark btn-sm">Saved Jobs</a>
    </div>

    <?php if (empty($resumes)): ?>
        <div class="alert alert-warning">Create a resume first before applying.</div>
        <a href="<?= base_url('resume/create') ?>" class="btn btn-primary btn-sm">Create Resume</a>
    <?php elseif ($isDeadlinePassed): ?>
        <div class="alert alert-warning">This vacancy is closed and no longer accepting applications.</div>
    <?php else: ?>
        <form method="POST" action="<?= base_url('application/apply') ?>">
        <input type="hidden" name="job_id" value="<?= $job['id'] ?>">
        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">

        <select name="resume_id" class="form-control mb-2" required>
            <?php foreach ($resumes as $resume): ?>
                <?php $content = $resume['parsed_content'] ?? []; ?>
                <?php $name = trim((string)($content['full_name'] ?? '')); ?>
                <?php $headline = trim((string)($content['headline'] ?? '')); ?>
                <option value="<?= $resume['id'] ?>">
                    <?= $name !== '' ? htmlspecialchars($name, ENT_QUOTES, 'UTF-8') : ('Resume #' . $resume['id']) ?>
                    <?= $headline !== '' ? (' - ' . htmlspecialchars($headline, ENT_QUOTES, 'UTF-8')) : '' ?>
                </option>
            <?php endforeach; ?>
        </select>

        <button class="btn btn-primary">Apply</button>
    </form>
    <?php endif; ?>
<?php endif; ?>

<a href="<?= base_url('job') ?>" class="btn btn-link mt-2">Back to jobs</a>

<?php require_once BASE_PATH . '/app/views/layout/footer.php'; ?>
