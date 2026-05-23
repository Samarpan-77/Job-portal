<?php require_once BASE_PATH . '/app/views/layout/header.php'; ?>
<?php $feedJobs = $jobs ?? []; ?>

<section class="relative overflow-hidden rounded-[2rem] border border-slate-200 bg-slate-950 p-8 text-white shadow-soft lg:p-10">
    <div class="absolute inset-0 bg-[radial-gradient(circle_at_top_right,_rgba(34,211,238,0.18),_transparent_30%),radial-gradient(circle_at_bottom_left,_rgba(14,165,233,0.16),_transparent_26%)] " style="
    background-color: #1aa3d2;"></div>
    <div class="relative grid gap-8 lg:grid-cols-[1.2fr_0.8fr] lg:items-center">
        <div>
            <h3 class="text-3xl font-bold tracking-tight">Employer Dashboard</h3>
            <p class="mt-4 max-w-2xl text-sm leading-7 text-slate-300 sm:text-base">
                Publish roles, review candidate flow, and manage hiring activity from one workspace.
                <?php if (!empty($profile['company_name'])): ?>
                    Company page: <?= htmlspecialchars($profile['company_name'], ENT_QUOTES, 'UTF-8') ?>.
                <?php endif; ?>
            </p>
        </div>
        <img class="h-64 w-full rounded-3xl object-cover shadow-xl shadow-sky-950/30" src="<?= base_url('../images/Image2.png') ?>" alt="Employer recruitment dashboard">
    </div>
</section>

<div class="mt-8 grid gap-4 md:grid-cols-2 xl:grid-cols-3">
    <div class="rounded-[2rem] border border-slate-200 bg-white/85 p-6 shadow-soft backdrop-blur-sm">
        <h5 class="text-base font-semibold text-slate-600">My Jobs</h5>
        <p class="mt-4 text-4xl font-bold tracking-tight text-slate-950"><?= $job_count ?></p>
        <div class="mt-6 flex flex-wrap gap-3">
            <a href="<?= base_url('job/create') ?>" class="inline-flex rounded-full bg-sky-600 px-4 py-2 text-sm font-semibold text-white transition hover:bg-sky-500">Post Job</a>
            <a href="<?= base_url('job') ?>" class="inline-flex rounded-full border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-700 transition hover:border-slate-300 hover:bg-slate-50">View All Jobs</a>
        </div>
    </div>

    <div class="rounded-[2rem] border border-slate-200 bg-white/85 p-6 shadow-soft backdrop-blur-sm">
        <h5 class="text-base font-semibold text-slate-600">Total Applicants</h5>
        <p class="mt-4 text-4xl font-bold tracking-tight text-slate-950"><?= $application_count ?></p>
        <a href="<?= base_url('application/employerApplications') ?>" class="mt-6 inline-flex rounded-full border border-sky-200 bg-sky-50 px-4 py-2 text-sm font-semibold text-sky-700 transition hover:border-sky-300 hover:bg-sky-100">Manage Applications</a>
    </div>

    <div class="rounded-[2rem] border border-slate-200 bg-white/85 p-6 shadow-soft backdrop-blur-sm">
        <h5 class="text-base font-semibold text-slate-600">Company Profile</h5>
        <p class="mt-4 text-4xl font-bold tracking-tight text-slate-950"><?= !empty($profile['company_name']) ? 'Live' : 'Setup' ?></p>
        <div class="mt-6 flex flex-wrap gap-3">
            <a href="<?= base_url('profile') ?>" class="inline-flex rounded-full border border-sky-200 bg-sky-50 px-4 py-2 text-sm font-semibold text-sky-700 transition hover:border-sky-300 hover:bg-sky-100">View Company Page</a>
            <a href="<?= base_url('profile/edit') ?>" class="inline-flex rounded-full border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-700 transition hover:border-slate-300 hover:bg-slate-50">Edit Company Profile</a>
        </div>
    </div>
</div>

