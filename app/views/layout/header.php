<?php require_once __DIR__ . '/../../config/config.php'; ?>
<?php $unreadNotifications = isset($_SESSION['user_id']) ? Notification::unreadCount((int)$_SESSION['user_id']) : 0; ?>
<?php $headerSearchQuery = trim((string)($_GET['q'] ?? '')); ?>
<?php
$currentPath = trim($_GET['url'] ?? '', '/');
$firstSegment = $currentPath === '' ? '' : explode('/', $currentPath)[0];
$isDashboard = ($firstSegment === 'dashboard' || ($firstSegment === '' && isset($_SESSION['user_id'])));
$bodyClass = 'page-dashboard';
$isLoggedIn = isset($_SESSION['user_id']);
$role = (string)($_SESSION['role'] ?? '');
$roleLabel = $isLoggedIn ? ucfirst($role ?: 'member') : '';
$savedJobCount = ($isLoggedIn && class_exists('SavedJob')) ? SavedJob::countByUser((int)$_SESSION['user_id']) : 0;
$isJobsActive = str_starts_with($currentPath, 'job');
$isDashboardActive = $firstSegment === 'dashboard' || ($currentPath === '' && $isLoggedIn);
$isResumeActive = $firstSegment === 'resume';
$isInterviewActive = str_starts_with($currentPath, 'interview');
$isRecommendationsActive = str_starts_with($currentPath, 'job/recommendations');
$isSavedActive = str_starts_with($currentPath, 'job/saved');
$isNotificationsActive = str_starts_with($currentPath, 'notification');
$isAdminActive = str_starts_with($currentPath, 'admin');
$isLoginActive = str_starts_with($currentPath, 'login');
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Job Portal</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'ui-sans-serif', 'system-ui']
                    }
                }
            }
        }
    </script>
    <link rel="stylesheet" href="<?= base_url('assets/css/app.css') ?>">
</head>

