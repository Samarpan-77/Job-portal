<?php require_once BASE_PATH . '/app/views/layout/header.php'; ?>

<h3>Manage Users</h3>

<table class="table table-bordered">
    <tr>
        <th>Name</th>
        <th>Email</th>
        <th>Role</th>
        <th>Action</th>
    </tr>
    <?php foreach ($users as $user): ?>
        <tr>
            <td><?= htmlspecialchars($user['name']) ?></td>
            <td><?= htmlspecialchars($user['email']) ?></td>
            <td><?= htmlspecialchars($user['role']) ?></td>
            <td>
                <a href="<?= base_url('admin/deleteUser?id=' . $user['id']) ?>" class="btn btn-danger btn-sm">Delete</a>
            </td>
        </tr>
    <?php endforeach; ?>
</table>

<?php require_once BASE_PATH . '/app/views/layout/footer.php'; ?>
