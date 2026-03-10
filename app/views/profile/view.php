<?php require_once BASE_PATH . '/app/views/layout/header.php'; ?>

<section class="hero-panel row align-items-center g-4">
    <div class="col-md-8">
        <h3><?= htmlspecialchars((string)$profile['name'], ENT_QUOTES, 'UTF-8') ?></h3>
        <p><?= htmlspecialchars((string)($profile['headline'] ?: 'Professional profile'), ENT_QUOTES, 'UTF-8') ?></p>
    </div>
    <div class="col-md-4">
        <?php if (!empty($profile['profile_image'])): ?>
            <img class="hero-image" src="<?= base_url($profile['profile_image']) ?>" alt="<?= htmlspecialchars((string)$profile['name'], ENT_QUOTES, 'UTF-8') ?>">
        <?php else: ?>
            <div class="profile-avatar profile-avatar-lg">
                <?= htmlspecialchars(strtoupper(substr((string)$profile['name'], 0, 1)), ENT_QUOTES, 'UTF-8') ?>
            </div>
        <?php endif; ?>
    </div>
</section>

<div class="glass-card auth-form-card">
    <p><strong>Location:</strong> <?= htmlspecialchars((string)($profile['location'] ?: 'Not provided'), ENT_QUOTES, 'UTF-8') ?></p>
    <p><strong>Website:</strong> <?= !empty($profile['website']) ? '<a href="' . htmlspecialchars((string)$profile['website'], ENT_QUOTES, 'UTF-8') . '" target="_blank" rel="noopener noreferrer">' . htmlspecialchars((string)$profile['website'], ENT_QUOTES, 'UTF-8') . '</a>' : 'Not provided' ?></p>
    <p class="mb-0"><strong>About:</strong> <?= nl2br(htmlspecialchars((string)($profile['bio'] ?: 'No bio added yet.'), ENT_QUOTES, 'UTF-8')) ?></p>
</div>

<?php require_once BASE_PATH . '/app/views/layout/footer.php'; ?>
