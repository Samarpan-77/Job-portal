<?php
// Job model will be autoloaded when needed

class JobController
{
    private const JOB_IMAGE_DIR = 'uploads/jobs';
    private const MAX_IMAGE_SIZE = 5242880;

    public function index()
    {
        $jobs = Job::getAll();
        $savedJobs = $this->getSavedJobMap();
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

        $title = trim((string)($_POST['title'] ?? ''));
        $description = trim((string)($_POST['description'] ?? ''));
        $salary = trim((string)($_POST['salary'] ?? ''));
        $location = trim((string)($_POST['location'] ?? ''));
        $employerId = (int)$_SESSION['user_id'];

        if ($title === '' || $description === '') {
            $_SESSION['flash_error'] = 'Title and description are required.';
            redirect_to('job/create');
        }

        $upload = $this->handleJobImageUpload($_FILES['image'] ?? null);
        if (!$upload['success']) {
            $_SESSION['flash_error'] = $upload['message'];
            redirect_to('job/create');
        }

        $jobId = Job::create(
            $title,
            $description,
            $salary,
            $location,
            $employerId,
            $upload['path']
        );

        if ($jobId <= 0) {
            if ($upload['path'] !== null) {
                $this->deleteImageFile($upload['path']);
            }

            $_SESSION['flash_error'] = 'Unable to create job right now.';
            redirect_to('job/create');
        }

        $_SESSION['flash_success'] = 'Job posted successfully.';
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
        $isSaved = false;
        if (isset($_SESSION['user_id']) && ($_SESSION['role'] ?? '') === 'user') {
            $resumes = Resume::getByUser((int)$_SESSION['user_id']);
            $isSaved = SavedJob::isSaved((int)$_SESSION['user_id'], $jobId);
        }

        require BASE_PATH . '/app/views/jobs/view.php';
    }

    public function uploadImage($id = null)
    {
        RoleMiddleware::requireRole('employer');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect_to('job/view/' . (int)$id);
        }

        verify_csrf();

        $job = $this->requireOwnedJob($id);
        $upload = $this->handleJobImageUpload($_FILES['image'] ?? null, true);
        if (!$upload['success']) {
            $_SESSION['flash_error'] = $upload['message'];
            redirect_to('job/view/' . $job['id']);
        }

        $previousImage = $job['image_path'] ?? null;
        Job::updateImagePath((int)$job['id'], $upload['path']);
        if ($previousImage) {
            $this->deleteImageFile($previousImage);
        }

