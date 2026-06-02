<?php require_once BASE_PATH . '/app/views/layout/header.php'; ?>

<section class="mb-6 flex items-center justify-between gap-4">
    <div>
        <h3 class="text-3xl font-bold tracking-tight text-slate-900">Notifications</h3>
    </div>
    <a href="<?= base_url('notification/readAll') ?>" class="inline-flex rounded-full border border-slate-200 bg-slate-50 px-4 py-2 text-sm font-semibold text-slate-700 transition hover:border-slate-300 hover:bg-slate-100">Mark All as Read</a>
</section>

<?php if (!empty($_SESSION['flash_success'])): ?>
    <div class="mb-4 rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800" role="alert">
        <?= htmlspecialchars($_SESSION['flash_success'], ENT_QUOTES, 'UTF-8') ?>
    </div>
    <?php unset($_SESSION['flash_success']); ?>
<?php endif; ?>

<?php if (empty($notifications)): ?>
    <div class="rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-700">No notifications yet.</div>
<?php else: ?>
    <div class="space-y-3">
        <?php foreach ($notifications as $notification): ?>
            <article class="rounded-[2rem] border <?= ((int)$notification['is_read'] === 0) ? 'border-slate-300 bg-slate-50/90' : 'border-slate-200 bg-white/90' ?> p-5 shadow-soft backdrop-blur-sm">
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <h6 class="text-base font-semibold text-slate-900"><?= htmlspecialchars($notification['title'], ENT_QUOTES, 'UTF-8') ?></h6>
                        <p class="mt-2 text-sm leading-7 text-slate-600"><?= htmlspecialchars($notification['message'], ENT_QUOTES, 'UTF-8') ?></p>
                    </div>
                    <small class="whitespace-nowrap text-xs text-slate-400"><?= htmlspecialchars($notification['created_at'], ENT_QUOTES, 'UTF-8') ?></small>
                </div>
            </article>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<?php require_once BASE_PATH . '/app/views/layout/footer.php'; ?>
