<?php

class ProfileController
{
    private const PROFILE_IMAGE_DIR = 'uploads/profiles';
    private const COMPANY_LOGO_DIR = 'uploads/companies';
    private const MAX_IMAGE_SIZE = 5242880;

    public function index()
    {
        if (!isset($_SESSION['user_id'])) {
            redirect_to('login');
        }

        $profile = User::getPublicProfileById((int)$_SESSION['user_id']);
        if (!$profile) {
            require BASE_PATH . '/app/views/errors/404.php';
            return;
        }

        require BASE_PATH . '/app/views/profile/index.php';
    }

    public function edit()
    {
        if (!isset($_SESSION['user_id'])) {
            redirect_to('login');
        }

        $profile = User::getPublicProfileById((int)$_SESSION['user_id']);
        if (!$profile) {
            require BASE_PATH . '/app/views/errors/404.php';
            return;
        }

        require BASE_PATH . '/app/views/profile/edit.php';
    }

    public function update()
    {
        if (!isset($_SESSION['user_id'])) {
            redirect_to('login');
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect_to('profile/edit');
        }

        verify_csrf();

        $userId = (int)$_SESSION['user_id'];
        $existing = User::getPublicProfileById($userId);
        if (!$existing) {
            $_SESSION['flash_error'] = 'Profile not found.';
            redirect_to('profile');
        }

        $name = trim((string)($_POST['name'] ?? ''));
        if ($name === '') {
            $_SESSION['flash_error'] = 'Name is required.';
            redirect_to('profile/edit');
        }

        $profileImageUpload = $this->handleImageUpload($_FILES['profile_image'] ?? null, self::PROFILE_IMAGE_DIR);
        if (!$profileImageUpload['success']) {
            $_SESSION['flash_error'] = $profileImageUpload['message'];
            redirect_to('profile/edit');
        }

        $companyLogoUpload = $this->handleImageUpload($_FILES['company_logo'] ?? null, self::COMPANY_LOGO_DIR);
        if (!$companyLogoUpload['success']) {
            if ($profileImageUpload['path']) {
                $this->deleteImageFile($profileImageUpload['path'], self::PROFILE_IMAGE_DIR);
            }
            $_SESSION['flash_error'] = $companyLogoUpload['message'];
            redirect_to('profile/edit');
        }

        $profileImagePath = $existing['profile_image'] ?? null;
        if (!empty($_POST['remove_profile_image'])) {
            $profileImagePath = null;
        } elseif ($profileImageUpload['path']) {
            $profileImagePath = $profileImageUpload['path'];
        }

        $companyLogoPath = $existing['company_logo'] ?? null;
        if (!empty($_POST['remove_company_logo'])) {
            $companyLogoPath = null;
        } elseif ($companyLogoUpload['path']) {
            $companyLogoPath = $companyLogoUpload['path'];
        }

        $data = [
            'name' => $name,
            'headline' => trim((string)($_POST['headline'] ?? '')) ?: null,
            'bio' => trim((string)($_POST['bio'] ?? '')) ?: null,
            'location' => trim((string)($_POST['location'] ?? '')) ?: null,
            'website' => trim((string)($_POST['website'] ?? '')) ?: null,
            'company_name' => trim((string)($_POST['company_name'] ?? '')) ?: null,
            'company_description' => trim((string)($_POST['company_description'] ?? '')) ?: null,
            'company_website' => trim((string)($_POST['company_website'] ?? '')) ?: null,
            'company_location' => trim((string)($_POST['company_location'] ?? '')) ?: null,
            'profile_image' => $profileImagePath,
            'company_logo' => $companyLogoPath,
        ];

        if (($_SESSION['role'] ?? '') !== 'employer') {
            $data['company_name'] = null;
            $data['company_description'] = null;
            $data['company_website'] = null;
            $data['company_location'] = null;
            $data['company_logo'] = null;
        }

        $updated = User::updateProfile($userId, $data);
        if (!$updated) {
            if ($profileImageUpload['path']) {
                $this->deleteImageFile($profileImageUpload['path'], self::PROFILE_IMAGE_DIR);
            }
            if ($companyLogoUpload['path']) {
                $this->deleteImageFile($companyLogoUpload['path'], self::COMPANY_LOGO_DIR);
            }
            $_SESSION['flash_error'] = 'Unable to update profile right now.';
            redirect_to('profile/edit');
        }

        if (!empty($_POST['remove_profile_image']) && !empty($existing['profile_image'])) {
            $this->deleteImageFile((string)$existing['profile_image'], self::PROFILE_IMAGE_DIR);
        } elseif ($profileImageUpload['path'] && !empty($existing['profile_image'])) {
            $this->deleteImageFile((string)$existing['profile_image'], self::PROFILE_IMAGE_DIR);
        }

        if (($_SESSION['role'] ?? '') === 'employer') {
            if (!empty($_POST['remove_company_logo']) && !empty($existing['company_logo'])) {
                $this->deleteImageFile((string)$existing['company_logo'], self::COMPANY_LOGO_DIR);
            } elseif ($companyLogoUpload['path'] && !empty($existing['company_logo'])) {
                $this->deleteImageFile((string)$existing['company_logo'], self::COMPANY_LOGO_DIR);
            }
        } elseif (!empty($existing['company_logo'])) {
            $this->deleteImageFile((string)$existing['company_logo'], self::COMPANY_LOGO_DIR);
        }

        $_SESSION['flash_success'] = 'Profile updated successfully.';
        redirect_to('profile');
    }

