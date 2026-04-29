<?php
// AIService will be autoloaded when instantiated

class InterviewController
{
    private const MAX_QUESTIONS = 5;

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

    public function begin()
    {
        if (!isset($_SESSION['user_id'])) {
            $this->jsonResponse([
                'started' => false,
                'message' => 'Unauthorized'
            ], 401);
        }

        verify_csrf();

        $role = trim((string)($_POST['role'] ?? ''));
        if ($role === '') {
            $this->jsonResponse([
                'started' => false,
                'message' => 'Please choose a role before starting.'
            ], 422);
        }

        $existingInterview = $_SESSION['active_interview'] ?? null;
        if (is_array($existingInterview) && !empty($existingInterview['session_id'])) {
            InterviewSession::complete((int)$existingInterview['session_id']);
        }

        $ai = new AIService();
        $opening = $ai->startHrInterview($role, self::MAX_QUESTIONS);

        $sessionId = InterviewSession::create((int)$_SESSION['user_id'], $role);
        $_SESSION['active_interview'] = [
            'session_id' => (int)$sessionId,
            'role' => $role,
            'current_question' => (string)$opening['question'],
            'question_number' => 1,
            'max_questions' => self::MAX_QUESTIONS,
            'history' => []
        ];

        $this->jsonResponse([
            'started' => true,
            'role' => $role,
            'session_id' => (int)$sessionId,
            'intro' => (string)($opening['intro'] ?? ''),
            'question' => (string)$opening['question'],
            'question_number' => 1,
            'max_questions' => self::MAX_QUESTIONS
        ]);
    }

    public function submit()
    {
        if (!isset($_SESSION['user_id'])) {
            $this->jsonResponse([
                'feedback' => 'Unauthorized',
                'score' => 0,
                'improved_answer' => ''
            ], 401);
        }

        verify_csrf();

        $activeInterview = $_SESSION['active_interview'] ?? null;
        if (!is_array($activeInterview) || empty($activeInterview['session_id']) || empty($activeInterview['current_question'])) {
            $this->jsonResponse([
                'feedback' => 'Start a new interview session first.',
                'score' => 0,
                'improved_answer' => ''
            ], 422);
        }

        $role = (string)($activeInterview['role'] ?? ($_POST['role'] ?? ''));
        $question = (string)$activeInterview['current_question'];
        $questionNumber = (int)($activeInterview['question_number'] ?? 1);
        $maxQuestions = (int)($activeInterview['max_questions'] ?? self::MAX_QUESTIONS);
        $history = is_array($activeInterview['history'] ?? null) ? $activeInterview['history'] : [];

        $ai = new AIService();
        $result = $ai->evaluateInterviewAnswer(
            $role,
            $question,
            (string)($_POST['answer'] ?? ''),
            $history,
            $questionNumber,
            $maxQuestions
        );

        InterviewMessage::create(
            (int)$activeInterview['session_id'],
            $question,
            (string)($_POST['answer'] ?? ''),
            (string)($result['feedback'] ?? ''),
            (int)($result['score'] ?? 0)
        );
        InterviewSession::updateScore((int)$activeInterview['session_id'], (int)($result['score'] ?? 0));

        $history[] = [
            'question' => $question,
            'answer' => (string)($_POST['answer'] ?? ''),
            'feedback' => (string)($result['feedback'] ?? ''),
            'score' => (int)($result['score'] ?? 0)
        ];

        if (!empty($result['completed'])) {
            InterviewSession::complete((int)$activeInterview['session_id']);
            unset($_SESSION['active_interview']);
        } else {
            $_SESSION['active_interview'] = [
                'session_id' => (int)$activeInterview['session_id'],
                'role' => $role,
                'current_question' => (string)($result['next_question'] ?? ''),
                'question_number' => $questionNumber + 1,
                'max_questions' => $maxQuestions,
                'history' => $history
            ];
        }

        $this->jsonResponse($result);
    }

    private function jsonResponse(array $payload, int $status = 200): void
    {
        http_response_code($status);
        header('Content-Type: application/json');
        echo json_encode($payload);
        exit;
    }
}
