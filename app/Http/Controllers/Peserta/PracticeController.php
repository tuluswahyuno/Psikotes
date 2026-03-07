<?php

namespace App\Http\Controllers\Peserta;

use App\Http\Controllers\Controller;
use App\Models\Section;
use App\Models\SubTopic;
use App\Models\PracticeAttempt;
use App\Models\PracticeAnswer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PracticeController extends Controller
{
    /**
     * Mulai latihan soal untuk sub-topik tertentu
     */
    public function start(Section $section, SubTopic $subTopic)
    {
        if ($subTopic->section_id !== $section->id) {
            abort(404);
        }

        $questions = $subTopic->practiceQuestions()->inRandomOrder()->get();

        if ($questions->isEmpty()) {
            return back()->with('error', 'Belum ada soal latihan untuk sub-topik ini.');
        }

        return view('peserta.practice.start', compact('section', 'subTopic', 'questions'));
    }

    /**
     * Submit jawaban latihan dan hitung skor
     */
    public function submit(Request $request)
    {
        $request->validate([
            'sub_topic_id' => 'required|exists:sub_topics,id',
            'answers' => 'required|array',
            'answers.*' => 'required|string',
        ]);

        $user = Auth::user();
        $subTopic = SubTopic::with('section')->findOrFail($request->sub_topic_id);
        $questions = $subTopic->practiceQuestions()->get()->keyBy('id');

        $totalQuestions = $questions->count();
        $correctAnswers = 0;

        // Create attempt
        $attempt = PracticeAttempt::create([
            'user_id' => $user->id,
            'sub_topic_id' => $subTopic->id,
            'total_questions' => $totalQuestions,
            'correct_answers' => 0,
            'score' => 0,
            'completed_at' => now(),
        ]);

        // Save answers
        foreach ($request->answers as $questionId => $userAnswer) {
            $question = $questions->get($questionId);
            if (!$question) continue;

            $isCorrect = strtoupper(trim($userAnswer)) === strtoupper(trim($question->correct_answer));
            if ($isCorrect) $correctAnswers++;

            PracticeAnswer::create([
                'attempt_id' => $attempt->id,
                'practice_question_id' => $questionId,
                'user_answer' => $userAnswer,
                'is_correct' => $isCorrect,
            ]);
        }

        // Update attempt with final score
        $score = $totalQuestions > 0 ? round(($correctAnswers / $totalQuestions) * 100, 2) : 0;
        $attempt->update([
            'correct_answers' => $correctAnswers,
            'score' => $score,
        ]);

        return redirect()->route('peserta.practice.result', $attempt->id);
    }

    /**
     * Tampilkan hasil latihan + pembahasan
     */
    public function result(PracticeAttempt $attempt)
    {
        $user = Auth::user();

        if ($attempt->user_id !== $user->id) {
            abort(403);
        }

        $attempt->load(['subTopic.section', 'answers.question']);

        return view('peserta.practice.result', compact('attempt'));
    }
}
