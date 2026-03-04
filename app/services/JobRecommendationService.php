<?php

class JobRecommendationService
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::connect();
    }

    public function getRecommendationsForUser(int $userId, int $limit = 5): array
    {
        $jobs = Job::getAll();
        if (empty($jobs)) {
            return [
                'matched_cluster' => null,
                'cluster_size' => 0,
                'jobs' => [],
                'message' => 'No jobs available yet.',
            ];
        }

        $jobTexts = [];
        foreach ($jobs as $job) {
            $jobTexts[] = $this->buildJobText($job);
        }

        $userText = $this->buildUserProfileText($userId);
        if ($userText === '') {
            $userText = $this->buildFallbackUserTextFromApplications($userId);
        }

        $vocabulary = $this->buildVocabulary($jobTexts, $userText, 120);
        if (empty($vocabulary)) {
            return [
                'matched_cluster' => null,
                'cluster_size' => 0,
                'jobs' => array_slice($jobs, 0, $limit),
                'message' => 'Not enough text signals yet. Showing recent jobs.',
            ];
        }

        $jobVectors = [];
        foreach ($jobTexts as $text) {
            $jobVectors[] = $this->vectorizeText($text, $vocabulary);
        }

        $userVector = $this->vectorizeText($userText, $vocabulary);
        $k = $this->pickClusterCount(count($jobVectors));
        $result = KMeansService::cluster($jobVectors, $k);

        $matchedCluster = 0;
        $bestDistance = null;
        foreach ($result['centroids'] as $clusterId => $centroid) {
            $distance = KMeansService::euclideanDistance($userVector, $centroid);
            if ($bestDistance === null || $distance < $bestDistance) {
                $bestDistance = $distance;
                $matchedCluster = (int)$clusterId;
            }
        }

        $appliedIds = $this->getAppliedJobIds($userId);
        $scored = [];
        foreach (($result['clusters'][$matchedCluster] ?? []) as $jobIdx) {
            $job = $jobs[$jobIdx];
            if (isset($appliedIds[(int)$job['id']])) {
                continue;
            }

            $score = KMeansService::cosineSimilarity($userVector, $jobVectors[$jobIdx]);
            $job['recommendation_score'] = $score;
            $scored[] = $job;
        }

        usort($scored, function ($a, $b) {
            return ($b['recommendation_score'] <=> $a['recommendation_score']);
        });

        return [
            'matched_cluster' => $matchedCluster + 1,
            'cluster_size' => count($result['clusters'][$matchedCluster] ?? []),
            'jobs' => array_slice($scored, 0, $limit),
            'message' => empty($scored)
                ? 'No new jobs in your best-matching cluster right now.'
                : 'Top jobs from your closest cluster.',
        ];
    }

    private function buildJobText(array $job): string
    {
        return trim(implode(' ', [
            (string)($job['title'] ?? ''),
            (string)($job['description'] ?? ''),
            (string)($job['location'] ?? ''),
            (string)($job['salary'] ?? ''),
        ]));
    }

    private function buildUserProfileText(int $userId): string
    {
        $stmt = $this->db->prepare("
            SELECT content_json
            FROM resumes
            WHERE user_id = ?
            ORDER BY created_at DESC
            LIMIT 1
        ");
        $stmt->execute([$userId]);
        $json = $stmt->fetchColumn();
        if (!$json) {
            return '';
        }

        $resume = Resume::decodeContent((string)$json);
        $parts = [
            (string)($resume['headline'] ?? ''),
            (string)($resume['summary'] ?? ''),
            implode(' ', $resume['skills'] ?? []),
            implode(' ', $resume['experience_items'] ?? []),
            implode(' ', $resume['projects'] ?? []),
            implode(' ', $resume['certifications'] ?? []),
            (string)($resume['address'] ?? ''),
        ];
        return trim(implode(' ', $parts));
    }

    private function buildFallbackUserTextFromApplications(int $userId): string
    {
        $stmt = $this->db->prepare("
            SELECT j.title, j.description, j.location
            FROM applications a
            JOIN jobs j ON a.job_id = j.id
            WHERE a.user_id = ?
            ORDER BY a.applied_at DESC
            LIMIT 5
        ");
        $stmt->execute([$userId]);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $parts = [];
        foreach ($rows as $row) {
            $parts[] = trim((string)$row['title'] . ' ' . (string)$row['description'] . ' ' . (string)$row['location']);
        }

        return trim(implode(' ', $parts));
    }

    private function buildVocabulary(array $jobTexts, string $userText, int $maxTerms): array
    {
        $counter = [];
        foreach ($jobTexts as $text) {
            foreach ($this->tokenize($text) as $token) {
                $counter[$token] = ($counter[$token] ?? 0) + 1;
            }
        }

        foreach ($this->tokenize($userText) as $token) {
            $counter[$token] = ($counter[$token] ?? 0) + 2;
        }

        arsort($counter);
        $terms = array_slice(array_keys($counter), 0, $maxTerms);
        return array_values($terms);
    }

    private function vectorizeText(string $text, array $vocabulary): array
    {
        $tokens = $this->tokenize($text);
        $freq = [];
        foreach ($tokens as $token) {
            $freq[$token] = ($freq[$token] ?? 0) + 1;
        }

        $vector = [];
        foreach ($vocabulary as $term) {
            $vector[] = (float)($freq[$term] ?? 0);
        }
        return $vector;
    }

    private function tokenize(string $text): array
    {
        $text = strtolower($text);
        $text = preg_replace('/[^a-z0-9\s]/', ' ', $text);
        $parts = preg_split('/\s+/', trim((string)$text));
        if (!is_array($parts)) {
            return [];
        }

        $stopwords = [
            'the', 'and', 'for', 'with', 'from', 'you', 'your', 'job', 'jobs', 'role',
            'to', 'of', 'in', 'on', 'at', 'a', 'an', 'is', 'are', 'as', 'by', 'or',
            'we', 'our', 'be', 'will', 'this', 'that', 'it',
        ];
        $stopwordSet = array_fill_keys($stopwords, true);

        $tokens = [];
        foreach ($parts as $part) {
            $token = trim((string)$part);
            if ($token === '' || isset($stopwordSet[$token]) || strlen($token) < 2) {
                continue;
            }
            $tokens[] = $token;
        }

        return $tokens;
    }

    private function pickClusterCount(int $jobCount): int
    {
        if ($jobCount <= 2) {
            return 1;
        }
        return max(2, min(5, (int)round(sqrt($jobCount / 2))));
    }

    private function getAppliedJobIds(int $userId): array
    {
        $stmt = $this->db->prepare("
            SELECT job_id
            FROM applications
            WHERE user_id = ?
        ");
        $stmt->execute([$userId]);
        $rows = $stmt->fetchAll(PDO::FETCH_COLUMN);
        $set = [];
        foreach ($rows as $jobId) {
            $set[(int)$jobId] = true;
        }
        return $set;
    }
}
