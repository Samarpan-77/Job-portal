<?php require_once BASE_PATH . '/app/views/layout/header.php'; ?>

<?php
$canManageImage = isset($_SESSION['user_id'], $_SESSION['role']) &&
    ($_SESSION['role'] === 'employer') &&
    ((int)$job['employer_id'] === (int)$_SESSION['user_id']);
$isDeadlinePassed = !empty($job['application_deadline']) && $job['application_deadline'] < date('Y-m-d');
?>

<?php if (!empty($_SESSION['flash_success'])): ?>
    <div class="mb-4 rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800" role="alert">
        <?= htmlspecialchars($_SESSION['flash_success'], ENT_QUOTES, 'UTF-8') ?>
    </div>
    <?php unset($_SESSION['flash_success']); ?>
<?php endif; ?>

<?php if (!empty($_SESSION['flash_error'])): ?>
    <div class="mb-4 rounded-2xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-700" role="alert">
        <?= htmlspecialchars($_SESSION['flash_error'], ENT_QUOTES, 'UTF-8') ?>
    </div>
    <?php unset($_SESSION['flash_error']); ?>
<?php endif; ?>

<section class="space-y-6">
    <div class="rounded-[2rem] border border-slate-200 bg-white/90 p-8 shadow-soft backdrop-blur-sm">
        <h3 class="text-3xl font-bold tracking-tight text-slate-900"><?= htmlspecialchars($job['title']) ?></h3>

        <?php if (!empty($job['image_path'])): ?>
            <div class="mb-6 rounded-[2rem] border border-slate-200 bg-slate-50 p-4">
                <div class="flex aspect-[16/9] items-center justify-center overflow-hidden rounded-[1.5rem] bg-white">
                    <img
                        src="<?= base_url($job['image_path']) ?>"
                        alt="<?= htmlspecialchars($job['title'], ENT_QUOTES, 'UTF-8') ?>"
                        class="h-full w-full object-contain"
                    >
                </div>
            </div>
        <?php endif; ?>

        <div class="grid gap-4 text-sm text-slate-700 sm:grid-cols-2">
            <p><strong class="text-slate-900">Location:</strong> <?= htmlspecialchars($job['location']) ?></p>
            <p><strong class="text-slate-900">Salary:</strong> <?= htmlspecialchars($job['salary']) ?></p>
            <p>
                <strong class="text-slate-900">Posted On:</strong>
                <?= !empty($job['created_at']) ? htmlspecialchars(date('F j, Y', strtotime((string)$job['created_at'])), ENT_QUOTES, 'UTF-8') : '<span class="text-slate-400">N/A</span>' ?>
            </p>
            <p>
                <strong class="text-slate-900">Application Deadline:</strong>
                <?php if (!empty($job['application_deadline'])): ?>
                    <?= htmlspecialchars($job['application_deadline'], ENT_QUOTES, 'UTF-8') ?>
                    <?php if ($isDeadlinePassed): ?>
                        <span class="font-semibold text-rose-600">(Closed)</span>
                    <?php endif; ?>
                <?php else: ?>
                    <span class="text-slate-400">No deadline set</span>
                <?php endif; ?>
            </p>
            <p class="sm:col-span-2">
                <strong class="text-slate-900">Company:</strong>
                <a href="<?= base_url('profile/company/' . $job['employer_id']) ?>" class="font-semibold text-slate-700 hover:text-slate-900">
                    <?= htmlspecialchars((string)$job['employer_display_name'], ENT_QUOTES, 'UTF-8') ?>
                </a>
            </p>
        </div>
        <p class="mt-6 leading-8 text-slate-700"><?= nl2br(htmlspecialchars($job['description'])) ?></p>
    </div>

    <?php if ($canManageImage): ?>
        <div class="rounded-[2rem] border border-slate-200 bg-white/90 p-6 shadow-soft backdrop-blur-sm">
            <h5 class="text-lg font-bold tracking-tight text-slate-900">Manage Job Image</h5>
            <form method="POST" action="<?= base_url('job/uploadImage/' . $job['id']) ?>" enctype="multipart/form-data" class="mt-4 space-y-4">
                <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                <input type="file" name="image" class="block w-full rounded-2xl border border-dashed border-slate-300 bg-slate-50 px-4 py-3 text-sm text-slate-700 file:mr-4 file:rounded-full file:border-0 file:bg-slate-900 file:px-4 file:py-2 file:text-sm file:font-semibold file:text-white hover:file:bg-slate-700" accept=".jpg,.jpeg,.png,.webp,.gif,image/*" required>
                <button class="inline-flex rounded-full bg-slate-900 px-4 py-2 text-sm font-semibold text-white transition hover:bg-slate-700"><?= !empty($job['image_path']) ? 'Replace Image' : 'Upload Image' ?></button>
            </form>

            <?php if (!empty($job['image_path'])): ?>
                <form method="POST" action="<?= base_url('job/removeImage/' . $job['id']) ?>" class="mt-3">
                    <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                    <button class="inline-flex rounded-full border border-slate-200 bg-slate-50 px-4 py-2 text-sm font-semibold text-slate-700 transition hover:border-slate-300 hover:bg-slate-100" onclick="return confirm('Remove this job image?');">Remove Image</button>
                </form>
            <?php endif; ?>
        </div>
    <?php endif; ?>

    <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'user'): ?>
        <div class="flex flex-wrap gap-3">
            <?php if (!empty($isSaved)): ?>
                <form method="POST" action="<?= base_url('job/unsave/' . $job['id']) ?>">
                    <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                    <input type="hidden" name="return_to" value="<?= 'job/view/' . $job['id'] ?>">
                    <button class="inline-flex rounded-full border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-700 transition hover:border-slate-300 hover:bg-slate-50">Remove from Saved</button>
                </form>
            <?php else: ?>
                <form method="POST" action="<?= base_url('job/save/' . $job['id']) ?>">
                    <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                    <input type="hidden" name="return_to" value="<?= 'job/view/' . $job['id'] ?>">
                    <button class="inline-flex rounded-full border border-slate-200 bg-slate-50 px-4 py-2 text-sm font-semibold text-slate-700 transition hover:border-slate-300 hover:bg-slate-100">Save Job</button>
                </form>
            <?php endif; ?>
            <a href="<?= base_url('job/saved') ?>" class="inline-flex rounded-full border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-700 transition hover:border-slate-300 hover:bg-slate-50">Saved Jobs</a>
        </div>

        <?php if (empty($resumes)): ?>
            <div class="rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-700">Create a resume first before applying.</div>
            <a href="<?= base_url('resume/create') ?>" class="inline-flex rounded-full bg-slate-900 px-4 py-2 text-sm font-semibold text-white transition hover:bg-slate-700">Create Resume</a>
        <?php elseif ($isDeadlinePassed): ?>
            <div class="rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-700">This vacancy is closed and no longer accepting applications.</div>
        <?php else: ?>
            <form method="POST" action="<?= base_url('application/apply') ?>" class="space-y-4 rounded-[2rem] border border-slate-200 bg-slate-50/80 p-6">
                <input type="hidden" name="job_id" value="<?= $job['id'] ?>">
                <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">

                <select name="resume_id" class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-slate-900 outline-none transition focus:border-slate-300 focus:ring-4 focus:ring-slate-100" required>
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

                <button class="inline-flex rounded-full bg-slate-900 px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-slate-700">Apply</button>
            </form>
        <?php endif; ?>
    <?php endif; ?>

    <a href="<?= base_url('job') ?>" class="inline-flex items-center rounded-full border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-700 shadow-sm transition hover:border-slate-300 hover:bg-slate-50">Back to jobs</a>
</section>

<?php require_once BASE_PATH . '/app/views/layout/footer.php'; ?>
