<?php require_once BASE_PATH . '/app/views/layout/header.php'; ?>
<?php require_once BASE_PATH . '/app/services/ResumeTemplateService.php'; ?>

<?php
$title = $metaTitle ?? 'Resume';
$fullName = $resumeData['full_name'] ?? '';
$headline = $resumeData['headline'] ?? '';
$backPath = (($_SESSION['role'] ?? '') === 'employer') ? 'application/employerApplications' : 'resume';
$currentTemplateId = $templateId ?? 'classic';
$isOwner = !isset($readOnly) || ($readOnly && (int)$_SESSION['user_id'] === ($resumeOwnerId ?? 0));
?>

<h3><?= htmlspecialchars($title, ENT_QUOTES, 'UTF-8') ?></h3>

<style>
    @media print {

        nav,
        .btn,
        .resume-actions,
        .template-selector,
        .alert {
            display: none !important;
        }
    }
</style>

<!-- Template Selector -->
<div class="template-selector mb-4 p-3 bg-light rounded">
    <div class="row align-items-center">
        <div class="col-md-8">
            <label class="form-label mb-0"><strong>Resume Template:</strong></label>
            <div class="btn-group" role="group">
                <?php foreach ($templates as $tId => $template): ?>
                    <form method="POST" action="<?= base_url('resume/changeTemplate/' . ($resumeId ?? 0)) ?>" style="display: inline;" class="template-form">
                        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                        <input type="hidden" name="template_id" value="<?= $tId ?>">
                        <button type="submit" class="btn btn-sm <?= ($currentTemplateId === $tId) ? 'btn-primary' : 'btn-outline-primary' ?>"
                            title="<?= htmlspecialchars($template['description'], ENT_QUOTES, 'UTF-8') ?>">
                            <?= htmlspecialchars($template['icon'], ENT_QUOTES, 'UTF-8') ?> <?= htmlspecialchars($template['name'], ENT_QUOTES, 'UTF-8') ?>
                        </button>
                    </form>
                <?php endforeach; ?>
            </div>
        </div>
        <div class="col-md-4 text-end">
            <a href="<?= base_url('resume/downloadPDF/' . ($resumeId ?? 0)) ?>" class="btn btn-outline-success btn-sm" title="Download as PDF">
                📥 Download PDF
            </a>
            <button type="button" class="btn btn-outline-dark btn-sm" onclick="window.print()" title="Print to PDF">
                🖨️ Print
            </button>
        </div>
    </div>
</div>

<!-- Resume Content Using Selected Template -->
<div class="resume-content mb-4">
    <?= ResumeTemplateService::renderResume($resumeData, $currentTemplateId) ?>
</div>

<!-- Resume Actions -->
<div class="resume-actions mt-3">
    <a href="<?= base_url($backPath) ?>" class="btn btn-link btn-sm">← Back</a>
    <?php if (isset($readOnly) && !$readOnly): ?>
        <a href="<?= base_url('resume/edit/' . ($resumeId ?? 0)) ?>" class="btn btn-warning btn-sm">Edit</a>
        <a href="<?= base_url('resume/delete/' . ($resumeId ?? 0)) ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">Delete</a>
    <?php endif; ?>
</div>

<style>
    .template-form {
        display: inline;
    }

    .resume-content {
        background: white;
        padding: 10px;
        border-radius: 4px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    }
</style>

<?php require_once BASE_PATH . '/app/views/layout/footer.php'; ?>