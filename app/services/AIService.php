<?php

class AIService
{
    private const OPENAI_MODEL = 'gpt-4o-mini';
    private const OPENROUTER_MODEL = '~openai/gpt-mini-latest';
    private const OPENAI_URL = 'https://api.openai.com/v1/chat/completions';
    private const OPENROUTER_URL = 'https://openrouter.ai/api/v1/chat/completions';

    private $apiKey;
    private $provider;
    private $model;
    private $baseUrl;
    private $httpReferer;
    private $appName;

    public function __construct()
    {
        $provider = strtolower(trim((string)(getenv('AI_PROVIDER') ?: '')));
        $openRouterKey = trim((string)(getenv('OPENROUTER_API_KEY') ?: ''));
        $openAiKey = trim((string)(getenv('OPENAI_API_KEY') ?: ''));

        if ($provider === '') {
            $provider = $openRouterKey !== '' ? 'openrouter' : 'openai';
        }

        $this->provider = in_array($provider, ['openrouter', 'openai'], true) ? $provider : 'openrouter';
        $this->apiKey = $this->provider === 'openrouter' ? $openRouterKey : $openAiKey;
        $this->model = $this->provider === 'openrouter'
            ? trim((string)(getenv('OPENROUTER_MODEL') ?: self::OPENROUTER_MODEL))
            : trim((string)(getenv('OPENAI_MODEL') ?: self::OPENAI_MODEL));
        $this->baseUrl = $this->provider === 'openrouter' ? self::OPENROUTER_URL : self::OPENAI_URL;
        $this->httpReferer = trim((string)(getenv('OPENROUTER_HTTP_REFERER') ?: base_url('')));
        $this->appName = trim((string)(getenv('OPENROUTER_APP_NAME') ?: 'Seven-7'));
    }

    public function startHrInterview($jobRole, $maxQuestions = 5)
    {
        $jobRole = trim((string)$jobRole);
        $maxQuestions = max(1, (int)$maxQuestions);

        if ($jobRole === '') {
            return [
                'intro' => 'We will keep this practical and focused on your communication.',
                'question' => 'Tell me about yourself and what kind of role you are aiming for.'
            ];
        }

        if ($this->apiKey === '') {
            return $this->localOpening($jobRole, $maxQuestions);
        }

        $prompt = "You are conducting a mock HR interview for the role of {$jobRole}. "
            . "Return strict JSON only with keys intro and question. "
            . "The intro should be warm, professional, and explain that you will ask {$maxQuestions} interview questions one at a time. "
            . "The question must be an HR-style opening question for this role.";

        $parsed = $this->requestJson([
            ['role' => 'system', 'content' => 'You are a supportive HR interviewer. Return strict JSON only.'],
            ['role' => 'user', 'content' => $prompt]
        ], 0.6);

        if (!is_array($parsed) || empty($parsed['question'])) {
            return $this->localOpening($jobRole, $maxQuestions);
        }

        return [
            'intro' => (string)($parsed['intro'] ?? 'Let us begin your practice interview.'),
            'question' => (string)$parsed['question']
        ];
    }

    public function evaluateInterviewAnswer($jobRole, $question, $answer, array $history = [], $questionNumber = 1, $maxQuestions = 5)
    {
        $jobRole = trim((string)$jobRole);
        $question = trim((string)$question);
        $answer = trim((string)$answer);
        $questionNumber = max(1, (int)$questionNumber);
        $maxQuestions = max(1, (int)$maxQuestions);

        if ($answer === '') {
            return [
                'feedback' => 'Answer is empty. Add a clear, structured response with your actions and results.',
                'score' => 0,
                'improved_answer' => '',
                'next_question' => $question,
                'completed' => false,
                'final_summary' => ''
            ];
        }

        if ($this->apiKey === '') {
            return $this->localEvaluation($jobRole, $question, $answer, $questionNumber, $maxQuestions);
        }

        $historyText = $this->buildHistoryText($history);
        $isFinalQuestion = $questionNumber >= $maxQuestions;
        $prompt = "You are acting as a professional HR interviewer for a {$jobRole} interview practice session.\n"
            . "Current question number: {$questionNumber} of {$maxQuestions}.\n"
            . "Current interview question: {$question}\n"
            . "Candidate answer: {$answer}\n"
            . "Previous interview context:\n{$historyText}\n\n"
            . "Return strict JSON only with keys:\n"
            . "- feedback: 2-4 sentences of specific HR feedback\n"
            . "- score: integer from 0 to 10\n"
            . "- improved_answer: a stronger sample answer in first person\n"
            . "- next_question: the next HR interview question, or empty string if the interview is complete\n"
            . "- completed: boolean\n"
            . "- final_summary: short summary of strengths and next improvement areas, only if completed is true\n\n"
            . "Rules:\n"
            . "- Keep the tone supportive and realistic, like an HR mock interview.\n"
            . "- Focus on communication, clarity, professionalism, motivation, teamwork, adaptability, and impact.\n"
            . "- If this is the final question, set completed to true and next_question to an empty string.\n"
            . "- If this is not the final question, set completed to false and ask one follow-up question only.";

        $parsed = $this->requestJson([
            ['role' => 'system', 'content' => 'You are a supportive HR interviewer. Return strict JSON only.'],
            ['role' => 'user', 'content' => $prompt]
        ], 0.5);

        if (!is_array($parsed) || !isset($parsed['feedback'], $parsed['score'])) {
            return $this->localEvaluation($jobRole, $question, $answer, $questionNumber, $maxQuestions);
        }

        $completed = (bool)($parsed['completed'] ?? false);
        $nextQuestion = trim((string)($parsed['next_question'] ?? ''));
        if ($isFinalQuestion) {
            $completed = true;
            $nextQuestion = '';
        } elseif ($nextQuestion === '') {
            $nextQuestion = $this->fallbackNextQuestion($jobRole, $questionNumber + 1);
        }

        return [
            'feedback' => (string)$parsed['feedback'],
            'score' => max(0, min(10, (int)$parsed['score'])),
            'improved_answer' => (string)($parsed['improved_answer'] ?? ''),
            'next_question' => $nextQuestion,
            'completed' => $completed,
            'final_summary' => (string)($parsed['final_summary'] ?? '')
        ];
    }

