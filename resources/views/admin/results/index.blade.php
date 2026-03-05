@extends('layouts.main')
@section('title', 'Hasil & Laporan')
@section('subtitle', 'Semua hasil tes peserta')

@section('content')
<!-- Filter -->
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 mb-6">
    <form method="GET" class="flex items-end gap-4">
        <div class="flex-1">
            <label class="form-label">Cari Peserta</label>
            <input type="text" name="search" value="{{ request('search') }}" class="form-input" placeholder="Nama peserta...">
        </div>
        <div class="w-48">
            <label class="form-label">Filter Tes</label>
            <select name="test_id" class="form-input">
                <option value="">Semua Tes</option>
                @foreach($tests as $test)
                    <option value="{{ $test->id }}" {{ request('test_id') == $test->id ? 'selected' : '' }}>{{ $test->title }}</option>
                @endforeach
            </select>
        </div>
        <button type="submit" class="btn-primary">Filter</button>
    </form>
</div>

<div class="table-container">
    <table class="w-full">
        <thead>
            <tr class="bg-gray-50 border-b border-gray-100">
                <th class="text-left px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Peserta</th>
                <th class="text-left px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Tes</th>
                <th class="text-center px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Skor</th>
                <th class="text-center px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Interpretasi</th>
                <th class="text-center px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Tanggal</th>
                <th class="text-right px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-50">
            @forelse($results as $result)
            <tr class="hover:bg-gray-50/50 transition-colors">
                <td class="px-6 py-4">
                    <p class="text-sm font-semibold text-gray-900">{{ $result->user->name }}</p>
                    <p class="text-xs text-gray-500">{{ $result->user->email }}</p>
                </td>
                <td class="px-6 py-4">
                    <span class="text-sm text-gray-700">{{ $result->test->title }}</span>
                </td>
                <td class="px-6 py-4 text-center">
                    <span class="text-lg font-bold {{ $result->total_score >= 75 ? 'text-emerald-600' : ($result->total_score >= 60 ? 'text-amber-600' : 'text-red-600') }}">
                        {{ $result->total_score }}
                    </span>
                </td>
                <td class="px-6 py-4 text-center">
                    <span class="text-xs font-medium px-2.5 py-1 rounded-full {{ $result->total_score >= 75 ? 'bg-emerald-50 text-emerald-700' : ($result->total_score >= 60 ? 'bg-amber-50 text-amber-700' : 'bg-red-50 text-red-700') }}">
                        {{ $result->interpretation }}
                    </span>
                </td>
                <td class="px-6 py-4 text-center text-sm text-gray-500">
                    {{ $result->created_at->format('d M Y H:i') }}
                </td>
                <td class="px-6 py-4 text-right">
                    <a href="{{ route('admin.results.show', $result) }}" class="text-indigo-600 hover:bg-indigo-50 p-1.5 rounded-lg transition-colors inline-block">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                    </a>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="px-6 py-12 text-center text-gray-400 text-sm">Belum ada hasil tes</td>
            </tr>
            @endforelse
        </tbody>
    </table>
    @if($results->hasPages())
        <div class="px-6 py-4 border-t border-gray-100">{{ $results->links() }}</div>
    @endif
</div>
@endsection
