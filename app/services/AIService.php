<?php

class AIService
{
    private $apiKey;

    public function __construct()
    {
        $envKey = getenv('OPENAI_API_KEY');
        $this->apiKey = $envKey ? trim($envKey) : '';
    }

    public function evaluateAnswer($jobRole, $answer)
    {
        $jobRole = trim((string)$jobRole);
        $answer = trim((string)$answer);

        if ($answer === '') {
            return [
                'feedback' => 'Answer is empty. Add a clear, structured response.',
                'score' => 0,
                'improved_answer' => ''
            ];
        }

        if ($this->apiKey === '') {
            return $this->localEvaluation($jobRole, $answer);
        }

        $prompt = "You are an HR interviewer for {$jobRole}. Return strict JSON with keys feedback, score (0-10), improved_answer. Candidate answer: {$answer}";

        $data = [
            'model' => 'gpt-4o-mini',
            'messages' => [
                ['role' => 'system', 'content' => 'You evaluate interview answers and return strict JSON only.'],
                ['role' => 'user', 'content' => $prompt]
            ],
            'temperature' => 0.4
        ];

        $ch = curl_init('https://api.openai.com/v1/chat/completions');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Authorization: Bearer ' . $this->apiKey
        ]);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_TIMEOUT, 20);

        $raw = curl_exec($ch);
        if ($raw === false) {
            curl_close($ch);
            return $this->localEvaluation($jobRole, $answer);
        }
        curl_close($ch);

        $decoded = json_decode($raw, true);
        $content = $decoded['choices'][0]['message']['content'] ?? '';
        $parsed = json_decode(trim((string)$content), true);

        if (!is_array($parsed) || !isset($parsed['feedback'], $parsed['score'])) {
            return $this->localEvaluation($jobRole, $answer);
        }

        return [
            'feedback' => (string)$parsed['feedback'],
            'score' => max(0, min(10, (int)$parsed['score'])),
            'improved_answer' => (string)($parsed['improved_answer'] ?? '')
        ];
    }

    private function localEvaluation($jobRole, $answer)
    {
        $length = strlen($answer);
        $keywords = ['experience', 'project', 'team', 'result', 'improve', 'challenge'];
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

        return [
            'feedback' => "This is a solid start for {$jobRole}. Add clearer structure (situation, action, result) and include measurable impact.",
            'score' => $score,
            'improved_answer' => 'In my recent project, I identified the core issue, coordinated with the team, implemented a practical fix, and measured impact through performance and reliability improvements.'
        ];
    }
}
