<?php
// database configuration is already included by bootstrap (index.php)

class ApplicationController
{

    private $db;

    public function __construct()
    {
        $this->db = Database::connect();
    }

    public function index()
    {
        if (($_SESSION['role'] ?? '') === 'employer') {
            $this->employerApplications();
            return;
        }
        $this->myApplications();
    }

    // Apply for Job
    public function apply()
    {

        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'user') {
            require BASE_PATH . '/app/views/errors/unauthorized.php';
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            verify_csrf();

            $job_id = (int)($_POST['job_id'] ?? 0);
            $resume_id = (int)($_POST['resume_id'] ?? 0);
            $user_id = $_SESSION['user_id'];

            // Prevent duplicate application
            $check = $this->db->prepare("
                SELECT id FROM applications 
                WHERE job_id=? AND user_id=?
            ");
            $check->execute([$job_id, $user_id]);

            if ($check->rowCount() === 0 && $job_id > 0 && $resume_id > 0) {
                $stmt = $this->db->prepare("
                    INSERT INTO applications (job_id,user_id,resume_id)
                    VALUES (?,?,?)
                ");
                $stmt->execute([$job_id, $user_id, $resume_id]);
            }

            redirect_to('application/myApplications');
        }
    }

    // View User Applications
    public function myApplications()
    {
        if (!isset($_SESSION['user_id']) || ($_SESSION['role'] ?? '') !== 'user') {
            require BASE_PATH . '/app/views/errors/unauthorized.php';
            exit;
        }

        $stmt = $this->db->prepare("
            SELECT applications.*, jobs.title, jobs.location
            FROM applications
            JOIN jobs ON applications.job_id = jobs.id
            WHERE applications.user_id=?
            ORDER BY applied_at DESC
        ");

        $stmt->execute([$_SESSION['user_id']]);
        $applications = $stmt->fetchAll(PDO::FETCH_ASSOC);

        require BASE_PATH . '/app/views/applications/my_applications.php';
    }

    // Employer View Applicants
    public function employerApplications()
    {

        if (!isset($_SESSION['user_id']) || ($_SESSION['role'] ?? '') !== 'employer') {
            require BASE_PATH . '/app/views/errors/unauthorized.php';
            exit;
        }

        $stmt = $this->db->prepare("
            SELECT applications.*, users.name, users.email, jobs.title AS job_title
            FROM applications
            JOIN jobs ON applications.job_id = jobs.id
            JOIN users ON applications.user_id = users.id
            WHERE jobs.employer_id=?
            ORDER BY applications.applied_at DESC
        ");

        $stmt->execute([$_SESSION['user_id']]);
        $applications = $stmt->fetchAll(PDO::FETCH_ASSOC);

        require BASE_PATH . '/app/views/applications/employer_applications.php';
    }

    public function viewResume($applicationId = null)
    {
        if (!isset($_SESSION['user_id']) || ($_SESSION['role'] ?? '') !== 'employer') {
            require BASE_PATH . '/app/views/errors/unauthorized.php';
            exit;
        }

        $applicationId = (int)$applicationId;
        if ($applicationId <= 0) {
            require BASE_PATH . '/app/views/errors/404.php';
            return;
        }

        $stmt = $this->db->prepare("
            SELECT applications.id, applications.resume_id, applications.applied_at, users.name AS applicant_name, jobs.title AS job_title
            FROM applications
            JOIN jobs ON applications.job_id = jobs.id
            JOIN users ON applications.user_id = users.id
            WHERE applications.id = ? AND jobs.employer_id = ?
            LIMIT 1
        ");
        $stmt->execute([$applicationId, (int)$_SESSION['user_id']]);
        $application = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$application) {
            require BASE_PATH . '/app/views/errors/404.php';
            return;
        }

        $resumeStmt = $this->db->prepare("
            SELECT id, content_json, created_at
            FROM resumes
            WHERE id = ?
            LIMIT 1
        ");
        $resumeStmt->execute([(int)$application['resume_id']]);
        $resume = $resumeStmt->fetch(PDO::FETCH_ASSOC);

        if (!$resume) {
            require BASE_PATH . '/app/views/errors/404.php';
            return;
        }

        $resumeData = Resume::decodeContent($resume['content_json'] ?? '');
        $readOnly = true;
        $metaTitle = 'Applicant Resume';
        require BASE_PATH . '/app/views/resumes/view.php';
    }

    // Update Application Status (Employer/Admin)
    public function update($applicationId = null, $status = null)
    {

        if (!isset($_SESSION['user_id']) || (($_SESSION['role'] ?? '') !== 'employer' && ($_SESSION['role'] ?? '') !== 'admin')) {
            require BASE_PATH . '/app/views/errors/unauthorized.php';
            exit;
        }

        $allowed = ['shortlisted', 'rejected', 'pending'];
        $applicationId = (int)$applicationId;
        $status = strtolower((string)$status);

        if ($applicationId > 0 && in_array($status, $allowed, true)) {
            $stmt = $this->db->prepare("UPDATE applications SET status=? WHERE id=?");
            $stmt->execute([$status, $applicationId]);
        }

        redirect_to('application/employerApplications');
    }
}
