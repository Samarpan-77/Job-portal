<?php require_once BASE_PATH . '/app/views/layout/header.php'; ?>

<?php
$feedJobs = $jobs ?? [];
$headline = trim((string)($profile['headline'] ?? ''));
$savedJobMap = $savedJobs ?? [];
$recommendedJobs = $recommendedJobs ?? [];
$recommendationMessage = trim((string)($recommendationMessage ?? ''));
$applicationCount = (int)($application_count ?? 0);
$savedJobCount = (int)($saved_job_count ?? 0);
$feedCount = count($feedJobs);
$profileInitial = strtoupper(substr((string)($profile['name'] ?? 'U'), 0, 1));
$profileImage = trim((string)($profile['profile_image'] ?? ''));
?>

<section id="dashboardScroll" class="dashboard-scroll-container mx-auto max-w-7xl space-y-6">
    <section class="overflow-hidden rounded-[2rem] border border-slate-200 bg-white text-slate-900 shadow-soft">
        <div class="bg-[radial-gradient(circle_at_top_right,_rgba(148,163,184,0.18),_transparent_30%),radial-gradient(circle_at_bottom_left,_rgba(226,232,240,0.7),_transparent_26%)] px-6 py-6 sm:px-8">
            <div class="flex flex-col gap-6 lg:flex-row lg:items-center lg:justify-between">
                <div class="max-w-2xl">
                    <p class="text-xs font-semibold uppercase tracking-[0.25em] text-slate-500">Home feed</p>
                    <h2 class="mt-2 text-3xl font-bold tracking-tight sm:text-4xl">
                        Hello, <?= htmlspecialchars((string)($profile['name'] ?? 'there'), ENT_QUOTES, 'UTF-8') ?>
                    </h2>
                    <p class="mt-3 text-sm leading-7 text-slate-600 sm:text-base">
                        <?= $headline !== '' ? htmlspecialchars($headline, ENT_QUOTES, 'UTF-8') : 'Your dashboard is ready.' ?>
                    </p>
                </div>

                <div class="grid grid-cols-3 gap-3 sm:min-w-[340px]">
                    <div class="rounded-2xl border border-slate-200 bg-white p-4">
                        <p class="text-[11px] font-semibold uppercase tracking-[0.2em] text-slate-500">Feed</p>
                        <p class="mt-2 text-2xl font-bold"><?= $feedCount ?></p>
                    </div>
                    <div class="rounded-2xl border border-slate-200 bg-white p-4">
                        <p class="text-[11px] font-semibold uppercase tracking-[0.2em] text-slate-500">Saved</p>
                        <p class="mt-2 text-2xl font-bold"><?= $savedJobCount ?></p>
                    </div>
                    <div class="rounded-2xl border border-slate-200 bg-white p-4">
                        <p class="text-[11px] font-semibold uppercase tracking-[0.2em] text-slate-500">Applied</p>
                        <p class="mt-2 text-2xl font-bold"><?= $applicationCount ?></p>
                    </div>
                </div>
            </div>

        </div>
    </section>

    <div class="grid gap-6 xl:grid-cols-[minmax(0,1fr)_320px]">
        <div class="space-y-5">

            <?php if (empty($feedJobs)): ?>
                <div class="rounded-[2rem] border border-slate-200 bg-slate-50 px-5 py-4 text-sm text-slate-700">
                    No jobs available right now.
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
                        $isSaved = !empty($savedJobMap[(int)$job['id']]);
                        ?>
                        <article class="overflow-hidden rounded-[2rem] border border-slate-200 bg-white/90 shadow-soft backdrop-blur-sm transition hover:-translate-y-0.5 hover:shadow-xl">
                            <div class="p-5 sm:p-6">
                                <div class="flex items-start gap-4">
                                    <a href="<?= base_url('profile/company/' . $job['employer_id']) ?>" class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-slate-100 text-sm font-bold text-slate-700 shadow-sm ring-1 ring-slate-200">
                                        <?= htmlspecialchars(strtoupper(substr((string)($job['employer_display_name'] ?? 'C'), 0, 1)), ENT_QUOTES, 'UTF-8') ?>
                                    </a>

                                    <div class="min-w-0 flex-1">
                                        <div class="flex flex-wrap items-center gap-2 text-xs font-semibold">
                                            <a href="<?= base_url('profile/company/' . $job['employer_id']) ?>" class="text-slate-700 hover:text-slate-900">
                                                <?= htmlspecialchars((string)$job['employer_display_name'], ENT_QUOTES, 'UTF-8') ?>
                                            </a>
                                            <span class="text-slate-300">&middot;</span>
                                            <span class="text-slate-500">
                                                <?= !empty($job['created_at']) ? htmlspecialchars(date('M j, Y', strtotime((string)$job['created_at'])), ENT_QUOTES, 'UTF-8') : 'Just now' ?>
                                            </span>
                                        </div>

                                        <h4 class="mt-3 text-xl font-bold tracking-tight text-slate-900">
                                            <a href="<?= base_url('job/view/' . $job['id']) ?>" class="hover:text-slate-700">
                                                <?= htmlspecialchars($job['title'], ENT_QUOTES, 'UTF-8') ?>
                                            </a>
                                        </h4>

                                        <p class="mt-2 text-sm text-slate-600">
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
                                    <a href="<?= base_url('job/view/' . $job['id']) ?>" class="mt-5 block rounded-[1.75rem] border border-slate-200 bg-slate-50 p-3">
                                        <div class="flex aspect-[16/9] items-center justify-center overflow-hidden rounded-[1.35rem] bg-white">
                                            <img
                                                src="<?= base_url($job['image_path']) ?>"
                                                alt="<?= htmlspecialchars($job['title'], ENT_QUOTES, 'UTF-8') ?>"
                                                class="h-full w-full object-contain">
                                        </div>
                                    </a>
                                <?php endif; ?>

                                <?php if ($excerpt !== ''): ?>
                                    <p class="mt-5 text-sm leading-7 text-slate-700">
                                        <?= htmlspecialchars($excerpt, ENT_QUOTES, 'UTF-8') ?>
                                    </p>
                                <?php endif; ?>

                                <div class="mt-5 flex flex-wrap items-center gap-3">
                                    <a href="<?= base_url('job/view/' . $job['id']) ?>" class="inline-flex rounded-full bg-slate-900 px-4 py-2 text-sm font-semibold text-white transition hover:bg-slate-700">View job</a>
                                    <?php if ($isSaved): ?>
                                        <form method="POST" action="<?= base_url('job/unsave/' . $job['id']) ?>" class="inline-flex">
                                            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                                            <input type="hidden" name="return_to" value="dashboard">
                                            <button class="inline-flex rounded-full border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-700 transition hover:border-slate-300 hover:bg-slate-50">Saved</button>
                                        </form>
                                    <?php else: ?>
                                        <form method="POST" action="<?= base_url('job/save/' . $job['id']) ?>" class="inline-flex">
                                            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                                            <input type="hidden" name="return_to" value="dashboard">
                                        <button class="inline-flex rounded-full border border-slate-200 bg-slate-50 px-4 py-2 text-sm font-semibold text-slate-700 transition hover:border-slate-300 hover:bg-slate-100">Save</button>
                                        </form>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </article>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>

        <aside class="space-y-5 xl:sticky xl:top-24 xl:self-start">
            <div class="overflow-hidden rounded-[2rem] border border-slate-200 bg-white/90 shadow-soft backdrop-blur-sm">
                <div class="h-24 bg-gradient-to-r from-slate-100 via-slate-50 to-white"></div>
                <div class="px-6 pb-6">
                    <div class="-mt-10 flex items-end gap-4">
                        <?php if ($profileImage !== ''): ?>
                            <img
                                src="<?= base_url($profileImage) ?>"
                                alt="<?= htmlspecialchars((string)($profile['name'] ?? 'Profile'), ENT_QUOTES, 'UTF-8') ?>"
                                class="h-20 w-20 rounded-3xl border-4 border-white object-cover shadow-lg shadow-slate-100">
                        <?php else: ?>
                            <div class="flex h-20 w-20 items-center justify-center rounded-3xl border-4 border-white bg-slate-100 text-2xl font-bold text-slate-700 shadow-lg shadow-slate-100">
                                <?= htmlspecialchars($profileInitial, ENT_QUOTES, 'UTF-8') ?>
                            </div>
                        <?php endif; ?>

                        <div class="pb-1">
                            <p class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-500">Profile</p>
                            <h4 class="mt-1 text-xl font-bold tracking-tight text-slate-900">
                                <?= htmlspecialchars((string)($profile['name'] ?? 'Your profile'), ENT_QUOTES, 'UTF-8') ?>
                            </h4>
                        </div>
                    </div>

                    <p class="mt-4 text-sm leading-7 text-slate-600">
                        <?= $headline !== '' ? htmlspecialchars($headline, ENT_QUOTES, 'UTF-8') : 'Keep your profile updated to improve recommendations.' ?>
                    </p>

                    <div class="mt-5 flex flex-col gap-3 sm:flex-row xl:flex-col">
                        <a href="<?= base_url('profile') ?>" class="inline-flex justify-center rounded-full border border-slate-200 bg-slate-50 px-4 py-2 text-sm font-semibold text-slate-700 transition hover:border-slate-300 hover:bg-slate-100">View Profile</a>
                        <a href="<?= base_url('profile/edit') ?>" class="inline-flex justify-center rounded-full border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-700 transition hover:border-slate-300 hover:bg-slate-50">Edit Profile</a>
                    </div>
                </div>
            </div>

            <div class="rounded-[2rem] border border-slate-200 bg-white/90 p-6 shadow-soft backdrop-blur-sm">
                <div class="flex items-center justify-between gap-3">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-500">Suggested</p>
                        <h4 class="mt-2 text-xl font-bold tracking-tight text-slate-900">For you</h4>
                    </div>
                    <a href="<?= base_url('job/recommendations') ?>" class="text-sm font-semibold text-slate-700 hover:text-slate-900">Open</a>
                </div>

                <?php if ($recommendationMessage !== ''): ?>
                    <p class="mt-3 text-sm leading-7 text-slate-600">
                        <?= htmlspecialchars($recommendationMessage, ENT_QUOTES, 'UTF-8') ?>
                    </p>
                <?php endif; ?>

                <div class="mt-5 space-y-3">
                    <?php if (empty($recommendedJobs)): ?>
                        <div class="rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-700">
                            No recommendations yet. Add a resume or apply to more jobs to improve matching.
                        </div>
                    <?php else: ?>
                        <?php foreach ($recommendedJobs as $job): ?>
                            <a href="<?= base_url('job/view/' . $job['id']) ?>" class="block rounded-2xl border border-slate-200 bg-slate-50 px-4 py-4 transition hover:border-slate-300 hover:bg-white">
                                <div class="flex items-start justify-between gap-3">
                                    <div>
                                        <h5 class="font-semibold text-slate-900"><?= htmlspecialchars((string)$job['title'], ENT_QUOTES, 'UTF-8') ?></h5>
                                        <p class="mt-1 text-sm text-slate-500">
                                            <?= htmlspecialchars((string)$job['employer_display_name'], ENT_QUOTES, 'UTF-8') ?>
                                        </p>
                                    </div>
                                    <span class="rounded-full bg-white px-2.5 py-1 text-xs font-semibold text-slate-700 ring-1 ring-slate-200">
                                        <?= number_format(((float)($job['recommendation_score'] ?? 0)) * 100, 1) ?>%
                                    </span>
                                </div>
                                <p class="mt-3 text-sm text-slate-600">
                                    <?= htmlspecialchars((string)$job['location'], ENT_QUOTES, 'UTF-8') ?>
                                    <span class="mx-2 text-slate-300">&middot;</span>
                                    <?= htmlspecialchars((string)$job['salary'], ENT_QUOTES, 'UTF-8') ?>
                                </p>
                            </a>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </aside>
    </div>
</section>

<?php require_once BASE_PATH . '/app/views/layout/footer.php'; ?>
