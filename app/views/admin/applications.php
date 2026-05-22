<?php require_once BASE_PATH . '/app/views/layout/header.php'; ?>

<section class="mb-6">
    <h3 class="text-3xl font-bold tracking-tight text-slate-950">All Applications</h3>
</section>

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

<div class="overflow-hidden rounded-[2rem] border border-slate-200 bg-white/85 shadow-soft backdrop-blur-sm">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-slate-200 text-left text-sm">
            <thead class="bg-slate-950 text-slate-100">
                <tr>
                    <th class="px-6 py-4 font-semibold">Applicant</th>
                    <th class="px-6 py-4 font-semibold">Job</th>
                    <th class="px-6 py-4 font-semibold">Status</th>
                    <th class="px-6 py-4 font-semibold">Applied At</th>
                    <th class="px-6 py-4 font-semibold">Action</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 bg-white/70">
                <?php foreach ($applications as $app): ?>
                    <tr class="align-top">
                        <td class="px-6 py-5 font-semibold text-slate-950"><?= htmlspecialchars($app['applicant']) ?></td>
                        <td class="px-6 py-5 text-slate-700"><?= htmlspecialchars($app['title']) ?></td>
                        <td class="px-6 py-5 text-slate-700"><?= htmlspecialchars($app['status']) ?></td>
                        <td class="px-6 py-5 text-slate-700"><?= htmlspecialchars($app['applied_at']) ?></td>
                        <td class="px-6 py-5">
                            <a
                                href="<?= base_url('application/delete/' . $app['id']) ?>"
                                class="inline-flex rounded-full border border-rose-200 bg-rose-50 px-4 py-2 text-xs font-semibold text-rose-700 transition hover:border-rose-300 hover:bg-rose-100"
                                onclick="return confirm('Delete this application?');"
                            >Delete</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once BASE_PATH . '/app/views/layout/footer.php'; ?>
