<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Test;
use App\Models\TestAssignment;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        $users = User::where('role', 'peserta')
            ->withCount(['testAssignments', 'results'])
            ->latest()
            ->paginate(10);
        return view('admin.users.index', compact('users'));
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
