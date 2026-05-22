<?php require_once BASE_PATH . '/app/views/layout/header.php'; ?>

<section class="relative overflow-hidden rounded-[2rem] border border-slate-200 bg-slate-950 p-8 text-white shadow-soft lg:p-10">
    <div class="absolute inset-0 bg-[radial-gradient(circle_at_top_right,_rgba(34,211,238,0.18),_transparent_30%),radial-gradient(circle_at_bottom_left,_rgba(14,165,233,0.16),_transparent_26%)]" style="
    background-color: #1aa3d2;
"></div>
    <div class="relative grid gap-8 lg:grid-cols-[1.2fr_0.8fr] lg:items-center">
        <div>
            <h3 class="text-3xl font-bold tracking-tight">Recommended Jobs (K-Means)</h3>
            <p class="mt-4 max-w-2xl text-sm leading-7 text-slate-300 sm:text-base">
                <?= htmlspecialchars($message, ENT_QUOTES, 'UTF-8') ?>
                <?php if ($matchedCluster !== null): ?>
                    Cluster <?= (int)$matchedCluster ?> matched with <?= (int)$clusterSize ?> similar job postings.
                <?php endif; ?>
            </p>
        </div>
        <img class="h-64 w-full rounded-3xl object-cover shadow-xl shadow-sky-950/30" src="<?= base_url('../images/Image1.png') ?>" alt="Smart job recommendations">
    </div>
</section>

<div class="mt-6 flex flex-wrap gap-3">
    <a href="<?= base_url('job') ?>" class="inline-flex rounded-full border border-sky-200 bg-sky-50 px-4 py-2 text-sm font-semibold text-sky-700 transition hover:border-sky-300 hover:bg-sky-100">All Jobs</a>
    <a href="<?= base_url('application/myApplications') ?>" class="inline-flex rounded-full border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-700 transition hover:border-slate-300 hover:bg-slate-50">My Applications</a>
    <a href="<?= base_url('job/saved') ?>" class="inline-flex rounded-full border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-700 transition hover:border-slate-300 hover:bg-slate-50">Saved Jobs</a>
</div>

<?php if (!empty($_SESSION['flash_success'])): ?>
    <div class="mt-4 rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800" role="alert">
        <?= htmlspecialchars($_SESSION['flash_success'], ENT_QUOTES, 'UTF-8') ?>
    </div>
    <?php unset($_SESSION['flash_success']); ?>
<?php endif; ?>

<?php if (empty($recommendedJobs)): ?>
    <div class="mt-6 rounded-2xl border border-sky-200 bg-sky-50 px-4 py-3 text-sm text-sky-800">No recommendation available yet. Add a resume or apply to more jobs to improve matching.</div>
<?php else: ?>
    <div class="mt-6 overflow-hidden rounded-[2rem] border border-slate-200 bg-white/85 shadow-soft backdrop-blur-sm">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200 text-left text-sm">
                <thead class="bg-slate-950 text-slate-100">
                    <tr>
                        <th class="px-6 py-4 font-semibold">Title</th>
                        <th class="px-6 py-4 font-semibold">Company</th>
                        <th class="px-6 py-4 font-semibold">Location</th>
                        <th class="px-6 py-4 font-semibold">Salary</th>
                        <th class="px-6 py-4 font-semibold">Match Score</th>
                        <th class="px-6 py-4 font-semibold">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 bg-white/70">
                    <?php foreach ($recommendedJobs as $job): ?>
                        <tr class="align-top">
                            <td class="px-6 py-5 font-semibold text-slate-950"><?= htmlspecialchars($job['title'], ENT_QUOTES, 'UTF-8') ?></td>
                            <td class="px-6 py-5">
                                <a href="<?= base_url('profile/company/' . $job['employer_id']) ?>" class="font-medium text-sky-700 hover:text-sky-600">
                                    <?= htmlspecialchars((string)$job['employer_display_name'], ENT_QUOTES, 'UTF-8') ?>
                                </a>
                            </td>
                            <td class="px-6 py-5 text-slate-700"><?= htmlspecialchars($job['location'], ENT_QUOTES, 'UTF-8') ?></td>
                            <td class="px-6 py-5 text-slate-700"><?= htmlspecialchars($job['salary'], ENT_QUOTES, 'UTF-8') ?></td>
                            <td class="px-6 py-5 text-slate-700"><?= number_format(((float)$job['recommendation_score']) * 100, 1) ?>%</td>
                            <td class="px-6 py-5">
                                <div class="flex flex-wrap gap-2">
                                    <a href="<?= base_url('job/view/' . $job['id']) ?>" class="inline-flex rounded-full bg-cyan-500 px-4 py-2 text-xs font-semibold text-white transition hover:bg-cyan-400">View</a>
                                    <?php if (!empty($savedJobs[(int)$job['id']])): ?>
                                        <form method="POST" action="<?= base_url('job/unsave/' . $job['id']) ?>" class="inline-flex">
                                            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                                            <input type="hidden" name="return_to" value="job/recommendations">
                                            <button class="inline-flex rounded-full border border-slate-200 bg-white px-4 py-2 text-xs font-semibold text-slate-700 transition hover:border-slate-300 hover:bg-slate-50">Saved</button>
                                        </form>
                                    <?php else: ?>
                                        <form method="POST" action="<?= base_url('job/save/' . $job['id']) ?>" class="inline-flex">
                                            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                                            <input type="hidden" name="return_to" value="job/recommendations">
                                            <button class="inline-flex rounded-full border border-sky-200 bg-sky-50 px-4 py-2 text-xs font-semibold text-sky-700 transition hover:border-sky-300 hover:bg-sky-100">Save</button>
                                        </form>
                                    <?php endif; ?>
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