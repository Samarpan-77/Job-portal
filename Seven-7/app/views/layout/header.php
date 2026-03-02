<?php require_once __DIR__ . '/../../config/config.php'; ?>
<!DOCTYPE html>
<html>

<head>
    <title>AI Job Portal</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>

    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="<?= BASE_URL ?>">Rojgar AI</a>

            <ul class="navbar-nav ms-auto">

                <?php if (isset($_SESSION['user_id'])): ?>

                    <li class="nav-item">
                        <a class="nav-link" href="<?= base_url('dashboard') ?>">Dashboard</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="<?= base_url('job') ?>">Jobs</a>
                    </li>

                    <?php if ($_SESSION['role'] === 'user'): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= base_url('interview/start') ?>">AI Interview</a>
                        </li>
                    <?php endif; ?>

                    <?php if ($_SESSION['role'] == 'admin'): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= base_url('admin/users') ?>">Manage Users</a>
                        </li>
                    <?php endif; ?>

                    <li class="nav-item">
                        <a class="nav-link" href="<?= base_url('logout') ?>">Logout</a>
                    </li>

                <?php else: ?>

                    <li class="nav-item">
                        <a class="nav-link" href="<?= base_url('login') ?>">Login</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="<?= base_url('register') ?>">Register</a>
                    </li>

                <?php endif; ?>

            </ul>
        </div>
    </nav>

    <div class="container mt-4">
