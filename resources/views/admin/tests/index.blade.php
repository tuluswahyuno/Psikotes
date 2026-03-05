@extends('layouts.main')
@section('title', 'Kelola Tes')
@section('subtitle', 'Daftar semua tes psikotes')

@section('actions')
    <a href="{{ route('admin.tests.create') }}" class="btn-primary">
        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        Buat Tes Baru
    </a>
@endsection

@section('content')
<div class="table-container">
    <table class="w-full">
        <thead>
            <tr class="bg-gray-50 border-b border-gray-100">
                <th class="text-left px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Tes</th>
                <th class="text-left px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Kategori</th>
                <th class="text-left px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Tipe</th>
                <th class="text-center px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Soal</th>
                <th class="text-center px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Durasi</th>
                <th class="text-center px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                <th class="text-right px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-50">
            @forelse($tests as $test)
            <tr class="hover:bg-gray-50/50 transition-colors">
                <td class="px-6 py-4">
                    <p class="text-sm font-semibold text-gray-900">{{ $test->title }}</p>
                    <p class="text-xs text-gray-500 mt-0.5 line-clamp-1">{{ $test->description }}</p>
                </td>
                <td class="px-6 py-4">
                    <span class="text-xs font-medium bg-indigo-50 text-indigo-700 px-2.5 py-1 rounded-lg">{{ $test->category ?: '-' }}</span>
                </td>
                <td class="px-6 py-4">
                    <span class="text-xs font-medium text-gray-600">{{ ucfirst(str_replace('_', ' ', $test->type)) }}</span>
                </td>
                <td class="px-6 py-4 text-center">
                    <span class="text-sm font-bold text-gray-900">{{ $test->questions_count }}</span>
                </td>
                <td class="px-6 py-4 text-center">
                    <span class="text-sm text-gray-600">{{ $test->duration_minutes }} mnt</span>
                </td>
                <td class="px-6 py-4 text-center">
                    @if($test->is_active)
                        <span class="inline-flex items-center gap-1 text-xs font-medium text-emerald-700 bg-emerald-50 px-2.5 py-1 rounded-full">
                            <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full"></span> Aktif
                        </span>
                    @else
                        <span class="inline-flex items-center gap-1 text-xs font-medium text-gray-500 bg-gray-100 px-2.5 py-1 rounded-full">
                            <span class="w-1.5 h-1.5 bg-gray-400 rounded-full"></span> Nonaktif
                        </span>
                    @endif
                </td>
                <td class="px-6 py-4 text-right">
                    <div class="flex items-center justify-end gap-2">
                        <a href="{{ route('admin.tests.show', $test) }}" class="text-indigo-600 hover:text-indigo-800 p-1.5 rounded-lg hover:bg-indigo-50 transition-colors" title="Detail">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                        </a>
                        <a href="{{ route('admin.tests.edit', $test) }}" class="text-amber-600 hover:text-amber-800 p-1.5 rounded-lg hover:bg-amber-50 transition-colors" title="Edit">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                        </a>
                        <form action="{{ route('admin.tests.destroy', $test) }}" method="POST" onsubmit="return confirm('Yakin hapus tes ini?')">
                            @csrf @method('DELETE')
                            <button class="text-red-500 hover:text-red-700 p-1.5 rounded-lg hover:bg-red-50 transition-colors" title="Hapus">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="px-6 py-12 text-center text-gray-400">
                    <svg class="w-12 h-12 mx-auto mb-3 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                    <p class="text-sm">Belum ada tes. Mulai buat tes pertama!</p>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
    @if($tests->hasPages())
        <div class="px-6 py-4 border-t border-gray-100">{{ $tests->links() }}</div>
    @endif
</div>
@endsection
