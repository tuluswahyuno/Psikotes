<?php

namespace App\Http\Controllers\Peserta;

use App\Http\Controllers\Controller;
use App\Models\Section;
use App\Models\SubTopic;
use App\Models\Material;
use App\Models\UserMaterialProgress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LearningController extends Controller
{
    /**
     * Hub utama: tampilkan 3 section SKD + overview progress
     */
    public function index()
    {
        $user = Auth::user();
        $sections = Section::with('subTopics.materials')->get();

        // Calculate progress per section
        $sectionProgress = [];
        foreach ($sections as $section) {
            $totalMaterials = 0;
            $completedMaterials = 0;
            $totalQuestions = 0;

            foreach ($section->subTopics as $subTopic) {
                $materialCount = $subTopic->materials->count();
                $totalMaterials += $materialCount;
                $totalQuestions += $subTopic->practiceQuestions()->count();

                $completedMaterials += UserMaterialProgress::where('user_id', $user->id)
                    ->whereIn('material_id', $subTopic->materials->pluck('id'))
                    ->whereNotNull('completed_at')
                    ->count();
            }

            $practiceAttempts = $user->practiceAttempts()
                ->whereIn('sub_topic_id', $section->subTopics->pluck('id'))
                ->count();

            $bestScore = $user->practiceAttempts()
                ->whereIn('sub_topic_id', $section->subTopics->pluck('id'))
                ->max('score') ?? 0;

            $progress = $totalMaterials > 0 ? round(($completedMaterials / $totalMaterials) * 100) : 0;

            $sectionProgress[$section->id] = [
                'total_materials' => $totalMaterials,
                'completed_materials' => $completedMaterials,
                'total_questions' => $totalQuestions,
                'practice_attempts' => $practiceAttempts,
                'best_score' => $bestScore,
                'progress' => $progress,
            ];
        }

        return view('peserta.learn.index', compact('sections', 'sectionProgress'));
    }

    /**
     * Halaman section: daftar sub-topik + progress per sub-topik
     */
    public function section(Section $section)
    {
        $user = Auth::user();
        $section->load('subTopics.materials');

        $subTopicProgress = [];
        foreach ($section->subTopics as $subTopic) {
            $totalMaterials = $subTopic->materials->count();
            $completedMaterials = UserMaterialProgress::where('user_id', $user->id)
                ->whereIn('material_id', $subTopic->materials->pluck('id'))
                ->whereNotNull('completed_at')
                ->count();

            $questionsCount = $subTopic->practiceQuestions()->count();
            $attempts = $user->practiceAttempts()->where('sub_topic_id', $subTopic->id)->count();
            $bestScore = $user->practiceAttempts()->where('sub_topic_id', $subTopic->id)->max('score') ?? 0;

            $materialProgress = $totalMaterials > 0 ? round(($completedMaterials / $totalMaterials) * 100) : 0;

            $subTopicProgress[$subTopic->id] = [
                'total_materials' => $totalMaterials,
                'completed_materials' => $completedMaterials,
                'material_progress' => $materialProgress,
                'questions_count' => $questionsCount,
                'attempts' => $attempts,
                'best_score' => $bestScore,
                'is_material_complete' => $totalMaterials > 0 && $completedMaterials >= $totalMaterials,
            ];
        }

        return view('peserta.learn.section', compact('section', 'subTopicProgress'));
    }

    /**
     * Halaman materi: tampilkan konten materi belajar
     */
    public function material(Section $section, SubTopic $subTopic)
    {
        $user = Auth::user();

        // Ensure the sub-topic belongs to this section
        if ($subTopic->section_id !== $section->id) {
            abort(404);
        }

        $materials = $subTopic->materials()->orderBy('order')->get();

        // Get completion status for each material
        $completedIds = UserMaterialProgress::where('user_id', $user->id)
            ->whereIn('material_id', $materials->pluck('id'))
            ->whereNotNull('completed_at')
            ->pluck('material_id')
            ->toArray();

        // Navigation: previous and next sub-topic
        $allSubTopics = $section->subTopics()->orderBy('order')->get();
        $currentIndex = $allSubTopics->search(fn($st) => $st->id === $subTopic->id);
        $prevSubTopic = $currentIndex > 0 ? $allSubTopics[$currentIndex - 1] : null;
        $nextSubTopic = $currentIndex < $allSubTopics->count() - 1 ? $allSubTopics[$currentIndex + 1] : null;

        $questionsCount = $subTopic->practiceQuestions()->count();

        return view('peserta.learn.material', compact(
            'section', 'subTopic', 'materials', 'completedIds',
            'prevSubTopic', 'nextSubTopic', 'questionsCount'
        ));
    }

    /**
     * Tandai materi selesai
     */
    public function completeMaterial(Request $request)
    {
        $request->validate([
            'material_id' => 'required|exists:materials,id',
        ]);

        $user = Auth::user();

        UserMaterialProgress::updateOrCreate(
            [
                'user_id' => $user->id,
                'material_id' => $request->material_id,
            ],
            [
                'completed_at' => now(),
            ]
        );

        return back()->with('success', 'Materi berhasil ditandai selesai!');
    }
}
