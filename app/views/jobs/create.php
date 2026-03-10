<?php require_once BASE_PATH . '/app/views/layout/header.php'; ?>

<h3>Create Job</h3>

<?php if (!empty($_SESSION['flash_error'])): ?>
    <div class="alert alert-danger" role="alert">
        <?= htmlspecialchars($_SESSION['flash_error'], ENT_QUOTES, 'UTF-8') ?>
    </div>
    <?php unset($_SESSION['flash_error']); ?>
<?php endif; ?>

<form method="POST" action="<?= base_url('job/store') ?>" enctype="multipart/form-data">
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

    <div class="mb-3">
        <label>Job Image</label>
        <input type="file" name="image" class="form-control" accept=".jpg,.jpeg,.png,.webp,.gif,image/*">
        <div class="form-text">Optional. JPG, PNG, WEBP, or GIF up to 5 MB.</div>
    </div>

    <button class="btn btn-success">Post Job</button>
</form>

<a href="<?= base_url('job') ?>" class="btn btn-link mt-2">Back to jobs</a>

<?php require_once BASE_PATH . '/app/views/layout/footer.php'; ?>
