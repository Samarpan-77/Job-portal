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
        $infoMessage = '';
        require BASE_PATH . '/app/views/resumes/create.php';
    }

    public function store()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect_to('resume/create');
        }

        verify_csrf();

        $formData = [
            'linkedin_text' => trim((string)($_POST['linkedin_text'] ?? '')),
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

        $action = trim((string)($_POST['action'] ?? 'save'));
        $infoMessage = '';

        if ($formData['linkedin_text'] !== '') {
            $parsed = Resume::parseLinkedInText($formData['linkedin_text']);
            $formData = array_merge($parsed, $formData);
        }

        if ($action === 'extract') {
            if ($formData['linkedin_text'] === '') {
                $errorMessage = 'Please paste LinkedIn profile text before extracting.';
            } else {
                $errorMessage = '';
                $infoMessage = 'LinkedIn text extracted. Review the fields and click Save Resume when ready.';
                $formData['skills'] = is_array($formData['skills']) ? implode(', ', $formData['skills']) : $formData['skills'];
                $formData['education_items'] = is_array($formData['education_items']) ? implode("\n", $formData['education_items']) : $formData['education_items'];
                $formData['experience_items'] = is_array($formData['experience_items']) ? implode("\n", $formData['experience_items']) : $formData['experience_items'];
                $formData['projects'] = is_array($formData['projects']) ? implode("\n", $formData['projects']) : $formData['projects'];
                $formData['certifications'] = is_array($formData['certifications']) ? implode("\n", $formData['certifications']) : $formData['certifications'];
            }
            require BASE_PATH . '/app/views/resumes/create.php';
            return;
        }

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
