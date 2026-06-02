<?php require_once BASE_PATH . '/app/views/layout/header.php'; ?>

<section class="mx-auto max-w-3xl rounded-[2rem] border border-slate-200 bg-white/90 p-8 shadow-soft backdrop-blur-sm">
    <h3 class="text-2xl font-bold tracking-tight text-slate-900">Create Job</h3>

    <?php if (!empty($_SESSION['flash_error'])): ?>
        <div class="mt-4 rounded-2xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-700" role="alert">
            <?= htmlspecialchars($_SESSION['flash_error'], ENT_QUOTES, 'UTF-8') ?>
        </div>
        <?php unset($_SESSION['flash_error']); ?>
    <?php endif; ?>

    <form method="POST" action="<?= base_url('job/store') ?>" enctype="multipart/form-data" class="mt-6 space-y-5">
        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">

        <div>
            <label class="mb-2 block text-sm font-semibold text-slate-700">Title</label>
            <input type="text" name="title" class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-slate-900 outline-none transition focus:border-slate-300 focus:bg-white focus:ring-4 focus:ring-slate-100" required>
        </div>

        <div>
            <label class="mb-2 block text-sm font-semibold text-slate-700">Description</label>
            <textarea name="description" class="min-h-40 w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-slate-900 outline-none transition focus:border-slate-300 focus:bg-white focus:ring-4 focus:ring-slate-100" required></textarea>
        </div>

        <div>
            <label class="mb-2 block text-sm font-semibold text-slate-700">Salary</label>
            <input type="text" name="salary" class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-slate-900 outline-none transition focus:border-slate-300 focus:bg-white focus:ring-4 focus:ring-slate-100">
        </div>

        <div>
            <label class="mb-2 block text-sm font-semibold text-slate-700">Location</label>
            <input type="text" name="location" class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-slate-900 outline-none transition focus:border-slate-300 focus:bg-white focus:ring-4 focus:ring-slate-100">
        </div>

        <div>
            <label class="mb-2 block text-sm font-semibold text-slate-700">Application Deadline</label>
            <input type="date" name="application_deadline" class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-slate-900 outline-none transition focus:border-slate-300 focus:bg-white focus:ring-4 focus:ring-slate-100" min="<?= date('Y-m-d') ?>">
            <div class="mt-2 text-sm text-slate-500">Optional. Employees can set the last day candidates are allowed to apply.</div>
        </div>

        <div>
            <label class="mb-2 block text-sm font-semibold text-slate-700">Job Image</label>
            <input type="file" name="image" class="block w-full rounded-2xl border border-dashed border-slate-300 bg-slate-50 px-4 py-3 text-sm text-slate-700 file:mr-4 file:rounded-full file:border-0 file:bg-slate-900 file:px-4 file:py-2 file:text-sm file:font-semibold file:text-white hover:file:bg-slate-700" accept=".jpg,.jpeg,.png,.webp,.gif,image/*">
            <div class="mt-2 text-sm text-slate-500">Optional. JPG, PNG, WEBP, or GIF up to 5 MB.</div>
        </div>

        <button class="inline-flex items-center justify-center rounded-2xl bg-slate-900 px-5 py-3 font-semibold text-white transition hover:bg-slate-700 focus:outline-none focus:ring-4 focus:ring-slate-200">Post Job</button>
    </form>

    <a href="<?= base_url('job') ?>" class="mt-5 inline-flex items-center rounded-full border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-700 shadow-sm transition hover:border-slate-300 hover:bg-slate-50">Back to jobs</a>
</section>

<?php require_once BASE_PATH . '/app/views/layout/footer.php'; ?>
