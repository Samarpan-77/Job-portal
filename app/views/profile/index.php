<?php require_once BASE_PATH . '/app/views/layout/header.php'; ?>

<?php if (!empty($_SESSION['flash_success'])): ?>
    <div class="alert alert-success" role="alert">
        <?= htmlspecialchars($_SESSION['flash_success'], ENT_QUOTES, 'UTF-8') ?>
    </div>
    <?php unset($_SESSION['flash_success']); ?>
<?php endif; ?>

<?php if (!empty($_SESSION['flash_error'])): ?>
    <div class="alert alert-danger" role="alert">
        <?= htmlspecialchars($_SESSION['flash_error'], ENT_QUOTES, 'UTF-8') ?>
    </div>
    <?php unset($_SESSION['flash_error']); ?>
<?php endif; ?>

<?php $isEmployer = (($profile['role'] ?? '') === 'employer'); ?>
<section class="hero-panel row align-items-center g-4">
    <div class="col-md-8">
        <h3><?= $isEmployer ? htmlspecialchars((string)($profile['company_name'] ?: $profile['name']), ENT_QUOTES, 'UTF-8') : htmlspecialchars((string)$profile['name'], ENT_QUOTES, 'UTF-8') ?></h3>
        <p>
            <?= htmlspecialchars((string)($isEmployer ? ($profile['company_description'] ?: 'Build your public company presence for candidates.') : ($profile['headline'] ?: 'Build your public professional profile.')), ENT_QUOTES, 'UTF-8') ?>
        </p>
    </div>
    <div class="col-md-4">
        <?php $image = $isEmployer ? ($profile['company_logo'] ?? '') : ($profile['profile_image'] ?? ''); ?>
        <?php if ($image !== ''): ?>
            <img class="hero-image" src="<?= base_url($image) ?>" alt="Profile image">
        <?php else: ?>
            <div class="profile-avatar profile-avatar-lg">
                <?= htmlspecialchars(strtoupper(substr((string)($isEmployer ? ($profile['company_name'] ?: $profile['name']) : $profile['name']), 0, 1)), ENT_QUOTES, 'UTF-8') ?>
            </div>
        <?php endif; ?>
    </div>
</section>

<div class="glass-card auth-form-card mb-3">
    <?php if ($isEmployer): ?>
        <p><strong>Company Name:</strong> <?= htmlspecialchars((string)($profile['company_name'] ?: $profile['name']), ENT_QUOTES, 'UTF-8') ?></p>
        <p><strong>Location:</strong> <?= htmlspecialchars((string)($profile['company_location'] ?: 'Not set'), ENT_QUOTES, 'UTF-8') ?></p>
        <p><strong>Website:</strong> <?= !empty($profile['company_website']) ? '<a href="' . htmlspecialchars((string)$profile['company_website'], ENT_QUOTES, 'UTF-8') . '" target="_blank" rel="noopener noreferrer">' . htmlspecialchars((string)$profile['company_website'], ENT_QUOTES, 'UTF-8') . '</a>' : 'Not set' ?></p>
        <p><strong>Contact:</strong> <?= htmlspecialchars((string)$profile['email'], ENT_QUOTES, 'UTF-8') ?></p>
    <?php else: ?>
        <p><strong>Name:</strong> <?= htmlspecialchars((string)$profile['name'], ENT_QUOTES, 'UTF-8') ?></p>
        <p><strong>Headline:</strong> <?= htmlspecialchars((string)($profile['headline'] ?: 'Not set'), ENT_QUOTES, 'UTF-8') ?></p>
        <p><strong>Location:</strong> <?= htmlspecialchars((string)($profile['location'] ?: 'Not set'), ENT_QUOTES, 'UTF-8') ?></p>
        <p><strong>Website:</strong> <?= !empty($profile['website']) ? '<a href="' . htmlspecialchars((string)$profile['website'], ENT_QUOTES, 'UTF-8') . '" target="_blank" rel="noopener noreferrer">' . htmlspecialchars((string)$profile['website'], ENT_QUOTES, 'UTF-8') . '</a>' : 'Not set' ?></p>
    <?php endif; ?>

    <p class="mb-0"><strong>About:</strong> <?= nl2br(htmlspecialchars((string)($isEmployer ? ($profile['company_description'] ?: 'No description added yet.') : ($profile['bio'] ?: 'No bio added yet.')), ENT_QUOTES, 'UTF-8')) ?></p>
</div>

<div class="d-flex gap-2">
    <a href="<?= base_url('profile/edit') ?>" class="btn btn-primary btn-sm">Edit Profile</a>
    <?php if ($isEmployer): ?>
        <a href="<?= base_url('profile/company/' . $profile['id']) ?>" class="btn btn-outline-secondary btn-sm">View Public Company Page</a>
    <?php else: ?>
        <a href="<?= base_url('profile/view/' . $profile['id']) ?>" class="btn btn-outline-secondary btn-sm">View Public Profile</a>
    <?php endif; ?>
</div>

<?php require_once BASE_PATH . '/app/views/layout/footer.php'; ?>
