<?php require_once BASE_PATH . '/app/views/layout/header.php'; ?>

<section class="hero-panel row align-items-center g-4">
    <div class="col-md-8">
        <h3>Recommended Jobs (K-Means)</h3>
        <p>
            <?= htmlspecialchars($message, ENT_QUOTES, 'UTF-8') ?>
            <?php if ($matchedCluster !== null): ?>
                Cluster <?= (int)$matchedCluster ?> matched with <?= (int)$clusterSize ?> similar job postings.
            <?php endif; ?>
        </p>
    </div>
    <div class="col-md-4">
        <img class="hero-image" src="<?= base_url('../images/Image1.png') ?>" alt="Smart job recommendations">
    </div>
</section>

<div class="d-flex gap-2 mb-3">
    <a href="<?= base_url('job') ?>" class="btn btn-outline-primary btn-sm">All Jobs</a>
    <a href="<?= base_url('application/myApplications') ?>" class="btn btn-outline-secondary btn-sm">My Applications</a>
    <a href="<?= base_url('job/saved') ?>" class="btn btn-outline-dark btn-sm">Saved Jobs</a>
</div>

<?php if (!empty($_SESSION['flash_success'])): ?>
    <div class="alert alert-success" role="alert">
        <?= htmlspecialchars($_SESSION['flash_success'], ENT_QUOTES, 'UTF-8') ?>
    </div>
    <?php unset($_SESSION['flash_success']); ?>
<?php endif; ?>

<?php if (empty($recommendedJobs)): ?>
    <div class="alert alert-info">No recommendation available yet. Add a resume or apply to more jobs to improve matching.</div>
<?php else: ?>
    <div class="table-modern">
        <table class="table table-hover mb-0">
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Company</th>
                    <th>Location</th>
                    <th>Salary</th>
                    <th>Match Score</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($recommendedJobs as $job): ?>
                    <tr>
                        <td><?= htmlspecialchars($job['title'], ENT_QUOTES, 'UTF-8') ?></td>
                        <td>
                            <a href="<?= base_url('profile/company/' . $job['employer_id']) ?>" class="job-company-link">
                                <?= htmlspecialchars((string)$job['employer_display_name'], ENT_QUOTES, 'UTF-8') ?>
                            </a>
                        </td>
                        <td><?= htmlspecialchars($job['location'], ENT_QUOTES, 'UTF-8') ?></td>
                        <td><?= htmlspecialchars($job['salary'], ENT_QUOTES, 'UTF-8') ?></td>
                        <td><?= number_format(((float)$job['recommendation_score']) * 100, 1) ?>%</td>
                        <td>
                            <a href="<?= base_url('job/view/' . $job['id']) ?>" class="btn btn-sm btn-info">View</a>
                            <?php if (!empty($savedJobs[(int)$job['id']])): ?>
                                <form method="POST" action="<?= base_url('job/unsave/' . $job['id']) ?>" class="d-inline-block">
                                    <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                                    <input type="hidden" name="return_to" value="job/recommendations">
                                    <button class="btn btn-sm btn-outline-secondary">Saved</button>
                                </form>
                            <?php else: ?>
                                <form method="POST" action="<?= base_url('job/save/' . $job['id']) ?>" class="d-inline-block">
                                    <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                                    <input type="hidden" name="return_to" value="job/recommendations">
                                    <button class="btn btn-sm btn-outline-primary">Save</button>
                                </form>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php endif; ?>

<?php require_once BASE_PATH . '/app/views/layout/footer.php'; ?>
