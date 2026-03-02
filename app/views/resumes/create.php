<?php require_once BASE_PATH . '/app/views/layout/header.php'; ?>

<h3>Create Resume</h3>

<?php if (!empty($errorMessage)): ?>
    <div class="alert alert-danger" role="alert">
        <?= htmlspecialchars($errorMessage, ENT_QUOTES, 'UTF-8') ?>
    </div>
<?php endif; ?>

<form method="POST" action="<?= base_url('resume/store') ?>">
    <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">

    <div class="mb-3">
        <label>Full Name</label>
        <input type="text" name="full_name" class="form-control" value="<?= htmlspecialchars($formData['full_name'] ?? '', ENT_QUOTES, 'UTF-8') ?>" required>
    </div>

    <div class="mb-3">
        <label>Professional Headline</label>
        <input type="text" name="headline" class="form-control" value="<?= htmlspecialchars($formData['headline'] ?? '', ENT_QUOTES, 'UTF-8') ?>" placeholder="e.g. Full Stack Developer with 3+ years experience">
    </div>

    <div class="mb-3">
        <label>Email</label>
        <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($formData['email'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
    </div>

    <div class="mb-3">
        <label>Phone</label>
        <input type="text" name="phone" class="form-control" value="<?= htmlspecialchars($formData['phone'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
    </div>

    <div class="mb-3">
        <label>Address</label>
        <input type="text" name="address" class="form-control" value="<?= htmlspecialchars($formData['address'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
    </div>

    <div class="mb-3">
        <label>Professional Summary</label>
        <textarea name="summary" class="form-control" rows="4" placeholder="Short profile summary"><?= htmlspecialchars($formData['summary'] ?? '', ENT_QUOTES, 'UTF-8') ?></textarea>
    </div>

    <div class="mb-3">
        <label>Skills (comma separated)</label>
        <input type="text" name="skills" class="form-control" value="<?= htmlspecialchars($formData['skills'] ?? '', ENT_QUOTES, 'UTF-8') ?>" placeholder="PHP, MySQL, JavaScript">
    </div>

    <div class="mb-3">
        <label>Work Experience (one per line)</label>
        <textarea name="experience_items" class="form-control" rows="4" placeholder="Software Engineer - ABC Corp (2023-2025)&#10;Intern - XYZ Ltd (2022)"><?= htmlspecialchars($formData['experience_items'] ?? '', ENT_QUOTES, 'UTF-8') ?></textarea>
    </div>

    <div class="mb-3">
        <label>Education (one per line)</label>
        <textarea name="education_items" class="form-control" rows="4" placeholder="BSc Computer Science - Tribhuvan University"><?= htmlspecialchars($formData['education_items'] ?? '', ENT_QUOTES, 'UTF-8') ?></textarea>
    </div>

    <div class="mb-3">
        <label>Projects (one per line)</label>
        <textarea name="projects" class="form-control" rows="3" placeholder="Job Portal Web App - PHP, MySQL"><?= htmlspecialchars($formData['projects'] ?? '', ENT_QUOTES, 'UTF-8') ?></textarea>
    </div>

    <div class="mb-3">
        <label>Certifications (one per line)</label>
        <textarea name="certifications" class="form-control" rows="3" placeholder="AWS Cloud Practitioner"><?= htmlspecialchars($formData['certifications'] ?? '', ENT_QUOTES, 'UTF-8') ?></textarea>
    </div>

    <button class="btn btn-primary">Save Resume</button>
</form>

<a href="<?= base_url('resume') ?>" class="btn btn-link mt-2">Back to resumes</a>

<?php require_once BASE_PATH . '/app/views/layout/footer.php'; ?>
