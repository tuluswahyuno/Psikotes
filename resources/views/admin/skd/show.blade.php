@extends('layouts.main')
@section('title', 'Detail Paket SKD')
@section('subtitle', $skdPackage->title)

@section('actions')
<div class="flex gap-2">
    <a href="{{ route('admin.skd-packages.edit', $skdPackage) }}" class="btn-secondary">
        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-5M16.5 3.5a2.121 2.121 0 113 3L12 15l-4 1 1-4 8.5-8.5z"/></svg>
        Edit Paket
    </a>
    <a href="{{ route('admin.skd-packages.index') }}" class="btn-secondary">Kembali</a>
</div>
@endsection

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <!-- Left: Stats & Info -->
    <div class="lg:col-span-1 space-y-6">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <h3 class="text-sm font-bold text-gray-900 mb-6 border-b border-gray-50 pb-2">Informasi Paket</h3>
            <div class="space-y-4">
                <div class="flex justify-between items-center">
                    <span class="text-xs text-gray-500 font-medium">Status</span>
                    <span class="px-2.5 py-1 text-[10px] font-bold rounded-full {{ $skdPackage->is_active ? 'bg-emerald-50 text-emerald-700' : 'bg-red-50 text-red-700' }}">
                        {{ $skdPackage->is_active ? 'AKTIF' : 'NONAKTIF' }}
                    </span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-xs text-gray-500 font-medium">Total Durasi</span>
                    <span class="text-sm font-bold text-gray-900">{{ $skdPackage->duration_minutes }} Menit</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-xs text-gray-500 font-medium">Total Soal</span>
                    <span class="text-sm font-bold text-indigo-600">{{ $skdPackage->total_questions }} Soal</span>
                </div>
                <div class="pt-4 mt-4 border-t border-gray-50">
                    <span class="text-xs text-gray-500 font-medium block mb-2">Deskripsi</span>
                    <p class="text-sm text-gray-600 leading-relaxed">{{ $skdPackage->description ?: 'Tidak ada deskripsi.' }}</p>
                </div>
            </div>
        </div>

        <!-- Result Overview Stats -->
        <div class="bg-gradient-to-br from-indigo-600 to-purple-700 rounded-2xl p-6 text-white shadow-lg">
            <h4 class="text-white/70 text-xs font-bold uppercase tracking-wider mb-4">Statistik Hasil</h4>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <p class="text-2xl font-bold">{{ $skdPackage->results->count() }}</p>
                    <p class="text-[10px] text-white/60">Total Peserta</p>
                </div>
                <div>
                    <p class="text-2xl font-bold">{{ $skdPackage->results->where('is_passed', true)->count() }}</p>
                    <p class="text-[10px] text-white/60">Lulus Seleksi</p>
                </div>
            </div>
            <div class="mt-6 pt-4 border-t border-white/10">
                <a href="{{ route('admin.skd-results.index', ['package_id' => $skdPackage->id]) }}" class="text-xs font-bold text-white flex items-center gap-1 hover:gap-2 transition-all">
                    Lihat Semua Hasil &rarr;
                </a>
            </div>
        </div>
    </div>

    <!-- Right: Sub-Test Structure -->
    <div class="lg:col-span-2 space-y-4">
        <h3 class="text-sm font-bold text-gray-900 px-1">Struktur Sub-Tes</h3>
        
        @foreach($skdPackage->packageTests as $pt)
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="p-4 flex items-center justify-between bg-gray-50/50 border-b border-gray-100">
                <div class="flex items-center gap-3">
                    <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase 
                        @if($pt->sub_test_type == 'twk') bg-blue-100 text-blue-700 
                        @elseif($pt->sub_test_type == 'tiu') bg-amber-100 text-amber-700 
                        @else bg-purple-100 text-purple-700 @endif">
                        {{ $pt->sub_test_type }}
                    </span>
                    <h4 class="font-bold text-gray-900 text-sm">{{ $pt->sub_test_label }}</h4>
                </div>
                <div class="flex gap-4 text-[10px] font-bold text-gray-500 uppercase">
                    <span class="flex items-center gap-1">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        {{ $pt->test->questions->count() }} Soal
                    </span>
                    <span class="flex items-center gap-1">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        PG: {{ $pt->passing_grade }}
                    </span>
                </div>
            </div>
            <div class="p-4">
                <div class="flex items-center justify-between text-xs text-gray-600">
                    <div class="flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-indigo-400"></span>
                        <span>Master Tes: <strong>{{ $pt->test->title }}</strong></span>
                    </div>
                    <a href="{{ route('admin.tests.show', $pt->test_id) }}" class="text-indigo-600 font-bold hover:underline italic">Lihat Master Tes &rarr;</a>
                </div>
                @if($pt->sub_test_type == 'tkp')
                <div class="mt-3 p-2 bg-purple-50 rounded-lg text-[10px] text-purple-700 font-medium flex items-start gap-2">
                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    TKP menggunakan sistem poin berjenjang (1-5) untuk setiap pilihan jawaban.
                </div>
                @else
                <div class="mt-3 p-2 bg-blue-50 rounded-lg text-[10px] text-blue-700 font-medium flex items-start gap-2">
                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    {{ strtoupper($pt->sub_test_type) }} menggunakan bobot 5 poin untuk jawaban benar dan 0 untuk salah.
                </div>
                @endif
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection
