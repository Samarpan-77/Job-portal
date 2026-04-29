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
                <th>Image</th>
                <th>Title</th>
                <th>Location</th>
                <th>Salary</th>
                <th>Deadline</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($jobs as $job): ?>
                <tr>
                    <td>
                        <?php if (!empty($job['image_path'])): ?>
                            <img
                                src="<?= base_url($job['image_path']) ?>"
                                alt="<?= htmlspecialchars($job['title'], ENT_QUOTES, 'UTF-8') ?>"
                                class="job-list-image"
                            >
                        <?php else: ?>
                            <span class="text-muted small">No image</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <div><?= htmlspecialchars($job['title']) ?></div>
                        <div class="small text-muted">
                            <a href="<?= base_url('profile/company/' . $job['employer_id']) ?>" class="job-company-link">
                                <?= htmlspecialchars((string)$job['employer_display_name'], ENT_QUOTES, 'UTF-8') ?>
                            </a>
                        </div>
                    </td>
                    <td><?= htmlspecialchars($job['location']) ?></td>
                    <td><?= htmlspecialchars($job['salary']) ?></td>
                    <td>
                        <?php if (!empty($job['application_deadline'])): ?>
                            <?= htmlspecialchars($job['application_deadline'], ENT_QUOTES, 'UTF-8') ?>
                            <?php if ($job['application_deadline'] < date('Y-m-d')): ?>
                                <div class="small text-danger">Closed</div>
                            <?php endif; ?>
                        <?php else: ?>
                            <span class="text-muted small">No deadline</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <a href="<?= base_url('job/view/' . $job['id']) ?>" class="btn btn-sm btn-info">View</a>
                        <?php if (($_SESSION['role'] ?? '') === 'user'): ?>
                            <?php if (!empty($savedJobs[(int)$job['id']])): ?>
                                <form method="POST" action="<?= base_url('job/unsave/' . $job['id']) ?>" class="d-inline-block">
                                    <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                                    <input type="hidden" name="return_to" value="job">
                                    <button class="btn btn-sm btn-outline-secondary">Saved</button>
                                </form>
                            <?php else: ?>
                                <form method="POST" action="<?= base_url('job/save/' . $job['id']) ?>" class="d-inline-block">
                                    <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                                    <input type="hidden" name="return_to" value="job">
                                    <button class="btn btn-sm btn-outline-primary">Save</button>
                                </form>
                            <?php endif; ?>
                        <?php endif; ?>
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
