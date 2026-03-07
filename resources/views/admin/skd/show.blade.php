@extends('layouts.main')
@section('title', 'Detail Paket SKD')
@section('subtitle', $skdPackage->title)

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <!-- Left: Stats & Info -->
    <div class="lg:col-span-1 space-y-5">
        <!-- Package Info -->
        <div class="bg-white dark:bg-slate-900 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-800 p-6">
            <h3 class="text-sm font-bold text-slate-900 dark:text-white mb-5 pb-3 border-b border-slate-100 dark:border-slate-800">Informasi Paket</h3>
            <div class="space-y-4">
                <div class="flex justify-between items-center">
                    <span class="text-xs text-slate-500 font-medium">Status</span>
                    <span class="px-2.5 py-1 text-[10px] font-bold rounded-full {{ $skdPackage->is_active ? 'bg-emerald-100 text-emerald-700' : 'bg-rose-100 text-rose-700' }}">
                        {{ $skdPackage->is_active ? 'AKTIF' : 'NONAKTIF' }}
                    </span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-xs text-slate-500 font-medium">Acak Soal</span>
                    <span class="text-sm font-bold text-slate-900 dark:text-white">{{ $skdPackage->randomize_questions ? 'Ya' : 'Tidak' }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-xs text-slate-500 font-medium">Total Durasi</span>
                    <span class="text-sm font-bold text-slate-900 dark:text-white">{{ $skdPackage->duration_minutes }} Menit</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-xs text-slate-500 font-medium">Total Soal</span>
                    <span class="text-sm font-bold text-primary">{{ $skdPackage->total_questions }} Soal</span>
                </div>
                @if($skdPackage->description)
                <div class="pt-4 border-t border-slate-100 dark:border-slate-800">
                    <span class="text-xs text-slate-500 font-medium block mb-2">Deskripsi</span>
                    <p class="text-sm text-slate-600 dark:text-slate-400 leading-relaxed">{{ $skdPackage->description }}</p>
                </div>
                @endif
            </div>
        </div>

        <!-- Actions -->
        <div class="flex flex-wrap gap-3">
            <a href="{{ route('admin.skd-packages.edit', $skdPackage) }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 font-bold rounded-xl text-sm hover:bg-slate-200 dark:hover:bg-slate-700 transition-all">
                <span class="material-symbols-outlined text-lg">edit</span>
                Edit Paket
            </a>
            <a href="{{ route('admin.skd-results.index', ['package_id' => $skdPackage->id]) }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-primary text-white font-bold rounded-xl text-sm hover:bg-blue-700 transition-all shadow-lg shadow-primary/20">
                <span class="material-symbols-outlined text-lg">assessment</span>
                Lihat Hasil
            </a>
        </div>

        <!-- Result Stats -->
        <div class="bg-gradient-to-br from-primary to-blue-700 rounded-2xl p-6 text-white shadow-lg">
            <h4 class="text-white/70 text-xs font-bold uppercase tracking-wider mb-4">Statistik Hasil</h4>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <p class="text-3xl font-black">{{ $skdPackage->results_count ?? $skdPackage->results->count() }}</p>
                    <p class="text-[11px] text-white/60">Total Peserta</p>
                </div>
                <div>
                    <p class="text-3xl font-black">{{ $skdPackage->results->where('is_passed', true)->count() }}</p>
                    <p class="text-[11px] text-white/60">Lulus Seleksi</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Right: Sub-Test Structure -->
    <div class="lg:col-span-2 space-y-5">
        <h3 class="text-sm font-bold text-slate-900 dark:text-white px-1 flex items-center gap-2">
            <span class="material-symbols-outlined text-slate-400">category</span>
            Konfigurasi Sub-Tes
        </h3>

        @php
            $subTestConfig = [
                'twk' => ['label' => 'Tes Wawasan Kebangsaan', 'color' => 'blue', 'icon' => 'TWK', 'desc' => 'Benar = 5 pts, Salah = 0 pts'],
                'tiu' => ['label' => 'Tes Intelegensi Umum', 'color' => 'purple', 'icon' => 'TIU', 'desc' => 'Benar = 5 pts, Salah = 0 pts'],
                'tkp' => ['label' => 'Tes Karakteristik Pribadi', 'color' => 'emerald', 'icon' => 'TKP', 'desc' => 'A=5, B=4, C=3, D=2, E=1 poin'],
            ];
        @endphp

        @foreach($subTestConfig as $slug => $cfg)
        <div class="bg-white dark:bg-slate-900 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-800 overflow-hidden">
            <div class="p-5 flex items-center justify-between bg-{{ $cfg['color'] }}-50/50 dark:bg-{{ $cfg['color'] }}-900/10 border-b border-{{ $cfg['color'] }}-100 dark:border-{{ $cfg['color'] }}-900/30">
                <div class="flex items-center gap-3">
                    <span class="size-10 rounded-xl bg-{{ $cfg['color'] }}-600 text-white flex items-center justify-center font-black text-xs shadow-lg">{{ $cfg['icon'] }}</span>
                    <div>
                        <h4 class="font-bold text-slate-900 dark:text-white text-sm">{{ $cfg['label'] }}</h4>
                        <p class="text-xs text-{{ $cfg['color'] }}-600 dark:text-{{ $cfg['color'] }}-400 mt-0.5">{{ $cfg['desc'] }}</p>
                    </div>
                </div>
                <div class="text-right">
                    <p class="text-2xl font-black text-{{ $cfg['color'] }}-600 dark:text-{{ $cfg['color'] }}-400">{{ $skdPackage->{$slug . '_question_count'} }}</p>
                    <p class="text-[10px] text-slate-500">soal</p>
                </div>
            </div>
            <div class="p-5 flex items-center justify-between">
                <div class="flex items-center gap-4 text-xs text-slate-600 dark:text-slate-400">
                    <div class="flex items-center gap-1.5">
                        <span class="material-symbols-outlined text-base text-slate-400">question_mark</span>
                        <span>Jumlah: <strong class="text-slate-800 dark:text-white">{{ $skdPackage->{$slug . '_question_count'} }} soal</strong></span>
                    </div>
                    <div class="flex items-center gap-1.5">
                        <span class="material-symbols-outlined text-base text-{{ $cfg['color'] }}-400">emoji_events</span>
                        <span>Passing Grade: <strong class="text-{{ $cfg['color'] }}-600 dark:text-{{ $cfg['color'] }}-400">{{ $skdPackage->{$slug . '_passing_grade'} }} poin</strong></span>
                    </div>
                </div>
                <div class="text-right">
                    <span class="text-[10px] text-slate-400">Bank tersedia</span>
                    <p class="text-sm font-bold text-slate-700 dark:text-slate-300">{{ $availableQuestions[$slug] ?? 0 }} soal</p>
                </div>
            </div>
        </div>
        @endforeach

        <!-- Bank Source Explanation -->
        <div class="bg-primary/5 border border-primary/20 rounded-2xl p-5 dark:bg-primary/10 dark:border-primary/30">
            <div class="flex items-center gap-3 mb-3 text-primary">
                <span class="material-symbols-outlined">auto_awesome</span>
                <h4 class="font-bold text-sm">Sumber Soal: Bank Soal Latihan</h4>
            </div>
            <p class="text-sm text-slate-600 dark:text-slate-400">
                Soal untuk paket ini ditarik <strong>otomatis</strong> dari Bank Soal Latihan saat peserta memulai simulasi.
                Setiap sesi peserta mendapatkan soal yang berbeda (acak dari bank yang sama).
                Untuk menambah soal, pergi ke menu <strong>Bank Soal → Import CSV</strong>.
            </p>
        </div>
    </div>
</div>
@endsection
