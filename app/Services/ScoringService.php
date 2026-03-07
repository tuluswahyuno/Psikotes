<?php

namespace App\Services;

use App\Models\PracticeQuestion;
use App\Models\Section;
use App\Models\SkdAnswer;
use App\Models\SkdResult;
use App\Models\SkdSession;
use App\Models\TestSession;
use App\Models\Result;
use App\Models\Answer;

class ScoringService
{
    public function calculateScore(TestSession $session): Result
    {
        $test    = $session->test;
        $answers = $session->answers()->with('option', 'question')->get();

        $totalScore     = 0;
        $categoryScores = [];
        $maxScore       = 0;

        foreach ($answers as $answer) {
            $question = $answer->question;

            switch ($test->type) {
                case 'multiple_choice':
                    $score = ($answer->option && $answer->option->is_correct) ? $question->weight : 0;
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

            $category = $question->type ?? 'general';
            if (!isset($categoryScores[$category])) $categoryScores[$category] = 0;
            $categoryScores[$category] += $score;
        }

        $percentage     = ($test->type === 'multiple_choice' && $maxScore > 0)
            ? round(($totalScore / $maxScore) * 100, 2)
            : $totalScore;
        $interpretation = ($test->type === 'multiple_choice')
            ? $this->getMultipleChoiceInterpretation($percentage)
            : "Skor total: {$totalScore} ({$test->type})";

        return Result::updateOrCreate(
            ['test_session_id' => $session->id],
            [
                'user_id'          => $session->user_id,
                'test_id'          => $session->test_id,
                'total_score'      => $percentage ?? $totalScore,
                'category_scores'  => $categoryScores,
                'interpretation'   => $interpretation,
            ]
        );
    }

    /**
     * Calculate SKD CPNS scores using PracticeQuestion bank directly.
     */
    public function calculateSkdScore(SkdSession $session): SkdResult
    {
        $package = $session->package;
        $snapshot = $session->question_snapshot ?? [];

        // Build question → sub_test_type map from snapshot
        $questionTypeMap = [];
        foreach (['twk', 'tiu', 'tkp'] as $type) {
            foreach ($snapshot[$type] ?? [] as $qId) {
                $questionTypeMap[$qId] = $type;
            }
        }

        // Load answers with practice questions
        $answers = $session->answers()->with('practiceQuestion')->get();

        $passingGrades = [
            'twk' => $package->twk_passing_grade,
            'tiu' => $package->tiu_passing_grade,
            'tkp' => $package->tkp_passing_grade,
        ];

        $scores = ['twk' => 0, 'tiu' => 0, 'tkp' => 0];

        foreach ($answers as $answer) {
            $pq = $answer->practiceQuestion;
            if (!$pq || !$answer->answer) continue;

            $subType = $questionTypeMap[$pq->id] ?? null;
            if (!$subType) continue;

            if ($subType === 'tkp') {
                // TKP: each option A-E has a weight; use PracticeQuestion options array
                // Convention: options stored as ['A' => score, 'B' => score, ...]
                // For TKP options are scored 1-5
                $optionScores = ['A' => 5, 'B' => 4, 'C' => 3, 'D' => 2, 'E' => 1];
                $score = $optionScores[strtoupper($answer->answer)] ?? 0;
            } else {
                // TWK & TIU: 5 points for correct, 0 for wrong
                $score = (strtoupper($answer->answer) === strtoupper($pq->correct_answer)) ? 5 : 0;
            }

            $answer->update(['score' => $score]);
            $scores[$subType] += $score;
        }

        $totalScore = $scores['twk'] + $scores['tiu'] + $scores['tkp'];
        $twkPassed  = $scores['twk'] >= $passingGrades['twk'];
        $tiuPassed  = $scores['tiu'] >= $passingGrades['tiu'];
        $tkpPassed  = $scores['tkp'] >= $passingGrades['tkp'];
        $isPassed   = $twkPassed && $tiuPassed && $tkpPassed;

        return SkdResult::updateOrCreate(
            ['skd_session_id' => $session->id],
            [
                'user_id'        => $session->user_id,
                'skd_package_id' => $session->skd_package_id,
                'twk_score'      => $scores['twk'],
                'tiu_score'      => $scores['tiu'],
                'tkp_score'      => $scores['tkp'],
                'total_score'    => $totalScore,
                'twk_passed'     => $twkPassed,
                'tiu_passed'     => $tiuPassed,
                'tkp_passed'     => $tkpPassed,
                'is_passed'      => $isPassed,
            ]
        );
    }

    private function getMultipleChoiceInterpretation(float $pct): string
    {
        if ($pct >= 90) return 'Sangat Baik';
        if ($pct >= 75) return 'Baik';
        if ($pct >= 60) return 'Cukup';
        if ($pct >= 40) return 'Kurang';
        return 'Sangat Kurang';
    }
}
