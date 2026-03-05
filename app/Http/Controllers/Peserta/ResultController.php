<?php

namespace App\Http\Controllers\Peserta;

use App\Http\Controllers\Controller;
use App\Models\Result;

class ResultController extends Controller
{
    public function index()
    {
        $results = Result::with(['test', 'session'])
            ->where('user_id', auth()->id())
            ->latest()
            ->paginate(10);

        return view('peserta.results.index', compact('results'));
    }

    public function show(Result $result)
    {
        if ($result->user_id !== auth()->id()) {
            abort(403);
        }

        $result->load(['test', 'session.answers.question', 'session.answers.option']);

        return view('peserta.results.show', compact('result'));
    }
}
