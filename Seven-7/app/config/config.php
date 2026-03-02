<?php

if (!defined('BASE_PATH')) {
    define('BASE_PATH', dirname(__DIR__, 2));
}

// Load .env file if present
$envFile = BASE_PATH . '/.env';
if (is_file($envFile) && is_readable($envFile)) {
    $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        $line = trim($line);
        if ($line === '' || str_starts_with($line, '#') || strpos($line, '=') === false) {
            continue;
        }
        [$key, $value] = explode('=', $line, 2);
        $key = trim($key);
        $value = trim($value);
        if ($key !== '' && getenv($key) === false) {
            putenv($key . '=' . $value);
            $_ENV[$key] = $value;
        }
    }
}

// Start secure session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// CLI runs do not always populate REQUEST_METHOD; default to GET for non-HTTP contexts.
if (!isset($_SERVER['REQUEST_METHOD'])) {
    $_SERVER['REQUEST_METHOD'] = 'GET';
}

// Base URL (XAMPP localhost)
define('BASE_URL', 'http://localhost/Seven-7/public/');

// Database credentials
define('DB_HOST', 'localhost');
define('DB_NAME', 'job-portal');
define('DB_USER', 'root');
define('DB_PASS', '');

// Security settings
define('SESSION_TIMEOUT', 1800); // 30 minutes

// Error reporting (Disable in production)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Session timeout handling
if (
    isset($_SESSION['LAST_ACTIVITY']) &&
    (time() - $_SESSION['LAST_ACTIVITY'] > SESSION_TIMEOUT)
) {

    session_unset();
    session_destroy();
    header("Location: " . BASE_URL . "login");
    exit;
}
$_SESSION['LAST_ACTIVITY'] = time();

// CSRF Token
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

function base_url($path = '')
{
    return rtrim(BASE_URL, '/') . '/' . ltrim($path, '/');
}

function redirect_to($path = '')
{
    header('Location: ' . base_url($path));
    exit;
}

function verify_csrf()
{
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        return;
    }

    $token = $_POST['csrf_token'] ?? '';
    if (!$token || !hash_equals($_SESSION['csrf_token'] ?? '', $token)) {
        http_response_code(403);
        echo 'Invalid CSRF token';
        exit;
    }
}
