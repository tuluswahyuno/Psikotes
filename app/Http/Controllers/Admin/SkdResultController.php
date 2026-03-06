<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SkdResult;

class SkdResultController extends Controller
{
    public function index()
    {
        $results = SkdResult::with(['user', 'package'])
            ->latest()
            ->paginate(15);

        return view('admin.skd.results.index', compact('results'));
    }

    public function show(SkdResult $skdResult)
    {
        $skdResult->load(['user', 'package.packageTests.test', 'session.answers.question', 'session.answers.option']);
        return view('admin.skd.results.show', compact('skdResult'));
    }
}
