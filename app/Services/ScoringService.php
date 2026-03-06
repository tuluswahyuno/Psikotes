<?php

namespace App\Services;

use App\Models\TestSession;
use App\Models\Result;
use App\Models\Answer;
use App\Models\SkdSession;
use App\Models\SkdResult;

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

    /**
     * Calculate SKD CPNS scores for all three sub-tests.
     */
    public function calculateSkdScore(SkdSession $session): SkdResult
    {
        $package = $session->package;
        $package->load('packageTests.test.questions.options');
        $answers = $session->answers()->with('option', 'question')->get();

        // Build a map of question_id -> sub_test_type
        $questionTypeMap = [];
        $passingGrades = [];

        foreach ($package->packageTests as $pt) {
            $passingGrades[$pt->sub_test_type] = $pt->passing_grade;
            foreach ($pt->test->questions as $question) {
                $questionTypeMap[$question->id] = [
                    'sub_test_type' => $pt->sub_test_type,
                    'score_per_correct' => $pt->score_per_correct,
                ];
            }
        }

        $scores = ['twk' => 0, 'tiu' => 0, 'tkp' => 0];

        foreach ($answers as $answer) {
            if (!$answer->option || !isset($questionTypeMap[$answer->question_id])) {
                continue;
            }

            $info = $questionTypeMap[$answer->question_id];
            $subType = $info['sub_test_type'];

            if ($subType === 'tkp') {
                // TKP uses graduated scoring (option score 1-5)
                $score = $answer->option->score ?? 0;
            } else {
                // TWK & TIU: correct = score_per_correct, wrong = 0
                $score = $answer->option->is_correct ? $info['score_per_correct'] : 0;
            }

            $answer->update(['score' => $score]);
            $scores[$subType] += $score;
        }

        $totalScore = $scores['twk'] + $scores['tiu'] + $scores['tkp'];

        $twkPassed = $scores['twk'] >= ($passingGrades['twk'] ?? 0);
        $tiuPassed = $scores['tiu'] >= ($passingGrades['tiu'] ?? 0);
        $tkpPassed = $scores['tkp'] >= ($passingGrades['tkp'] ?? 0);
        $isPassed = $twkPassed && $tiuPassed && $tkpPassed;

        return SkdResult::updateOrCreate(
            ['skd_session_id' => $session->id],
            [
                'user_id' => $session->user_id,
                'skd_package_id' => $session->skd_package_id,
                'twk_score' => $scores['twk'],
                'tiu_score' => $scores['tiu'],
                'tkp_score' => $scores['tkp'],
                'total_score' => $totalScore,
                'twk_passed' => $twkPassed,
                'tiu_passed' => $tiuPassed,
                'tkp_passed' => $tkpPassed,
                'is_passed' => $isPassed,
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
