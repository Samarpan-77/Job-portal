<?php
// models are autoloaded by the bootstrap, no need to require them manually

class AuthController
{
    private const RESET_TOKEN_BYTES = 32;
    private const DEFAULT_RESET_TOKEN_EXPIRY_SECONDS = 900;

    private function getResetTokenExpirySeconds(): int
    {
        $configured = (int)(getenv('RESET_TOKEN_EXPIRY_SECONDS') ?: 0);
        return $configured >= 60 ? $configured : self::DEFAULT_RESET_TOKEN_EXPIRY_SECONDS;
    }

    public function index()
    {
        if (isset($_SESSION['user_id'])) {
            redirect_to('dashboard');
        }
        redirect_to('auth/login');
    }

    public function register()
    {
        $errorMessage = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            verify_csrf();

            $name = trim((string)($_POST['name'] ?? ''));
            $email = trim((string)($_POST['email'] ?? ''));
            $passwordPlain = (string)($_POST['password'] ?? '');
            $role = $_POST['role'] ?? 'user';

            if (!in_array($role, ['user', 'employer'], true)) {
                $role = 'user';
            }

            if ($name === '') {
                $errorMessage = 'Name is required.';
            } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errorMessage = 'Please enter a valid email address.';
            } elseif (strlen($passwordPlain) < 6) {
                $errorMessage = 'Password must be at least 6 characters.';
            } elseif (User::findByEmail($email)) {
                $errorMessage = 'An account with this email already exists.';
            } else {
                $password = password_hash($passwordPlain, PASSWORD_BCRYPT);
                $created = User::create($name, $email, $password, $role);

                if ($created) {
                    $_SESSION['flash_success'] = 'Registration successful. Please login.';
                    redirect_to('login');
                }

                $errorMessage = 'Registration failed. Please try again.';
            }
        }

        require BASE_PATH . '/app/views/auth/register.php';
    }

    public function login()
    {
        $errorMessage = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            verify_csrf();

            $email = trim((string)($_POST['email'] ?? ''));
            $password = (string)($_POST['password'] ?? '');
            $user = User::findByEmail($email);

            $isValidPassword = false;

            if ($user) {
                $storedPassword = (string)($user['password'] ?? '');
                $passwordInfo = password_get_info($storedPassword);

                if (($passwordInfo['algo'] ?? null) !== null && $passwordInfo['algo'] !== 0) {
                    $isValidPassword = password_verify($password, $storedPassword);
                } else {
                    // Legacy rows may store plain-text passwords; allow once and rehash.
                    $isValidPassword = hash_equals($storedPassword, $password);
                    if ($isValidPassword) {
                        $newHash = password_hash($password, PASSWORD_BCRYPT);
                        User::updatePassword((int)$user['id'], $newHash);
                    }
                }
            }

            if ($user && $isValidPassword) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['role'] = $user['role'];

                redirect_to('dashboard');
            }

            $errorMessage = 'Invalid email or password.';
        }

        require BASE_PATH . '/app/views/auth/login.php';
    }

    public function logout()
    {
        session_destroy();
        redirect_to('auth/login');
    }

    public function forgotPassword()
    {
        $errorMessage = '';
        $successMessage = '';
        $devResetLink = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            verify_csrf();

            $email = trim((string)($_POST['email'] ?? ''));
            $successMessage = 'If that email exists, a password reset link has been generated.';

            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errorMessage = 'Please enter a valid email address.';
                $successMessage = '';
            } else {
                $user = User::findByEmail($email);
                if ($user) {
                    $token = bin2hex(random_bytes(self::RESET_TOKEN_BYTES));
                    $tokenHash = hash('sha256', $token);
                    $resetExpirySeconds = $this->getResetTokenExpirySeconds();
                    $expiresAt = date('Y-m-d H:i:s', time() + $resetExpirySeconds);

                    PasswordReset::deleteByUserId((int)$user['id']);
                    $created = PasswordReset::create((int)$user['id'], $tokenHash, $expiresAt);

                    if ($created) {
                        $resetLink = base_url('reset-password?token=' . urlencode($token));
                        $mailSent = MailService::sendPasswordResetEmail(
                            (string)$user['email'],
                            (string)($user['name'] ?? ''),
                            $resetLink,
                            $resetExpirySeconds
                        );

                        if (!$mailSent && getenv('MAIL_SHOW_DEV_RESET_LINK') === '1') {
                            $devResetLink = $resetLink;
                        }
                    }
                }
            }
        }

        require BASE_PATH . '/app/views/auth/forgot_password.php';
    }

    public function resetPassword()
    {
        $errorMessage = '';
        $token = trim((string)($_GET['token'] ?? ($_POST['token'] ?? '')));
        $isTokenFormatValid = strlen($token) >= 32 && ctype_xdigit($token);
        $canShowForm = false;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            verify_csrf();

            $password = (string)($_POST['password'] ?? '');
            $confirmPassword = (string)($_POST['confirm_password'] ?? '');

            if (!$isTokenFormatValid) {
                $errorMessage = 'Invalid or expired reset link.';
            } else {
                $resetRequest = PasswordReset::findValidByToken($token);
                if (!$resetRequest) {
                    $errorMessage = 'Invalid or expired reset link.';
                } else {
                    $canShowForm = true;
                    if (strlen($password) < 8) {
                        $errorMessage = 'Password must be at least 8 characters.';
                    } elseif ($password !== $confirmPassword) {
                        $errorMessage = 'Passwords do not match.';
                    } else {
                        $newHash = password_hash($password, PASSWORD_BCRYPT);
                        $updated = User::updatePassword((int)$resetRequest['user_id'], $newHash);

                        if ($updated) {
                            PasswordReset::markUsed((int)$resetRequest['id']);
                            PasswordReset::deleteByUserId((int)$resetRequest['user_id']);
                            $_SESSION['flash_success'] = 'Password reset successful. Please login.';
                            redirect_to('login');
                        }

                        $errorMessage = 'Failed to reset password. Please try again.';
                    }
                }
            }
        } elseif (!$isTokenFormatValid) {
            $errorMessage = 'Invalid or expired reset link.';
        } else {
            $resetRequest = PasswordReset::findValidByToken($token);
            if (!$resetRequest) {
                $errorMessage = 'Invalid or expired reset link.';
            } else {
                $canShowForm = true;
            }
        }

        require BASE_PATH . '/app/views/auth/reset_password.php';
    }
}
