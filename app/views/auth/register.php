<?php require_once BASE_PATH . '/app/views/layout/header.php'; ?>

<section class="auth-shell">
    <div class="hero-panel row align-items-center g-4">
        <div class="col-md-7">
            <h2>Create your account</h2>
            <p>Join as a job seeker or employer and manage hiring with a clean and structured dashboard.</p>
        </div>
        <div class="col-md-5">
            <img class="hero-image" src="<?= base_url('../images/Image2.png') ?>" alt="Hiring and career planning">
        </div>
    </div>

    <div class="glass-card auth-form-card">
        <h3 class="mb-3">Register</h3>

        <?php if (!empty($errorMessage)): ?>
            <div class="alert alert-danger" role="alert">
                <?= htmlspecialchars($errorMessage, ENT_QUOTES, 'UTF-8') ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="<?= base_url('register') ?>">
            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">

            <div class="mb-3">
                <label class="form-label">Name</label>
                <input type="text" name="name" class="form-control" value="<?= htmlspecialchars((string)($_POST['name'] ?? ''), ENT_QUOTES, 'UTF-8') ?>" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control" value="<?= htmlspecialchars((string)($_POST['email'] ?? ''), ENT_QUOTES, 'UTF-8') ?>" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Password</label>
                <div class="input-group">
                    <input type="password" id="register-password" name="password" class="form-control" required>
                    <button
                        type="button"
                        class="btn btn-outline-secondary"
                        data-toggle-password="register-password"
                        aria-label="Show password"
                        title="Show password"
                    >&#128065;</button>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">Account Type</label>
                <select name="role" class="form-control">
                    <option value="user" <?= (($_POST['role'] ?? 'user') === 'user') ? 'selected' : '' ?>>Job Seeker</option>
                    <option value="employer" <?= (($_POST['role'] ?? '') === 'employer') ? 'selected' : '' ?>>Employer</option>
                </select>
            </div>

            <?php if (hcaptcha_is_enabled()): ?>
                <div class="mb-3 captcha-wrap">
                    <div class="h-captcha" data-sitekey="<?= htmlspecialchars(hcaptcha_site_key(), ENT_QUOTES, 'UTF-8') ?>"></div>
                </div>
            <?php endif; ?>

            <button class="btn btn-success">Register</button>
        </form>
    </div>
</section>

<script>
document.addEventListener('click', function (event) {
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
