<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PracticeQuestion;
use App\Models\Section;
use App\Models\SkdPackage;
use Illuminate\Http\Request;

class SkdPackageController extends Controller
{
    public function index(Request $request)
    {
        $query = SkdPackage::withCount('results');

        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%');
        }
        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active');
        }

        $packages = $query->latest()->paginate(10)->withQueryString();

        $stats = [
            'total'        => SkdPackage::count(),
            'active'       => SkdPackage::where('is_active', true)->count(),
            'participants' => \App\Models\SkdSession::where('status', 'completed')->distinct('user_id')->count('user_id'),
        ];

        // Count available questions per sub-test (for display)
        $availableQuestions = $this->getAvailableCount();

        return view('admin.skd.index', compact('packages', 'stats', 'availableQuestions'));
    }

    public function create()
    {
        $availableQuestions = $this->getAvailableCount();
        return view('admin.skd.create', compact('availableQuestions'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'               => 'required|string|max:255',
            'description'         => 'nullable|string',
            'duration_minutes'    => 'required|integer|min:1|max:300',
            'is_active'           => 'boolean',
            'randomize_questions' => 'boolean',
            'twk_question_count'  => 'required|integer|min:1',
            'tiu_question_count'  => 'required|integer|min:1',
            'tkp_question_count'  => 'required|integer|min:1',
            'twk_passing_grade'   => 'required|integer|min:0',
            'tiu_passing_grade'   => 'required|integer|min:0',
            'tkp_passing_grade'   => 'required|integer|min:0',
        ]);

        $package = SkdPackage::create([
            'title'               => $validated['title'],
            'description'         => $validated['description'] ?? null,
            'duration_minutes'    => $validated['duration_minutes'],
            'is_active'           => $request->boolean('is_active'),
            'randomize_questions' => $request->boolean('randomize_questions', true),
            'twk_question_count'  => $validated['twk_question_count'],
            'tiu_question_count'  => $validated['tiu_question_count'],
            'tkp_question_count'  => $validated['tkp_question_count'],
            'twk_passing_grade'   => $validated['twk_passing_grade'],
            'tiu_passing_grade'   => $validated['tiu_passing_grade'],
            'tkp_passing_grade'   => $validated['tkp_passing_grade'],
        ]);

        return redirect()->route('admin.skd-packages.show', $package)
            ->with('success', 'Paket SKD berhasil dibuat!');
    }

    public function show(SkdPackage $skdPackage)
    {
        $skdPackage->loadCount('results');
        $availableQuestions = $this->getAvailableCount();
        return view('admin.skd.show', compact('skdPackage', 'availableQuestions'));
    }

    public function edit(SkdPackage $skdPackage)
    {
        $availableQuestions = $this->getAvailableCount();
        return view('admin.skd.edit', compact('skdPackage', 'availableQuestions'));
    }

    public function update(Request $request, SkdPackage $skdPackage)
    {
        $validated = $request->validate([
            'title'               => 'required|string|max:255',
            'description'         => 'nullable|string',
            'duration_minutes'    => 'required|integer|min:1|max:300',
            'is_active'           => 'boolean',
            'randomize_questions' => 'boolean',
            'twk_question_count'  => 'required|integer|min:1',
            'tiu_question_count'  => 'required|integer|min:1',
            'tkp_question_count'  => 'required|integer|min:1',
            'twk_passing_grade'   => 'required|integer|min:0',
            'tiu_passing_grade'   => 'required|integer|min:0',
            'tkp_passing_grade'   => 'required|integer|min:0',
        ]);

        $skdPackage->update([
            'title'               => $validated['title'],
            'description'         => $validated['description'] ?? null,
            'duration_minutes'    => $validated['duration_minutes'],
            'is_active'           => $request->boolean('is_active'),
            'randomize_questions' => $request->boolean('randomize_questions', true),
            'twk_question_count'  => $validated['twk_question_count'],
            'tiu_question_count'  => $validated['tiu_question_count'],
            'tkp_question_count'  => $validated['tkp_question_count'],
            'twk_passing_grade'   => $validated['twk_passing_grade'],
            'tiu_passing_grade'   => $validated['tiu_passing_grade'],
            'tkp_passing_grade'   => $validated['tkp_passing_grade'],
        ]);

        return redirect()->route('admin.skd-packages.show', $skdPackage)
            ->with('success', 'Paket SKD berhasil diperbarui!');
    }

    public function destroy(SkdPackage $skdPackage)
    {
        $skdPackage->delete();
        return redirect()->route('admin.skd-packages.index')
            ->with('success', 'Paket SKD berhasil dihapus!');
    }

    /**
     * Get count of available PracticeQuestions per SKD sub-test (TWK/TIU/TKP).
     */
    private function getAvailableCount(): array
    {
        $sections = Section::with('subTopics')->get()->keyBy('slug');
        $result   = [];

        foreach (['twk', 'tiu', 'tkp'] as $slug) {
            $section = $sections->get($slug);
            if ($section) {
                $subTopicIds      = $section->subTopics->pluck('id');
                $result[$slug]    = PracticeQuestion::whereIn('sub_topic_id', $subTopicIds)->count();
            } else {
                $result[$slug] = 0;
            }
        }
        return $result;
    }
}
