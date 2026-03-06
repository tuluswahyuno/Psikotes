<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SkdPackage;
use App\Models\SkdPackageTest;
use App\Models\Test;
use Illuminate\Http\Request;

class SkdPackageController extends Controller
{
    public function index()
    {
        $packages = SkdPackage::with('packageTests.test')
            ->withCount('results')
            ->latest()
            ->paginate(10);

        return view('admin.skd.index', compact('packages'));
    }

    public function create()
    {
        $tests = Test::where('is_active', true)->get();
        return view('admin.skd.create', compact('tests'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'duration_minutes' => 'required|integer|min:1|max:300',
            'is_active' => 'boolean',
            'twk_test_id' => 'required|exists:tests,id',
            'tiu_test_id' => 'required|exists:tests,id',
            'tkp_test_id' => 'required|exists:tests,id',
            'twk_passing_grade' => 'required|integer|min:0',
            'tiu_passing_grade' => 'required|integer|min:0',
            'tkp_passing_grade' => 'required|integer|min:0',
        ]);

        $package = SkdPackage::create([
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'duration_minutes' => $validated['duration_minutes'],
            'is_active' => $request->boolean('is_active'),
        ]);

        $subTests = [
            'twk' => ['test_id' => $validated['twk_test_id'], 'passing_grade' => $validated['twk_passing_grade'], 'score_per_correct' => 5],
            'tiu' => ['test_id' => $validated['tiu_test_id'], 'passing_grade' => $validated['tiu_passing_grade'], 'score_per_correct' => 5],
            'tkp' => ['test_id' => $validated['tkp_test_id'], 'passing_grade' => $validated['tkp_passing_grade'], 'score_per_correct' => 5],
        ];

        foreach ($subTests as $type => $data) {
            SkdPackageTest::create([
                'skd_package_id' => $package->id,
                'test_id' => $data['test_id'],
                'sub_test_type' => $type,
                'passing_grade' => $data['passing_grade'],
                'score_per_correct' => $data['score_per_correct'],
            ]);
        }

        return redirect()->route('admin.skd-packages.show', $package)
            ->with('success', 'Paket SKD berhasil dibuat!');
    }

    public function show(SkdPackage $skdPackage)
    {
        $skdPackage->load(['packageTests.test.questions']);
        return view('admin.skd.show', compact('skdPackage'));
    }

    public function edit(SkdPackage $skdPackage)
    {
        $skdPackage->load('packageTests');
        $tests = Test::where('is_active', true)->get();
        return view('admin.skd.edit', compact('skdPackage', 'tests'));
    }

    public function update(Request $request, SkdPackage $skdPackage)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'duration_minutes' => 'required|integer|min:1|max:300',
            'is_active' => 'boolean',
            'twk_test_id' => 'required|exists:tests,id',
            'tiu_test_id' => 'required|exists:tests,id',
            'tkp_test_id' => 'required|exists:tests,id',
            'twk_passing_grade' => 'required|integer|min:0',
            'tiu_passing_grade' => 'required|integer|min:0',
            'tkp_passing_grade' => 'required|integer|min:0',
        ]);

        $skdPackage->update([
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'duration_minutes' => $validated['duration_minutes'],
            'is_active' => $request->boolean('is_active'),
        ]);

        // Update sub-tests
        $skdPackage->packageTests()->delete();

        $subTests = [
            'twk' => ['test_id' => $validated['twk_test_id'], 'passing_grade' => $validated['twk_passing_grade'], 'score_per_correct' => 5],
            'tiu' => ['test_id' => $validated['tiu_test_id'], 'passing_grade' => $validated['tiu_passing_grade'], 'score_per_correct' => 5],
            'tkp' => ['test_id' => $validated['tkp_test_id'], 'passing_grade' => $validated['tkp_passing_grade'], 'score_per_correct' => 5],
        ];

        foreach ($subTests as $type => $data) {
            SkdPackageTest::create([
                'skd_package_id' => $skdPackage->id,
                'test_id' => $data['test_id'],
                'sub_test_type' => $type,
                'passing_grade' => $data['passing_grade'],
                'score_per_correct' => $data['score_per_correct'],
            ]);
        }

        return redirect()->route('admin.skd-packages.show', $skdPackage)
            ->with('success', 'Paket SKD berhasil diperbarui!');
    }

    public function destroy(SkdPackage $skdPackage)
    {
        $skdPackage->delete();
        return redirect()->route('admin.skd-packages.index')
            ->with('success', 'Paket SKD berhasil dihapus!');
    }
}
