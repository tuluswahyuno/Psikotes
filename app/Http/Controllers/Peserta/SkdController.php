<?php

namespace App\Http\Controllers\Peserta;

use App\Http\Controllers\Controller;
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
        $packages = SkdPackage::where('is_active', true)
            ->with('packageTests.test')
            ->get();

        // Get user's completed sessions
        $completedPackageIds = SkdSession::where('user_id', auth()->id())
            ->where('status', 'completed')
            ->pluck('skd_package_id')
            ->toArray();

        // Get user's active sessions
        $activeSessions = SkdSession::where('user_id', auth()->id())
            ->where('status', 'in_progress')
            ->pluck('skd_package_id', 'id')
            ->toArray();

        return view('peserta.skd.index', compact('packages', 'completedPackageIds', 'activeSessions'));
    }

    public function show(SkdPackage $skdPackage)
    {
        $skdPackage->load('packageTests.test.questions');

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
            $session = SkdSession::create([
                'user_id' => $user->id,
                'skd_package_id' => $skdPackage->id,
                'started_at' => now(),
                'status' => 'in_progress',
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

        $skdPackage->load('packageTests.test.questions.options');

        // Organize questions by sub-test type
        $questionsByType = [];
        foreach ($skdPackage->packageTests as $pt) {
            $questions = $pt->test->questions->sortBy('order');
            $questionsByType[$pt->sub_test_type] = [
                'label' => $pt->sub_test_label,
                'passing_grade' => $pt->passing_grade,
                'questions' => $questions,
            ];
        }

        // Get existing answers with is_doubtful status
        $existingAnswers = $skdSession->answers()
            ->get(['question_id', 'option_id', 'is_doubtful'])
            ->keyBy('question_id')
            ->map(function($item) {
                return [
                    'option_id' => $item->option_id,
                    'is_doubtful' => (bool)$item->is_doubtful
                ];
            })
            ->toArray();

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
            'question_id' => 'required|exists:questions,id',
            'option_id' => 'nullable|exists:options,id',
            'is_doubtful' => 'nullable|boolean',
        ]);

        $updateData = [];
        if ($request->has('option_id')) {
            $updateData['option_id'] = $validated['option_id'];
        }
        if ($request->has('is_doubtful')) {
            $updateData['is_doubtful'] = $validated['is_doubtful'] ?? false;
        }

        if (!empty($updateData)) {
            \App\Models\SkdAnswer::updateOrCreate(
                ['skd_session_id' => $skdSession->id, 'question_id' => $validated['question_id']],
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
        $userId = auth()->id();
        $results = SkdResult::with(['package'])
            ->where('user_id', $userId)
            ->latest()
            ->paginate(10);

        // Sub-query data for the summary metrics
        $allResults = SkdResult::where('user_id', $userId)->orderBy('created_at', 'asc')->get();
        $totalSimulasi = $allResults->count();
        $skorTertinggi = $allResults->max('total_score') ?? 0;
        $lulusCount = $allResults->where('is_passed', true)->count();
        $rataKelulusan = $totalSimulasi > 0 ? round(($lulusCount / $totalSimulasi) * 100) : 0;

        // Visual trend data (last 7)
        $trendResults = $allResults->take(-7)->values();

        // Calculate trends
        $thisWeekCount = $allResults->where('created_at', '>=', now()->startOfWeek())->count();
        $trendTotal = "+{$thisWeekCount} minggu ini";

        $lastMonthMax = $allResults->where('created_at', '<', now()->startOfMonth())->max('total_score') ?? 0;
        $trendSkor = $skorTertinggi > $lastMonthMax && $lastMonthMax > 0 ? "+".($skorTertinggi - $lastMonthMax)." dari bulan lalu" : "Terbaik sejauh ini";

        $trendLulus = $rataKelulusan > 50 ? "+".($rataKelulusan - 50)."% di atas rata-rata" : "Tetap semangat";
        
        return view('peserta.skd.results', compact('results', 'totalSimulasi', 'skorTertinggi', 'rataKelulusan', 'trendResults', 'trendTotal', 'trendSkor', 'trendLulus'));
    }

    public function resultShow(SkdResult $skdResult)
    {
        if ($skdResult->user_id !== auth()->id()) {
            abort(403);
        }

        $skdResult->load(['package.packageTests.test', 'session.answers.question', 'session.answers.option']);

        return view('peserta.skd.result-detail', compact('skdResult'));
    }

    private function finishSession(SkdSession $skdSession)
    {
        $skdSession->update([
            'finished_at' => now(),
            'status' => 'completed',
        ]);

        $scoringService = new ScoringService();
        $result = $scoringService->calculateSkdScore($skdSession);

        return redirect()->route('peserta.skd.results.show', $result)
            ->with('success', 'Simulasi SKD berhasil diselesaikan!');
    }

    public function review(SkdResult $skdResult)
    {
        if ($skdResult->user_id !== auth()->id()) {
            abort(403);
        }

        $skdResult->load([
            'package.packageTests.test.questions.options',
            'session.answers'
        ]);

        $questions = collect();
        $answers = $skdResult->session->answers->keyBy('question_id');
        
        $number = 1;
        $stats = [
            'benar' => 0,
            'salah' => 0,
            'kosong' => 0,
        ];

        foreach ($skdResult->package->packageTests as $pt) {
            foreach ($pt->test->questions->sortBy('order') as $q) {
                $q->number = $number++;
                $q->sub_test_type = $pt->sub_test_type;
                $q->sub_test_label = $pt->sub_test_label;
                
                $userAnswer = $answers->get($q->id);
                $q->user_answer_id = $userAnswer ? $userAnswer->option_id : null;
                
                if (!$q->user_answer_id) {
                    $q->status = 'kosong';
                    $stats['kosong']++;
                } else {
                    $selectedOption = $q->options->where('id', $q->user_answer_id)->first();
                    if ($pt->sub_test_type == 'tkp') {
                        if ($selectedOption && $selectedOption->score == 5) {
                            $q->status = 'benar';
                            $stats['benar']++;
                        } else {
                            $q->status = 'salah'; 
                            $stats['salah']++;
                        }
                    } else {
                        if ($selectedOption && $selectedOption->is_correct) {
                            $q->status = 'benar';
                            $stats['benar']++;
                        } else {
                            $q->status = 'salah';
                            $stats['salah']++;
                        }
                    }
                }
                
                $questions->push($q);
            }
        }

        return view('peserta.skd.review', compact('skdResult', 'questions', 'stats'));
    }

    public function leaderboard(Request $request)
    {
        $packages = SkdPackage::where('is_active', true)->get();
        $selectedPackageId = $request->get('package_id');

        // Get the best (highest) total_score per user, optionally filtered by package
        $query = SkdResult::query()
            ->select('user_id', DB::raw('MAX(total_score) as best_score'),
                DB::raw('MAX(twk_score) as best_twk'),
                DB::raw('MAX(tiu_score) as best_tiu'),
                DB::raw('MAX(tkp_score) as best_tkp'))
            ->groupBy('user_id')
            ->orderByDesc('best_score');

        if ($selectedPackageId) {
            $query->where('skd_package_id', $selectedPackageId);
        }

        $rankings = $query->get();

        // Fetch user info and assign ranks
        $userIds = $rankings->pluck('user_id')->toArray();
        $users = User::whereIn('id', $userIds)->get()->keyBy('id');

        $rank = 1;
        $leaderboard = $rankings->map(function ($item) use ($users, &$rank) {
            $user = $users->get($item->user_id);
            return (object) [
                'rank' => $rank++,
                'user_id' => $item->user_id,
                'name' => $user ? $user->name : 'Unknown',
                'best_score' => $item->best_score,
                'best_twk' => $item->best_twk,
                'best_tiu' => $item->best_tiu,
                'best_tkp' => $item->best_tkp,
            ];
        });

        // Find current user's rank
        $myRank = $leaderboard->firstWhere('user_id', auth()->id());

        // If accessed from result page link, show standalone; otherwise use sidebar layout
        $viewName = $request->get('standalone') ? 'peserta.skd.leaderboard' : 'peserta.skd.leaderboard-sidebar';

        return view($viewName, compact('leaderboard', 'packages', 'selectedPackageId', 'myRank'));
    }
}
