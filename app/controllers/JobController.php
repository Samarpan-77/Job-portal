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
}
