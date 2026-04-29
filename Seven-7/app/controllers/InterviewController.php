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

    private function getQuestions($role)
    {
        $questions = [
            'Backend Developer' => [
                "Tell me about a challenging backend problem you've solved.",
                "How do you handle database optimization?",
                "Describe your experience with APIs.",
                "How do you ensure code security?",
                "What frameworks have you worked with?"
            ],
            'Frontend Developer' => [
                "Describe a complex UI you've built.",
                "How do you optimize frontend performance?",
                "Tell me about your experience with JavaScript frameworks.",
                "How do you handle responsive design?",
                "What tools do you use for debugging?"
            ],
            'Data Analyst' => [
                "How do you approach data cleaning?",
                "Describe a data visualization you've created.",
                "What statistical methods do you use?",
                "How do you handle large datasets?",
                "Tell me about a business insight you derived from data."
            ],
            'DevOps Engineer' => [
                "How do you manage CI/CD pipelines?",
                "Describe your experience with cloud platforms.",
                "How do you handle infrastructure scaling?",
                "What monitoring tools do you use?",
                "How do you ensure system reliability?"
            ]
        ];
        return $questions[$role] ?? [];
    }

    public function chat()
    {
        if (!isset($_SESSION['user_id'])) {
            http_response_code(401);
            echo json_encode(['message' => 'Unauthorized']);
            return;
        }

        verify_csrf();

        $role = (string)($_POST['role'] ?? '');
        $message = trim((string)($_POST['message'] ?? ''));

        if (!isset($_SESSION['interview'])) {
            $_SESSION['interview'] = [
                'role' => $role,
                'questions' => $this->getQuestions($role),
                'current' => 0,
                'scores' => [],
                'feedbacks' => []
            ];
        }

        $interview = &$_SESSION['interview'];

        if ($message !== '') {
            // Evaluate the answer
            $ai = new AIService();
            $question = $interview['questions'][$interview['current']] ?? '';
            $result = $ai->evaluateAnswer($role, $message, $question);
            $interview['scores'][] = $result['score'];
            $interview['feedbacks'][] = $result['feedback'];
            $interview['current']++;
        }

        $response = [];

        if ($interview['current'] < count($interview['questions'])) {
            $response['question'] = $interview['questions'][$interview['current']];
            $response['feedback'] = $message !== '' ? $result['feedback'] : '';
            $response['score'] = $message !== '' ? $result['score'] : null;
        } else {
            // End interview
            $avgScore = array_sum($interview['scores']) / count($interview['scores']);
            $response['finished'] = true;
            $response['average_score'] = round($avgScore, 1);
            $response['overall_feedback'] = implode(' ', array_slice($interview['feedbacks'], -3)); // Last 3 feedbacks
            unset($_SESSION['interview']); // Reset
        }

        header('Content-Type: application/json');
        echo json_encode($response);
    }
}
