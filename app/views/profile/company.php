<?php require_once BASE_PATH . '/app/views/layout/header.php'; ?>

<section class="hero-panel row align-items-center g-4">
    <div class="col-md-8">
        <h3><?= htmlspecialchars((string)($company['company_name'] ?: $company['name']), ENT_QUOTES, 'UTF-8') ?></h3>
        <p><?= htmlspecialchars((string)($company['company_description'] ?: 'Company profile'), ENT_QUOTES, 'UTF-8') ?></p>
    </div>
    <div class="col-md-4">
        <?php if (!empty($company['company_logo'])): ?>
            <img class="hero-image" src="<?= base_url($company['company_logo']) ?>" alt="<?= htmlspecialchars((string)($company['company_name'] ?: $company['name']), ENT_QUOTES, 'UTF-8') ?>">
        <?php else: ?>
            <div class="profile-avatar profile-avatar-lg">
                <?= htmlspecialchars(strtoupper(substr((string)($company['company_name'] ?: $company['name']), 0, 1)), ENT_QUOTES, 'UTF-8') ?>
            </div>
        <?php endif; ?>
    </div>
</section>

<div class="glass-card auth-form-card mb-3">
    <p><strong>Location:</strong> <?= htmlspecialchars((string)($company['company_location'] ?: 'Not provided'), ENT_QUOTES, 'UTF-8') ?></p>
    <p><strong>Website:</strong> <?= !empty($company['company_website']) ? '<a href="' . htmlspecialchars((string)$company['company_website'], ENT_QUOTES, 'UTF-8') . '" target="_blank" rel="noopener noreferrer">' . htmlspecialchars((string)$company['company_website'], ENT_QUOTES, 'UTF-8') . '</a>' : 'Not provided' ?></p>
    <p><strong>Contact:</strong> <?= htmlspecialchars((string)$company['email'], ENT_QUOTES, 'UTF-8') ?></p>
    <p class="mb-0"><strong>About:</strong> <?= nl2br(htmlspecialchars((string)($company['company_description'] ?: 'No company description added yet.'), ENT_QUOTES, 'UTF-8')) ?></p>
</div>

<div class="glass-card auth-form-card">
    <h4 class="mb-3">Open Roles</h4>
    <?php if (empty($jobs)): ?>
        <div class="alert alert-info mb-0">No open jobs from this company right now.</div>
    <?php else: ?>
        <div class="table-modern">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Location</th>
                        <th>Salary</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($jobs as $job): ?>
                        <tr>
                            <td><?= htmlspecialchars((string)$job['title'], ENT_QUOTES, 'UTF-8') ?></td>
                            <td><?= htmlspecialchars((string)$job['location'], ENT_QUOTES, 'UTF-8') ?></td>
                            <td><?= htmlspecialchars((string)$job['salary'], ENT_QUOTES, 'UTF-8') ?></td>
                            <td><a href="<?= base_url('job/view/' . $job['id']) ?>" class="btn btn-sm btn-info">View</a></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

<?php require_once BASE_PATH . '/app/views/layout/footer.php'; ?>
