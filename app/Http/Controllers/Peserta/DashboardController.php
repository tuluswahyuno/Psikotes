<?php

namespace App\Http\Controllers\Peserta;

use App\Http\Controllers\Controller;
use App\Models\TestAssignment;
use App\Models\Result;
use App\Models\SkdResult;
use App\Models\SkdSession;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        // Psikotes data
        $assignments = TestAssignment::with('test')
            ->where('user_id', $user->id)
            ->where('status', 'pending')
            ->get();
        $completedCount = Result::where('user_id', $user->id)->count();
        $pendingCount = $assignments->count();

        // SKD data
        $skdCompletedCount = SkdSession::where('user_id', $user->id)
            ->where('status', 'completed')
            ->count();

        // Latest SKD results for activity feed
        $latestSkdResults = SkdResult::with('package')
            ->where('user_id', $user->id)
            ->latest()
            ->take(5)
            ->get();

        // Best scores for progress bars
        $bestTwk = SkdResult::where('user_id', $user->id)->max('twk_score') ?? 0;
        $bestTiu = SkdResult::where('user_id', $user->id)->max('tiu_score') ?? 0;
        $bestTkp = SkdResult::where('user_id', $user->id)->max('tkp_score') ?? 0;

        // Progress percentages (TWK max 150, TIU max 175, TKP max 225)
        $twkPercent = $bestTwk > 0 ? min(100, round($bestTwk / 150 * 100)) : 0;
        $tiuPercent = $bestTiu > 0 ? min(100, round($bestTiu / 175 * 100)) : 0;
        $tkpPercent = $bestTkp > 0 ? min(100, round($bestTkp / 225 * 100)) : 0;

        return view('peserta.dashboard', compact(
            'assignments', 'completedCount', 'pendingCount',
            'skdCompletedCount', 'latestSkdResults',
            'bestTwk', 'bestTiu', 'bestTkp',
            'twkPercent', 'tiuPercent', 'tkpPercent'
        ));
    }
}
