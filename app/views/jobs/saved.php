<?php require_once BASE_PATH . '/app/views/layout/header.php'; ?>

<section class="relative overflow-hidden rounded-[2rem] border border-slate-200 bg-white p-8 text-slate-900 shadow-soft lg:p-10">
    <div class="absolute inset-0 bg-[radial-gradient(circle_at_top_right,_rgba(148,163,184,0.18),_transparent_30%),radial-gradient(circle_at_bottom_left,_rgba(226,232,240,0.7),_transparent_26%)]"></div>
    <div class="relative">
        <h3 class="text-3xl font-bold tracking-tight">Saved Jobs</h3>
        <p class="mt-4 max-w-2xl text-sm leading-7 text-slate-600 sm:text-base">Keep shortlisted roles here and revisit them before you apply.</p>
    </div>
</section>

<?php if (!empty($_SESSION['flash_success'])): ?>
    <div class="mt-4 rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800" role="alert">
        <?= htmlspecialchars($_SESSION['flash_success'], ENT_QUOTES, 'UTF-8') ?>
    </div>
    <?php unset($_SESSION['flash_success']); ?>
<?php endif; ?>

<?php if (!empty($_SESSION['flash_error'])): ?>
    <div class="mt-4 rounded-2xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-700" role="alert">
        <?= htmlspecialchars($_SESSION['flash_error'], ENT_QUOTES, 'UTF-8') ?>
    </div>
    <?php unset($_SESSION['flash_error']); ?>
<?php endif; ?>

<?php if (empty($jobs)): ?>
    <div class="mt-6 rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-700">No saved jobs yet. Browse jobs and save the ones you want to track.</div>
<?php else: ?>
    <div class="mt-6 overflow-hidden rounded-[2rem] border border-slate-200 bg-white/90 shadow-soft backdrop-blur-sm">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200 text-left text-sm">
                <thead class="bg-slate-100 text-slate-700">
                    <tr>
                        <th class="px-6 py-4 font-semibold">Title</th>
                        <th class="px-6 py-4 font-semibold">Company</th>
                        <th class="px-6 py-4 font-semibold">Location</th>
                        <th class="px-6 py-4 font-semibold">Saved On</th>
                        <th class="px-6 py-4 font-semibold">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 bg-white/70">
                    <?php foreach ($jobs as $job): ?>
                        <tr class="align-top">
                            <td class="px-6 py-5 font-semibold text-slate-900"><?= htmlspecialchars((string)$job['title'], ENT_QUOTES, 'UTF-8') ?></td>
                            <td class="px-6 py-5 text-slate-600"><?= htmlspecialchars((string)$job['employer_display_name'], ENT_QUOTES, 'UTF-8') ?></td>
                            <td class="px-6 py-5 text-slate-600"><?= htmlspecialchars((string)$job['location'], ENT_QUOTES, 'UTF-8') ?></td>
                            <td class="px-6 py-5 text-slate-600"><?= htmlspecialchars((string)$job['saved_at'], ENT_QUOTES, 'UTF-8') ?></td>
                            <td class="px-6 py-5">
                                <div class="flex flex-wrap gap-2">
                                    <a href="<?= base_url('job/view/' . $job['id']) ?>" class="inline-flex rounded-full bg-slate-900 px-4 py-2 text-xs font-semibold text-white transition hover:bg-slate-700">View</a>
                                    <form method="POST" action="<?= base_url('job/unsave/' . $job['id']) ?>">
                                        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                                        <input type="hidden" name="return_to" value="job/saved">
                                        <button class="inline-flex rounded-full border border-slate-200 bg-slate-50 px-4 py-2 text-xs font-semibold text-slate-700 transition hover:border-slate-300 hover:bg-slate-100">Remove</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
<?php endif; ?>

<?php require_once BASE_PATH . '/app/views/layout/footer.php'; ?>
