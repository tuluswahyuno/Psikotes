@extends('layouts.main')
@section('title', 'Belajar SKD')

@section('content')
<!-- Header -->
<div class="flex flex-wrap items-center justify-between gap-4 mb-8">
    <div>
        <h2 class="text-3xl font-black text-slate-900 dark:text-white tracking-tight">📚 Belajar SKD</h2>
        <p class="text-slate-500 dark:text-slate-400 mt-1">Pelajari materi dan latihan soal per section untuk persiapan SKD CPNS</p>
    </div>
    <a href="{{ route('peserta.dashboard') }}" class="text-sm font-medium text-slate-600 dark:text-slate-400 hover:text-primary transition-colors flex items-center gap-1">
        <span class="material-symbols-outlined text-lg">arrow_back</span>
        Kembali ke Dashboard
    </a>
</div>

<!-- Section Cards -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    @foreach($sections as $section)
    @php
        $progress = $sectionProgress[$section->id];
        $colors = [
            'blue' => ['bg' => 'bg-blue-500', 'light' => 'bg-blue-50 dark:bg-blue-900/20', 'text' => 'text-blue-600 dark:text-blue-400', 'border' => 'border-blue-200 dark:border-blue-800', 'ring' => 'ring-blue-500/20', 'bar' => 'bg-blue-500'],
            'purple' => ['bg' => 'bg-purple-500', 'light' => 'bg-purple-50 dark:bg-purple-900/20', 'text' => 'text-purple-600 dark:text-purple-400', 'border' => 'border-purple-200 dark:border-purple-800', 'ring' => 'ring-purple-500/20', 'bar' => 'bg-purple-500'],
            'emerald' => ['bg' => 'bg-emerald-500', 'light' => 'bg-emerald-50 dark:bg-emerald-900/20', 'text' => 'text-emerald-600 dark:text-emerald-400', 'border' => 'border-emerald-200 dark:border-emerald-800', 'ring' => 'ring-emerald-500/20', 'bar' => 'bg-emerald-500'],
        ];
        $c = $colors[$section->color] ?? $colors['blue'];
    @endphp
    <a href="{{ route('peserta.learn.section', $section->slug) }}" class="group bg-white dark:bg-slate-900 rounded-2xl border {{ $c['border'] }} shadow-sm hover:shadow-lg hover:ring-4 {{ $c['ring'] }} transition-all duration-300 overflow-hidden">
        <!-- Top Color Bar -->
        <div class="h-1.5 {{ $c['bar'] }}"></div>
        
        <div class="p-6">
            <div class="flex items-center gap-4 mb-4">
                <div class="size-14 rounded-xl {{ $c['bg'] }} text-white flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform">
                    <span class="material-symbols-outlined text-2xl">{{ $section->icon }}</span>
                </div>
                <div>
                    <h3 class="text-xl font-black text-slate-900 dark:text-white">{{ $section->name }}</h3>
                    <p class="text-xs font-medium {{ $c['text'] }}">{{ $section->subTopics->count() }} Sub-topik</p>
                </div>
            </div>
            
            <p class="text-sm text-slate-500 dark:text-slate-400 mb-5 line-clamp-2">{{ $section->description }}</p>

            <!-- Progress Bar -->
            <div class="mb-4">
                <div class="flex items-center justify-between mb-1.5">
                    <span class="text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Progress Materi</span>
                    <span class="text-sm font-black {{ $c['text'] }}">{{ $progress['progress'] }}%</span>
                </div>
                <div class="w-full bg-slate-100 dark:bg-slate-800 rounded-full h-2.5 overflow-hidden">
                    <div class="{{ $c['bar'] }} h-full rounded-full transition-all duration-500" style="width: {{ $progress['progress'] }}%"></div>
                </div>
            </div>

            <!-- Stats -->
            <div class="grid grid-cols-3 gap-3 pt-4 border-t border-slate-100 dark:border-slate-800">
                <div class="text-center">
                    <p class="text-lg font-black text-slate-900 dark:text-white">{{ $progress['completed_materials'] }}/{{ $progress['total_materials'] }}</p>
                    <p class="text-[10px] font-medium text-slate-400 uppercase tracking-wider">Materi</p>
                </div>
                <div class="text-center">
                    <p class="text-lg font-black text-slate-900 dark:text-white">{{ $progress['practice_attempts'] }}</p>
                    <p class="text-[10px] font-medium text-slate-400 uppercase tracking-wider">Latihan</p>
                </div>
                <div class="text-center">
                    <p class="text-lg font-black {{ $c['text'] }}">{{ $progress['best_score'] }}%</p>
                    <p class="text-[10px] font-medium text-slate-400 uppercase tracking-wider">Top Skor</p>
                </div>
            </div>
        </div>
    </a>
    @endforeach
</div>

<!-- Quick Overview -->
<div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 p-6 shadow-sm">
    <h3 class="text-lg font-bold text-slate-900 dark:text-white mb-4 flex items-center gap-2">
        <span class="material-symbols-outlined text-primary">insights</span>
        Ringkasan Belajar
    </h3>
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        @php
            $totalMaterials = collect($sectionProgress)->sum('total_materials');
            $totalCompleted = collect($sectionProgress)->sum('completed_materials');
            $totalAttempts = collect($sectionProgress)->sum('practice_attempts');
            $overallProgress = $totalMaterials > 0 ? round(($totalCompleted / $totalMaterials) * 100) : 0;
        @endphp
        <div class="bg-slate-50 dark:bg-slate-800/50 rounded-xl p-4 text-center">
            <p class="text-2xl font-black text-slate-900 dark:text-white">{{ $totalCompleted }}</p>
            <p class="text-xs font-medium text-slate-500 mt-1">Materi Dibaca</p>
        </div>
        <div class="bg-slate-50 dark:bg-slate-800/50 rounded-xl p-4 text-center">
            <p class="text-2xl font-black text-slate-900 dark:text-white">{{ $totalMaterials }}</p>
            <p class="text-xs font-medium text-slate-500 mt-1">Total Materi</p>
        </div>
        <div class="bg-slate-50 dark:bg-slate-800/50 rounded-xl p-4 text-center">
            <p class="text-2xl font-black text-primary">{{ $totalAttempts }}</p>
            <p class="text-xs font-medium text-slate-500 mt-1">Latihan Selesai</p>
        </div>
        <div class="bg-slate-50 dark:bg-slate-800/50 rounded-xl p-4 text-center">
            <p class="text-2xl font-black text-emerald-600">{{ $overallProgress }}%</p>
            <p class="text-xs font-medium text-slate-500 mt-1">Progress Keseluruhan</p>
        </div>
    </div>
</div>
@endsection
