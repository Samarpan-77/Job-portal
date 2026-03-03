<?php require_once BASE_PATH . '/app/views/layout/header.php'; ?>

<section class="d-flex justify-content-between align-items-center mb-3">
    <h3 class="mb-0">Notifications</h3>
    <a href="<?= base_url('notification/readAll') ?>" class="btn btn-outline-primary btn-sm">Mark All as Read</a>
</section>

<?php if (!empty($_SESSION['flash_success'])): ?>
    <div class="alert alert-success" role="alert">
        <?= htmlspecialchars($_SESSION['flash_success'], ENT_QUOTES, 'UTF-8') ?>
    </div>
    <?php unset($_SESSION['flash_success']); ?>
<?php endif; ?>

<?php if (empty($notifications)): ?>
    <div class="alert alert-info">No notifications yet.</div>
<?php else: ?>
    <div class="vstack gap-2">
        <?php foreach ($notifications as $notification): ?>
            <article class="glass-card p-3 <?= ((int)$notification['is_read'] === 0) ? 'border-primary' : '' ?>">
                <div class="d-flex justify-content-between align-items-start gap-3">
                    <div>
                        <h6 class="mb-1"><?= htmlspecialchars($notification['title'], ENT_QUOTES, 'UTF-8') ?></h6>
                        <p class="mb-1 text-muted"><?= htmlspecialchars($notification['message'], ENT_QUOTES, 'UTF-8') ?></p>
                    </div>
                    <small class="text-muted text-nowrap"><?= htmlspecialchars($notification['created_at'], ENT_QUOTES, 'UTF-8') ?></small>
                </div>
            </article>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<?php require_once BASE_PATH . '/app/views/layout/footer.php'; ?>