<body class="<?= htmlspecialchars($bodyClass, ENT_QUOTES, 'UTF-8') ?>">
    <div class="min-h-screen">
        <?php
        $currentUser = $isLoggedIn ? (User::findById((int)$_SESSION['user_id']) ?: []) : [];
        $displayName = trim((string)($currentUser['name'] ?? ''));
        $profileImage = trim((string)($currentUser['profile_image'] ?? ''));
        if ($profileImage === '' && $role === 'employer') {
            $profileImage = trim((string)($currentUser['company_logo'] ?? ''));
        }
        $avatarSource = $displayName !== '' ? $displayName : ($roleLabel !== '' ? $roleLabel : 'U');
        $avatarInitial = strtoupper(substr($avatarSource, 0, 1));
        ?>
        <nav class="sticky top-0 z-50 px-2 pt-2 sm:px-4">
            <div class="mx-auto max-w-[1520px] rounded-[1.5rem] border border-white/10 bg-[#111827]/95 text-white shadow-soft backdrop-blur-xl">
                <div class="flex flex-col gap-4 px-4 py-4 lg:flex-row lg:items-center lg:gap-5 lg:px-6">
                    <a class="flex items-center gap-3 text-lg font-bold tracking-tight text-white" href="<?= $isLoggedIn ? base_url('dashboard') : BASE_URL ?>" aria-label="Go to home">
                        <img src="<?= base_url('../images/logo.png') ?>" alt="Job Portal" class="h-11 w-11 rounded-2xl bg-[#1d2840] p-2 shadow-lg shadow-black/20" style="background-color: white;">
                    </a>

                    <ul class="flex flex-wrap items-center gap-2 text-sm font-semibold text-slate-300 lg:gap-3">
                        <?php if ($isLoggedIn): ?>
                            <li>
                                <a class="inline-flex items-center rounded-2xl px-4 py-2.5 transition hover:bg-white/5 hover:text-white <?= $isDashboardActive ? 'bg-[#0b1220] text-white shadow-inner shadow-black/30' : 'text-slate-300' ?>" href="<?= base_url('dashboard') ?>">Dashboard</a>
                            </li>
                            <?php if ($role === 'user'): ?>
                                <li>
                                    <a class="inline-flex items-center rounded-2xl px-4 py-2.5 transition hover:bg-white/5 hover:text-white <?= $isResumeActive ? 'bg-[#0b1220] text-white shadow-inner shadow-black/30' : 'text-slate-300' ?>" href="<?= base_url('resume') ?>">Resume</a>
                                </li>
                                <li>
                                    <a class="inline-flex items-center rounded-2xl px-4 py-2.5 transition hover:bg-white/5 hover:text-white <?= $isInterviewActive ? 'bg-[#0b1220] text-white shadow-inner shadow-black/30' : 'text-slate-300' ?>" href="<?= base_url('interview/start') ?>">Interviews</a>
                                </li>
                                <li>
                                    <a class="inline-flex items-center rounded-2xl px-4 py-2.5 transition hover:bg-white/5 hover:text-white <?= $isRecommendationsActive ? 'bg-[#0b1220] text-white shadow-inner shadow-black/30' : 'text-slate-300' ?>" href="<?= base_url('job/recommendations') ?>">Jobs</a>
                                </li>
                            <?php endif; ?>
                            <?php if ($role === 'admin'): ?>
                                <li>
                                    <a class="inline-flex items-center rounded-2xl px-4 py-2.5 transition hover:bg-white/5 hover:text-white <?= $isAdminActive ? 'bg-[#0b1220] text-white shadow-inner shadow-black/30' : 'text-slate-300' ?>" href="<?= base_url('admin/users') ?>">Manage Users</a>
                                </li>
                            <?php endif; ?>
                        <?php endif; ?>
                    </ul>

                    <form class="relative w-full flex-1 lg:max-w-[25rem]" action="<?= base_url('job') ?>" method="GET">
                        <label class="sr-only" for="headerSearchInput">Search</label>
                        <svg aria-hidden="true" viewBox="0 0 24 24" class="pointer-events-none absolute left-4 top-1/2 h-5 w-5 -translate-y-1/2 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round">
                            <circle cx="11" cy="11" r="7"></circle>
                            <path d="M20 20l-3.25-3.25"></path>
                        </svg>
                        <input
                            id="headerSearchInput"
                            name="q"
                            type="search"
                            value="<?= htmlspecialchars($headerSearchQuery, ENT_QUOTES, 'UTF-8') ?>"
                            placeholder="Search jobs, locations, or companies"
                            class="w-full rounded-2xl border border-white/10 bg-[#273041] py-3 pl-11 pr-4 text-sm text-slate-100 outline-none transition placeholder:text-slate-400 focus:border-cyan-400/50 focus:ring-2 focus:ring-cyan-400/20">
                    </form>

                    <div class="flex items-center gap-4 lg:ml-1">
                        <?php if ($isLoggedIn): ?>
                            <a
                                href="<?= base_url('job/saved') ?>"
                                class="relative inline-flex h-11 w-11 items-center justify-center rounded-full border border-white/10 bg-white/0 text-slate-300 transition hover:bg-white/5 hover:text-white"
                                aria-label="Saved jobs">
                                <svg aria-hidden="true" viewBox="0 0 24 24" class="h-5 w-5 fill-none stroke-current stroke-2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M6 4h12a1 1 0 0 1 1 1v16l-7-4-7 4V5a1 1 0 0 1 1-1Z"></path>
                                </svg>
                                <?php if ($savedJobCount > 0): ?>
                                    <span class="absolute -right-1 -top-1 inline-flex min-w-5 items-center justify-center rounded-full bg-cyan-500 px-1.5 py-0.5 text-[10px] font-bold leading-none text-slate-950">
                                        <?= $savedJobCount ?>
                                    </span>
                                <?php endif; ?>
                            </a>

                            <a
                                href="<?= base_url('notification') ?>"
                                class="relative inline-flex h-11 w-11 items-center justify-center rounded-full border border-white/10 bg-white/0 text-slate-300 transition hover:bg-white/5 hover:text-white"
                                aria-label="Notifications">
                                <svg aria-hidden="true" viewBox="0 0 24 24" class="h-5 w-5 fill-none stroke-current stroke-2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M15 17H9a4 4 0 0 1-4-4v-2a7 7 0 1 1 14 0v2a4 4 0 0 1-4 4Z"></path>
                                    <path d="M10 19a2 2 0 0 0 4 0"></path>
                                </svg>
                                <?php if ($unreadNotifications > 0): ?>
                                    <span class="absolute -right-1 -top-1 inline-flex min-w-5 items-center justify-center rounded-full bg-rose-500 px-1.5 py-0.5 text-[10px] font-bold leading-none text-white">
                                        <?= $unreadNotifications ?>
                                    </span>
                                <?php endif; ?>
                            </a>

                            <a href="<?= base_url('profile') ?>" class="flex items-center gap-3 rounded-full border border-white/10 bg-white/0 px-2 py-1 text-slate-100 transition hover:bg-white/5">
                                <?php if ($profileImage !== ''): ?>
                                    <img src="<?= base_url($profileImage) ?>" alt="<?= htmlspecialchars($displayName !== '' ? $displayName : 'Profile', ENT_QUOTES, 'UTF-8') ?>" class="h-10 w-10 rounded-full object-cover ring-2 ring-white/10">
                                <?php else: ?>
                                    <span class="flex h-10 w-10 items-center justify-center rounded-full bg-gradient-to-br from-cyan-400 to-sky-500 text-sm font-bold text-slate-950 ring-2 ring-white/10">
                                        <?= htmlspecialchars($avatarInitial, ENT_QUOTES, 'UTF-8') ?>
                                    </span>
                                <?php endif; ?>
                            </a>
                        <?php else: ?>
                            <a class="rounded-full border border-white/10 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-white/10" href="<?= base_url('login') ?>">Login</a>
                            <a class="rounded-full bg-cyan-500 px-4 py-2.5 text-sm font-semibold text-slate-950 transition hover:bg-cyan-400" href="<?= base_url('register') ?>">Register</a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </nav>

        <main class="mx-auto w-full max-w-7xl px-4 py-6 sm:px-6 lg:px-8">