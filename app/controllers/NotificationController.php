<?php

class NotificationController
{
    public function index()
    {
        if (!isset($_SESSION['user_id'])) {
            require BASE_PATH . '/app/views/errors/unauthorized.php';
            exit;
        }

        $userId = (int)$_SESSION['user_id'];
        $notifications = Notification::getByUser($userId, 100);
        require BASE_PATH . '/app/views/notifications/index.php';
    }

    public function readAll()
    {
        if (!isset($_SESSION['user_id'])) {
            require BASE_PATH . '/app/views/errors/unauthorized.php';
            exit;
        }

        Notification::markAllAsRead((int)$_SESSION['user_id']);
        $_SESSION['flash_success'] = 'All notifications marked as read.';
        redirect_to('notification');
    }
}
