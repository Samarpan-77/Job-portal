<?php
// models are autoloaded by the bootstrap, no need to require them manually

class AuthController
{
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
}
