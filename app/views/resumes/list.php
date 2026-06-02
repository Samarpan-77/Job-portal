<?php require_once BASE_PATH . '/app/views/layout/header.php'; ?>
<?php require_once BASE_PATH . '/app/services/ResumeTemplateService.php'; ?>

<section class="mb-6 flex items-center justify-between gap-4">
    <h3 class="text-3xl font-bold tracking-tight text-slate-900">My Resumes</h3>
    <a href="<?= base_url('resume/create') ?>" class="inline-flex rounded-full bg-slate-900 px-4 py-2 text-sm font-semibold text-white transition hover:bg-slate-700">Create Resume</a>
</section>

    <div class="overflow-hidden rounded-[2rem] border border-slate-200 bg-white/90 shadow-soft backdrop-blur-sm">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-slate-200 text-left text-sm">
            <thead class="bg-slate-100 text-slate-700">
                <tr>
                    <th class="px-6 py-4 font-semibold">ID</th>
                    <th class="px-6 py-4 font-semibold">Name</th>
                    <th class="px-6 py-4 font-semibold">Headline</th>
                    <th class="px-6 py-4 font-semibold">Template</th>
                    <th class="px-6 py-4 font-semibold">Date</th>
                    <th class="px-6 py-4 font-semibold">Action</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 bg-white/70">
                <?php
                $templates = ResumeTemplateService::getAvailableTemplates();
                foreach ($resumes as $resume):
                    $content = $resume['parsed_content'] ?? [];
                    $templateId = $resume['template_id'] ?? 'classic';
                    $template = $templates[$templateId] ?? $templates['classic'];
                    $fullName = trim((string)($content['full_name'] ?? ''));
                    $headline = trim((string)($content['headline'] ?? ''));
                ?>
                    <tr class="align-top">
                        <td class="px-6 py-5 text-slate-700"><?= $resume['id'] ?></td>
                        <td class="px-6 py-5 font-semibold text-slate-900"><?= $fullName !== '' ? htmlspecialchars($fullName, ENT_QUOTES, 'UTF-8') : '-' ?></td>
                        <td class="px-6 py-5 text-slate-700"><?= $headline !== '' ? htmlspecialchars($headline, ENT_QUOTES, 'UTF-8') : '-' ?></td>
                        <td class="px-6 py-5 text-slate-700">
                            <span title="<?= htmlspecialchars($template['description'], ENT_QUOTES, 'UTF-8') ?>">
                                <?= htmlspecialchars($template['icon'], ENT_QUOTES, 'UTF-8') ?>
                                <?= htmlspecialchars($template['name'], ENT_QUOTES, 'UTF-8') ?>
                            </span>
                        </td>
                        <td class="px-6 py-5 text-slate-700"><?= htmlspecialchars($resume['created_at'], ENT_QUOTES, 'UTF-8') ?></td>
                        <td class="px-6 py-5">
                            <div class="flex flex-wrap gap-2">
                                <a href="<?= base_url('resume/view/' . $resume['id']) ?>" class="inline-flex rounded-full bg-slate-900 px-4 py-2 text-xs font-semibold text-white transition hover:bg-slate-700">View</a>
                                <a href="<?= base_url('resume/delete/' . $resume['id']) ?>" class="inline-flex rounded-full border border-slate-200 bg-slate-50 px-4 py-2 text-xs font-semibold text-slate-700 transition hover:border-slate-300 hover:bg-slate-100" onclick="return confirm('Are you sure?');">Delete</a>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php if (empty($resumes)): ?>
    <div class="mt-6 rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-700">
        No resumes yet. <a class="font-semibold text-slate-700 hover:text-slate-900" href="<?= base_url('resume/create') ?>">Create your first resume</a>
    </div>
<?php endif; ?>

<?php require_once BASE_PATH . '/app/views/layout/footer.php'; ?>
