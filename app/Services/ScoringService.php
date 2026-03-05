<?php

namespace App\Services;

use App\Models\TestSession;
use App\Models\Result;
use App\Models\Answer;

class ScoringService
{
    public function calculateScore(TestSession $session): Result
    {
        $test = $session->test;
        $answers = $session->answers()->with('option', 'question')->get();

        $totalScore = 0;
        $categoryScores = [];
        $maxScore = 0;

        foreach ($answers as $answer) {
            $question = $answer->question;

            switch ($test->type) {
                case 'multiple_choice':
                    if ($answer->option && $answer->option->is_correct) {
                        $score = $question->weight;
                    } else {
                        $score = 0;
                    }
                    $maxScore += $question->weight;
                    break;

                case 'likert':
                case 'personality':
                    $score = $answer->option ? $answer->option->score * $question->weight : 0;
                    break;

                default:
                    $score = 0;
            }

            $answer->update(['score' => $score]);
            $totalScore += $score;

            // Group by category if applicable
            $category = $question->type ?? 'general';
            if (!isset($categoryScores[$category])) {
                $categoryScores[$category] = 0;
            }
            $categoryScores[$category] += $score;
        }

        // Calculate percentage for multiple choice
        if ($test->type === 'multiple_choice' && $maxScore > 0) {
            $percentage = round(($totalScore / $maxScore) * 100, 2);
            $interpretation = $this->getMultipleChoiceInterpretation($percentage);
        } else {
            $percentage = $totalScore;
            $interpretation = $this->getGenericInterpretation($totalScore, $test->type);
        }

        return Result::updateOrCreate(
            ['test_session_id' => $session->id],
            [
                'user_id' => $session->user_id,
                'test_id' => $session->test_id,
                'total_score' => $percentage ?? $totalScore,
                'category_scores' => $categoryScores,
                'interpretation' => $interpretation,
            ]
        );
    }

    private function getMultipleChoiceInterpretation(float $percentage): string
    {
        if ($percentage >= 90) return 'Sangat Baik';
        if ($percentage >= 75) return 'Baik';
        if ($percentage >= 60) return 'Cukup';
        if ($percentage >= 40) return 'Kurang';
        return 'Sangat Kurang';
    }

    private function getGenericInterpretation(float $score, string $type): string
    {
        return "Skor total: {$score} ({$type})";
    }
}
