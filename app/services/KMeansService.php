<?php

class KMeansService
{
    public static function cluster(array $vectors, int $k, int $maxIterations = 30): array
    {
        $count = count($vectors);
        if ($count === 0) {
            return [
                'assignments' => [],
                'centroids' => [],
                'clusters' => [],
            ];
        }

        $k = max(1, min($k, $count));
        $centroids = array_slice($vectors, 0, $k);
        $assignments = array_fill(0, $count, 0);

        for ($iter = 0; $iter < $maxIterations; $iter++) {
            $changed = false;

            for ($i = 0; $i < $count; $i++) {
                $bestCluster = 0;
                $bestDistance = null;

                for ($c = 0; $c < $k; $c++) {
                    $distance = self::euclideanDistance($vectors[$i], $centroids[$c]);
                    if ($bestDistance === null || $distance < $bestDistance) {
                        $bestDistance = $distance;
                        $bestCluster = $c;
                    }
                }

                if ($assignments[$i] !== $bestCluster) {
                    $assignments[$i] = $bestCluster;
                    $changed = true;
                }
            }

            $newCentroids = [];
            for ($c = 0; $c < $k; $c++) {
                $clusterVectors = [];
                for ($i = 0; $i < $count; $i++) {
                    if ($assignments[$i] === $c) {
                        $clusterVectors[] = $vectors[$i];
                    }
                }

                if (empty($clusterVectors)) {
                    $newCentroids[$c] = $centroids[$c];
                    continue;
                }

                $newCentroids[$c] = self::meanVector($clusterVectors);
            }

            $centroids = $newCentroids;

            if (!$changed) {
                break;
            }
        }

        $clusters = [];
        for ($c = 0; $c < $k; $c++) {
            $clusters[$c] = [];
        }
        for ($i = 0; $i < $count; $i++) {
            $clusters[$assignments[$i]][] = $i;
        }

        return [
            'assignments' => $assignments,
            'centroids' => $centroids,
            'clusters' => $clusters,
        ];
    }

    public static function cosineSimilarity(array $a, array $b): float
    {
        $dot = 0.0;
        $normA = 0.0;
        $normB = 0.0;
        $size = min(count($a), count($b));

        for ($i = 0; $i < $size; $i++) {
            $dot += $a[$i] * $b[$i];
            $normA += $a[$i] * $a[$i];
            $normB += $b[$i] * $b[$i];
        }

        if ($normA <= 0 || $normB <= 0) {
            return 0.0;
        }

        return $dot / (sqrt($normA) * sqrt($normB));
    }

    public static function euclideanDistance(array $a, array $b): float
    {
        $sum = 0.0;
        $size = min(count($a), count($b));
        for ($i = 0; $i < $size; $i++) {
            $delta = $a[$i] - $b[$i];
            $sum += $delta * $delta;
        }
        return sqrt($sum);
    }

    private static function meanVector(array $vectors): array
    {
        $count = count($vectors);
        $dimensions = count($vectors[0]);
        $mean = array_fill(0, $dimensions, 0.0);

        foreach ($vectors as $vector) {
            for ($d = 0; $d < $dimensions; $d++) {
                $mean[$d] += $vector[$d];
            }
        }

        for ($d = 0; $d < $dimensions; $d++) {
            $mean[$d] /= $count;
        }

        return $mean;
    }
}
