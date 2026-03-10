<?php require_once BASE_PATH . '/app/views/layout/header.php'; ?>

<section class="auth-shell">
    <div class="glass-card auth-form-card">
        <h3 class="mb-3">Reset Password</h3>

        <?php if (!empty($errorMessage)): ?>
            <div class="alert alert-danger" role="alert">
                <?= htmlspecialchars($errorMessage, ENT_QUOTES, 'UTF-8') ?>
            </div>
        <?php endif; ?>

        <?php if (!empty($canShowForm)): ?>
            <form method="POST" action="<?= base_url('reset-password') ?>">
                <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                <input type="hidden" name="token" value="<?= htmlspecialchars($token, ENT_QUOTES, 'UTF-8') ?>">

                <div class="mb-3">
                    <label class="form-label">New Password</label>
                    <div class="password-field">
                        <input type="password" name="password" class="form-control password-input" required minlength="8">
                        <button type="button" class="password-toggle" aria-label="Show password" aria-pressed="false" data-password-toggle>
                            <span aria-hidden="true">👁</span>
                        </button>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Confirm New Password</label>
                    <div class="password-field">
                        <input type="password" name="confirm_password" class="form-control password-input" required minlength="8">
                        <button type="button" class="password-toggle" aria-label="Show password" aria-pressed="false" data-password-toggle>
                            <span aria-hidden="true">👁</span>
                        </button>
                    </div>
                </div>

                <button class="btn btn-success">Reset Password</button>
            </form>
        <?php endif; ?>

        <p class="mt-3 mb-0"><a href="<?= base_url('login') ?>">Back to login</a></p>
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
    });
});
</script>

<?php require_once BASE_PATH . '/app/views/layout/footer.php'; ?>
