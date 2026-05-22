<?php require_once BASE_PATH . '/app/views/layout/header.php'; ?>

<section class="mx-auto max-w-2xl">
    <div class="rounded-[2rem] border border-slate-200 bg-white/85 p-8 shadow-soft backdrop-blur-sm">
        <h3 class="text-2xl font-bold tracking-tight text-slate-950">Reset Password</h3>

        <?php if (!empty($errorMessage)): ?>
            <div class="mt-4 rounded-2xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-700" role="alert">
                <?= htmlspecialchars($errorMessage, ENT_QUOTES, 'UTF-8') ?>
            </div>
        <?php endif; ?>

        <?php if (!empty($canShowForm)): ?>
            <form method="POST" action="<?= base_url('reset-password') ?>" class="mt-6 space-y-5">
                <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                <input type="hidden" name="token" value="<?= htmlspecialchars($token, ENT_QUOTES, 'UTF-8') ?>">

                <div>
                    <label class="mb-2 block text-sm font-semibold text-slate-700">New Password</label>
                    <div class="password-field relative">
                        <input type="password" name="password" class="password-input w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 pr-12 text-slate-900 outline-none transition focus:border-sky-500 focus:bg-white focus:ring-4 focus:ring-sky-100" required minlength="8">
                        <button type="button" class="password-toggle absolute right-3 top-1/2 inline-flex h-9 w-9 -translate-y-1/2 items-center justify-center rounded-full border border-slate-200 bg-white text-slate-500 transition hover:border-sky-500 hover:text-sky-600" aria-label="Show password" aria-pressed="false" data-password-toggle>
                            <span aria-hidden="true">Show</span>
                        </button>
                    </div>
                </div>

                <div>
                    <label class="mb-2 block text-sm font-semibold text-slate-700">Confirm New Password</label>
                    <div class="password-field relative">
                        <input type="password" name="confirm_password" class="password-input w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 pr-12 text-slate-900 outline-none transition focus:border-sky-500 focus:bg-white focus:ring-4 focus:ring-sky-100" required minlength="8">
                        <button type="button" class="password-toggle absolute right-3 top-1/2 inline-flex h-9 w-9 -translate-y-1/2 items-center justify-center rounded-full border border-slate-200 bg-white text-slate-500 transition hover:border-sky-500 hover:text-sky-600" aria-label="Show password" aria-pressed="false" data-password-toggle>
                            <span aria-hidden="true">Show</span>
                        </button>
                    </div>
                </div>

                <button class="inline-flex w-full items-center justify-center rounded-2xl bg-emerald-600 px-5 py-3 font-semibold text-white transition hover:bg-emerald-500 focus:outline-none focus:ring-4 focus:ring-emerald-200">Reset Password</button>
            </form>
        <?php endif; ?>

        <p class="mt-5 mb-0 text-sm text-slate-600"><a class="font-semibold text-sky-700 hover:text-sky-600" href="<?= base_url('login') ?>">Back to login</a></p>
    </div>
</section>

<script>
document.querySelectorAll('[data-password-toggle]').forEach(function (button) {
    button.addEventListener('click', function () {
        var input = button.parentElement.querySelector('.password-input');
        var showing = input.type === 'text';

        input.type = showing ? 'password' : 'text';
        button.setAttribute('aria-pressed', showing ? 'false' : 'true');
        button.setAttribute('aria-label', showing ? 'Show password' : 'Hide password');
        button.querySelector('span').textContent = showing ? 'Show' : 'Hide';
    });
});
</script>

<?php require_once BASE_PATH . '/app/views/layout/footer.php'; ?>
