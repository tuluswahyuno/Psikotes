<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SkdResult;

class SkdResultController extends Controller
{
    public function index(\Illuminate\Http\Request $request)
    {
        $query = SkdResult::with(['user', 'package']);

        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->whereHas('user', function($uQ) use ($request) {
                    $uQ->where('name', 'like', '%' . $request->search . '%')
                      ->orWhere('email', 'like', '%' . $request->search . '%');
                })->orWhereHas('package', function($pQ) use ($request) {
                    $pQ->where('title', 'like', '%' . $request->search . '%');
                });
            });
        }

        if ($request->filled('package_id')) {
            $query->where('skd_package_id', $request->package_id);
        }

        if ($request->filled('status')) {
            if ($request->status === 'lulus') {
                $query->where('is_passed', true);
            } elseif ($request->status === 'tidak_lulus') {
                $query->where('is_passed', false);
            }
        }

        $results = $query->latest()->paginate(10)->withQueryString();

        $totalResults = SkdResult::count();
        $stats = [
            'today' => SkdResult::whereDate('created_at', today())->count(),
            'pass_rate' => $totalResults > 0 ? round((SkdResult::where('is_passed', true)->count() / $totalResults) * 100, 1) : 0.0,
            'active_participants' => SkdResult::distinct('user_id')->count('user_id'),
            'highest_score' => SkdResult::max('total_score') ?? 0,
        ];

        $packages = \App\Models\SkdPackage::select('id', 'title')->get();

        return view('admin.skd.results.index', compact('results', 'stats', 'packages'));
    }

    public function show(SkdResult $skdResult)
    {
        $skdResult->load(['user', 'package.packageTests.test', 'session.answers.question', 'session.answers.option']);
        return view('admin.skd.results.show', compact('skdResult'));
    }
}
