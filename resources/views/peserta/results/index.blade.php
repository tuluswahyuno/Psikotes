@extends('layouts.main')
@section('title', 'Hasil Tes Saya')
@section('subtitle', 'Riwayat semua tes yang sudah dikerjakan')

@section('content')
<div class="table-container">
    <table class="w-full">
        <thead>
            <tr class="bg-gray-50 border-b border-gray-100">
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
                    <p class="text-sm font-semibold text-gray-900">{{ $result->test->title }}</p>
                    <p class="text-xs text-gray-500">{{ $result->test->category }}</p>
                </td>
                <td class="px-6 py-4 text-center">
                    <span class="text-xl font-bold {{ $result->total_score >= 75 ? 'text-emerald-600' : ($result->total_score >= 60 ? 'text-amber-600' : 'text-red-600') }}">
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
                    <a href="{{ route('peserta.results.show', $result) }}" class="btn-secondary text-xs">Lihat Detail</a>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="px-6 py-12 text-center text-gray-400 text-sm">Belum ada hasil tes</td>
            </tr>
            @endforelse
        </tbody>
    </table>
    @if($results->hasPages())
        <div class="px-6 py-4 border-t border-gray-100">{{ $results->links() }}</div>
    @endif
</div>
@endsection
