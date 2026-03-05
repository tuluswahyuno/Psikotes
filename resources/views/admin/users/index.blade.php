@extends('layouts.main')
@section('title', 'Kelola Peserta')
@section('subtitle', 'Daftar peserta dan assign tes')

@section('actions')
    <a href="{{ route('admin.users.create') }}" class="btn-primary">
        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        Tambah Peserta
    </a>
@endsection

@section('content')
<div class="table-container">
    <table class="w-full">
        <thead>
            <tr class="bg-gray-50 border-b border-gray-100">
                <th class="text-left px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Peserta</th>
                <th class="text-center px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Tes Assigned</th>
                <th class="text-center px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Tes Selesai</th>
                <th class="text-right px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-50">
            @forelse($users as $user)
            <tr class="hover:bg-gray-50/50 transition-colors">
                <td class="px-6 py-4">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 bg-gradient-to-br from-emerald-400 to-teal-500 rounded-lg flex items-center justify-center text-white text-xs font-bold">
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-gray-900">{{ $user->name }}</p>
                            <p class="text-xs text-gray-500">{{ $user->email }}</p>
                        </div>
                    </div>
                </td>
                <td class="px-6 py-4 text-center">
                    <span class="text-sm font-bold text-gray-900">{{ $user->test_assignments_count }}</span>
                </td>
                <td class="px-6 py-4 text-center">
                    <span class="text-sm font-bold text-gray-900">{{ $user->results_count }}</span>
                </td>
                <td class="px-6 py-4 text-right">
                    <div class="flex items-center justify-end gap-2">
                        <a href="{{ route('admin.users.assign', $user) }}" class="text-indigo-600 hover:bg-indigo-50 p-1.5 rounded-lg transition-colors" title="Assign Tes">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                        </a>
                        <a href="{{ route('admin.users.edit', $user) }}" class="text-amber-600 hover:bg-amber-50 p-1.5 rounded-lg transition-colors" title="Edit">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                        </a>
                        <form action="{{ route('admin.users.destroy', $user) }}" method="POST" onsubmit="return confirm('Yakin hapus peserta ini?')">
                            @csrf @method('DELETE')
                            <button class="text-red-500 hover:bg-red-50 p-1.5 rounded-lg transition-colors" title="Hapus">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="4" class="px-6 py-12 text-center text-gray-400 text-sm">Belum ada peserta</td>
            </tr>
            @endforelse
        </tbody>
    </table>
    @if($users->hasPages())
        <div class="px-6 py-4 border-t border-gray-100">{{ $users->links() }}</div>
    @endif
</div>
@endsection
