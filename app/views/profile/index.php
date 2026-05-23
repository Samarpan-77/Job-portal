<?php require_once BASE_PATH . '/app/views/layout/header.php'; ?>

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

<?php $isEmployer = (($profile['role'] ?? '') === 'employer'); ?>
<section class="relative overflow-hidden rounded-[2rem] border border-slate-200 bg-slate-950 p-8 text-white shadow-soft lg:p-10">
    <div class="absolute inset-0 bg-[radial-gradient(circle_at_top_right,_rgba(34,211,238,0.18),_transparent_30%),radial-gradient(circle_at_bottom_left,_rgba(14,165,233,0.16),_transparent_26%)]" style="
    background-color: #1aa3d2;
"></div>
    <div class="relative grid gap-8 lg:grid-cols-[1.2fr_0.8fr] lg:items-center">
        <div>
            <h3 class="text-3xl font-bold tracking-tight"><?= $isEmployer ? htmlspecialchars((string)($profile['company_name'] ?: $profile['name']), ENT_QUOTES, 'UTF-8') : htmlspecialchars((string)$profile['name'], ENT_QUOTES, 'UTF-8') ?></h3>
            <p class="mt-4 max-w-2xl text-sm leading-7 text-slate-300 sm:text-base">
                <?= htmlspecialchars((string)($isEmployer ? ($profile['company_description'] ?: 'Build your public company presence for candidates.') : ($profile['headline'] ?: 'Build your public professional profile.')), ENT_QUOTES, 'UTF-8') ?>
            </p>
        </div>
        <?php $image = $isEmployer ? ($profile['company_logo'] ?? '') : ($profile['profile_image'] ?? ''); ?>
        <?php if ($image !== ''): ?>
            <img class="h-64 w-full rounded-3xl object-cover shadow-xl shadow-sky-950/30" src="<?= base_url($image) ?>" alt="Profile image">
        <?php else: ?>
            <div class="flex h-64 w-full items-center justify-center rounded-3xl bg-white/95 text-5xl font-bold text-sky-700 shadow-xl shadow-sky-950/30">
                <?= htmlspecialchars(strtoupper(substr((string)($isEmployer ? ($profile['company_name'] ?: $profile['name']) : $profile['name']), 0, 1)), ENT_QUOTES, 'UTF-8') ?>
            </div>
        <?php endif; ?>
    </div>
</section>

<div class="mt-6 rounded-[2rem] border border-slate-200 bg-white/85 p-6 shadow-soft backdrop-blur-sm">
    <?php if ($isEmployer): ?>
        <p class="text-slate-700"><strong class="text-slate-950">Company Name:</strong> <?= htmlspecialchars((string)($profile['company_name'] ?: $profile['name']), ENT_QUOTES, 'UTF-8') ?></p>
        <p class="mt-3 text-slate-700"><strong class="text-slate-950">Location:</strong> <?= htmlspecialchars((string)($profile['company_location'] ?: 'Not set'), ENT_QUOTES, 'UTF-8') ?></p>
        <p class="mt-3 text-slate-700"><strong class="text-slate-950">Website:</strong> <?= !empty($profile['company_website']) ? '<a class="font-semibold text-sky-700 hover:text-sky-600" href="' . htmlspecialchars((string)$profile['company_website'], ENT_QUOTES, 'UTF-8') . '" target="_blank" rel="noopener noreferrer">' . htmlspecialchars((string)$profile['company_website'], ENT_QUOTES, 'UTF-8') . '</a>' : 'Not set' ?></p>
        <p class="mt-3 text-slate-700"><strong class="text-slate-950">Contact:</strong> <?= htmlspecialchars((string)$profile['email'], ENT_QUOTES, 'UTF-8') ?></p>
    <?php else: ?>
        <p class="text-slate-700"><strong class="text-slate-950">Name:</strong> <?= htmlspecialchars((string)$profile['name'], ENT_QUOTES, 'UTF-8') ?></p>
        <p class="mt-3 text-slate-700"><strong class="text-slate-950">Headline:</strong> <?= htmlspecialchars((string)($profile['headline'] ?: 'Not set'), ENT_QUOTES, 'UTF-8') ?></p>
        <p class="mt-3 text-slate-700"><strong class="text-slate-950">Location:</strong> <?= htmlspecialchars((string)($profile['location'] ?: 'Not set'), ENT_QUOTES, 'UTF-8') ?></p>
        <p class="mt-3 text-slate-700"><strong class="text-slate-950">Website:</strong> <?= !empty($profile['website']) ? '<a class="font-semibold text-sky-700 hover:text-sky-600" href="' . htmlspecialchars((string)$profile['website'], ENT_QUOTES, 'UTF-8') . '" target="_blank" rel="noopener noreferrer">' . htmlspecialchars((string)$profile['website'], ENT_QUOTES, 'UTF-8') . '</a>' : 'Not set' ?></p>
    <?php endif; ?>

    <p class="mt-4 leading-8 text-slate-700"><strong class="text-slate-950">About:</strong> <?= nl2br(htmlspecialchars((string)($isEmployer ? ($profile['company_description'] ?: 'No description added yet.') : ($profile['bio'] ?: 'No bio added yet.')), ENT_QUOTES, 'UTF-8')) ?></p>
</div>

<div class="mt-6 flex flex-wrap gap-3">
    <a href="<?= base_url('profile/edit') ?>" class="inline-flex rounded-full bg-sky-600 px-4 py-2 text-sm font-semibold text-white transition hover:bg-sky-500">Edit Profile</a>
    <?php if ($isEmployer): ?>
        <a href="<?= base_url('profile/company/' . $profile['id']) ?>" class="inline-flex rounded-full border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-700 transition hover:border-slate-300 hover:bg-slate-50">View Public Company Page</a>
    <?php else: ?>
        <a href="<?= base_url('profile/view/' . $profile['id']) ?>" class="inline-flex rounded-full border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-700 transition hover:border-slate-300 hover:bg-slate-50">View Public Profile</a>
    <?php endif; ?>
    <a href="<?= base_url('logout') ?>" class="inline-flex rounded-full border border-rose-200 bg-rose-50 px-4 py-2 text-sm font-semibold text-rose-700 transition hover:border-rose-300 hover:bg-rose-100">Log Out</a>
</div>

<?php require_once BASE_PATH . '/app/views/layout/footer.php'; ?>
