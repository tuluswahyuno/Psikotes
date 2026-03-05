<?php

namespace App\Http\Controllers\Peserta;

use App\Http\Controllers\Controller;
use App\Models\TestAssignment;
use App\Models\Result;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $assignments = TestAssignment::with('test')
            ->where('user_id', $user->id)
            ->where('status', 'pending')
            ->get();

        $completedCount = Result::where('user_id', $user->id)->count();
        $pendingCount = $assignments->count();

        return view('peserta.dashboard', compact('assignments', 'completedCount', 'pendingCount'));
    }
}
