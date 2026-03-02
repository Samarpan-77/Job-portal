<?php require_once BASE_PATH . '/app/views/layout/header.php'; ?>

<h3><?= htmlspecialchars($job['title']) ?></h3>

<p><strong>Location:</strong> <?= htmlspecialchars($job['location']) ?></p>
<p><strong>Salary:</strong> <?= htmlspecialchars($job['salary']) ?></p>
<p><?= nl2br(htmlspecialchars($job['description'])) ?></p>

<?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'user'): ?>
    <?php if (empty($resumes)): ?>
        <div class="alert alert-warning">Create a resume first before applying.</div>
        <a href="<?= base_url('resume/create') ?>" class="btn btn-primary btn-sm">Create Resume</a>
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
