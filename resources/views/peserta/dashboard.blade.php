@extends('layouts.main')
@section('title', 'Dashboard Peserta')

@section('content')
<!-- Header Section -->
<div class="flex flex-wrap items-center justify-between gap-4 mb-8">
    <div>
        <h2 class="text-3xl font-black text-slate-900 dark:text-white tracking-tight">Selamat datang, {{ auth()->user()->name }}!</h2>
        <p class="text-slate-500 dark:text-slate-400 mt-1">Siap melanjutkan persiapan CPNS hari ini? Progresmu terlihat bagus.</p>
    </div>
    <a href="{{ route('peserta.skd.index') }}" class="bg-primary hover:bg-primary/90 text-white font-semibold py-3 px-6 rounded-xl flex items-center gap-2 shadow-lg shadow-primary/20 transition-all">
        <span class="material-symbols-outlined">play_circle</span>
        Mulai Simulasi
    </a>
</div>

<!-- Stats Overview Grid -->
<div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
    <!-- SKD Prep Card -->
    <div class="bg-white dark:bg-slate-900 p-6 rounded-xl border border-slate-200 dark:border-slate-800 shadow-sm relative overflow-hidden group">
        <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:scale-110 transition-transform">
            <span class="material-symbols-outlined text-6xl text-primary">description</span>
        </div>
        <div class="relative z-10">
            <div class="flex items-center gap-2 text-primary font-bold text-sm uppercase tracking-wider mb-2">
                <span class="material-symbols-outlined text-sm">verified</span>
                Simulasi SKD
            </div>
            <h3 class="text-slate-900 dark:text-white text-2xl font-bold mb-4">Seleksi Kompetensi Dasar</h3>
            <div class="flex items-end gap-4">
                <div>
                    <p class="text-slate-500 dark:text-slate-400 text-xs font-semibold mb-1">Simulasi Selesai</p>
                    <p class="text-4xl font-black text-slate-900 dark:text-white">{{ str_pad($skdCompletedCount, 2, '0', STR_PAD_LEFT) }}</p>
                </div>
                @if($skdCompletedCount > 0)
                <div class="bg-emerald-100 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-400 px-2 py-1 rounded text-sm font-bold flex items-center mb-1">
                    <span class="material-symbols-outlined text-sm mr-1">trending_up</span> {{ $skdCompletedCount }} paket
                </div>
                @endif
            </div>
            <div class="mt-6 flex gap-3">
                <a href="{{ route('peserta.skd.results') }}" class="bg-primary/10 hover:bg-primary/20 text-primary px-4 py-2 rounded-lg text-sm font-bold transition-colors">Lihat Riwayat</a>
                <a href="{{ route('peserta.skd.leaderboard') }}" class="bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 px-4 py-2 rounded-lg text-sm font-bold hover:bg-slate-200 dark:hover:bg-slate-700 transition-colors">Leaderboard</a>
            </div>
        </div>
    </div>

    <!-- Psychotest Prep Card -->
    <div class="bg-white dark:bg-slate-900 p-6 rounded-xl border border-slate-200 dark:border-slate-800 shadow-sm relative overflow-hidden group">
        <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:scale-110 transition-transform">
            <span class="material-symbols-outlined text-6xl text-primary">psychology</span>
        </div>
        <div class="relative z-10">
            <div class="flex items-center gap-2 text-primary font-bold text-sm uppercase tracking-wider mb-2">
                <span class="material-symbols-outlined text-sm">psychology</span>
                Psikotes
            </div>
            <h3 class="text-slate-900 dark:text-white text-2xl font-bold mb-4">Tes Psikologi</h3>
            <div class="flex items-end gap-4">
                <div>
                    <p class="text-slate-500 dark:text-slate-400 text-xs font-semibold mb-1">Tes Selesai</p>
                    <p class="text-4xl font-black text-slate-900 dark:text-white">{{ str_pad($completedCount, 2, '0', STR_PAD_LEFT) }}</p>
                </div>
                @if($pendingCount > 0)
                <div class="bg-amber-100 dark:bg-amber-900/30 text-amber-700 dark:text-amber-400 px-2 py-1 rounded text-sm font-bold flex items-center mb-1">
                    <span class="material-symbols-outlined text-sm mr-1">schedule</span> {{ $pendingCount }} pending
                </div>
                @endif
            </div>
            <div class="mt-6 flex gap-3">
                <a href="{{ route('peserta.results.index') }}" class="bg-primary/10 hover:bg-primary/20 text-primary px-4 py-2 rounded-lg text-sm font-bold transition-colors">Lihat Riwayat</a>
            </div>
        </div>
    </div>
</div>

<!-- Belajar SKD Card -->
<div class="bg-gradient-to-r from-primary/5 to-purple-500/5 dark:from-primary/10 dark:to-purple-900/10 p-6 rounded-xl border border-primary/20 dark:border-primary/30 shadow-sm mb-8 relative overflow-hidden group">
    <div class="absolute top-0 right-0 p-4 opacity-5 group-hover:scale-110 transition-transform">
        <span class="material-symbols-outlined text-8xl text-primary">auto_stories</span>
    </div>
    <div class="relative z-10 flex flex-wrap items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-2 text-primary font-bold text-sm uppercase tracking-wider mb-2">
                <span class="material-symbols-outlined text-sm">auto_stories</span>
                Fitur Baru
            </div>
            <h3 class="text-slate-900 dark:text-white text-xl font-bold mb-1">Belajar & Latihan SKD</h3>
            <p class="text-sm text-slate-500 dark:text-slate-400">Pelajari materi TWK, TIU, TKP dan kerjakan latihan soal per sub-topik untuk mempersiapkan ujian.</p>
        </div>
        <a href="{{ route('peserta.learn.index') }}" class="bg-primary hover:bg-primary/90 text-white font-semibold py-3 px-6 rounded-xl flex items-center gap-2 shadow-lg shadow-primary/20 transition-all shrink-0">
            <span class="material-symbols-outlined">menu_book</span>
            Mulai Belajar
        </a>
    </div>
