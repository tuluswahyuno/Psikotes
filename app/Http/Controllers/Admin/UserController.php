<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Test;
use App\Models\TestAssignment;
use App\Models\Result;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::where('role', 'peserta')
            ->withCount(['testAssignments', 'results'])
            ->withAvg('results', 'score_percentage');

        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }

        $users = $query->latest()->paginate(10)->withQueryString();

        // Calculate Stats
        $stats = [
            'total' => User::where('role', 'peserta')->count(),
            'active_today' => Result::whereDate('created_at', today())->distinct('user_id')->count('user_id'),
            'new_registrations' => User::where('role', 'peserta')->where('created_at', '>=', now()->subDays(7))->count(),
            'high_performers' => User::where('role', 'peserta')->whereHas('results')->withAvg('results', 'score_percentage')->get()->filter(fn($u) => $u->results_avg_score_percentage >= 85)->count(),
            'mid_performers' => User::where('role', 'peserta')->whereHas('results')->withAvg('results', 'score_percentage')->get()->filter(fn($u) => $u->results_avg_score_percentage >= 60 && $u->results_avg_score_percentage < 85)->count(),
            'low_performers' => User::where('role', 'peserta')->whereHas('results')->withAvg('results', 'score_percentage')->get()->filter(fn($u) => $u->results_avg_score_percentage < 60)->count(),
        ];

        return view('admin.users.index', compact('users', 'stats'));
    }

    public function create()
    {
        return view('admin.users.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
        ]);

        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => bcrypt($validated['password']),
            'role' => 'peserta',
        ]);

        return redirect()->route('admin.users.index')
            ->with('success', 'Peserta berhasil ditambahkan!');
    }

    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        $user->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
        ]);

        if (!empty($validated['password'])) {
            $user->update(['password' => bcrypt($validated['password'])]);
        }

        return redirect()->route('admin.users.index')
            ->with('success', 'Data peserta berhasil diperbarui!');
    }

    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('admin.users.index')
            ->with('success', 'Peserta berhasil dihapus!');
    }

    public function assign(User $user)
    {
        $tests = Test::where('is_active', true)->get();
        $assignedTestIds = $user->testAssignments()->pluck('test_id')->toArray();
        return view('admin.users.assign', compact('user', 'tests', 'assignedTestIds'));
    }

    public function storeAssignment(Request $request, User $user)
    {
        $validated = $request->validate([
            'test_ids' => 'required|array',
            'test_ids.*' => 'exists:tests,id',
            'deadline' => 'nullable|date|after:now',
        ]);

        foreach ($validated['test_ids'] as $testId) {
            TestAssignment::updateOrCreate(
                ['user_id' => $user->id, 'test_id' => $testId],
                ['deadline' => $validated['deadline'] ?? null, 'status' => 'pending']
            );
        }

        return redirect()->route('admin.users.index')
            ->with('success', 'Tes berhasil di-assign ke peserta!');
    }
}
