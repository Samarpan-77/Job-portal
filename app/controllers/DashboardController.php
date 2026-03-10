<?php

class DashboardController
{
    private $db;

    public function __construct()
    {
        $this->db = Database::connect();
    }

    public function index()
    {
        if (!isset($_SESSION['user_id'])) {
            redirect_to('auth/login');
        }

        $role = $_SESSION['role'] ?? 'user';
        $userId = (int)$_SESSION['user_id'];

        if ($role === 'admin') {
            $user_count = (int)$this->db->query("SELECT COUNT(*) FROM users")->fetchColumn();
            $job_count = (int)$this->db->query("SELECT COUNT(*) FROM jobs")->fetchColumn();
            $application_count = (int)$this->db->query("SELECT COUNT(*) FROM applications")->fetchColumn();
            $interview_count = (int)$this->db->query("SELECT COUNT(*) FROM interview_sessions")->fetchColumn();
            require BASE_PATH . '/app/views/dashboard/admin_dashboard.php';
            return;
        }

        if ($role === 'employer') {
            $stmtJobs = $this->db->prepare("SELECT COUNT(*) FROM jobs WHERE employer_id = ?");
            $stmtJobs->execute([$userId]);
            $job_count = (int)$stmtJobs->fetchColumn();

            $stmtApps = $this->db->prepare("
                SELECT COUNT(*)
                FROM applications a
                JOIN jobs j ON a.job_id = j.id
                WHERE j.employer_id = ?
            ");
            $stmtApps->execute([$userId]);
            $application_count = (int)$stmtApps->fetchColumn();

            $profile = User::getPublicProfileById($userId);

            require BASE_PATH . '/app/views/dashboard/employer_dashboard.php';
            return;
        }

        $stmtUserApps = $this->db->prepare("SELECT COUNT(*) FROM applications WHERE user_id = ?");
        $stmtUserApps->execute([$userId]);
        $application_count = (int)$stmtUserApps->fetchColumn();

        $stmtResumes = $this->db->prepare("SELECT COUNT(*) FROM resumes WHERE user_id = ?");
        $stmtResumes->execute([$userId]);
        $resume_count = (int)$stmtResumes->fetchColumn();

        $stmtInterviews = $this->db->prepare("SELECT COUNT(*) FROM interview_sessions WHERE user_id = ?");
        $stmtInterviews->execute([$userId]);
        $interview_count = (int)$stmtInterviews->fetchColumn();

        $saved_job_count = SavedJob::countByUser($userId);
        $profile = User::getPublicProfileById($userId);

        require BASE_PATH . '/app/views/dashboard/user_dashboard.php';
    }
}
