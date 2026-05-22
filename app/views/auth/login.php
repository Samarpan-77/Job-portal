<?php require_once BASE_PATH . '/app/views/layout/header.php'; ?>

<section class="mx-auto grid max-w-5xl gap-8 lg:grid-cols-[1.15fr_0.85fr]">
    <div class="relative overflow-hidden rounded-[2rem] border border-slate-200 bg-slate-950 p-8 text-white shadow-soft lg:p-10">
        <div class="absolute inset-0 bg-[radial-gradient(circle_at_top_right,_rgba(34,211,238,0.22),_transparent_30%),radial-gradient(circle_at_bottom_left,_rgba(14,165,233,0.18),_transparent_28%)]" style="background-color: rgba(130, 170, 192, 0.55);"></div>
        <div class="relative grid gap-8">
            <div>
                <h2 class="text-3xl font-bold tracking-tight sm:text-4xl">Welcome back to Job Portal</h2>
                <p class="mt-4 max-w-2xl text-sm leading-7 text-slate-300 sm:text-base">Track applications, build your resume, and practice interviews with a focused, modern workflow.</p>
            </div>
            <img class="h-64 w-full rounded-3xl object-cover shadow-xl shadow-sky-950/30" src="<?= base_url('../images/Image1.png') ?>" alt="Career growth workspace">
        </div>
    </div>

    <div class="rounded-[2rem] border border-slate-200 bg-white/85 p-8 shadow-soft backdrop-blur-sm">
        <h3 class="text-2xl font-bold tracking-tight text-slate-950">Login</h3>

        <?php if (!empty($_SESSION['flash_success'])): ?>
            <div class="mt-4 rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800" role="alert">
                <?= htmlspecialchars($_SESSION['flash_success'], ENT_QUOTES, 'UTF-8') ?>
            </div>
            <?php unset($_SESSION['flash_success']); ?>
        <?php endif; ?>

        <?php if (!empty($errorMessage)): ?>
            <div class="mt-4 rounded-2xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-700" role="alert">
                <?= htmlspecialchars($errorMessage, ENT_QUOTES, 'UTF-8') ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="<?= base_url('login') ?>" class="mt-6 space-y-5">
            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">

            <div>
                <label class="mb-2 block text-sm font-semibold text-slate-700">Email</label>
                <input type="email" name="email" class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-slate-900 outline-none transition placeholder:text-slate-400 focus:border-sky-500 focus:bg-white focus:ring-4 focus:ring-sky-100" value="<?= htmlspecialchars((string)($_POST['email'] ?? ''), ENT_QUOTES, 'UTF-8') ?>" required>
            </div>

            <div>
                <label class="mb-2 block text-sm font-semibold text-slate-700">Password</label>
                <div class="relative">
                    <input type="password" id="login-password" name="password" class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 pr-12 text-slate-900 outline-none transition placeholder:text-slate-400 focus:border-sky-500 focus:bg-white focus:ring-4 focus:ring-sky-100" required>
                    <button
                        type="button"
                        class="absolute inset-y-0 right-3 my-auto inline-flex h-9 w-9 items-center justify-center rounded-full border border-slate-200 bg-white text-slate-500 transition hover:border-sky-500 hover:text-sky-600"
                        data-toggle-password="login-password"
                        aria-label="Show password"
                        title="Show password">&#128065;</button>
                </div>
            </div>

            <?php if (hcaptcha_is_enabled()): ?>
                <div class="overflow-x-auto">
                    <div class="h-captcha" data-sitekey="<?= htmlspecialchars(hcaptcha_site_key(), ENT_QUOTES, 'UTF-8') ?>"></div>
                </div>
            <?php endif; ?>

            <button class="inline-flex w-full items-center justify-center rounded-2xl bg-sky-600 px-5 py-3 font-semibold text-white transition hover:bg-sky-500 focus:outline-none focus:ring-4 focus:ring-sky-200">Login</button>
        </form>

        <p class="mt-5 mb-0 text-sm text-slate-600"><a class="font-semibold text-sky-700 hover:text-sky-600" href="<?= base_url('forgot-password') ?>">Forgot password?</a></p>
        <p class="mt-2 mb-0 text-sm text-slate-600">No account? <a class="font-semibold text-sky-700 hover:text-sky-600" href="<?= base_url('register') ?>">Create one</a></p>
    </div>
</section>

<script>
    document.addEventListener('click', function(event) {
        var button = event.target.closest('[data-toggle-password]');
        if (!button) {
            return;
        }

        var inputId = button.getAttribute('data-toggle-password');
        var input = document.getElementById(inputId);
        if (!input) {
            return;
        }

        var showPassword = input.type === 'password';
        input.type = showPassword ? 'text' : 'password';
        button.setAttribute('aria-label', showPassword ? 'Hide password' : 'Show password');
        button.setAttribute('title', showPassword ? 'Hide password' : 'Show password');
    });
</script>

<?php if (hcaptcha_is_enabled()): ?>
    <script src="https://js.hcaptcha.com/1/api.js" async defer></script>
<?php endif; ?>

<?php require_once BASE_PATH . '/app/views/layout/footer.php'; ?>