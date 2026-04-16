<?php
// database config is loaded by bootstrap; no need to require here

class ResumeController
{

    private $db;

    public function __construct()
    {
        $this->db = Database::connect();

        if (!isset($_SESSION['user_id'])) {
            // show unauthorized error view
            require BASE_PATH . '/app/views/errors/unauthorized.php';
            exit;
        }
    }

    // Create Resume (Stored as JSON)
    public function index()
    {
        $this->list();
    }

    public function create()
    {
        $formData = [];
        $errorMessage = '';
        require BASE_PATH . '/app/views/resumes/create.php';
    }

    public function store()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect_to('resume/create');
        }

        verify_csrf();

        $formData = [
            'full_name' => trim((string)($_POST['full_name'] ?? '')),
            'headline' => trim((string)($_POST['headline'] ?? '')),
            'email' => trim((string)($_POST['email'] ?? '')),
            'phone' => trim((string)($_POST['phone'] ?? '')),
            'address' => trim((string)($_POST['address'] ?? '')),
            'summary' => trim((string)($_POST['summary'] ?? '')),
            'skills' => trim((string)($_POST['skills'] ?? '')),
            'education_items' => trim((string)($_POST['education_items'] ?? '')),
            'experience_items' => trim((string)($_POST['experience_items'] ?? '')),
            'projects' => trim((string)($_POST['projects'] ?? '')),
            'certifications' => trim((string)($_POST['certifications'] ?? '')),
        ];

        $normalized = Resume::normalizeData($formData);
        if ($normalized['full_name'] === '') {
            $errorMessage = 'Full name is required.';
            require BASE_PATH . '/app/views/resumes/create.php';
            return;
        }

        if ($normalized['email'] !== '' && !filter_var($normalized['email'], FILTER_VALIDATE_EMAIL)) {
            $errorMessage = 'Please enter a valid email address.';
            require BASE_PATH . '/app/views/resumes/create.php';
            return;
        }

        Resume::create((int)$_SESSION['user_id'], $normalized);
        redirect_to('resume');
    }

    // View My Resumes
    public function list()
    {

        $stmt = $this->db->prepare("
            SELECT * FROM resumes WHERE user_id=?
            ORDER BY created_at DESC
        ");

        $stmt->execute([$_SESSION['user_id']]);
        $resumes = $stmt->fetchAll(PDO::FETCH_ASSOC);

        require BASE_PATH . '/app/views/resumes/list.php';
    }

    // Delete Resume
    public function delete($id = null)
    {
        $resumeId = (int)$id;
        if ($resumeId > 0) {
            $stmt = $this->db->prepare("
                DELETE FROM resumes WHERE id=? AND user_id=?
            ");
            $stmt->execute([$resumeId, $_SESSION['user_id']]);
        }

        redirect_to('resume');
    }

    // View Resume (Decode JSON)
    public function view($id = null)
    {
        $resumeId = (int)$id;
        if ($resumeId <= 0) {
            require BASE_PATH . '/app/views/errors/404.php';
            return;
        }

        $resume = Resume::find($resumeId, (int)$_SESSION['user_id']);
        if (!$resume) {
            require BASE_PATH . '/app/views/errors/404.php';
            return;
        }

        $resumeData = $resume['parsed_content'];
        $readOnly = true;
        require BASE_PATH . '/app/views/resumes/view.php';
    }
}
