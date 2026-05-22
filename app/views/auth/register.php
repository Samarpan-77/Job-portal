<?php require_once BASE_PATH . '/app/views/layout/header.php'; ?>

<section class="mx-auto grid max-w-5xl gap-8 lg:grid-cols-[1.15fr_0.85fr]">
    <div class="relative overflow-hidden rounded-[2rem] border border-slate-200 bg-slate-950 p-8 text-white shadow-soft lg:p-10">
        <div class="absolute inset-0 bg-[radial-gradient(circle_at_top_right,_rgba(45,212,191,0.2),_transparent_30%),radial-gradient(circle_at_bottom_left,_rgba(14,165,233,0.18),_transparent_28%)]" style="background-color: rgba(8, 164, 248, 0.9);"></div>
        <div class="relative grid gap-8">
            <div>
                <h2 class="text-3xl font-bold tracking-tight sm:text-4xl">Create your account</h2>
                <p class="mt-4 max-w-2xl text-sm leading-7 text-slate-300 sm:text-base">Join as a job seeker or employer and manage hiring with a clean and structured dashboard.</p>
            </div>
            <img class="w-full h-auto rounded-3xl object-contain shadow-xl shadow-sky-950/30" src="<?= base_url('../images/Image2.png') ?>" alt="Hiring and career planning">
        </div>
    </div>

    <div class="rounded-[2rem] border border-slate-200 bg-white/85 p-8 shadow-soft backdrop-blur-sm">
        <h3 class="text-2xl font-bold tracking-tight text-slate-950">Register</h3>

        <?php if (!empty($errorMessage)): ?>
            <div class="mt-4 rounded-2xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-700" role="alert">
                <?= htmlspecialchars($errorMessage, ENT_QUOTES, 'UTF-8') ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="<?= base_url('register') ?>" class="mt-6 space-y-5">
            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">

            <div>
                <label class="mb-2 block text-sm font-semibold text-slate-700">Name</label>
                <input type="text" name="name" class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-slate-900 outline-none transition placeholder:text-slate-400 focus:border-sky-500 focus:bg-white focus:ring-4 focus:ring-sky-100" value="<?= htmlspecialchars((string)($_POST['name'] ?? ''), ENT_QUOTES, 'UTF-8') ?>" required>
            </div>

            <div>
                <label class="mb-2 block text-sm font-semibold text-slate-700">Email</label>
                <input type="email" name="email" class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-slate-900 outline-none transition placeholder:text-slate-400 focus:border-sky-500 focus:bg-white focus:ring-4 focus:ring-sky-100" value="<?= htmlspecialchars((string)($_POST['email'] ?? ''), ENT_QUOTES, 'UTF-8') ?>" required>
            </div>

            <div>
                <label class="mb-2 block text-sm font-semibold text-slate-700">Password</label>
                <div class="relative">
                    <input type="password" id="register-password" name="password" class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 pr-12 text-slate-900 outline-none transition placeholder:text-slate-400 focus:border-sky-500 focus:bg-white focus:ring-4 focus:ring-sky-100" required>
                    <button
                        type="button"
                        class="absolute inset-y-0 right-3 my-auto inline-flex h-9 w-9 items-center justify-center rounded-full border border-slate-200 bg-white text-slate-500 transition hover:border-sky-500 hover:text-sky-600"
                        data-toggle-password="register-password"
                        aria-label="Show password"
                        title="Show password">&#128065;</button>
                </div>
            </div>

            <div>
                <label class="mb-2 block text-sm font-semibold text-slate-700">Account Type</label>
                <select name="role" class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-slate-900 outline-none transition focus:border-sky-500 focus:bg-white focus:ring-4 focus:ring-sky-100">
                    <option value="user" <?= (($_POST['role'] ?? 'user') === 'user') ? 'selected' : '' ?>>Job Seeker</option>
                    <option value="employer" <?= (($_POST['role'] ?? '') === 'employer') ? 'selected' : '' ?>>Employer</option>
                </select>
            </div>

            <?php if (hcaptcha_is_enabled()): ?>
                <div class="overflow-x-auto">
                    <div class="h-captcha" data-sitekey="<?= htmlspecialchars(hcaptcha_site_key(), ENT_QUOTES, 'UTF-8') ?>"></div>
                </div>
            <?php endif; ?>

            <button class="inline-flex w-full items-center justify-center rounded-2xl bg-emerald-600 px-5 py-3 font-semibold text-white transition hover:bg-emerald-500 focus:outline-none focus:ring-4 focus:ring-emerald-200">Register</button>
        </form>
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