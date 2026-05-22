<?php require_once BASE_PATH . '/app/views/layout/header.php'; ?>

<section class="relative overflow-hidden rounded-[2rem] border border-slate-200 bg-slate-950 p-8 text-white shadow-soft lg:p-10">
    <div class="absolute inset-0 bg-[radial-gradient(circle_at_top_right,_rgba(34,211,238,0.18),_transparent_30%),radial-gradient(circle_at_bottom_left,_rgba(14,165,233,0.16),_transparent_26%)]" style="
    background-color: #1aa3d2;
"></div>
    <div class="relative grid gap-8 lg:grid-cols-[1.2fr_0.8fr] lg:items-center">
        <div>
            <h3 class="text-3xl font-bold tracking-tight">Admin Dashboard</h3>
            <p class="mt-4 max-w-2xl text-sm leading-7 text-slate-300 sm:text-base">Get high-level visibility into users, jobs, applications, and interview activity.</p>
        </div>
        <img class="h-64 w-full rounded-3xl object-contain bg-white/95 p-6 shadow-xl shadow-sky-950/30" src="<?= base_url('../images/logo.png') ?>" alt="Admin overview">
    </div>
</section>

<div class="mt-8 grid gap-4 md:grid-cols-2 xl:grid-cols-4">
    <div class="rounded-[2rem] border border-slate-200 bg-white/85 p-6 shadow-soft backdrop-blur-sm">
        <h5 class="text-base font-semibold text-slate-600">Total Users</h5>
        <p class="mt-4 text-4xl font-bold tracking-tight text-slate-950"><?= $user_count ?></p>
    </div>

    <div class="rounded-[2rem] border border-slate-200 bg-white/85 p-6 shadow-soft backdrop-blur-sm">
        <h5 class="text-base font-semibold text-slate-600">Total Jobs</h5>
        <p class="mt-4 text-4xl font-bold tracking-tight text-slate-950"><?= $job_count ?></p>
    </div>

    <div class="rounded-[2rem] border border-slate-200 bg-white/85 p-6 shadow-soft backdrop-blur-sm">
        <h5 class="text-base font-semibold text-slate-600">Total Applications</h5>
        <p class="mt-4 text-4xl font-bold tracking-tight text-slate-950"><?= $application_count ?></p>
    </div>

    <div class="rounded-[2rem] border border-slate-200 bg-white/85 p-6 shadow-soft backdrop-blur-sm">
        <h5 class="text-base font-semibold text-slate-600">Interview Sessions</h5>
        <p class="mt-4 text-4xl font-bold tracking-tight text-slate-950"><?= $interview_count ?></p>
    </div>
</div>

<div class="mt-6 flex flex-wrap gap-3">
    <a href="<?= base_url('admin/users') ?>" class="inline-flex rounded-full border border-sky-200 bg-sky-50 px-4 py-2 text-sm font-semibold text-sky-700 transition hover:border-sky-300 hover:bg-sky-100">Manage Users</a>
    <a href="<?= base_url('admin/applications') ?>" class="inline-flex rounded-full border border-sky-200 bg-sky-50 px-4 py-2 text-sm font-semibold text-sky-700 transition hover:border-sky-300 hover:bg-sky-100">All Applications</a>
    <a href="<?= base_url('admin/interviewAnalytics') ?>" class="inline-flex rounded-full border border-sky-200 bg-sky-50 px-4 py-2 text-sm font-semibold text-sky-700 transition hover:border-sky-300 hover:bg-sky-100">Interview Analytics</a>
</div>

<?php require_once BASE_PATH . '/app/views/layout/footer.php'; ?>