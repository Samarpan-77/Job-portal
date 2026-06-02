<?php require_once BASE_PATH . '/app/views/layout/header.php'; ?>

<section class="relative overflow-hidden rounded-[2rem] border border-slate-200 bg-white p-8 text-slate-900 shadow-soft lg:p-10">
    <div class="absolute inset-0 bg-[radial-gradient(circle_at_top_right,_rgba(148,163,184,0.18),_transparent_30%),radial-gradient(circle_at_bottom_left,_rgba(226,232,240,0.7),_transparent_26%)]"></div>
    <div class="relative grid gap-8 lg:grid-cols-[1.2fr_0.8fr] lg:items-center">
        <div>
            <h3 class="text-3xl font-bold tracking-tight"><?= htmlspecialchars((string)($company['company_name'] ?: $company['name']), ENT_QUOTES, 'UTF-8') ?></h3>
            <p class="mt-4 max-w-2xl text-sm leading-7 text-slate-600 sm:text-base"><?= htmlspecialchars((string)($company['company_description'] ?: 'Company profile'), ENT_QUOTES, 'UTF-8') ?></p>
        </div>
        <?php if (!empty($company['company_logo'])): ?>
            <img class="h-64 w-full rounded-3xl object-cover shadow-xl shadow-slate-100" src="<?= base_url($company['company_logo']) ?>" alt="<?= htmlspecialchars((string)($company['company_name'] ?: $company['name']), ENT_QUOTES, 'UTF-8') ?>">
        <?php else: ?>
            <div class="flex h-64 w-full items-center justify-center rounded-3xl bg-white/95 text-5xl font-bold text-slate-700 shadow-xl shadow-slate-100">
                <?= htmlspecialchars(strtoupper(substr((string)($company['company_name'] ?: $company['name']), 0, 1)), ENT_QUOTES, 'UTF-8') ?>
            </div>
        <?php endif; ?>
    </div>
</section>

<div class="mt-6 rounded-[2rem] border border-slate-200 bg-white/90 p-6 shadow-soft backdrop-blur-sm">
    <p class="text-slate-700"><strong class="text-slate-900">Location:</strong> <?= htmlspecialchars((string)($company['company_location'] ?: 'Not provided'), ENT_QUOTES, 'UTF-8') ?></p>
    <p class="mt-3 text-slate-700"><strong class="text-slate-900">Website:</strong> <?= !empty($company['company_website']) ? '<a class="font-semibold text-slate-700 hover:text-slate-900" href="' . htmlspecialchars((string)$company['company_website'], ENT_QUOTES, 'UTF-8') . '" target="_blank" rel="noopener noreferrer">' . htmlspecialchars((string)$company['company_website'], ENT_QUOTES, 'UTF-8') . '</a>' : 'Not provided' ?></p>
    <p class="mt-3 text-slate-700"><strong class="text-slate-900">Contact:</strong> <?= htmlspecialchars((string)$company['email'], ENT_QUOTES, 'UTF-8') ?></p>
    <p class="mt-4 leading-8 text-slate-700"><strong class="text-slate-900">About:</strong> <?= nl2br(htmlspecialchars((string)($company['company_description'] ?: 'No company description added yet.'), ENT_QUOTES, 'UTF-8')) ?></p>
</div>

<div class="mt-6 rounded-[2rem] border border-slate-200 bg-white/90 p-6 shadow-soft backdrop-blur-sm">
    <h4 class="text-xl font-bold tracking-tight text-slate-900">Open Roles</h4>
    <?php if (empty($jobs)): ?>
        <div class="mt-4 rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-700">No open jobs from this company right now.</div>
    <?php else: ?>
        <div class="mt-4 overflow-hidden rounded-[2rem] border border-slate-200">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200 text-left text-sm">
                    <thead class="bg-slate-100 text-slate-700">
                        <tr>
                            <th class="px-6 py-4 font-semibold">Title</th>
                            <th class="px-6 py-4 font-semibold">Location</th>
                            <th class="px-6 py-4 font-semibold">Salary</th>
                            <th class="px-6 py-4 font-semibold">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 bg-white/70">
                        <?php foreach ($jobs as $job): ?>
                            <tr class="align-top">
                                <td class="px-6 py-5 font-semibold text-slate-900"><?= htmlspecialchars((string)$job['title'], ENT_QUOTES, 'UTF-8') ?></td>
                                <td class="px-6 py-5 text-slate-700"><?= htmlspecialchars((string)$job['location'], ENT_QUOTES, 'UTF-8') ?></td>
                                <td class="px-6 py-5 text-slate-700"><?= htmlspecialchars((string)$job['salary'], ENT_QUOTES, 'UTF-8') ?></td>
                                <td class="px-6 py-5">
                                    <a href="<?= base_url('job/view/' . $job['id']) ?>" class="inline-flex rounded-full bg-slate-900 px-4 py-2 text-xs font-semibold text-white transition hover:bg-slate-700">View</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    <?php endif; ?>
</div>

<?php require_once BASE_PATH . '/app/views/layout/footer.php'; ?>
