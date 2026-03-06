<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Test;
use App\Models\User;
use App\Models\Result;
use App\Models\TestSession;
use App\Models\SkdPackage;
use App\Models\SkdSession;
use App\Models\SkdResult;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // ============ STAT CARDS ============
        $stats = [
            // Psikotes
            'total_tests'       => Test::count(),
            'active_tests'      => Test::where('is_active', true)->count(),
            // SKD
            'total_skd'         => SkdPackage::count(),
            'active_skd'        => SkdPackage::where('is_active', true)->count(),
            // Peserta
            'total_peserta'     => User::where('role', 'peserta')->count(),
            // Sessions
            'psikotes_sessions' => TestSession::where('status', 'completed')->count(),
            'skd_sessions'      => SkdSession::where('status', 'completed')->count(),
            // Kelulusan SKD
            'skd_passed'        => SkdResult::where('is_passed', true)->count(),
            'skd_total_results' => SkdResult::count(),
        ];

        // Recent data
        $recentSkdResults = SkdResult::with(['user', 'package'])->latest()->take(5)->get();
        $recentPeserta    = User::where('role', 'peserta')->latest()->take(5)->get();

        // ============ LINE CHART — Aktivitas 7 Hari ============
        $chartDates = [];
        $chartPsikotes = [];
        $chartSkd = [];

        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);
            $chartDates[] = $date->format('d M');
            $chartPsikotes[] = TestSession::whereDate('created_at', $date)->count();
            $chartSkd[]      = SkdSession::whereDate('created_at', $date)->count();
        }

        $activityChart = [
            'labels'   => $chartDates,
            'psikotes' => $chartPsikotes,
            'skd'      => $chartSkd,
        ];

        // ============ DOUGHNUT CHART — Kelulusan SKD ============
        $skdPassed = SkdResult::where('is_passed', true)->count();
        $skdFailed = SkdResult::where('is_passed', false)->count();

        if (($skdPassed + $skdFailed) === 0) {
            $skdDistribution = [
                'labels' => ['Belum Ada Data'],
                'data'   => [1],
                'colors' => ['#e5e7eb'],
            ];
        } else {
            $skdDistribution = [
                'labels' => ['Lulus SKD', 'Tidak Lulus SKD'],
                'data'   => [$skdPassed, $skdFailed],
                'colors' => ['#10b981', '#ef4444'],
            ];
        }

        return view('admin.dashboard', compact(
            'stats',
            'recentSkdResults',
            'recentPeserta',
            'activityChart',
            'skdDistribution'
        ));
    }
}