<div class="mt-8 grid gap-6 xl:grid-cols-[minmax(0,1fr)_320px]">
    <div class="space-y-4">
        <div class="flex items-center justify-between gap-3">
            <div>
                <p class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-400">Job feed</p>
                <h4 class="mt-2 text-2xl font-bold tracking-tight text-slate-950">Your latest posted jobs</h4>
            </div>
            <span class="rounded-full bg-sky-50 px-3 py-1 text-xs font-semibold text-sky-700 ring-1 ring-sky-200">
                <?= count($feedJobs) ?> jobs
            </span>
        </div>

        <?php if (empty($feedJobs)): ?>
            <div class="rounded-[2rem] border border-sky-200 bg-sky-50 px-5 py-4 text-sm text-sky-800">
                You have not posted any jobs yet.
            </div>
        <?php else: ?>
            <div class="space-y-4">
                <?php foreach ($feedJobs as $job): ?>
                    <?php
                    $description = trim(preg_replace('/\s+/', ' ', strip_tags((string)($job['description'] ?? ''))));
                    $excerpt = function_exists('mb_substr') ? mb_substr($description, 0, 240) : substr($description, 0, 240);
                    if (strlen($description) > strlen($excerpt)) {
                        $excerpt .= '...';
                    }
                    ?>
                    <article class="overflow-hidden rounded-[2rem] border border-slate-200 bg-white/90 shadow-soft backdrop-blur-sm transition hover:-translate-y-0.5 hover:shadow-xl">
                        <div class="p-5 sm:p-6">
                            <div class="flex items-start gap-4">
                                <a href="<?= base_url('profile') ?>" class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-gradient-to-br from-sky-600 to-cyan-500 text-sm font-bold text-white shadow-lg shadow-sky-100">
                                    <?= htmlspecialchars(strtoupper(substr((string)($profile['company_name'] ?? $profile['name'] ?? 'C'), 0, 1)), ENT_QUOTES, 'UTF-8') ?>
                                </a>

                                <div class="min-w-0 flex-1">
                                    <div class="flex flex-wrap items-center gap-2 text-xs font-semibold">
                                        <a href="<?= base_url('profile') ?>" class="text-sky-700 hover:text-sky-600">
                                            <?= htmlspecialchars((string)($profile['company_name'] ?: $profile['name']), ENT_QUOTES, 'UTF-8') ?>
                                        </a>
                                        <span class="text-slate-300">&middot;</span>
                                        <span class="text-slate-400">
                                            <?= !empty($job['created_at']) ? htmlspecialchars(date('M j, Y', strtotime((string)$job['created_at'])), ENT_QUOTES, 'UTF-8') : 'Just now' ?>
                                        </span>
                                    </div>

                                    <h4 class="mt-3 text-xl font-bold tracking-tight text-slate-950">
                                        <a href="<?= base_url('job/view/' . $job['id']) ?>" class="hover:text-sky-700">
                                            <?= htmlspecialchars($job['title'], ENT_QUOTES, 'UTF-8') ?>
                                        </a>
                                    </h4>

                                    <p class="mt-2 text-sm text-slate-500">
                                        <?= htmlspecialchars((string)$job['location'], ENT_QUOTES, 'UTF-8') ?>
                                        <span class="mx-2 text-slate-300">&middot;</span>
                                        <?= htmlspecialchars((string)$job['salary'], ENT_QUOTES, 'UTF-8') ?>
                                        <?php if (!empty($job['application_deadline'])): ?>
                                            <span class="mx-2 text-slate-300">&middot;</span>
                                            Deadline <?= htmlspecialchars((string)$job['application_deadline'], ENT_QUOTES, 'UTF-8') ?>
                                        <?php endif; ?>
                                    </p>
                                </div>
                            </div>

                            <?php if (!empty($job['image_path'])): ?>
                                <a href="<?= base_url('job/view/' . $job['id']) ?>" class="mt-5 block overflow-hidden rounded-[1.75rem]">
                                    <img
                                        src="<?= base_url($job['image_path']) ?>"
                                        alt="<?= htmlspecialchars($job['title'], ENT_QUOTES, 'UTF-8') ?>"
                                        class="h-72 w-full object-cover">
                                </a>
                            <?php endif; ?>

                            <?php if ($excerpt !== ''): ?>
                                <p class="mt-5 text-sm leading-7 text-slate-700">
                                    <?= htmlspecialchars($excerpt, ENT_QUOTES, 'UTF-8') ?>
                                </p>
                            <?php endif; ?>

                            <div class="mt-5 flex flex-wrap items-center gap-3">
                                <a href="<?= base_url('job/view/' . $job['id']) ?>" class="inline-flex rounded-full bg-cyan-500 px-4 py-2 text-sm font-semibold text-white transition hover:bg-cyan-400">View job</a>
                                <a href="<?= base_url('profile') ?>" class="inline-flex rounded-full border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-700 transition hover:border-slate-300 hover:bg-slate-50">View company page</a>
                            </div>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php require_once BASE_PATH . '/app/views/layout/footer.php'; ?>
