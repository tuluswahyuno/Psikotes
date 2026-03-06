@extends('layouts.main')
@section('title', 'Riwayat Hasil SKD')
@section('subtitle', 'Daftar skor simulasi SKD seluruh peserta')

@section('content')
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="p-6 border-b border-gray-50 bg-gray-50/30 flex justify-between items-center">
        <h3 class="text-sm font-bold text-gray-900 uppercase tracking-wider">Laporan Hasil CAT</h3>
        <div class="flex gap-2">
             <!-- Future: Export functionality could go here -->
        </div>
    </div>
    <table class="w-full text-left border-collapse">
        <thead>
            <tr class="bg-gray-50/50 border-b border-gray-100">
                <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Peserta</th>
                <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Paket SKD</th>
                <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider text-center">TWK</th>
                <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider text-center">TIU</th>
                <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider text-center">TKP</th>
                <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider text-center">Total</th>
                <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider text-center">Status</th>
                <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider text-right">Tgl Ujian</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse($results as $result)
            <tr class="hover:bg-gray-50/50 transition-colors cursor-pointer" onclick="window.location='{{ route('admin.skd-results.show', $result) }}'">
                <td class="px-6 py-4">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-lg bg-indigo-100 text-indigo-700 flex items-center justify-center text-[10px] font-bold">
                            {{ strtoupper(substr($result->user->name, 0, 1)) }}
                        </div>
                        <div>
                            <p class="text-sm font-bold text-gray-900">{{ $result->user->name }}</p>
                            <p class="text-[10px] text-gray-500 capitalize">{{ $result->user->role }}</p>
                        </div>
                    </div>
                </td>
                <td class="px-6 py-4">
                    <p class="text-sm font-medium text-gray-700">{{ $result->package->title }}</p>
                </td>
                <td class="px-6 py-4 text-center">
                    <span class="text-sm font-bold {{ $result->twk_passed ? 'text-emerald-600' : 'text-red-500' }}">{{ $result->twk_score }}</span>
                </td>
                <td class="px-6 py-4 text-center">
                    <span class="text-sm font-bold {{ $result->tiu_passed ? 'text-emerald-600' : 'text-red-500' }}">{{ $result->tiu_score }}</span>
                </td>
                <td class="px-6 py-4 text-center">
                    <span class="text-sm font-bold {{ $result->tkp_passed ? 'text-emerald-600' : 'text-red-500' }}">{{ $result->tkp_score }}</span>
                </td>
                <td class="px-6 py-4 text-center">
                    <span class="text-sm font-black text-gray-900">{{ $result->total_score }}</span>
                </td>
                <td class="px-6 py-4 text-center">
                    <span class="px-2.5 py-1 text-[10px] font-black rounded-full {{ $result->is_passed ? 'bg-emerald-100 text-emerald-700' : 'bg-red-100 text-red-700' }}">
                        {{ $result->is_passed ? 'LULUS' : 'TIDAK LULUS' }}
                    </span>
                </td>
                <td class="px-6 py-4 text-right">
                    <p class="text-[10px] text-gray-500 font-medium">{{ $result->created_at->format('d/m/Y') }}</p>
                    <p class="text-[10px] text-gray-400 font-medium">{{ $result->created_at->format('H:i') }} WIB</p>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="8" class="px-6 py-12 text-center text-gray-400 italic text-sm">
                    Belum ada data hasil ujian SKD.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
    @if($results->hasPages())
    <div class="px-6 py-4 border-t border-gray-100 bg-gray-50/50">
        {{ $results->links() }}
    </div>
    @endif
</div>
@endsection
