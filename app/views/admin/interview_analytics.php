<?php require_once BASE_PATH . '/app/views/layout/header.php'; ?>

<section class="mb-6">
    <h3 class="text-3xl font-bold tracking-tight text-slate-950">Interview Analytics</h3>
</section>

<div class="grid gap-4 md:grid-cols-2">
    <div class="rounded-[2rem] border border-slate-200 bg-white/85 p-6 shadow-soft backdrop-blur-sm">
        <h5 class="text-base font-semibold text-slate-600">Average Score</h5>
        <p class="mt-4 text-4xl font-bold tracking-tight text-slate-950"><?= round((float)$avgScore, 2) ?></p>
    </div>
    <div class="rounded-[2rem] border border-slate-200 bg-white/85 p-6 shadow-soft backdrop-blur-sm">
        <h5 class="text-base font-semibold text-slate-600">Total Sessions</h5>
        <p class="mt-4 text-4xl font-bold tracking-tight text-slate-950"><?= (int)$totalSessions ?></p>
    </div>
</div>

<?php require_once BASE_PATH . '/app/views/layout/footer.php'; ?>
