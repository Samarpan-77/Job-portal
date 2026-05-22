<?php require_once BASE_PATH . '/app/views/layout/header.php'; ?>

<?php
$jobs = $jobs ?? [];
$savedJobs = $savedJobs ?? [];
$searchQuery = trim((string)($_GET['q'] ?? ''));
?>

<section class="relative overflow-hidden rounded-[2rem] border border-slate-200 bg-slate-950 p-8 text-white shadow-soft lg:p-10">
    <div class="absolute inset-0 bg-[radial-gradient(circle_at_top_right,_rgba(34,211,238,0.18),_transparent_30%),radial-gradient(circle_at_bottom_left,_rgba(14,165,233,0.16),_transparent_26%)]" style="
    background-color: #1aa3d2;
"></div>
    <div class="relative grid gap-8 lg:grid-cols-[1.2fr_0.8fr] lg:items-center">
        <div>
            <h3 class="text-3xl font-bold tracking-tight">Available Jobs</h3>
            <p class="mt-4 max-w-2xl text-sm leading-7 text-slate-300 sm:text-base">Explore current opportunities and open detailed job descriptions to apply with confidence.</p>
        </div>
        <img class="h-64 w-full rounded-3xl object-cover shadow-xl shadow-sky-950/30" src="<?= base_url('../images/Image2.png') ?>" alt="Career opportunities">
    </div>
</section>

<?php if ($searchQuery !== ''): ?>
    <div class="mt-6 flex flex-wrap items-center gap-3 rounded-2xl border border-cyan-200 bg-cyan-50 px-4 py-3 text-sm text-cyan-900">
        <span>Showing results for <strong><?= htmlspecialchars($searchQuery, ENT_QUOTES, 'UTF-8') ?></strong></span>
        <a href="<?= base_url('job') ?>" class="inline-flex rounded-full border border-cyan-200 bg-white px-3 py-1.5 font-semibold text-cyan-700 transition hover:bg-cyan-100">Clear search</a>
    </div>
<?php endif; ?>

<?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'employer'): ?>
    <a href="<?= base_url('job/create') ?>" class="mt-6 inline-flex rounded-full bg-sky-600 px-4 py-2 text-sm font-semibold text-white transition hover:bg-sky-500">Post New Job</a>
<?php endif; ?>

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

<div class="mt-6 overflow-hidden rounded-[2rem] border border-slate-200 bg-white/85 shadow-soft backdrop-blur-sm">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-slate-200 text-left text-sm">
            <thead class="bg-slate-950 text-slate-100">
                <tr>
                    <th class="px-6 py-4 font-semibold">Image</th>
                    <th class="px-6 py-4 font-semibold">Title</th>
                    <th class="px-6 py-4 font-semibold">Location</th>
                    <th class="px-6 py-4 font-semibold">Salary</th>
                    <th class="px-6 py-4 font-semibold">Deadline</th>
                    <th class="px-6 py-4 font-semibold">Action</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 bg-white/70">
                <?php foreach ($jobs as $job): ?>
                    <tr class="align-top">
                        <td class="px-6 py-5">
                            <?php if (!empty($job['image_path'])): ?>
                                <img
                                    src="<?= base_url($job['image_path']) ?>"
                                    alt="<?= htmlspecialchars($job['title'], ENT_QUOTES, 'UTF-8') ?>"
                                    class="h-16 w-24 rounded-2xl border border-slate-200 object-cover">
                            <?php else: ?>
                                <span class="text-xs text-slate-400">No image</span>
                            <?php endif; ?>
                        </td>
                        <td class="px-6 py-5">
                            <div class="font-semibold text-slate-950"><?= htmlspecialchars($job['title']) ?></div>
                            <div class="mt-1 text-sm text-slate-500">
                                <a href="<?= base_url('profile/company/' . $job['employer_id']) ?>" class="font-medium text-sky-700 hover:text-sky-600">
                                    <?= htmlspecialchars((string)$job['employer_display_name'], ENT_QUOTES, 'UTF-8') ?>
                                </a>
                            </div>
                        </td>
                        <td class="px-6 py-5 text-slate-700"><?= htmlspecialchars($job['location']) ?></td>
                        <td class="px-6 py-5 text-slate-700"><?= htmlspecialchars($job['salary']) ?></td>
                        <td class="px-6 py-5">
                            <?php if (!empty($job['application_deadline'])): ?>
                                <?= htmlspecialchars($job['application_deadline'], ENT_QUOTES, 'UTF-8') ?>
                                <?php if ($job['application_deadline'] < date('Y-m-d')): ?>
                                    <div class="mt-1 text-xs font-semibold text-rose-600">Closed</div>
                                <?php endif; ?>
                            <?php else: ?>
                                <span class="text-xs text-slate-400">No deadline</span>
                            <?php endif; ?>
                        </td>
                        <td class="px-6 py-5">
                            <div class="flex flex-wrap gap-2">
                                <a href="<?= base_url('job/view/' . $job['id']) ?>" class="inline-flex rounded-full bg-cyan-500 px-4 py-2 text-xs font-semibold text-white transition hover:bg-cyan-400">View</a>
                                <?php if (($_SESSION['role'] ?? '') === 'user'): ?>
                                    <?php if (!empty($savedJobs[(int)$job['id']])): ?>
                                        <form method="POST" action="<?= base_url('job/unsave/' . $job['id']) ?>" class="inline-flex">
                                            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                                            <input type="hidden" name="return_to" value="job">
                                            <button class="inline-flex rounded-full border border-slate-200 bg-white px-4 py-2 text-xs font-semibold text-slate-700 transition hover:border-slate-300 hover:bg-slate-50">Saved</button>
                                        </form>
                                    <?php else: ?>
                                        <form method="POST" action="<?= base_url('job/save/' . $job['id']) ?>" class="inline-flex">
                                            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                                            <input type="hidden" name="return_to" value="job">
                                            <button class="inline-flex rounded-full border border-sky-200 bg-sky-50 px-4 py-2 text-xs font-semibold text-sky-700 transition hover:border-sky-300 hover:bg-sky-100">Save</button>
                                        </form>
                                    <?php endif; ?>
                                <?php endif; ?>
                                <?php
                                $canDelete = isset($_SESSION['user_id'], $_SESSION['role']) &&
                                    (
                                        (($_SESSION['role'] ?? '') === 'admin') ||
                                        ((($_SESSION['role'] ?? '') === 'employer') && ((int)$job['employer_id'] === (int)$_SESSION['user_id']))
                                    );
                                ?>
                                <?php if ($canDelete): ?>
                                    <a
                                        href="<?= base_url('job/delete/' . $job['id']) ?>"
                                        class="inline-flex rounded-full border border-rose-200 bg-rose-50 px-4 py-2 text-xs font-semibold text-rose-700 transition hover:border-rose-300 hover:bg-rose-100"
                                        onclick="return confirm('Delete this job post?');">Delete</a>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once BASE_PATH . '/app/views/layout/footer.php'; ?>
