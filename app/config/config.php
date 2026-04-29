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

        if ($key === '') {
            continue;
        }

        // Strip wrapping quotes and inline comments for unquoted values.
        if (strlen($value) >= 2) {
            $firstChar = $value[0];
            $lastChar = $value[strlen($value) - 1];
            if (($firstChar === '"' && $lastChar === '"') || ($firstChar === "'" && $lastChar === "'")) {
                $value = substr($value, 1, -1);
            } else {
                $hashPos = strpos($value, '#');
                if ($hashPos !== false) {
                    $value = rtrim(substr($value, 0, $hashPos));
                }
            }
        }

        // Always apply .env so local changes are reflected without stale process env values.
        putenv($key . '=' . $value);
        $_ENV[$key] = $value;
        $_SERVER[$key] = $value;
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

function hcaptcha_site_key(): string
{
    return trim((string)(getenv('HCAPTCHA_SITE_KEY') ?: ''));
}

function hcaptcha_secret_key(): string
{
    return trim((string)(getenv('HCAPTCHA_SECRET_KEY') ?: ''));
}

function hcaptcha_is_enabled(): bool
{
    return hcaptcha_site_key() !== '' && hcaptcha_secret_key() !== '';
}

function verify_hcaptcha_response(string $token): bool
{
    if (!hcaptcha_is_enabled()) {
        return true;
    }

    $token = trim($token);
    if ($token === '') {
        return false;
    }

    $payload = http_build_query([
        'secret' => hcaptcha_secret_key(),
        'response' => $token,
        'remoteip' => $_SERVER['REMOTE_ADDR'] ?? '',
    ]);

    $responseBody = false;

    if (function_exists('curl_init')) {
        $ch = curl_init('https://hcaptcha.com/siteverify');
        curl_setopt_array($ch, [
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => $payload,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 10,
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/x-www-form-urlencoded',
            ],
        ]);
        $responseBody = curl_exec($ch);
        curl_close($ch);
    } else {
        $context = stream_context_create([
            'http' => [
                'method' => 'POST',
                'header' => "Content-Type: application/x-www-form-urlencoded\r\n",
                'content' => $payload,
                'timeout' => 10,
            ],
        ]);
        $responseBody = @file_get_contents('https://hcaptcha.com/siteverify', false, $context);
    }

    if (!is_string($responseBody) || $responseBody === '') {
        return false;
    }

    $decoded = json_decode($responseBody, true);
    return is_array($decoded) && !empty($decoded['success']);
}
