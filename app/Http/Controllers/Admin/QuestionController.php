<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Question;
use App\Models\Option;
use App\Models\Test;
use Illuminate\Http\Request;

class QuestionController extends Controller
{
    public function create(Test $test)
    {
        return view('admin.questions.create', compact('test'));
    }

    public function store(Request $request, Test $test)
    {
        $validated = $request->validate([
            'question_text' => 'required|string',
            'type' => 'required|in:choice,scale',
            'weight' => 'required|integer|min:1',
            'options' => 'required|array|min:2',
            'options.*.option_text' => 'required|string',
            'options.*.score' => 'required|integer',
            'options.*.is_correct' => 'nullable|boolean',
        ]);

        $maxOrder = $test->questions()->max('order') ?? 0;

        $question = $test->questions()->create([
            'question_text' => $validated['question_text'],
            'type' => $validated['type'],
            'weight' => $validated['weight'],
            'order' => $maxOrder + 1,
        ]);

        foreach ($validated['options'] as $i => $optData) {
            $question->options()->create([
                'option_text' => $optData['option_text'],
                'score' => $optData['score'],
                'is_correct' => isset($optData['is_correct']) ? (bool) $optData['is_correct'] : false,
                'order' => $i + 1,
            ]);
        }

        return redirect()->route('admin.tests.show', $test)
            ->with('success', 'Soal berhasil ditambahkan!');
    }

    public function edit(Test $test, Question $question)
    {
        $question->load('options');
        return view('admin.questions.edit', compact('test', 'question'));
    }

    public function update(Request $request, Test $test, Question $question)
    {
        $validated = $request->validate([
            'question_text' => 'required|string',
            'type' => 'required|in:choice,scale',
            'weight' => 'required|integer|min:1',
            'options' => 'required|array|min:2',
            'options.*.id' => 'nullable|integer',
            'options.*.option_text' => 'required|string',
            'options.*.score' => 'required|integer',
            'options.*.is_correct' => 'nullable|boolean',
        ]);

        $question->update([
            'question_text' => $validated['question_text'],
            'type' => $validated['type'],
            'weight' => $validated['weight'],
        ]);

        // Delete old options and recreate
        $question->options()->delete();

        foreach ($validated['options'] as $i => $optData) {
            $question->options()->create([
                'option_text' => $optData['option_text'],
                'score' => $optData['score'],
                'is_correct' => isset($optData['is_correct']) ? (bool) $optData['is_correct'] : false,
                'order' => $i + 1,
            ]);
        }

        return redirect()->route('admin.tests.show', $test)
            ->with('success', 'Soal berhasil diperbarui!');
    }

    public function destroy(Test $test, Question $question)
    {
        $question->delete();
        return redirect()->route('admin.tests.show', $test)
            ->with('success', 'Soal berhasil dihapus!');
    }
}
