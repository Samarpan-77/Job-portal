<?php require_once __DIR__ . '/../../config/config.php'; ?>
<?php $unreadNotifications = isset($_SESSION['user_id']) ? Notification::unreadCount((int)$_SESSION['user_id']) : 0; ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AI Job Portal</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?= base_url('assets/css/app.css') ?>">
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark app-navbar sticky-top">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center gap-2 fw-semibold" href="<?= BASE_URL ?>">
                <img src="<?= base_url('../images/logo.png') ?>" alt="Rojgar AI" class="brand-logo">
                <span>Rojgar AI</span>
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav" aria-controls="mainNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="mainNav">
            <ul class="navbar-nav ms-auto">

                <?php if (isset($_SESSION['user_id'])): ?>

                    <li class="nav-item">
                        <a class="nav-link" href="<?= base_url('dashboard') ?>">Dashboard</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="<?= base_url('job') ?>">Jobs</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="<?= base_url('notification') ?>">
                            Notifications
                            <?php if ($unreadNotifications > 0): ?>
                                <span class="badge bg-danger ms-1"><?= $unreadNotifications ?></span>
                            <?php endif; ?>
                        </a>
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
        </div>
    </nav>

    <main class="container py-4">
