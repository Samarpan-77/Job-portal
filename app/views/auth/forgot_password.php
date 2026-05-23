<?php require_once BASE_PATH . '/app/views/layout/header.php'; ?>

<section class="mx-auto max-w-2xl">
    <div class="rounded-[2rem] border border-slate-200 bg-white/85 p-8 shadow-soft backdrop-blur-sm">
        <h3 class="text-2xl font-bold tracking-tight text-slate-950">Forgot Password</h3>
        <p class="mt-3 text-sm leading-7 text-slate-600">Enter your account email to generate a secure reset link.</p>

        <?php if (!empty($successMessage)): ?>
            <div class="mt-4 rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800" role="alert">
                <?= htmlspecialchars($successMessage, ENT_QUOTES, 'UTF-8') ?>
            </div>
        <?php endif; ?>

        <?php if (!empty($errorMessage)): ?>
            <div class="mt-4 rounded-2xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-700" role="alert">
                <?= htmlspecialchars($errorMessage, ENT_QUOTES, 'UTF-8') ?>
            </div>
        <?php endif; ?>

        <?php if (!empty($devResetLink)): ?>
            <div class="mt-4 rounded-2xl border border-amber-200 bg-amber-50 px-4 py-3 text-sm text-amber-800" role="alert">
                <strong>Local XAMPP dev link:</strong>
                <a class="font-semibold text-amber-900 underline decoration-amber-400" href="<?= htmlspecialchars($devResetLink, ENT_QUOTES, 'UTF-8') ?>">
                    <?= htmlspecialchars($devResetLink, ENT_QUOTES, 'UTF-8') ?>
                </a>
            </div>
        <?php endif; ?>

        <form method="POST" action="<?= base_url('forgot-password') ?>" class="mt-6 space-y-5">
            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">

            <div>
                <label class="mb-2 block text-sm font-semibold text-slate-700">Email</label>
                <input type="email" name="email" class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-slate-900 outline-none transition focus:border-sky-500 focus:bg-white focus:ring-4 focus:ring-sky-100" required>
            </div>

            <button class="inline-flex w-full items-center justify-center rounded-2xl bg-sky-600 px-5 py-3 font-semibold text-white transition hover:bg-sky-500 focus:outline-none focus:ring-4 focus:ring-sky-200">Generate Reset Link</button>
        </form>

        <p class="mt-5 mb-0 text-sm text-slate-600"><a class="inline-flex items-center rounded-full border border-slate-200 bg-white px-4 py-2 font-semibold text-slate-700 shadow-sm transition hover:border-slate-300 hover:bg-slate-50" href="<?= base_url('login') ?>">Back to login</a></p>
    </div>
</section>

<?php require_once BASE_PATH . '/app/views/layout/footer.php'; ?>
