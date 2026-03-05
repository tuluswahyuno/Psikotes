<?php

namespace App\Http\Controllers\Peserta;

use App\Http\Controllers\Controller;
use App\Models\Test;
use App\Models\TestAssignment;
use App\Models\TestSession;
use App\Models\Answer;
use App\Services\ScoringService;
use Illuminate\Http\Request;

class TestSessionController extends Controller
{
    public function show(Test $test)
    {
        $user = auth()->user();

        // Check if user is assigned to this test
        $assignment = TestAssignment::where('user_id', $user->id)
            ->where('test_id', $test->id)
            ->where('status', 'pending')
            ->firstOrFail();

        // Check if there's an existing active session
        $existingSession = TestSession::where('user_id', $user->id)
            ->where('test_id', $test->id)
            ->where('status', 'in_progress')
            ->first();

        return view('peserta.tests.show', compact('test', 'assignment', 'existingSession'));
    }

    public function start(Test $test)
    {
        $user = auth()->user();

        // Verify assignment
        $assignment = TestAssignment::where('user_id', $user->id)
            ->where('test_id', $test->id)
            ->where('status', 'pending')
            ->firstOrFail();

        // Check for existing active session
        $session = TestSession::where('user_id', $user->id)
            ->where('test_id', $test->id)
            ->where('status', 'in_progress')
            ->first();

        if (!$session) {
            $session = TestSession::create([
                'user_id' => $user->id,
                'test_id' => $test->id,
                'started_at' => now(),
                'status' => 'in_progress',
            ]);
        }

        return redirect()->route('peserta.tests.attempt', [$test, $session]);
    }

    public function attempt(Test $test, TestSession $session)
    {
        if ($session->user_id !== auth()->id() || $session->status !== 'in_progress') {
            abort(403);
        }

        // Check if time has expired
        if ($session->isExpired()) {
            return $this->finishSession($session);
        }

        $questions = $test->questions()->with('options')->get();

        if ($test->randomize_questions) {
            $questions = $questions->shuffle();
        }

        // Get existing answers
        $existingAnswers = $session->answers()->pluck('option_id', 'question_id')->toArray();

        return view('peserta.tests.attempt', compact('test', 'session', 'questions', 'existingAnswers'));
    }

    public function saveAnswer(Request $request, TestSession $session)
    {
        if ($session->user_id !== auth()->id() || $session->status !== 'in_progress') {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        if ($session->isExpired()) {
            $this->finishSession($session);
            return response()->json(['redirect' => route('peserta.results.show', $session->result)]);
        }

        $validated = $request->validate([
            'question_id' => 'required|exists:questions,id',
            'option_id' => 'required|exists:options,id',
        ]);

        Answer::updateOrCreate(
            ['test_session_id' => $session->id, 'question_id' => $validated['question_id']],
            ['option_id' => $validated['option_id']]
        );

        return response()->json(['success' => true]);
    }

    public function submit(TestSession $session)
    {
        if ($session->user_id !== auth()->id() || $session->status !== 'in_progress') {
            abort(403);
        }

        return $this->finishSession($session);
    }

    private function finishSession(TestSession $session)
    {
        $session->update([
            'finished_at' => now(),
            'status' => 'completed',
        ]);

        // Update assignment status
        TestAssignment::where('user_id', $session->user_id)
            ->where('test_id', $session->test_id)
            ->update(['status' => 'completed']);

        // Calculate score
        $scoringService = new ScoringService();
        $result = $scoringService->calculateScore($session);

        return redirect()->route('peserta.results.show', $result)
            ->with('success', 'Tes berhasil diselesaikan!');
    }
}
