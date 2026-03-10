<?php

// =============================
// BOOTSTRAP APPLICATION
// =============================

// Define project root path
define('BASE_PATH', dirname(__DIR__));

// Load Core Config
require_once BASE_PATH . '/app/config/config.php';
require_once BASE_PATH . '/app/config/database.php';

// Optional: Error Logging
ini_set('log_errors', 1);
ini_set('error_log', BASE_PATH . '/logs.txt');


// =============================
// SIMPLE AUTOLOADER
// =============================

spl_autoload_register(function ($class) {

    $paths = [
        BASE_PATH . '/app/controllers/',
        BASE_PATH . '/app/models/',
        BASE_PATH . '/app/middleware/',
        BASE_PATH . '/app/services/'
    ];

    foreach ($paths as $path) {
        if (file_exists($path . $class . '.php')) {
            require_once $path . $class . '.php';
            return;
        }
    }
});


// =============================
// ROUTER
// =============================

// Get URL
$url = $_GET['url'] ?? '';
$url = trim($url, '/');
$urlParts = explode('/', $url);

// Default controller & method
if (empty($urlParts[0])) {
    if (isset($_SESSION['user_id'])) {
        $controllerName = 'DashboardController';
        $method = 'index';
    } else {
        $controllerName = 'AuthController';
        $method = 'login';
    }
    $params = [];
} else {
    $aliases = [
        'login' => ['AuthController', 'login'],
        'register' => ['AuthController', 'register'],
        'logout' => ['AuthController', 'logout'],
        'forgot-password' => ['AuthController', 'forgotPassword'],
        'reset-password' => ['AuthController', 'resetPassword'],
        'dashboard' => ['DashboardController', 'index'],
    ];

    if (isset($aliases[$urlParts[0]])) {
        [$controllerName, $method] = $aliases[$urlParts[0]];
        $params = array_slice($urlParts, 1);
    } else {
        $controllerName = ucfirst($urlParts[0]) . 'Controller';
        $method = $urlParts[1] ?? 'index';
        $params = array_slice($urlParts, 2);
    }
}

// Controller file path
$controllerFile = BASE_PATH . '/app/controllers/' . $controllerName . '.php';


// =============================
// CONTROLLER EXECUTION
// =============================

if (file_exists($controllerFile)) {

    require_once $controllerFile;
    $controller = new $controllerName();

    if (method_exists($controller, $method)) {

        call_user_func_array([$controller, $method], $params);
    } else {

        require BASE_PATH . '/app/views/errors/404.php';
    }
} else {

    require BASE_PATH . '/app/views/errors/404.php';
}
