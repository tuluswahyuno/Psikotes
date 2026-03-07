<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Test;
use Illuminate\Http\Request;

class TestController extends Controller
{
    public function index(Request $request)
    {
        $query = Test::withCount('questions')->latest();

        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('type', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        if ($request->filled('status')) {
            $query->where('is_active', $request->status);
        }

        $tests = $query->paginate(10)->withQueryString();
        
        return view('admin.tests.index', compact('tests'));
    }

    public function create()
    {
        return view('admin.tests.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'duration_minutes' => 'required|integer|min:1|max:300',
            'type' => 'required|in:multiple_choice,likert,personality',
            'is_active' => 'boolean',
            'randomize_questions' => 'boolean',
        ]);

        $validated['is_active'] = $request->boolean('is_active');
        $validated['randomize_questions'] = $request->boolean('randomize_questions');

        $test = Test::create($validated);

        return redirect()->route('admin.tests.show', $test)
            ->with('success', 'Tes berhasil dibuat!');
    }

    public function show(Test $test)
    {
        $test->load(['questions.options']);
        return view('admin.tests.show', compact('test'));
    }

    public function edit(Test $test)
    {
        return view('admin.tests.edit', compact('test'));
    }

    public function update(Request $request, Test $test)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'duration_minutes' => 'required|integer|min:1|max:300',
            'type' => 'required|in:multiple_choice,likert,personality',
            'is_active' => 'boolean',
            'randomize_questions' => 'boolean',
        ]);

        $validated['is_active'] = $request->boolean('is_active');
        $validated['randomize_questions'] = $request->boolean('randomize_questions');

        $test->update($validated);

        return redirect()->route('admin.tests.show', $test)
            ->with('success', 'Tes berhasil diperbarui!');
    }

    public function destroy(Test $test)
    {
        $test->delete();
        return redirect()->route('admin.tests.index')
            ->with('success', 'Tes berhasil dihapus!');
    }
}
