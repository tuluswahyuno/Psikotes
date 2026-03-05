<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Test;
use App\Models\User;
use App\Models\Result;
use App\Models\TestSession;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // 1. Get stats for cards
        $stats = [
            'total_tests' => Test::count(),
            'active_tests' => Test::where('is_active', true)->count(),
            'total_peserta' => User::where('role', 'peserta')->count(),
            'total_sessions' => TestSession::where('status', 'completed')->count(),
            'recent_results' => Result::with(['user', 'test'])->latest()->take(5)->get(),
            'recent_peserta' => User::where('role', 'peserta')->latest()->take(5)->get(),
        ];

        // 2. Prepare Data for Line Chart (Test Activity Last 7 Days)
        $chartDates = [];
        $chartSessions = [];
        
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);
            $chartDates[] = $date->format('d M');
            $sessionsCount = TestSession::whereDate('created_at', $date)->count();
            $chartSessions[] = $sessionsCount;
        }

        $activityChart = [
            'labels' => $chartDates,
            'data' => $chartSessions,
        ];

        // 3. Prepare Data for Doughnut Chart (Score Distribution)
        // Group by total_score categories
        $excellent = Result::where('total_score', '>=', 90)->count();
        $good = Result::whereBetween('total_score', [75, 89])->count();
        $average = Result::whereBetween('total_score', [60, 74])->count();
        $poor = Result::where('total_score', '<', 60)->count();

        // If no results, put placeholder
        if (($excellent + $good + $average + $poor) === 0) {
            $scoreDistribution = [
                'labels' => ['Belum Ada Data'],
                'data' => [1],
                'colors' => ['#e5e7eb'] // gray
            ];
        } else {
             $scoreDistribution = [
                'labels' => ['Sangat Baik (90-100)', 'Baik (75-89)', 'Cukup (60-74)', 'Kurang (<60)'],
                'data' => [$excellent, $good, $average, $poor],
                'colors' => ['#10b981', '#3b82f6', '#f59e0b', '#ef4444'] // emerald, blue, amber, red
            ];
        }

        return view('admin.dashboard', compact('stats', 'activityChart', 'scoreDistribution'));
    }
}
