@extends('layouts.main')
@section('title', 'Detail Hasil SKD')
@section('subtitle', 'Laporan skor peserta: ' . $skdResult->user->name)

@section('actions')
<a href="{{ route('admin.skd-results.index') }}" class="btn-secondary">Kembali ke Daftar</a>
@endsection

@section('content')
<div class="max-w-5xl mx-auto">
    <!-- Header Score Card -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden mb-8">
        <div class="grid grid-cols-1 md:grid-cols-4 divide-y md:divide-y-0 md:divide-x divide-gray-100">
            <div class="p-8 flex flex-col items-center justify-center text-center">
                <span class="text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-1">Status Akhir</span>
                <span class="text-2xl font-black {{ $skdResult->is_passed ? 'text-emerald-600' : 'text-red-600' }}">
                    {{ $skdResult->is_passed ? 'LULUS' : 'TIDAK LULUS' }}
                </span>
                <p class="text-[10px] text-gray-400 mt-2 font-medium">Berdasarkan Passing Grade Kepmenpan</p>
            </div>
            <div class="p-8 flex flex-col items-center justify-center text-center bg-gray-50/20">
                <span class="text-[10px] font-bold text-blue-600 uppercase tracking-widest mb-1">Skor TWK</span>
                <span class="text-4xl font-black text-gray-900">{{ $skdResult->twk_score }}</span>
                <span class="mt-2 px-3 py-0.5 rounded-full text-[9px] font-black {{ $skdResult->twk_passed ? 'bg-emerald-100 text-emerald-700' : 'bg-red-100 text-red-700' }}">
                    {{ $skdResult->twk_passed ? 'MEMENUHI PG' : 'TIDAK MEMENUHI' }}
                </span>
            </div>
            <div class="p-8 flex flex-col items-center justify-center text-center bg-gray-50/20">
                <span class="text-[10px] font-bold text-amber-600 uppercase tracking-widest mb-1">Skor TIU</span>
                <span class="text-4xl font-black text-gray-900">{{ $skdResult->tiu_score }}</span>
                <span class="mt-2 px-3 py-0.5 rounded-full text-[9px] font-black {{ $skdResult->tiu_passed ? 'bg-emerald-100 text-emerald-700' : 'bg-red-100 text-red-700' }}">
                    {{ $skdResult->tiu_passed ? 'MEMENUHI PG' : 'TIDAK MEMENUHI' }}
                </span>
            </div>
            <div class="p-8 flex flex-col items-center justify-center text-center bg-gray-50/20">
                <span class="text-[10px] font-bold text-purple-600 uppercase tracking-widest mb-1">Skor TKP</span>
                <span class="text-4xl font-black text-gray-900">{{ $skdResult->tkp_score }}</span>
                <span class="mt-2 px-3 py-0.5 rounded-full text-[9px] font-black {{ $skdResult->tkp_passed ? 'bg-emerald-100 text-emerald-700' : 'bg-red-100 text-red-700' }}">
                    {{ $skdResult->tkp_passed ? 'MEMENUHI PG' : 'TIDAK MEMENUHI' }}
                </span>
            </div>
        </div>
        <div class="bg-gray-50 p-4 border-t border-gray-100 flex justify-between items-center">
            <div class="flex items-center gap-6">
                 <div>
                    <span class="text-[10px] font-bold text-gray-400 uppercase mr-2">Total Skor</span>
                    <span class="text-xl font-black text-gray-900">{{ $skdResult->total_score }}</span>
                 </div>
                 <div class="text-[10px] text-gray-500 font-medium border-l border-gray-200 pl-6 h-6 flex items-center">
                    Ujian dilaksanakan pada {{ $skdResult->created_at->format('d F Y, H:i') }} WIB
                 </div>
            </div>
        </div>
    </div>

    <!-- Details Table -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 bg-gray-50/50 border-b border-gray-100">
            <h4 class="text-sm font-bold text-gray-900">Detail Jawaban CAT</h4>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="text-[10px] font-bold text-gray-400 uppercase bg-gray-50/30">
                        <th class="px-6 py-3">No</th>
                        <th class="px-6 py-3">Sub-Tes</th>
                        <th class="px-6 py-3">Soal</th>
                        <th class="px-6 py-3">Jawaban</th>
                        <th class="px-6 py-3 text-center">Poin</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @php 
                        $questionIndex = 1;
                        // Map question_id to sub_test labels
                        $qMap = [];
                        foreach($skdResult->package->packageTests as $pt) {
                            foreach($pt->test->questions as $q) {
                                $qMap[$q->id] = $pt->sub_test_type;
                            }
                        }
                    @endphp
                    @foreach($skdResult->session->answers as $answer)
                    <tr class="text-sm">
                        <td class="px-6 py-4 text-gray-400 font-medium">{{ $questionIndex++ }}</td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-0.5 rounded text-[9px] font-bold uppercase 
                                @if($qMap[$answer->question_id] == 'twk') bg-blue-50 text-blue-600 
                                @elseif($qMap[$answer->question_id] == 'tiu') bg-amber-50 text-amber-600 
                                @else bg-purple-50 text-purple-600 @endif">
                                {{ $qMap[$answer->question_id] }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-gray-900 font-medium max-w-xs truncate">{{ $answer->question->question_text }}</td>
                        <td class="px-6 py-4">
                            @if($answer->option)
                                <span class="{{ $answer->score > 0 ? 'text-emerald-600' : 'text-red-500' }} font-bold">
                                    {{ $answer->option->option_text }}
                                </span>
                            @else
                                <span class="text-gray-400 italic">Tidak Dijawab</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-center font-bold text-gray-900">{{ $answer->score }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
