<?php
// AIService will be autoloaded when instantiated

class InterviewController
{
    public function index()
    {
        $this->start();
    }

    public function start()
    {
        if (!isset($_SESSION['user_id'])) {
            redirect_to('auth/login');
        }
        require BASE_PATH . '/app/views/interview/start.php';
    }

    public function submit()
    {
        if (!isset($_SESSION['user_id'])) {
            http_response_code(401);
            echo json_encode(['feedback' => 'Unauthorized', 'score' => 0, 'improved_answer' => '']);
            return;
        }

        verify_csrf();

        $ai = new AIService();
        $result = $ai->evaluateAnswer((string)($_POST['role'] ?? ''), (string)($_POST['answer'] ?? ''));

        header('Content-Type: application/json');
        echo json_encode($result);
    }
}
