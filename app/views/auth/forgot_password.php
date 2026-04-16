<?php require_once BASE_PATH . '/app/views/layout/header.php'; ?>

<section class="auth-shell">
    <div class="glass-card auth-form-card">
        <h3 class="mb-3">Forgot Password</h3>
        <p class="text-muted">Enter your account email to generate a secure reset link.</p>

        <?php if (!empty($successMessage)): ?>
            <div class="alert alert-success" role="alert">
                <?= htmlspecialchars($successMessage, ENT_QUOTES, 'UTF-8') ?>
            </div>
        <?php endif; ?>

        <?php if (!empty($errorMessage)): ?>
            <div class="alert alert-danger" role="alert">
                <?= htmlspecialchars($errorMessage, ENT_QUOTES, 'UTF-8') ?>
            </div>
        <?php endif; ?>

        <?php if (!empty($devResetLink)): ?>
            <div class="alert alert-warning" role="alert">
                <strong>Local XAMPP dev link:</strong>
                <a href="<?= htmlspecialchars($devResetLink, ENT_QUOTES, 'UTF-8') ?>">
                    <?= htmlspecialchars($devResetLink, ENT_QUOTES, 'UTF-8') ?>
                </a>
            </div>
        <?php endif; ?>

        <form method="POST" action="<?= base_url('forgot-password') ?>">
            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">

            <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control" required>
            </div>

            <button class="btn btn-primary">Generate Reset Link</button>
        </form>

        <p class="mt-3 mb-0"><a href="<?= base_url('login') ?>">Back to login</a></p>
    </div>
</section>

<?php require_once BASE_PATH . '/app/views/layout/footer.php'; ?>
