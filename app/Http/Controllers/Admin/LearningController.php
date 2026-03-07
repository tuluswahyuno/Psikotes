<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Section;
use App\Models\SubTopic;
use App\Models\Material;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class LearningController extends Controller
{
    // ─── Sections Hub ────────────────────────────────────────────────────────
    public function sections()
    {
        $sections = Section::withCount(['subTopics', 'subTopics as materials_count' => function ($q) {
            $q->join('materials', 'sub_topics.id', '=', 'materials.sub_topic_id');
        }])->get();

        return view('admin.learning.sections', compact('sections'));
    }

    // ─── Sub-Topics ──────────────────────────────────────────────────────────
    public function subTopics(Section $section)
    {
        $subTopics = $section->subTopics()->withCount('materials', 'practiceQuestions')->orderBy('order')->get();
        return view('admin.learning.sub-topics.index', compact('section', 'subTopics'));
    }

    public function createSubTopic(Section $section)
    {
        return view('admin.learning.sub-topics.form', compact('section'));
    }

    public function storeSubTopic(Request $request, Section $section)
    {
        $data = $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
            'order'       => 'nullable|integer',
        ]);

        $data['section_id'] = $section->id;
        $data['slug'] = Str::slug($data['title']);
        $data['order'] = $data['order'] ?? ($section->subTopics()->max('order') + 1);

        SubTopic::create($data);

        return redirect()->route('admin.learning.sub-topics', $section)
            ->with('success', 'Sub-topik berhasil ditambahkan!');
    }

    public function editSubTopic(Section $section, SubTopic $subTopic)
    {
        return view('admin.learning.sub-topics.form', compact('section', 'subTopic'));
    }

    public function updateSubTopic(Request $request, Section $section, SubTopic $subTopic)
    {
        $data = $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
            'order'       => 'nullable|integer',
        ]);

        $data['slug'] = Str::slug($data['title']);

        $subTopic->update($data);

        return redirect()->route('admin.learning.sub-topics', $section)
            ->with('success', 'Sub-topik berhasil diperbarui!');
    }

    public function destroySubTopic(Section $section, SubTopic $subTopic)
    {
        $subTopic->delete();
        return redirect()->route('admin.learning.sub-topics', $section)
            ->with('success', 'Sub-topik berhasil dihapus!');
    }

    // ─── Materials ───────────────────────────────────────────────────────────
    public function materials(Section $section, SubTopic $subTopic)
    {
        $materials = $subTopic->materials()->orderBy('order')->get();
        return view('admin.learning.materials.index', compact('section', 'subTopic', 'materials'));
    }

    public function createMaterial(Section $section, SubTopic $subTopic)
    {
        return view('admin.learning.materials.form', compact('section', 'subTopic'));
    }

    public function storeMaterial(Request $request, Section $section, SubTopic $subTopic)
    {
        $data = $request->validate([
            'title'   => 'required|string|max:255',
            'content' => 'required|string',
            'order'   => 'nullable|integer',
        ]);

        $data['sub_topic_id'] = $subTopic->id;
        $data['order'] = $data['order'] ?? ($subTopic->materials()->max('order') + 1);

        Material::create($data);

        return redirect()->route('admin.learning.materials', [$section, $subTopic])
            ->with('success', 'Materi berhasil ditambahkan!');
    }

    public function editMaterial(Section $section, SubTopic $subTopic, Material $material)
    {
        return view('admin.learning.materials.form', compact('section', 'subTopic', 'material'));
    }

    public function updateMaterial(Request $request, Section $section, SubTopic $subTopic, Material $material)
    {
        $data = $request->validate([
            'title'   => 'required|string|max:255',
            'content' => 'required|string',
            'order'   => 'nullable|integer',
        ]);

        $material->update($data);

        return redirect()->route('admin.learning.materials', [$section, $subTopic])
            ->with('success', 'Materi berhasil diperbarui!');
    }

    public function destroyMaterial(Section $section, SubTopic $subTopic, Material $material)
    {
        $material->delete();
        return redirect()->route('admin.learning.materials', [$section, $subTopic])
            ->with('success', 'Materi berhasil dihapus!');
    }
}
