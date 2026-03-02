<?php
class RoleMiddleware
{

    public static function requireRole($role)
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (!isset($_SESSION['role']) || $_SESSION['role'] != $role) {
            require BASE_PATH . '/app/views/errors/unauthorized.php';
            exit;
        }
    }
}
