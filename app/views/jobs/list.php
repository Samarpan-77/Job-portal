<?php require_once BASE_PATH . '/app/views/layout/header.php'; ?>

<section class="hero-panel row align-items-center g-4">
    <div class="col-md-8">
        <h3>Available Jobs</h3>
        <p>Explore current opportunities and open detailed job descriptions to apply with confidence.</p>
    </div>
    <div class="col-md-4">
        <img class="hero-image" src="<?= base_url('../images/Image2.png') ?>" alt="Career opportunities">
    </div>
</section>

<?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'employer'): ?>
    <a href="<?= base_url('job/create') ?>" class="btn btn-primary btn-sm mb-3">Post New Job</a>
<?php endif; ?>

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
                    <td><?= htmlspecialchars($job['title']) ?></td>
                    <td><?= htmlspecialchars($job['location']) ?></td>
                    <td><?= htmlspecialchars($job['salary']) ?></td>
                    <td>
                        <a href="<?= base_url('job/view/' . $job['id']) ?>" class="btn btn-sm btn-info">View</a>
                        <?php
                            $canDelete = isset($_SESSION['user_id'], $_SESSION['role']) &&
                                (
                                    (($_SESSION['role'] ?? '') === 'admin') ||
                                    ((($_SESSION['role'] ?? '') === 'employer') && ((int)$job['employer_id'] === (int)$_SESSION['user_id']))
                                );
                        ?>
                        <?php if ($canDelete): ?>
                            <a
                                href="<?= base_url('job/delete/' . $job['id']) ?>"
                                class="btn btn-sm btn-outline-danger"
                                onclick="return confirm('Delete this job post?');"
                            >Delete</a>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php require_once BASE_PATH . '/app/views/layout/footer.php'; ?>
