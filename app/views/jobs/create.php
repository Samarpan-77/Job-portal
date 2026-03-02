<?php require_once BASE_PATH . '/app/views/layout/header.php'; ?>

<h3>Create Job</h3>

<form method="POST" action="<?= base_url('job/store') ?>">
    <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">

    <div class="mb-3">
        <label>Title</label>
        <input type="text" name="title" class="form-control" required>
    </div>

    <div class="mb-3">
        <label>Description</label>
        <textarea name="description" class="form-control" required></textarea>
    </div>

    <div class="mb-3">
        <label>Salary</label>
        <input type="text" name="salary" class="form-control">
    </div>

    <div class="mb-3">
        <label>Location</label>
        <input type="text" name="location" class="form-control">
    </div>

    <button class="btn btn-success">Post Job</button>
</form>

<a href="<?= base_url('job') ?>" class="btn btn-link mt-2">Back to jobs</a>

<?php require_once BASE_PATH . '/app/views/layout/footer.php'; ?>
