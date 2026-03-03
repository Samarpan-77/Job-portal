<?php
// Job model will be autoloaded when needed

class JobController
{
    public function index()
    {
        $jobs = Job::getAll();
        require BASE_PATH . '/app/views/jobs/list.php';
    }

    public function create()
    {
        RoleMiddleware::requireRole('employer');
        require BASE_PATH . '/app/views/jobs/create.php';
    }

    public function store()
    {
        RoleMiddleware::requireRole('employer');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect_to('job/create');
        }

        verify_csrf();

        Job::create(
            trim((string)($_POST['title'] ?? '')),
            trim((string)($_POST['description'] ?? '')),
            trim((string)($_POST['salary'] ?? '')),
            trim((string)($_POST['location'] ?? '')),
            (int)$_SESSION['user_id']
        );

        redirect_to('job');
    }

    public function view($id = null)
    {
        $jobId = (int)$id;
        if ($jobId <= 0) {
            require BASE_PATH . '/app/views/errors/404.php';
            return;
        }

        $job = Job::findById($jobId);
        if (!$job) {
            require BASE_PATH . '/app/views/errors/404.php';
            return;
        }

        $resumes = [];
        if (isset($_SESSION['user_id']) && ($_SESSION['role'] ?? '') === 'user') {
            $resumes = Resume::getByUser((int)$_SESSION['user_id']);
        }

        require BASE_PATH . '/app/views/jobs/view.php';
    }

    public function delete($id = null)
    {
        if (!isset($_SESSION['user_id'])) {
            require BASE_PATH . '/app/views/errors/unauthorized.php';
            exit;
        }

        $jobId = (int)$id;
        if ($jobId <= 0) {
            $_SESSION['flash_error'] = 'Invalid job.';
            redirect_to('job');
        }

        $job = Job::findById($jobId);
        if (!$job) {
            $_SESSION['flash_error'] = 'Job not found.';
            redirect_to('job');
        }

        $role = $_SESSION['role'] ?? '';
        $userId = (int)$_SESSION['user_id'];
        $isAdmin = $role === 'admin';
        $isOwnerEmployer = ($role === 'employer' && (int)$job['employer_id'] === $userId);

        if (!$isAdmin && !$isOwnerEmployer) {
            require BASE_PATH . '/app/views/errors/unauthorized.php';
            exit;
        }

        $applicantStmt = Database::connect()->prepare("
            SELECT DISTINCT user_id
            FROM applications
            WHERE job_id = ?
        ");
        $applicantStmt->execute([$jobId]);
        $applicants = $applicantStmt->fetchAll(PDO::FETCH_COLUMN);

        Job::delete($jobId);

        foreach ($applicants as $applicantId) {
            Notification::create(
                (int)$applicantId,
                'Job post removed',
                'A job you applied to, "' . trim((string)$job['title']) . '", has been removed.',
                'job',
                $jobId
            );
        }

        $_SESSION['flash_success'] = 'Job deleted successfully.';
        redirect_to('job');
    }
}
