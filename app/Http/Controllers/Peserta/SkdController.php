<?php

namespace App\Http\Controllers\Peserta;

use App\Http\Controllers\Controller;
use App\Models\PracticeQuestion;
use App\Models\Section;
use App\Models\SkdAnswer;
use App\Models\SkdPackage;
use App\Models\SkdResult;
use App\Models\SkdSession;
use App\Models\User;
use App\Services\ScoringService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SkdController extends Controller
{
    public function index()
    {
        $packages = SkdPackage::where('is_active', true)->get();

        $completedPackageIds = SkdSession::where('user_id', auth()->id())
            ->where('status', 'completed')
            ->pluck('skd_package_id')->toArray();

        $activeSessions = SkdSession::where('user_id', auth()->id())
            ->where('status', 'in_progress')
            ->pluck('skd_package_id', 'id')->toArray();

        return view('peserta.skd.index', compact('packages', 'completedPackageIds', 'activeSessions'));
    }

    public function show(SkdPackage $skdPackage)
    {
        $existingSession = SkdSession::where('user_id', auth()->id())
            ->where('skd_package_id', $skdPackage->id)
            ->where('status', 'in_progress')
            ->first();

        $hasCompleted = SkdSession::where('user_id', auth()->id())
            ->where('skd_package_id', $skdPackage->id)
            ->where('status', 'completed')
            ->exists();

        return view('peserta.skd.show', compact('skdPackage', 'existingSession', 'hasCompleted'));
    }

    public function start(SkdPackage $skdPackage)
    {
        $user = auth()->user();

        // Check for existing active session
        $session = SkdSession::where('user_id', $user->id)
            ->where('skd_package_id', $skdPackage->id)
            ->where('status', 'in_progress')
            ->first();

        if (!$session) {
            // Auto-pull questions from PracticeQuestion bank per section
            $snapshot = $this->generateSnapshot($skdPackage);

            $session = SkdSession::create([
                'user_id'           => $user->id,
                'skd_package_id'    => $skdPackage->id,
                'question_snapshot' => $snapshot,
                'started_at'        => now(),
                'status'            => 'in_progress',
            ]);
        }

        return redirect()->route('peserta.skd.attempt', [$skdPackage, $session]);
    }

    public function attempt(SkdPackage $skdPackage, SkdSession $skdSession)
    {
        if ($skdSession->user_id !== auth()->id() || $skdSession->status !== 'in_progress') {
            abort(403);
        }

        if ($skdSession->isExpired()) {
            return $this->finishSession($skdSession);
        }

        $snapshot = $skdSession->question_snapshot ?? [];

        // Organize questions by sub-test type from snapshot
        $questionsByType = [];
        $subTestLabels   = ['twk' => 'Tes Wawasan Kebangsaan (TWK)', 'tiu' => 'Tes Intelegensi Umum (TIU)', 'tkp' => 'Tes Karakteristik Pribadi (TKP)'];
        $passingGrades   = ['twk' => $skdPackage->twk_passing_grade, 'tiu' => $skdPackage->tiu_passing_grade, 'tkp' => $skdPackage->tkp_passing_grade];

        foreach (['twk', 'tiu', 'tkp'] as $type) {
            $ids = $snapshot[$type] ?? [];
            if (empty($ids)) continue;

            $questions = PracticeQuestion::whereIn('id', $ids)->get()->keyBy('id');
            // Maintain the snapshotted order
            $ordered   = collect($ids)->map(fn($id) => $questions->get($id))->filter();

            $questionsByType[$type] = [
                'label'         => $subTestLabels[$type],
                'passing_grade' => $passingGrades[$type],
                'questions'     => $ordered,
            ];
        }

        // Get existing answers
        $existingAnswers = $skdSession->answers()
            ->get(['practice_question_id', 'answer', 'is_doubtful'])
            ->keyBy('practice_question_id')
            ->map(fn($item) => [
                'answer'     => $item->answer,
                'is_doubtful' => (bool) $item->is_doubtful,
            ])->toArray();

        return view('peserta.skd.attempt', compact('skdPackage', 'skdSession', 'questionsByType', 'existingAnswers'));
    }

    public function saveAnswer(Request $request, SkdSession $skdSession)
    {
        if ($skdSession->user_id !== auth()->id() || $skdSession->status !== 'in_progress') {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        if ($skdSession->isExpired()) {
            $this->finishSession($skdSession);
            $result = $skdSession->result;
            return response()->json(['redirect' => route('peserta.skd.results.show', $result ?? $skdSession->id)]);
        }

        $validated = $request->validate([
            'practice_question_id' => 'required|exists:practice_questions,id',
            'answer'               => 'nullable|string|size:1|in:A,B,C,D,E',
            'is_doubtful'          => 'nullable|boolean',
        ]);

        $updateData = [];
        if ($request->has('answer')) $updateData['answer']     = $validated['answer'];
        if ($request->has('is_doubtful')) $updateData['is_doubtful'] = $validated['is_doubtful'] ?? false;

        if (!empty($updateData)) {
            SkdAnswer::updateOrCreate(
                ['skd_session_id' => $skdSession->id, 'practice_question_id' => $validated['practice_question_id']],
                $updateData
            );
        }

        return response()->json(['success' => true]);
    }

    public function submit(SkdSession $skdSession)
    {
        if ($skdSession->user_id !== auth()->id() || $skdSession->status !== 'in_progress') {
            abort(403);
        }
        return $this->finishSession($skdSession);
    }

    public function results()
    {
        $userId  = auth()->id();
        $results = SkdResult::with(['package'])->where('user_id', $userId)->latest()->paginate(10);

        $allResults    = SkdResult::where('user_id', $userId)->orderBy('created_at', 'asc')->get();
        $totalSimulasi = $allResults->count();
        $skorTertinggi = $allResults->max('total_score') ?? 0;
        $lulusCount    = $allResults->where('is_passed', true)->count();
        $rataKelulusan = $totalSimulasi > 0 ? round(($lulusCount / $totalSimulasi) * 100) : 0;
        $trendResults  = $allResults->take(-7)->values();

        $thisWeekCount  = $allResults->where('created_at', '>=', now()->startOfWeek())->count();
        $trendTotal     = "+{$thisWeekCount} minggu ini";
        $lastMonthMax   = $allResults->where('created_at', '<', now()->startOfMonth())->max('total_score') ?? 0;
        $trendSkor      = $skorTertinggi > $lastMonthMax && $lastMonthMax > 0 ? "+".($skorTertinggi - $lastMonthMax)." dari bulan lalu" : "Terbaik sejauh ini";
        $trendLulus     = $rataKelulusan > 50 ? "+".($rataKelulusan - 50)."% di atas rata-rata" : "Tetap semangat";

        return view('peserta.skd.results', compact('results', 'totalSimulasi', 'skorTertinggi', 'rataKelulusan', 'trendResults', 'trendTotal', 'trendSkor', 'trendLulus'));
    }

    public function resultShow(SkdResult $skdResult)
    {
        if ($skdResult->user_id !== auth()->id()) abort(403);
        $skdResult->load(['package', 'session.answers.practiceQuestion']);
        return view('peserta.skd.result-detail', compact('skdResult'));
    }

    public function review(SkdResult $skdResult)
    {
        if ($skdResult->user_id !== auth()->id()) abort(403);

        $session  = $skdResult->session;
        $snapshot = $session->question_snapshot ?? [];
        $answers  = $session->answers()->get()->keyBy('practice_question_id');

        $questions = collect();
        $stats     = ['benar' => 0, 'salah' => 0, 'kosong' => 0];
        $number    = 1;

        $subTestLabels = ['twk' => 'Tes Wawasan Kebangsaan (TWK)', 'tiu' => 'Tes Intelegensi Umum (TIU)', 'tkp' => 'Tes Karakteristik Pribadi (TKP)'];

        foreach (['twk', 'tiu', 'tkp'] as $type) {
            $ids = $snapshot[$type] ?? [];
            if (empty($ids)) continue;

            $pqs = PracticeQuestion::whereIn('id', $ids)->get()->keyBy('id');
            foreach ($ids as $qId) {
                $pq = $pqs->get($qId);
                if (!$pq) continue;

                $pq->number         = $number++;
                $pq->sub_test_type  = $type;
                $pq->sub_test_label = $subTestLabels[$type];

                $userAnswer     = $answers->get($pq->id);
                $pq->user_answer = $userAnswer ? strtoupper($userAnswer->answer) : null;

                if (!$pq->user_answer) {
                    $pq->status = 'kosong';
                    $stats['kosong']++;
                } elseif ($type === 'tkp') {
                    // TKP: any answer is "valid" — not graded as correct/wrong
                    $pq->status = 'benar';
                    $stats['benar']++;
                } else {
                    if ($pq->user_answer === strtoupper($pq->correct_answer)) {
                        $pq->status = 'benar';
                        $stats['benar']++;
                    } else {
                        $pq->status = 'salah';
                        $stats['salah']++;
                    }
                }
                $questions->push($pq);
            }
        }

        return view('peserta.skd.review', compact('skdResult', 'questions', 'stats'));
    }

    public function leaderboard(Request $request)
    {
        $packages         = SkdPackage::where('is_active', true)->get();
        $selectedPackageId = $request->get('package_id');

        $query = SkdResult::query()
            ->select('user_id', DB::raw('MAX(total_score) as best_score'), DB::raw('MAX(twk_score) as best_twk'), DB::raw('MAX(tiu_score) as best_tiu'), DB::raw('MAX(tkp_score) as best_tkp'))
            ->groupBy('user_id')
            ->orderByDesc('best_score');

        if ($selectedPackageId) {
            $query->where('skd_package_id', $selectedPackageId);
        }

        $rankings = $query->get();
        $userIds  = $rankings->pluck('user_id')->toArray();
        $users    = User::whereIn('id', $userIds)->get()->keyBy('id');

        $rank        = 1;
        $leaderboard = $rankings->map(function ($item) use ($users, &$rank) {
            $user = $users->get($item->user_id);
            return (object) [
                'rank'       => $rank++,
                'user_id'    => $item->user_id,
                'name'       => $user ? $user->name : 'Unknown',
                'best_score' => $item->best_score,
                'best_twk'   => $item->best_twk,
                'best_tiu'   => $item->best_tiu,
                'best_tkp'   => $item->best_tkp,
            ];
        });

        $myRank   = $leaderboard->firstWhere('user_id', auth()->id());
        $viewName = $request->get('standalone') ? 'peserta.skd.leaderboard' : 'peserta.skd.leaderboard-sidebar';

        return view($viewName, compact('leaderboard', 'packages', 'selectedPackageId', 'myRank'));
    }

    private function finishSession(SkdSession $skdSession)
    {
        $skdSession->update(['finished_at' => now(), 'status' => 'completed']);
        $result = (new ScoringService())->calculateSkdScore($skdSession);
        return redirect()->route('peserta.skd.results.show', $result)
            ->with('success', 'Simulasi SKD berhasil diselesaikan!');
    }

    /**
     * Generate question snapshot from PracticeQuestion bank for a given package.
     * Returns ['twk' => [id1,id2,...], 'tiu' => [...], 'tkp' => [...]]
     */
    private function generateSnapshot(SkdPackage $package): array
    {
        $sections = Section::with('subTopics')->get()->keyBy('slug');
        $snapshot = [];

        $config = [
            'twk' => $package->twk_question_count,
            'tiu' => $package->tiu_question_count,
            'tkp' => $package->tkp_question_count,
        ];

        foreach ($config as $slug => $count) {
            $section = $sections->get($slug);
            if (!$section) {
                $snapshot[$slug] = [];
                continue;
            }

            $subTopicIds = $section->subTopics->pluck('id');
            $query       = PracticeQuestion::whereIn('sub_topic_id', $subTopicIds);

            if ($package->randomize_questions) {
                $query->inRandomOrder();
            }

            $snapshot[$slug] = $query->limit($count)->pluck('id')->toArray();
        }

        return $snapshot;
    }
}
