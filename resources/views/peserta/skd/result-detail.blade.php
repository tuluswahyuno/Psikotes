@extends('layouts.main')
@section('title', 'Detail Hasil SKD')

@section('content')
<div class="max-w-6xl mx-auto space-y-8">
    <!-- Breadcrumbs -->
    <nav class="flex items-center gap-2 mb-6 text-sm">
        <a class="text-primary hover:underline" href="{{ route('peserta.dashboard') }}">Beranda</a>
        <span class="material-symbols-outlined text-xs text-slate-400">chevron_right</span>
        <a class="text-primary hover:underline" href="{{ route('peserta.skd.results') }}">Riwayat Ujian</a>
        <span class="material-symbols-outlined text-xs text-slate-400">chevron_right</span>
        <span class="text-slate-500">Hasil Simulasi #SKD-{{ str_pad($skdResult->id, 4, '0', STR_PAD_LEFT) }}</span>
    </nav>
    <!-- Page Title & Actions -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-8">
        <div>
            <h1 class="text-3xl font-black tracking-tight mb-2">Laporan Hasil Simulasi SKD</h1>
            <p class="text-slate-500">Simulasi dilaksanakan pada {{ $skdResult->created_at->format('d M Y') }} • Standar Permenpan-RB Terbaru</p>
        </div>
        <div class="flex gap-3">
            <button class="flex items-center gap-2 px-4 py-2 bg-slate-200 dark:bg-slate-800 rounded-lg text-sm font-bold hover:bg-slate-300 transition-colors" onclick="window.print()">
                <span class="material-symbols-outlined text-sm">download</span>
                <span>Sertifikat PDF</span>
            </button>
            <a href="{{ route('peserta.skd.results.review', $skdResult) }}" class="flex items-center gap-2 px-6 py-2 bg-primary text-white rounded-lg text-sm font-bold shadow-lg shadow-primary/20 hover:bg-primary/90 transition-all">
                <span class="material-symbols-outlined text-sm">history_edu</span>
                <span>Lihat Pembahasan</span>
            </a>
            <a href="{{ route('peserta.skd.leaderboard') }}" class="flex items-center gap-2 px-4 py-2 bg-amber-100 dark:bg-amber-900/30 text-amber-700 dark:text-amber-300 rounded-lg text-sm font-bold hover:bg-amber-200 transition-colors border border-amber-200 dark:border-amber-800">
                <span class="material-symbols-outlined text-sm">emoji_events</span>
                <span>Leaderboard</span>
            </a>
        </div>
    </div>
    
    <!-- Main Status Card -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
        <!-- Graduation Status -->
        <div class="lg:col-span-2 relative overflow-hidden bg-white dark:bg-slate-900 rounded-xl p-8 shadow-sm border border-slate-200 dark:border-slate-800">
            <div class="flex flex-col md:flex-row items-center gap-8 relative z-10">
                <div class="relative flex items-center justify-center shrink-0">
                    <!-- Radial Chart representation -->
                    @php
                        $percentage = min(100, round(($skdResult->total_score / 550) * 100));
                        $offset = 440 - (440 * $percentage / 100);
                    @endphp
                    <svg class="w-40 h-40 transform -rotate-90">
                        <circle class="text-slate-100 dark:text-slate-800" cx="80" cy="80" fill="transparent" r="70" stroke="currentColor" stroke-width="12"></circle>
                        <circle class="text-primary transition-all duration-1000 ease-out" cx="80" cy="80" fill="transparent" r="70" stroke="currentColor" stroke-dasharray="440" stroke-dashoffset="{{ $offset }}" stroke-width="12"></circle>
                    </svg>
                    <div class="absolute inset-0 flex flex-col items-center justify-center">
                        <span class="text-3xl font-black text-slate-900 dark:text-white">{{ $skdResult->total_score }}</span>
                        <span class="text-[10px] text-slate-400 font-bold uppercase tracking-widest">Total Skor</span>
                    </div>
                </div>
                <div class="flex-1 text-center md:text-left">
                    @if($skdResult->is_passed)
                    <div class="inline-flex items-center gap-2 px-3 py-1 bg-success/10 text-success rounded-full mb-4">
                        <span class="material-symbols-outlined text-sm">check_circle</span>
                        <span class="text-xs font-bold tracking-wide">STATUS: LULUS AMBANG BATAS</span>
                    </div>
                    <h3 class="text-2xl font-bold mb-2">Selamat, {{ explode(' ', auth()->user()->name)[0] }}!</h3>
                    <p class="text-slate-500 leading-relaxed mb-4">
                        Anda berhasil melampaui nilai ambang batas (Passing Grade) di seluruh kategori sub-tes. Pertahankan performa Anda untuk menghadapi ujian yang sesungguhnya.
                    </p>
                    @else
                    <div class="inline-flex items-center gap-2 px-3 py-1 bg-danger/10 text-danger rounded-full mb-4">
                        <span class="material-symbols-outlined text-sm">cancel</span>
                        <span class="text-xs font-bold tracking-wide">STATUS: TIDAK LULUS AMBANG BATAS</span>
                    </div>
                    <h3 class="text-2xl font-bold mb-2">Tetap Semangat, {{ explode(' ', auth()->user()->name)[0] }}!</h3>
                    <p class="text-slate-500 leading-relaxed mb-4">
                        Anda belum berhasil melampaui nilai ambang batas pada beberapa tes. Teruslah berlatih untuk meningkatkan performa Anda.
                    </p>
                    @endif
                    <div class="flex flex-wrap justify-center md:justify-start gap-4">
                        <div class="flex items-center gap-2 text-sm text-slate-600 dark:text-slate-400">
                            <span class="material-symbols-outlined text-success text-lg">emoji_events</span>
                            <span>Ranking: <strong>- / -</strong></span>
                        </div>
                        <div class="flex items-center gap-2 text-sm text-slate-600 dark:text-slate-400">
                            <span class="material-symbols-outlined text-primary text-lg">timer</span>
                            <span>Waktu: <strong>{{ $skdResult->package->duration_minutes }} menit</strong></span>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Background Pattern -->
            <div class="absolute top-0 right-0 w-64 h-64 bg-primary/5 rounded-full -mr-32 -mt-32 blur-3xl"></div>
        </div>
        
        <!-- Quick Action Card -->
        <div class="bg-primary rounded-xl p-8 text-white shadow-xl shadow-primary/20 flex flex-col justify-between relative overflow-hidden group">
            <div class="relative z-10">
                <span class="material-symbols-outlined text-4xl mb-4 opacity-80">psychology</span>
                <h4 class="text-xl font-bold mb-2">Analisis Kelemahan</h4>
                <p class="text-primary-100/80 text-sm mb-6">Fokus pada materi yang skornya paling mendesak untuk diperbaiki.</p>
            </div>
            <button class="relative z-10 w-full py-3 bg-white text-primary rounded-lg font-bold text-sm hover:bg-slate-100 transition-colors">
                Lihat Rekomendasi Belajar
            </button>
            <div class="absolute bottom-0 right-0 opacity-10 group-hover:scale-110 transition-transform">
                <span class="material-symbols-outlined text-[120px] translate-y-1/4 translate-x-1/4">insights</span>
            </div>
        </div>
    </div>
    
    <!-- Scores Grid -->
    @php
        $twk_pg = $skdResult->package->packageTests->where('sub_test_type', 'twk')->first()->passing_grade ?? 65;
        $tiu_pg = $skdResult->package->packageTests->where('sub_test_type', 'tiu')->first()->passing_grade ?? 80;
        $tkp_pg = $skdResult->package->packageTests->where('sub_test_type', 'tkp')->first()->passing_grade ?? 166;
    @endphp
    
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <!-- TWK Card -->
        <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl p-6 shadow-sm relative overflow-hidden">
            <div class="flex justify-between items-start mb-4">
                <div>
                    <h5 class="text-sm font-bold text-slate-500 uppercase tracking-wider mb-1">TWK</h5>
                    <p class="text-xs text-slate-400">Tes Wawasan Kebangsaan</p>
                </div>
                <span class="material-symbols-outlined {{ $skdResult->twk_passed ? 'text-success' : 'text-danger' }}">flag</span>
            </div>
            <div class="flex items-end gap-3 mb-4">
                <div class="text-4xl font-black text-slate-900 dark:text-white">{{ $skdResult->twk_score }}</div>
                <div class="text-lg font-bold text-slate-400 mb-1">/ 150</div>
            </div>
            <div class="space-y-3">
                <div class="flex justify-between text-xs font-semibold">
                    <span>Passing Grade: {{ $twk_pg }}</span>
                    @if($skdResult->twk_score >= $twk_pg)
                        <span class="text-success">+{{ $skdResult->twk_score - $twk_pg }} Poin</span>
                    @else
                        <span class="text-danger">{{ $skdResult->twk_score - $twk_pg }} Poin</span>
                    @endif
                </div>
                <div class="h-2 w-full bg-slate-100 dark:bg-slate-800 rounded-full overflow-hidden">
                    <div class="h-full {{ $skdResult->twk_passed ? 'bg-primary' : 'bg-danger' }}" style="width: {{ min(100, round(($skdResult->twk_score / 150) * 100)) }}%;"></div>
                </div>
            </div>
        </div>
        
        <!-- TIU Card -->
        <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl p-6 shadow-sm relative overflow-hidden">
            <div class="flex justify-between items-start mb-4">
                <div>
                    <h5 class="text-sm font-bold text-slate-500 uppercase tracking-wider mb-1">TIU</h5>
                    <p class="text-xs text-slate-400">Tes Intelegensia Umum</p>
                </div>
                <span class="material-symbols-outlined {{ $skdResult->tiu_passed ? 'text-success' : 'text-danger' }}">calculate</span>
            </div>
            <div class="flex items-end gap-3 mb-4">
                <div class="text-4xl font-black text-slate-900 dark:text-white">{{ $skdResult->tiu_score }}</div>
                <div class="text-lg font-bold text-slate-400 mb-1">/ 175</div>
            </div>
            <div class="space-y-3">
                <div class="flex justify-between text-xs font-semibold">
                    <span>Passing Grade: {{ $tiu_pg }}</span>
                    @if($skdResult->tiu_score >= $tiu_pg)
                        <span class="text-success">+{{ $skdResult->tiu_score - $tiu_pg }} Poin</span>
                    @else
                        <span class="text-danger">{{ $skdResult->tiu_score - $tiu_pg }} Poin</span>
                    @endif
                </div>
                <div class="h-2 w-full bg-slate-100 dark:bg-slate-800 rounded-full overflow-hidden">
                    <div class="h-full {{ $skdResult->tiu_passed ? 'bg-primary' : 'bg-danger' }}" style="width: {{ min(100, round(($skdResult->tiu_score / 175) * 100)) }}%;"></div>
                </div>
            </div>
        </div>
        
        <!-- TKP Card -->
        <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl p-6 shadow-sm relative overflow-hidden">
            <div class="flex justify-between items-start mb-4">
                <div>
                    <h5 class="text-sm font-bold text-slate-500 uppercase tracking-wider mb-1">TKP</h5>
                    <p class="text-xs text-slate-400">Tes Karakteristik Pribadi</p>
                </div>
                <span class="material-symbols-outlined {{ $skdResult->tkp_passed ? 'text-success' : 'text-danger' }}">groups</span>
            </div>
            <div class="flex items-end gap-3 mb-4">
                <div class="text-4xl font-black text-slate-900 dark:text-white">{{ $skdResult->tkp_score }}</div>
                <div class="text-lg font-bold text-slate-400 mb-1">/ 225</div>
            </div>
            <div class="space-y-3">
                <div class="flex justify-between text-xs font-semibold">
                    <span>Passing Grade: {{ $tkp_pg }}</span>
                    @if($skdResult->tkp_score >= $tkp_pg)
                        <span class="text-success">+{{ $skdResult->tkp_score - $tkp_pg }} Poin</span>
                    @else
                        <span class="text-danger">{{ $skdResult->tkp_score - $tkp_pg }} Poin</span>
                    @endif
                </div>
                <div class="h-2 w-full bg-slate-100 dark:bg-slate-800 rounded-full overflow-hidden">
                    <div class="h-full {{ $skdResult->tkp_passed ? 'bg-primary' : 'bg-danger' }}" style="width: {{ min(100, round(($skdResult->tkp_score / 225) * 100)) }}%;"></div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Secondary Sections -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Next Step Recommendation -->
        <div class="bg-white dark:bg-slate-900 rounded-xl border border-slate-200 dark:border-slate-800 p-6 shadow-sm flex items-center gap-6">
            <div class="size-20 bg-slate-100 dark:bg-slate-800 rounded-xl flex items-center justify-center shrink-0">
                <span class="material-symbols-outlined text-primary text-4xl">local_fire_department</span>
            </div>
            <div>
                <h4 class="font-bold mb-1">Tingkatkan Nilai Anda!</h4>
                <p class="text-sm text-slate-500 mb-4">Silakan ikuti simulasi kembali untuk mendapatkan score yang memuaskan.</p>
                <a href="{{ route('peserta.skd.index') }}" class="text-sm font-bold text-primary hover:underline flex items-center gap-1">
                    Mulai Simulasi Lagi <span class="material-symbols-outlined text-sm">arrow_forward</span>
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