    public function view($id = null)
    {
        $profile = User::getPublicProfileById((int)$id);
        if (!$profile || ($profile['role'] ?? '') !== 'user') {
            require BASE_PATH . '/app/views/errors/404.php';
            return;
        }

        require BASE_PATH . '/app/views/profile/view.php';
    }

    public function company($id = null)
    {
        $company = User::getEmployerCompanyById((int)$id);
        if (!$company) {
            require BASE_PATH . '/app/views/errors/404.php';
            return;
        }

        $jobs = Job::getByEmployer((int)$company['id']);
        require BASE_PATH . '/app/views/profile/company.php';
    }

    private function handleImageUpload($file, string $directory): array
    {
        if (!$file || !isset($file['error']) || (int)$file['error'] === UPLOAD_ERR_NO_FILE) {
            return ['success' => true, 'message' => '', 'path' => null];
        }

        if ((int)$file['error'] !== UPLOAD_ERR_OK) {
            return ['success' => false, 'message' => 'Image upload failed. Please try again.', 'path' => null];
        }

        if ((int)$file['size'] <= 0 || (int)$file['size'] > self::MAX_IMAGE_SIZE) {
            return ['success' => false, 'message' => 'Image must be smaller than 5 MB.', 'path' => null];
        }

        $tmpName = (string)($file['tmp_name'] ?? '');
        if ($tmpName === '' || !is_uploaded_file($tmpName)) {
            return ['success' => false, 'message' => 'Invalid uploaded image.', 'path' => null];
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

        $absoluteDir = BASE_PATH . '/public/' . $directory;
        if (!is_dir($absoluteDir) && !mkdir($absoluteDir, 0775, true) && !is_dir($absoluteDir)) {
            return ['success' => false, 'message' => 'Unable to prepare image storage.', 'path' => null];
        }

        $filename = 'img_' . bin2hex(random_bytes(16)) . '.' . $allowedTypes[$mimeType];
        $relativePath = $directory . '/' . $filename;
        $absolutePath = BASE_PATH . '/public/' . $relativePath;

        if (!move_uploaded_file($tmpName, $absolutePath)) {
            return ['success' => false, 'message' => 'Unable to save the uploaded image.', 'path' => null];
        }

        return ['success' => true, 'message' => '', 'path' => $relativePath];
    }

    private function deleteImageFile(string $relativePath, string $directory): void
    {
        $relativePath = ltrim($relativePath, '/\\');
        if ($relativePath === '' || !str_starts_with(str_replace('\\', '/', $relativePath), $directory . '/')) {
            return;
        }

        $absolutePath = BASE_PATH . '/public/' . str_replace('/', DIRECTORY_SEPARATOR, $relativePath);
        if (is_file($absolutePath)) {
            @unlink($absolutePath);
        }
    }
}