        $_SESSION['flash_success'] = 'Job image updated successfully.';
        redirect_to('job/view/' . $job['id']);
    }

    public function removeImage($id = null)
    {
        RoleMiddleware::requireRole('employer');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect_to('job/view/' . (int)$id);
        }

        verify_csrf();

        $job = $this->requireOwnedJob($id);
        $imagePath = $job['image_path'] ?? null;

        if (!$imagePath) {
            $_SESSION['flash_error'] = 'This job does not have an image.';
            redirect_to('job/view/' . $job['id']);
        }

        Job::updateImagePath((int)$job['id'], null);
        $this->deleteImageFile($imagePath);

        $_SESSION['flash_success'] = 'Job image removed successfully.';
        redirect_to('job/view/' . $job['id']);
    }

    public function recommendations()
    {
        RoleMiddleware::requireRole('user');

        $service = new JobRecommendationService();
        $recommendation = $service->getRecommendationsForUser((int)$_SESSION['user_id'], 6);
        $recommendedJobs = $recommendation['jobs'] ?? [];
        $savedJobs = $this->getSavedJobMap();
        $matchedCluster = $recommendation['matched_cluster'] ?? null;
        $clusterSize = $recommendation['cluster_size'] ?? 0;
        $message = $recommendation['message'] ?? '';

        require BASE_PATH . '/app/views/jobs/recommendations.php';
    }

    public function saved()
    {
        RoleMiddleware::requireRole('user');

        $jobs = SavedJob::getByUser((int)$_SESSION['user_id']);
        $savedJobs = SavedJob::getSavedJobIds((int)$_SESSION['user_id']);
        require BASE_PATH . '/app/views/jobs/saved.php';
    }

    public function save($id = null)
    {
        RoleMiddleware::requireRole('user');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect_to('job');
        }

        verify_csrf();

        $jobId = (int)$id;
        $job = Job::findById($jobId);
        if (!$job) {
            $_SESSION['flash_error'] = 'Job not found.';
            redirect_to('job');
        }

        SavedJob::save((int)$_SESSION['user_id'], $jobId);
        $_SESSION['flash_success'] = 'Job saved successfully.';
        redirect_to((string)($_POST['return_to'] ?? 'job/view/' . $jobId));
    }

    public function unsave($id = null)
    {
        RoleMiddleware::requireRole('user');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect_to('job');
        }

        verify_csrf();

        $jobId = (int)$id;
        SavedJob::remove((int)$_SESSION['user_id'], $jobId);
        $_SESSION['flash_success'] = 'Job removed from saved list.';
        redirect_to((string)($_POST['return_to'] ?? 'job/saved'));
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

        $imagePath = $job['image_path'] ?? null;
        Job::delete($jobId);
        if ($imagePath) {
            $this->deleteImageFile($imagePath);
        }

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

    private function requireOwnedJob($id)
    {
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

        if ((int)$job['employer_id'] !== (int)($_SESSION['user_id'] ?? 0)) {
            require BASE_PATH . '/app/views/errors/unauthorized.php';
            exit;
        }

        return $job;
    }

    private function handleJobImageUpload($file, $required = false)
    {
        if (!$file || !isset($file['error'])) {
            return $required
                ? ['success' => false, 'message' => 'Please choose an image to upload.', 'path' => null]
                : ['success' => true, 'message' => '', 'path' => null];
        }

        if ((int)$file['error'] === UPLOAD_ERR_NO_FILE) {
            return $required
                ? ['success' => false, 'message' => 'Please choose an image to upload.', 'path' => null]
                : ['success' => true, 'message' => '', 'path' => null];
        }

        if ((int)$file['error'] !== UPLOAD_ERR_OK) {
            return ['success' => false, 'message' => 'Image upload failed. Please try again.', 'path' => null];
        }

        if ((int)$file['size'] <= 0 || (int)$file['size'] > self::MAX_IMAGE_SIZE) {
            return ['success' => false, 'message' => 'Image must be smaller than 5 MB.', 'path' => null];
        }

        $tmpName = (string)($file['tmp_name'] ?? '');
        if ($tmpName === '' || !is_uploaded_file($tmpName)) {
            return ['success' => false, 'message' => 'Invalid uploaded file.', 'path' => null];
        }

        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mimeType = $finfo ? (string)finfo_file($finfo, $tmpName) : '';
        if ($finfo) {
            finfo_close($finfo);
        }

        $allowedTypes = [
            'image/jpeg' => 'jpg',
            'image/png' => 'png',
            'image/webp' => 'webp',
            'image/gif' => 'gif',
        ];

        if (!isset($allowedTypes[$mimeType])) {
            return ['success' => false, 'message' => 'Only JPG, PNG, WEBP, and GIF images are allowed.', 'path' => null];
        }

        $relativeDir = self::JOB_IMAGE_DIR;
        $absoluteDir = BASE_PATH . '/public/' . $relativeDir;
        if (!is_dir($absoluteDir) && !mkdir($absoluteDir, 0775, true) && !is_dir($absoluteDir)) {
            return ['success' => false, 'message' => 'Unable to prepare image storage.', 'path' => null];
        }

        $filename = 'job_' . bin2hex(random_bytes(16)) . '.' . $allowedTypes[$mimeType];
        $relativePath = $relativeDir . '/' . $filename;
        $absolutePath = BASE_PATH . '/public/' . $relativePath;

        if (!move_uploaded_file($tmpName, $absolutePath)) {
            return ['success' => false, 'message' => 'Unable to save the uploaded image.', 'path' => null];
        }

        return ['success' => true, 'message' => '', 'path' => $relativePath];
    }

    private function deleteImageFile($relativePath)
    {
        $relativePath = ltrim((string)$relativePath, '/\\');
        if ($relativePath === '' || !str_starts_with(str_replace('\\', '/', $relativePath), self::JOB_IMAGE_DIR . '/')) {
            return;
        }

        $absolutePath = BASE_PATH . '/public/' . str_replace('/', DIRECTORY_SEPARATOR, $relativePath);
        if (is_file($absolutePath)) {
            @unlink($absolutePath);
        }
    }

    private function getSavedJobMap(): array
    {
        if (!isset($_SESSION['user_id']) || (($_SESSION['role'] ?? '') !== 'user')) {
            return [];
        }

        return SavedJob::getSavedJobIds((int)$_SESSION['user_id']);
    }
}
