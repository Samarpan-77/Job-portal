<?php require_once BASE_PATH . '/app/views/layout/header.php'; ?>

<?php $isEmployer = (($profile['role'] ?? '') === 'employer'); ?>

<?php if (!empty($_SESSION['flash_error'])): ?>
    <div class="alert alert-danger" role="alert">
        <?= htmlspecialchars($_SESSION['flash_error'], ENT_QUOTES, 'UTF-8') ?>
    </div>
    <?php unset($_SESSION['flash_error']); ?>
<?php endif; ?>

<section class="hero-panel">
    <h3><?= $isEmployer ? 'Edit Company Profile' : 'Edit Profile' ?></h3>
    <p><?= $isEmployer ? 'Set the company identity candidates will see on job posts and company pages.' : 'Add the profile details that make recommendations and public identity more useful.' ?></p>
</section>

<div class="glass-card auth-form-card">
    <form method="POST" action="<?= base_url('profile/update') ?>" enctype="multipart/form-data">
        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">

        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label">Name</label>
                <input type="text" name="name" class="form-control" value="<?= htmlspecialchars((string)$profile['name'], ENT_QUOTES, 'UTF-8') ?>" required>
            </div>

            <?php if (!$isEmployer): ?>
                <div class="col-md-6">
                    <label class="form-label">Headline</label>
                    <input type="text" name="headline" class="form-control" value="<?= htmlspecialchars((string)($profile['headline'] ?? ''), ENT_QUOTES, 'UTF-8') ?>">
                </div>

                <div class="col-md-6">
                    <label class="form-label">Location</label>
                    <input type="text" name="location" class="form-control" value="<?= htmlspecialchars((string)($profile['location'] ?? ''), ENT_QUOTES, 'UTF-8') ?>">
                </div>

                <div class="col-md-6">
                    <label class="form-label">Website</label>
                    <input type="url" name="website" class="form-control" value="<?= htmlspecialchars((string)($profile['website'] ?? ''), ENT_QUOTES, 'UTF-8') ?>">
                </div>

                <div class="col-12">
                    <label class="form-label">Bio</label>
                    <textarea name="bio" class="form-control" rows="4"><?= htmlspecialchars((string)($profile['bio'] ?? ''), ENT_QUOTES, 'UTF-8') ?></textarea>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Profile Image</label>
                    <input type="file" name="profile_image" class="form-control" accept=".jpg,.jpeg,.png,.webp,.gif,image/*">
                    <?php if (!empty($profile['profile_image'])): ?>
                        <div class="form-check mt-2">
                            <input class="form-check-input" type="checkbox" name="remove_profile_image" value="1" id="remove_profile_image">
                            <label class="form-check-label" for="remove_profile_image">Remove current image</label>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endif; ?>

            <?php if ($isEmployer): ?>
                <div class="col-md-6">
                    <label class="form-label">Company Name</label>
                    <input type="text" name="company_name" class="form-control" value="<?= htmlspecialchars((string)($profile['company_name'] ?? ''), ENT_QUOTES, 'UTF-8') ?>">
                </div>

                <div class="col-md-6">
                    <label class="form-label">Company Location</label>
                    <input type="text" name="company_location" class="form-control" value="<?= htmlspecialchars((string)($profile['company_location'] ?? ''), ENT_QUOTES, 'UTF-8') ?>">
                </div>

                <div class="col-md-6">
                    <label class="form-label">Company Website</label>
                    <input type="url" name="company_website" class="form-control" value="<?= htmlspecialchars((string)($profile['company_website'] ?? ''), ENT_QUOTES, 'UTF-8') ?>">
                </div>

                <div class="col-md-6">
                    <label class="form-label">Company Logo</label>
                    <input type="file" name="company_logo" class="form-control" accept=".jpg,.jpeg,.png,.webp,.gif,image/*">
                    <?php if (!empty($profile['company_logo'])): ?>
                        <div class="form-check mt-2">
                            <input class="form-check-input" type="checkbox" name="remove_company_logo" value="1" id="remove_company_logo">
                            <label class="form-check-label" for="remove_company_logo">Remove current logo</label>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="col-12">
                    <label class="form-label">Company Description</label>
                    <textarea name="company_description" class="form-control" rows="5"><?= htmlspecialchars((string)($profile['company_description'] ?? ''), ENT_QUOTES, 'UTF-8') ?></textarea>
                </div>
            <?php endif; ?>
        </div>

        <div class="d-flex gap-2 mt-3">
            <button class="btn btn-success">Save Changes</button>
            <a href="<?= base_url('profile') ?>" class="btn btn-outline-secondary">Cancel</a>
        </div>
    </form>
</div>

<?php require_once BASE_PATH . '/app/views/layout/footer.php'; ?>
