<?php require_once BASE_PATH . '/app/views/layout/header.php'; ?>

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

<?php require_once BASE_PATH . '/app/views/layout/footer.php'; ?>