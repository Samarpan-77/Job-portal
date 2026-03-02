<?php require_once BASE_PATH . '/app/views/layout/header.php'; ?>

<h3>My Resumes</h3>

<a href="<?= base_url('resume/create') ?>" class="btn btn-primary btn-sm mb-3">Create Resume</a>

<table class="table table-bordered">
    <tr>
        <th>ID</th>
        <th>Name</th>
        <th>Headline</th>
        <th>Date</th>
        <th>Action</th>
    </tr>

    <?php foreach ($resumes as $resume): ?>
        <?php $content = $resume['parsed_content'] ?? []; ?>
        <tr>
            <td><?= $resume['id'] ?></td>
            <td><?= htmlspecialchars($content['full_name'] ?? '-', ENT_QUOTES, 'UTF-8') ?></td>
            <td><?= htmlspecialchars($content['headline'] ?? '-', ENT_QUOTES, 'UTF-8') ?></td>
            <td><?= $resume['created_at'] ?></td>
            <td>
                <a href="<?= base_url('resume/view/' . $resume['id']) ?>"
                    class="btn btn-outline-primary btn-sm">View</a>
                <a href="<?= base_url('resume/delete/' . $resume['id']) ?>"
                    class="btn btn-danger btn-sm">Delete</a>
            </td>
        </tr>
    <?php endforeach; ?>

</table>

<?php require_once BASE_PATH . '/app/views/layout/footer.php'; ?>
