<?php
// database config is loaded by bootstrap; no need to require here
require_once BASE_PATH . '/app/services/ResumeTemplateService.php';
require_once BASE_PATH . '/app/services/PDFService.php';

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
        $templates = ResumeTemplateService::getAvailableTemplates();
        $selectedTemplate = $_GET['template'] ?? 'classic';
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

        $templateId = trim((string)($_POST['template_id'] ?? 'classic'));
        $action = trim((string)($_POST['action'] ?? 'save'));
        $infoMessage = '';

        // Debug logging
        error_log('DEBUG: action=' . $action . ', linkedin_text_length=' . strlen($formData['linkedin_text']));

        if ($formData['linkedin_text'] !== '') {
            $parsed = Resume::parseLinkedInText($formData['linkedin_text']);
            foreach ($parsed as $key => $value) {
                if (!isset($formData[$key]) || $formData[$key] === '') {
                    $formData[$key] = $value;
                }
            }
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
            $templates = ResumeTemplateService::getAvailableTemplates();
            $selectedTemplate = $templateId;
            require BASE_PATH . '/app/views/resumes/create.php';
            return;
        }

        $normalized = Resume::normalizeData($formData);
        if ($normalized['full_name'] === '') {
            $errorMessage = 'Full name is required.';
            $templates = ResumeTemplateService::getAvailableTemplates();
            $selectedTemplate = $templateId;
            require BASE_PATH . '/app/views/resumes/create.php';
            return;
        }

        if ($normalized['email'] !== '' && !filter_var($normalized['email'], FILTER_VALIDATE_EMAIL)) {
            $errorMessage = 'Please enter a valid email address.';
            $templates = ResumeTemplateService::getAvailableTemplates();
            $selectedTemplate = $templateId;
            require BASE_PATH . '/app/views/resumes/create.php';
            return;
        }

        Resume::create((int)$_SESSION['user_id'], $normalized, $templateId);
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

    // Change Resume Template
    public function changeTemplate($id = null)
    {
        $resumeId = (int)$id;
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect_to('resume');
        }

        verify_csrf();

        $templateId = trim((string)($_POST['template_id'] ?? 'classic'));
        if ($resumeId > 0) {
            Resume::updateTemplate($resumeId, (int)$_SESSION['user_id'], $templateId);
        }

        redirect_to('resume/view/' . $resumeId);
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
        $templateId = $resume['template_id'] ?? 'classic';
        $templates = ResumeTemplateService::getAvailableTemplates();
        $readOnly = true;
        $resumeId = $id;
        require BASE_PATH . '/app/views/resumes/view.php';
    }

    // Download Resume as PDF
    public function downloadPDF($id = null)
    {
        $resumeId = (int)$id;
        if ($resumeId <= 0) {
            redirect_to('resume');
            return;
        }

        $resume = Resume::find($resumeId, (int)$_SESSION['user_id']);
        if (!$resume) {
            redirect_to('resume');
            return;
        }

        $resumeData = $resume['parsed_content'];
        $templateId = $resume['template_id'] ?? 'classic';
        $fullName = $resumeData['full_name'] ?? 'Resume';

        // Sanitize filename - remove special characters
        $filename = preg_replace('/[^a-zA-Z0-9_-]/', '_', $fullName);
        $filename = preg_replace('/_+/', '_', $filename);
        $filename = trim($filename, '_') . '.pdf';

        // Generate HTML content using template
        $htmlContent = ResumeTemplateService::renderResume($resumeData, $templateId);

        // Wrap in proper HTML structure for PDF
        $htmlContent = '<!DOCTYPE html>
        <html>
        <head>
            <meta charset="utf-8">
            <title>' . htmlspecialchars($filename, ENT_QUOTES, 'UTF-8') . '</title>
            <style>
                * {
                    margin: 0;
                    padding: 0;
                    box-sizing: border-box;
                }
                body {
                    font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
                    color: #333;
                }
                @page {
                    size: A4;
                    margin: 15mm;
                }
                @media print {
                    body { margin: 0; }
                }
            </style>
        </head>
        <body>
            ' . $htmlContent . '
        </body>
        </html>';

        // Generate and download PDF
        $success = PDFService::generatePDF($htmlContent, $filename, null);

        if ($success === false) {
            header('Content-Type: text/html; charset=utf-8');
            echo '<!DOCTYPE html>' .
                '<html>' .
                '<head>' .
                '<meta charset="utf-8">' .
                '<title>PDF unavailable</title>' .
                '<style>body{font-family:Arial,Helvetica,sans-serif;color:#333;padding:24px;}a{color:#1a73e8;text-decoration:none;}</style>' .
                '</head>' .
                '<body>' .
                '<h1>PDF download unavailable</h1>' .
                '<p>The server could not generate a PDF because the required PDF library is not installed.</p>' .
                '<p>Please use your browser print function or install the <strong>html2pdf</strong> and <strong>tcpdf</strong> libraries.</p>' .
                '<p><a href="' . htmlspecialchars(base_url('resume/view/' . $resumeId), ENT_QUOTES, 'UTF-8') . '">Return to resume</a></p>' .
                '</body>' .
                '</html>';
            exit;
        }
    }
}
