<?php require_once BASE_PATH . '/app/views/layout/header.php'; ?>
<?php require_once BASE_PATH . '/app/services/ResumeTemplateService.php'; ?>

<?php
$title = $metaTitle ?? 'Resume';
$fullName = $resumeData['full_name'] ?? '';
$headline = $resumeData['headline'] ?? '';
$backPath = (($_SESSION['role'] ?? '') === 'employer') ? 'application/employerApplications' : 'resume';
$templates = (isset($templates) && is_array($templates)) ? $templates : ResumeTemplateService::getAvailableTemplates();
$currentTemplateId = $templateId ?? 'classic';
$isOwner = !isset($readOnly) || ($readOnly && (int)$_SESSION['user_id'] === ($resumeOwnerId ?? 0));
$canChangeTemplate = !$readOnly || $isOwner;
$canDownload = $isOwner && (($_SESSION['role'] ?? '') === 'user');
?>

<section class="space-y-6">
    <div class="rounded-[2rem] border border-slate-200 bg-white/85 p-8 shadow-soft backdrop-blur-sm">
        <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
            <div>
                <h3 class="text-3xl font-bold tracking-tight text-slate-950"><?= htmlspecialchars($title, ENT_QUOTES, 'UTF-8') ?></h3>
            </div>
            <div class="flex flex-wrap gap-3">
                <?php if ($canDownload): ?>
                    <a href="<?= base_url('resume/downloadPDF/' . ($resumeId ?? 0)) ?>" class="inline-flex rounded-full border border-emerald-200 bg-emerald-50 px-4 py-2 text-sm font-semibold text-emerald-700 transition hover:border-emerald-300 hover:bg-emerald-100" title="Download as PDF">📥 Download PDF</a>
                <?php endif; ?>
                <button type="button" class="inline-flex rounded-full border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-700 transition hover:border-slate-300 hover:bg-slate-50" onclick="window.print()" title="Print to PDF">🖨️ Print</button>
            </div>
        </div>
    </div>

    <div class="rounded-[2rem] border border-slate-200 bg-white/85 p-6 shadow-soft backdrop-blur-sm">
        <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
            <div>
                <label class="mb-2 block text-sm font-semibold text-slate-700"><strong>Resume Template:</strong></label>
                <?php if ($canChangeTemplate): ?>
                    <div class="flex flex-wrap gap-2">
                        <?php foreach ($templates as $tId => $template): ?>
                            <form method="POST" action="<?= base_url('resume/changeTemplate/' . ($resumeId ?? 0)) ?>" class="inline-flex">
                                <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                                <input type="hidden" name="template_id" value="<?= $tId ?>">
                                <button type="submit" class="rounded-full border px-4 py-2 text-sm font-semibold transition <?= ($currentTemplateId === $tId) ? 'border-sky-500 bg-sky-50 text-sky-700' : 'border-slate-200 bg-white text-slate-700 hover:border-slate-300 hover:bg-slate-50' ?>" title="<?= htmlspecialchars($template['description'], ENT_QUOTES, 'UTF-8') ?>">
                                    <?= htmlspecialchars($template['icon'], ENT_QUOTES, 'UTF-8') ?> <?= htmlspecialchars($template['name'], ENT_QUOTES, 'UTF-8') ?>
                                </button>
                            </form>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <?php $activeTemplate = $templates[$currentTemplateId] ?? ($templates['classic'] ?? null); ?>
                    <span class="inline-flex rounded-full border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-700">
                        <?= htmlspecialchars((string)($activeTemplate['icon'] ?? ''), ENT_QUOTES, 'UTF-8') ?>
                        <?= htmlspecialchars((string)($activeTemplate['name'] ?? 'Classic'), ENT_QUOTES, 'UTF-8') ?>
                    </span>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="overflow-hidden rounded-[2rem] border border-slate-200 bg-white/85 shadow-soft backdrop-blur-sm">
        <div class="resume-content p-4">
            <?= ResumeTemplateService::renderResume($resumeData, $currentTemplateId) ?>
        </div>
    </div>

    <div class="flex flex-wrap gap-3">
        <a href="<?= base_url($backPath) ?>" class="inline-flex items-center rounded-full border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-700 shadow-sm transition hover:border-slate-300 hover:bg-slate-50">← Back</a>
        <?php if (isset($readOnly) && !$readOnly): ?>
            <a href="<?= base_url('resume/edit/' . ($resumeId ?? 0)) ?>" class="inline-flex rounded-full border border-amber-200 bg-amber-50 px-4 py-2 text-sm font-semibold text-amber-700 transition hover:border-amber-300 hover:bg-amber-100">Edit</a>
            <a href="<?= base_url('resume/delete/' . ($resumeId ?? 0)) ?>" class="inline-flex rounded-full border border-rose-200 bg-rose-50 px-4 py-2 text-sm font-semibold text-rose-700 transition hover:border-rose-300 hover:bg-rose-100" onclick="return confirm('Are you sure?')">Delete</a>
        <?php endif; ?>
    </div>
</section>

<?php require_once BASE_PATH . '/app/views/layout/footer.php'; ?>
