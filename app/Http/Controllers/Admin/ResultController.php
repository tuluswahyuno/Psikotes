<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Result;
use App\Models\Test;
use Illuminate\Http\Request;

class ResultController extends Controller
{
    public function index(Request $request)
    {
        $query = Result::with(['user', 'test', 'session']);

        if ($request->filled('test_id')) {
            $query->where('test_id', $request->test_id);
        }

        if ($request->filled('search')) {
            $query->whereHas('user', function ($q) use ($request) {
                $q->where('name', 'like', "%{$request->search}%");
            });
        }

        $results = $query->latest()->paginate(10);
        $tests = Test::all();

        return view('admin.results.index', compact('results', 'tests'));
    }

    public function show(Result $result)
    {
        $result->load(['user', 'test', 'session.answers.question', 'session.answers.option']);
        return view('admin.results.show', compact('result'));
    }
}
