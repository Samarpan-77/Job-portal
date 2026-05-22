<?php require_once BASE_PATH . '/app/views/layout/header.php'; ?>

<section class="relative overflow-hidden rounded-[2rem] border border-slate-200 bg-slate-950 p-8 text-white shadow-soft lg:p-10">
    <div class="absolute inset-0 bg-[radial-gradient(circle_at_top_right,_rgba(34,211,238,0.18),_transparent_30%),radial-gradient(circle_at_bottom_left,_rgba(14,165,233,0.16),_transparent_26%)]" style="
    background-color: #1aa3d2;
"></div>
    <div class="relative grid gap-8 lg:grid-cols-[1.2fr_0.8fr] lg:items-center">
        <div>
            <h3 class="text-3xl font-bold tracking-tight"><?= htmlspecialchars((string)$profile['name'], ENT_QUOTES, 'UTF-8') ?></h3>
            <p class="mt-4 max-w-2xl text-sm leading-7 text-slate-300 sm:text-base"><?= htmlspecialchars((string)($profile['headline'] ?: 'Professional profile'), ENT_QUOTES, 'UTF-8') ?></p>
        </div>
        <?php if (!empty($profile['profile_image'])): ?>
            <img class="h-64 w-full rounded-3xl object-cover shadow-xl shadow-sky-950/30" src="<?= base_url($profile['profile_image']) ?>" alt="<?= htmlspecialchars((string)$profile['name'], ENT_QUOTES, 'UTF-8') ?>">
        <?php else: ?>
            <div class="flex h-64 w-full items-center justify-center rounded-3xl bg-white/95 text-5xl font-bold text-sky-700 shadow-xl shadow-sky-950/30">
                <?= htmlspecialchars(strtoupper(substr((string)$profile['name'], 0, 1)), ENT_QUOTES, 'UTF-8') ?>
            </div>
        <?php endif; ?>
    </div>
</section>

<div class="mt-6 rounded-[2rem] border border-slate-200 bg-white/85 p-6 shadow-soft backdrop-blur-sm">
    <p class="text-slate-700"><strong class="text-slate-950">Location:</strong> <?= htmlspecialchars((string)($profile['location'] ?: 'Not provided'), ENT_QUOTES, 'UTF-8') ?></p>
    <p class="mt-3 text-slate-700"><strong class="text-slate-950">Website:</strong> <?= !empty($profile['website']) ? '<a class="font-semibold text-sky-700 hover:text-sky-600" href="' . htmlspecialchars((string)$profile['website'], ENT_QUOTES, 'UTF-8') . '" target="_blank" rel="noopener noreferrer">' . htmlspecialchars((string)$profile['website'], ENT_QUOTES, 'UTF-8') . '</a>' : 'Not provided' ?></p>
    <p class="mt-4 leading-8 text-slate-700"><strong class="text-slate-950">About:</strong> <?= nl2br(htmlspecialchars((string)($profile['bio'] ?: 'No bio added yet.'), ENT_QUOTES, 'UTF-8')) ?></p>
</div>

<?php require_once BASE_PATH . '/app/views/layout/footer.php'; ?>