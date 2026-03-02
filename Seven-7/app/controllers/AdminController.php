<?php
// configuration loaded by bootstrap

class AdminController
{

    private $db;

    public function __construct()
    {
        $this->db = Database::connect();

        if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
            require BASE_PATH . '/app/views/errors/unauthorized.php';
            exit;
        }
    }

    // Admin Dashboard
    public function dashboard()
    {

        $user_count = (int)$this->db->query("SELECT COUNT(*) FROM users")->fetchColumn();
        $job_count = (int)$this->db->query("SELECT COUNT(*) FROM jobs")->fetchColumn();
        $application_count = (int)$this->db->query("SELECT COUNT(*) FROM applications")->fetchColumn();
        $interview_count = (int)$this->db->query("SELECT COUNT(*) FROM interview_sessions")->fetchColumn();

        require BASE_PATH . '/app/views/dashboard/admin_dashboard.php';
    }

    public function index()
    {
        $this->dashboard();
    }

    // Manage Users
    public function users()
    {
        $stmt = $this->db->query("SELECT id,name,email,role,created_at FROM users ORDER BY created_at DESC");
        $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
        require BASE_PATH . '/app/views/admin/users.php';
    }

    // Delete User
    public function deleteUser()
    {
        $id = $_GET['id'] ?? null;
        if ($id) {
            $stmt = $this->db->prepare("DELETE FROM users WHERE id=?");
            $stmt->execute([$id]);
        }
        redirect_to('admin/users');
    }

    // View All Applications
    public function applications()
    {
        $stmt = $this->db->query("
            SELECT applications.*, users.name AS applicant, jobs.title 
            FROM applications
            JOIN users ON applications.user_id = users.id
            JOIN jobs ON applications.job_id = jobs.id
            ORDER BY applied_at DESC
        ");
        $applications = $stmt->fetchAll(PDO::FETCH_ASSOC);
        require BASE_PATH . '/app/views/admin/applications.php';
    }

    // Interview Analytics
    public function interviewAnalytics()
    {
        $avgScore = $this->db->query("
            SELECT AVG(total_score) FROM interview_sessions WHERE status='completed'
        ")->fetchColumn();

        $totalSessions = $this->db->query("
            SELECT COUNT(*) FROM interview_sessions
        ")->fetchColumn();

        require BASE_PATH . '/app/views/admin/interview_analytics.php';
    }
}