</div>

<!-- Recent Activity and Learning Progress -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <!-- Activity Feed -->
    <div class="lg:col-span-2">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-xl font-bold text-slate-900 dark:text-white">Aktivitas Terbaru</h3>
            <a href="{{ route('peserta.skd.results') }}" class="text-primary text-sm font-bold hover:underline">Lihat Semua</a>
        </div>
        <div class="space-y-4">
            @forelse($latestSkdResults as $result)
            <div class="flex items-center gap-4 p-4 bg-white dark:bg-slate-900 rounded-xl border border-slate-200 dark:border-slate-800">
                <div class="h-12 w-12 rounded-full {{ $result->is_passed ? 'bg-emerald-100 dark:bg-emerald-900/30 text-emerald-600' : 'bg-red-100 dark:bg-red-900/30 text-red-600' }} flex items-center justify-center shrink-0">
                    <span class="material-symbols-outlined">{{ $result->is_passed ? 'check_circle' : 'cancel' }}</span>
                </div>
                <div class="flex-1">
                    <p class="text-slate-900 dark:text-white font-bold">{{ $result->package->title ?? 'Simulasi SKD' }}</p>
                    <p class="text-slate-500 dark:text-slate-400 text-sm">Skor: {{ $result->total_score }}/550 • {{ $result->created_at->diffForHumans() }}</p>
                </div>
                <span class="text-xs font-bold px-2 py-1 rounded {{ $result->is_passed ? 'text-emerald-600 bg-emerald-100 dark:bg-emerald-900/30' : 'text-red-600 bg-red-100 dark:bg-red-900/30' }}">
                    {{ $result->is_passed ? 'Lulus' : 'Gagal' }}
                </span>
            </div>
            @empty
            <div class="flex items-center gap-4 p-6 bg-white dark:bg-slate-900 rounded-xl border border-slate-200 dark:border-slate-800 text-center justify-center">
                <div class="text-slate-400">
                    <span class="material-symbols-outlined text-4xl block mb-2">history_edu</span>
                    <p class="text-sm">Belum ada aktivitas simulasi. Mulai simulasi pertama Anda!</p>
                </div>
            </div>
            @endforelse

            @foreach($assignments as $assignment)
            <div class="flex items-center gap-4 p-4 bg-white dark:bg-slate-900 rounded-xl border border-slate-200 dark:border-slate-800">
                <div class="h-12 w-12 rounded-full bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center text-blue-600 shrink-0">
                    <span class="material-symbols-outlined">psychology</span>
                </div>
                <div class="flex-1">
                    <p class="text-slate-900 dark:text-white font-bold">{{ $assignment->test->title }}</p>
                    <p class="text-slate-500 dark:text-slate-400 text-sm">Psikotes • {{ $assignment->test->duration_minutes }} menit</p>
                </div>
                <a href="{{ route('peserta.tests.show', $assignment->test) }}" class="text-amber-600 text-xs font-bold bg-amber-100 dark:bg-amber-900/30 px-2 py-1 rounded">Kerjakan</a>
            </div>
            @endforeach
        </div>
    </div>

    <!-- Learning Progress -->
    <div class="flex flex-col gap-6">
        <h3 class="text-xl font-bold text-slate-900 dark:text-white">Progres Belajar</h3>
        <div class="bg-white dark:bg-slate-900 p-6 rounded-xl border border-slate-200 dark:border-slate-800 shadow-sm">
            @php
                $colors = [
                    'blue' => 'bg-blue-500 text-blue-600',
                    'purple' => 'bg-purple-500 text-purple-600',
                    'emerald' => 'bg-emerald-500 text-emerald-600',
                ];
            @endphp
            @foreach($learningProgress as $lp)
            @php
                $colorClasses = $colors[$lp->color] ?? 'bg-blue-500 text-blue-600';
                $bgClass = explode(' ', $colorClasses)[0];
                $textClass = explode(' ', $colorClasses)[1];
            @endphp
            <div class="mb-6">
                <div class="flex justify-between text-sm mb-2">
                    <span class="font-bold text-slate-700 dark:text-slate-300">{{ $lp->name }}</span>
                    <span class="font-black {{ $textClass }}">{{ $lp->progress }}%</span>
                </div>
                <div class="w-full bg-slate-100 dark:bg-slate-800 rounded-full h-2">
                    <div class="{{ $bgClass }} h-2 rounded-full" style="width: {{ $lp->progress }}%"></div>
                </div>
            </div>
            @endforeach

            <a href="{{ route('peserta.learn.index') }}" class="block w-full mt-6 bg-slate-900 dark:bg-slate-100 text-white dark:text-slate-900 py-3 rounded-xl font-bold hover:opacity-90 transition-opacity text-center">
                Lanjutkan Belajar
            </a>
        </div>

        <div class="bg-gradient-to-br from-primary to-blue-700 p-6 rounded-xl text-white shadow-xl shadow-primary/20">
            <p class="text-white/80 text-sm font-bold uppercase tracking-wider mb-1">Target Mingguan</p>
            <h4 class="text-xl font-black mb-4">Selesaikan 5 simulasi lagi untuk mencapai targetmu!</h4>
            <a href="{{ route('peserta.skd.index') }}" class="inline-block bg-white text-primary px-4 py-2 rounded-lg text-xs font-bold shadow-md">Mulai Sekarang</a>
        </div>
    </div>
</div>
@endsection