    private function requestJson(array $messages, float $temperature = 0.4): ?array
    {
        $data = [
            'model' => $this->model !== '' ? $this->model : ($this->provider === 'openrouter' ? self::OPENROUTER_MODEL : self::OPENAI_MODEL),
            'messages' => $messages,
            'temperature' => $temperature,
            'max_tokens' => 800,
            'response_format' => ['type' => 'json_object']
        ];

        $headers = [
            'Content-Type: application/json',
            'Authorization: Bearer ' . $this->apiKey
        ];

        if ($this->provider === 'openrouter') {
            $headers[] = 'X-API-Key: ' . $this->apiKey;
            if ($this->httpReferer !== '') {
                $headers[] = 'HTTP-Referer: ' . $this->httpReferer;
            }
            if ($this->appName !== '') {
                $headers[] = 'X-Title: ' . $this->appName;
            }
        }

        $ch = curl_init($this->baseUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Seven-7 AI Interview Client');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_TIMEOUT, 20);

        $raw = curl_exec($ch);
        if ($raw === false) {
            curl_close($ch);
            return null;
        }
        $httpCode = (int)curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpCode >= 400) {
            error_log(strtoupper($this->provider) . ' request failed with status ' . $httpCode . ': ' . $raw);
            return null;
        }

        $decoded = json_decode($raw, true);
        $content = $decoded['choices'][0]['message']['content'] ?? '';
        if (is_array($content)) {
            $content = json_encode($content);
        }

        $parsed = json_decode(trim((string)$content), true);
        if (is_array($parsed)) {
            return $parsed;
        }

        return null;
    }

    private function localOpening($jobRole, $maxQuestions)
    {
        return [
            'intro' => "I will act as your HR interviewer for this {$jobRole} practice session. We will go through {$maxQuestions} questions, and I will give feedback after each answer.",
            'question' => 'Tell me about yourself and why this role interests you.'
        ];
    }

    private function localEvaluation($jobRole, $question, $answer, $questionNumber, $maxQuestions)
    {
        $question = trim((string)$question);
        $length = strlen($answer);
        $keywords = ['experience', 'project', 'team', 'result', 'improve', 'challenge', 'customer', 'deadline'];
        $hits = 0;
        foreach ($keywords as $kw) {
            if (stripos($answer, $kw) !== false) {
                $hits++;
            }
        }

        $score = 3;
        if ($length > 120) {
            $score += 2;
        }
        if ($length > 240) {
            $score += 2;
        }
        $score += min(3, $hits);
        $score = max(0, min(10, $score));
        $completed = $questionNumber >= $maxQuestions;

        return [
            'feedback' => "This is a solid start for {$jobRole}. Answer the question more directly, use a clear situation-action-result structure, and add measurable outcomes where possible.",
            'score' => $score,
            'improved_answer' => "In a recent role, I handled a situation similar to '{$question}' by clarifying the goal, aligning with the team, taking ownership of the key action items, and tracking results. That helped me deliver a strong outcome while staying organized and communicative.",
            'next_question' => $completed ? '' : $this->fallbackNextQuestion($jobRole, $questionNumber + 1),
            'completed' => $completed,
            'final_summary' => $completed
                ? "You show a good foundation for {$jobRole}. Keep improving specificity, confidence, and measurable examples to make your answers feel stronger in a real HR round."
                : ''
        ];
    }

    private function buildHistoryText(array $history): string
    {
        if ($history === []) {
            return 'No previous questions yet.';
        }

        $lines = [];
        foreach ($history as $index => $item) {
            $question = trim((string)($item['question'] ?? ''));
            $answer = trim((string)($item['answer'] ?? ''));
            $lines[] = 'Q' . ($index + 1) . ': ' . $question;
            $lines[] = 'A' . ($index + 1) . ': ' . $answer;
        }

        return implode("\n", $lines);
    }

    private function fallbackNextQuestion($jobRole, $questionNumber): string
    {
        $questions = [
            2 => "Why do you want to work as a {$jobRole}, and what motivates you most in this field?",
            3 => 'Tell me about a time you handled a challenge at work or in a project.',
            4 => 'How do you prioritize tasks and stay organized when deadlines overlap?',
            5 => 'Describe how you work with teammates or stakeholders when opinions differ.',
            6 => 'Why should we hire you, and what would you bring to this role in your first few months?'
        ];

        return $questions[$questionNumber] ?? "What makes you a strong fit for this {$jobRole} role?";
    }
}
