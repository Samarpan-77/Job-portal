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

            $jobStmt = $this->db->prepare("
                SELECT id, title, employer_id, application_deadline
                FROM jobs
                WHERE id = ?
                LIMIT 1
            ");
            $jobStmt->execute([$job_id]);
            $job = $jobStmt->fetch(PDO::FETCH_ASSOC);

            if (!$job) {
                $_SESSION['flash_error'] = 'Job not found.';
                redirect_to('job');
            }

            $deadline = trim((string)($job['application_deadline'] ?? ''));
            if ($deadline !== '' && $deadline < date('Y-m-d')) {
                $_SESSION['flash_error'] = 'This vacancy is no longer accepting applications.';
                redirect_to('job/view/' . $job_id);
            }

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

                $applicationId = (int)$this->db->lastInsertId();
                if ($job) {
                    $title = trim((string)$job['title']);
                    Notification::create(
                        (int)$job['employer_id'],
                        'New application received',
                        'A candidate applied for "' . $title . '". Review the application and update the status.',
                        'application',
                        $applicationId
                    );
                }

                $_SESSION['flash_success'] = 'Application submitted successfully. You will be notified when the status changes.';
            } else {
                $_SESSION['flash_info'] = 'You have already applied to this job.';
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
            SELECT id, user_id, content_json, template_id, created_at
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
        $resumeId = (int)$resume['id'];
        $resumeOwnerId = (int)$resume['user_id'];
        $templateId = trim((string)($resume['template_id'] ?? 'classic'));
        $templates = ResumeTemplateService::getAvailableTemplates();
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
            $detailStmt = $this->db->prepare("
                SELECT a.id, a.user_id, a.status AS current_status, j.title, j.employer_id
                FROM applications a
                JOIN jobs j ON a.job_id = j.id
                WHERE a.id = ?
                LIMIT 1
            ");
            $detailStmt->execute([$applicationId]);
            $application = $detailStmt->fetch(PDO::FETCH_ASSOC);

            if (!$application) {
                $_SESSION['flash_error'] = 'Application not found.';
                redirect_to('application/employerApplications');
            }

            $isAdmin = (($_SESSION['role'] ?? '') === 'admin');
            $isOwnerEmployer = ((int)$application['employer_id'] === (int)$_SESSION['user_id']);

            if (!$isAdmin && !$isOwnerEmployer) {
                require BASE_PATH . '/app/views/errors/unauthorized.php';
                exit;
            }

            $currentStatus = strtolower((string)$application['current_status']);
            $jobTitle = trim((string)$application['title']);
            $statusLabel = ucfirst($status);

            if ($currentStatus !== $status) {
                $stmt = $this->db->prepare("UPDATE applications SET status=? WHERE id=?");
                $stmt->execute([$status, $applicationId]);

                Notification::create(
                    (int)$application['user_id'],
                    'Application status updated',
                    'Your application for "' . $jobTitle . '" is now marked as ' . $statusLabel . '.',
                    'application',
                    $applicationId
                );

                $_SESSION['flash_success'] = 'Application status updated to ' . $statusLabel . '. Candidate has been notified.';
            } else {
                $_SESSION['flash_info'] = 'Application is already marked as ' . $statusLabel . '.';
            }
        }

        redirect_to('application/employerApplications');
    }

    public function delete($applicationId = null)
    {
        if (!isset($_SESSION['user_id'])) {
            require BASE_PATH . '/app/views/errors/unauthorized.php';
            exit;
        }

        $applicationId = (int)$applicationId;
        if ($applicationId <= 0) {
            $_SESSION['flash_error'] = 'Invalid application.';
            redirect_to('application');
        }

        $detailStmt = $this->db->prepare("
            SELECT a.id, a.user_id, j.employer_id, j.title
            FROM applications a
            JOIN jobs j ON a.job_id = j.id
            WHERE a.id = ?
            LIMIT 1
        ");
        $detailStmt->execute([$applicationId]);
        $application = $detailStmt->fetch(PDO::FETCH_ASSOC);

        if (!$application) {
            $_SESSION['flash_error'] = 'Application not found.';
            redirect_to('application');
        }

        $role = $_SESSION['role'] ?? '';
        $userId = (int)$_SESSION['user_id'];
        $isAdmin = $role === 'admin';
        $isApplicant = ($role === 'user' && (int)$application['user_id'] === $userId);

        if (!$isAdmin && !$isApplicant) {
            require BASE_PATH . '/app/views/errors/unauthorized.php';
            exit;
        }

        $deleteStmt = $this->db->prepare("DELETE FROM applications WHERE id = ?");
        $deleteStmt->execute([$applicationId]);

        if ($isApplicant) {
            Notification::create(
                (int)$application['employer_id'],
                'Application withdrawn',
                'A candidate withdrew their application for "' . trim((string)$application['title']) . '".',
                'application',
                $applicationId
            );
            $_SESSION['flash_success'] = 'Application deleted successfully.';
            redirect_to('application/myApplications');
        }

        if ($isAdmin) {
            Notification::create(
                (int)$application['user_id'],
                'Application removed',
                'Your application for "' . trim((string)$application['title']) . '" has been removed by an administrator.',
                'application',
                $applicationId
            );
        }

        $_SESSION['flash_success'] = 'Application deleted successfully.';
        if ($role === 'admin') {
            redirect_to('admin/applications');
        }
        redirect_to('application/employerApplications');
    }
}
