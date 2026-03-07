<?php

namespace App\Http\Controllers\Peserta;

use App\Http\Controllers\Controller;
use App\Models\TestAssignment;
use App\Models\Result;
use App\Models\SkdResult;
use App\Models\SkdSession;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        // Psikotes data
        $assignments = TestAssignment::with('test')
            ->where('user_id', $user->id)
            ->where('status', 'pending')
            ->get();
        $completedCount = Result::where('user_id', $user->id)->count();
        $pendingCount = $assignments->count();

        // SKD data
        $skdCompletedCount = SkdSession::where('user_id', $user->id)
            ->where('status', 'completed')
            ->count();

        // Latest SKD results for activity feed
        $latestSkdResults = SkdResult::with('package')
            ->where('user_id', $user->id)
            ->latest()
            ->take(5)
            ->get();

        // Learning Progress from Belajar SKD
        $learningSections = \App\Models\Section::with('subTopics.materials')->get();
        $learningProgress = [];
        
        foreach ($learningSections as $section) {
            $totalMaterials = 0;
            $completedMaterials = 0;
            
            foreach ($section->subTopics as $subTopic) {
                $materialCount = $subTopic->materials->count();
                $totalMaterials += $materialCount;
                
                if ($materialCount > 0) {
                    $completedMaterials += \App\Models\UserMaterialProgress::where('user_id', $user->id)
                        ->whereIn('material_id', $subTopic->materials->pluck('id'))
                        ->whereNotNull('completed_at')
                        ->count();
                }
            }
            
            $progress = $totalMaterials > 0 ? round(($completedMaterials / $totalMaterials) * 100) : 0;
            
            $learningProgress[] = (object) [
                'name' => $section->name,
                'slug' => $section->slug,
                'color' => $section->color ?? 'blue',
                'progress' => $progress,
            ];
        }

        return view('peserta.dashboard', compact(
            'assignments', 'completedCount', 'pendingCount',
            'skdCompletedCount', 'latestSkdResults',
            'learningProgress'
        ));
    }
}
