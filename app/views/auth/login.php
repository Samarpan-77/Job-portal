<?php require_once BASE_PATH . '/app/views/layout/header.php'; ?>

<section class="auth-shell">
    <div class="hero-panel row align-items-center g-4">
        <div class="col-md-7">
            <h2>Welcome back to Rojgar AI</h2>
            <p>Track applications, build your resume, and practice interviews with a focused, modern workflow.</p>
        </div>
        <div class="col-md-5">
            <img class="hero-image" src="<?= base_url('../images/Image1.png') ?>" alt="Career growth workspace">
        </div>
    </div>

    <div class="glass-card auth-form-card">
        <h3 class="mb-3">Login</h3>

        <?php if (!empty($_SESSION['flash_success'])): ?>
            <div class="alert alert-success" role="alert">
                <?= htmlspecialchars($_SESSION['flash_success'], ENT_QUOTES, 'UTF-8') ?>
            </div>
            <?php unset($_SESSION['flash_success']); ?>
        <?php endif; ?>

        <?php if (!empty($errorMessage)): ?>
            <div class="alert alert-danger" role="alert">
                <?= htmlspecialchars($errorMessage, ENT_QUOTES, 'UTF-8') ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="<?= base_url('login') ?>">
            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">

            <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Password</label>
                <div class="input-group">
                    <input type="password" id="login-password" name="password" class="form-control" required>
                    <button
                        type="button"
                        class="btn btn-outline-secondary"
                        data-toggle-password="login-password"
                        aria-label="Show password"
                        title="Show password"
                    >&#128065;</button>
                </div>
            </div>

            <button class="btn btn-primary">Login</button>
        </form>

        <p class="mt-3 mb-0"><a href="<?= base_url('forgot-password') ?>">Forgot password?</a></p>
        <p class="mt-3 mb-0">No account? <a href="<?= base_url('register') ?>">Create one</a></p>
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

<?php require_once BASE_PATH . '/app/views/layout/footer.php'; ?>
