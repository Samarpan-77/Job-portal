<?php require_once BASE_PATH . '/app/views/layout/header.php'; ?>

<section class="mb-6">
    <h3 class="text-3xl font-bold tracking-tight text-slate-950">Applicants</h3>
</section>

<?php if (!empty($_SESSION['flash_success'])): ?>
    <div class="mb-4 rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800" role="alert">
        <?= htmlspecialchars($_SESSION['flash_success'], ENT_QUOTES, 'UTF-8') ?>
    </div>
    <?php unset($_SESSION['flash_success']); ?>
<?php endif; ?>

<?php if (!empty($_SESSION['flash_info'])): ?>
    <div class="mb-4 rounded-2xl border border-sky-200 bg-sky-50 px-4 py-3 text-sm text-sky-800" role="alert">
        <?= htmlspecialchars($_SESSION['flash_info'], ENT_QUOTES, 'UTF-8') ?>
    </div>
    <?php unset($_SESSION['flash_info']); ?>
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
                    <th class="px-6 py-4 font-semibold">Job</th>
                    <th class="px-6 py-4 font-semibold">Name</th>
                    <th class="px-6 py-4 font-semibold">Email</th>
                    <th class="px-6 py-4 font-semibold">Status</th>
                    <th class="px-6 py-4 font-semibold">Action</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 bg-white/70">
                <?php foreach ($applications as $app): ?>
                    <tr class="align-top">
                        <td class="px-6 py-5 font-semibold text-slate-950"><?= htmlspecialchars($app['job_title'], ENT_QUOTES, 'UTF-8') ?></td>
                        <td class="px-6 py-5">
                            <a href="<?= base_url('profile/view/' . $app['user_id']) ?>" class="font-medium text-sky-700 hover:text-sky-600">
                                <?= htmlspecialchars($app['name'], ENT_QUOTES, 'UTF-8') ?>
                            </a>
                        </td>
                        <td class="px-6 py-5 text-slate-700"><?= htmlspecialchars($app['email'], ENT_QUOTES, 'UTF-8') ?></td>
                        <td class="px-6 py-5">
                            <?php
                                $status = strtolower((string)$app['status']);
                                $badgeClass = $status === 'shortlisted' ? 'border-emerald-200 bg-emerald-50 text-emerald-700' : ($status === 'rejected' ? 'border-rose-200 bg-rose-50 text-rose-700' : 'border-amber-200 bg-amber-50 text-amber-700');
                                $label = $status === 'shortlisted' ? 'Shortlisted' : ($status === 'rejected' ? 'Rejected' : 'Pending');
                            ?>
                            <span class="inline-flex rounded-full border px-3 py-1 text-xs font-semibold <?= $badgeClass ?>"><?= htmlspecialchars($label, ENT_QUOTES, 'UTF-8') ?></span>
                        </td>
                        <td class="px-6 py-5">
                            <div class="flex flex-wrap gap-2">
                                <a href="<?= base_url('application/viewResume/' . $app['id']) ?>" class="inline-flex rounded-full border border-sky-200 bg-sky-50 px-4 py-2 text-xs font-semibold text-sky-700 transition hover:border-sky-300 hover:bg-sky-100">View Resume</a>
                                <?php if (strtolower((string)$app['status']) === 'shortlisted'): ?>
                                    <?php
                                        $subject = 'Regarding your application for ' . (string)$app['job_title'];
                                        $body = "Hi " . (string)$app['name'] . ",\n\n";
                                        $body .= "Congratulations! You have been shortlisted for the " . (string)$app['job_title'] . " position.\n\n";
                                        $body .= "Please reply to this email so we can discuss the next steps.\n\n";
                                        $body .= "Best regards,\n";
                                        $body .= (string)($_SESSION['user_name'] ?? 'Hiring Team');
                                    ?>
                                    <a
                                        href="mailto:<?= rawurlencode((string)$app['email']) ?>?subject=<?= rawurlencode($subject) ?>&body=<?= rawurlencode($body) ?>"
                                        class="inline-flex rounded-full border border-amber-200 bg-amber-50 px-4 py-2 text-xs font-semibold text-amber-700 transition hover:border-amber-300 hover:bg-amber-100"
                                    >Contact</a>
                                <?php else: ?>
                                    <a href="<?= base_url('application/update/' . $app['id'] . '/shortlisted') ?>" class="inline-flex rounded-full bg-emerald-600 px-4 py-2 text-xs font-semibold text-white transition hover:bg-emerald-500">Shortlist</a>
                                    <a href="<?= base_url('application/update/' . $app['id'] . '/rejected') ?>" class="inline-flex rounded-full bg-rose-600 px-4 py-2 text-xs font-semibold text-white transition hover:bg-rose-500">Reject</a>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once BASE_PATH . '/app/views/layout/footer.php'; ?>
