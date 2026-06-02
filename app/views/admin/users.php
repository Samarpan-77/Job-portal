<?php require_once BASE_PATH . '/app/views/layout/header.php'; ?>

<section class="mb-6">
    <h3 class="text-3xl font-bold tracking-tight text-slate-900">Manage Users</h3>
</section>

<div class="overflow-hidden rounded-[2rem] border border-slate-200 bg-white/90 shadow-soft backdrop-blur-sm">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-slate-200 text-left text-sm">
            <thead class="bg-slate-100 text-slate-700">
                <tr>
                    <th class="px-6 py-4 font-semibold">Name</th>
                    <th class="px-6 py-4 font-semibold">Email</th>
                    <th class="px-6 py-4 font-semibold">Role</th>
                    <th class="px-6 py-4 font-semibold">Action</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 bg-white/70">
                <?php foreach ($users as $user): ?>
                    <tr class="align-top">
                        <td class="px-6 py-5 font-semibold text-slate-900"><?= htmlspecialchars($user['name']) ?></td>
                        <td class="px-6 py-5 text-slate-700"><?= htmlspecialchars($user['email']) ?></td>
                        <td class="px-6 py-5 text-slate-700"><?= htmlspecialchars($user['role']) ?></td>
                        <td class="px-6 py-5">
                            <a href="<?= base_url('admin/deleteUser?id=' . $user['id']) ?>" class="inline-flex rounded-full border border-slate-200 bg-slate-50 px-4 py-2 text-xs font-semibold text-slate-700 transition hover:border-slate-300 hover:bg-slate-100">Delete</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once BASE_PATH . '/app/views/layout/footer.php'; ?>
