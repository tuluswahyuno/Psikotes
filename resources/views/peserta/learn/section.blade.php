@extends('layouts.main')
@section('title', $section->name . ' – Belajar SKD')

@php
    $colors = [
        'blue' => ['bg' => 'bg-blue-500', 'light' => 'bg-blue-50 dark:bg-blue-900/20', 'text' => 'text-blue-600 dark:text-blue-400', 'border' => 'border-blue-200 dark:border-blue-800', 'bar' => 'bg-blue-500', 'badge' => 'bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-300'],
        'purple' => ['bg' => 'bg-purple-500', 'light' => 'bg-purple-50 dark:bg-purple-900/20', 'text' => 'text-purple-600 dark:text-purple-400', 'border' => 'border-purple-200 dark:border-purple-800', 'bar' => 'bg-purple-500', 'badge' => 'bg-purple-100 dark:bg-purple-900/30 text-purple-700 dark:text-purple-300'],
        'emerald' => ['bg' => 'bg-emerald-500', 'light' => 'bg-emerald-50 dark:bg-emerald-900/20', 'text' => 'text-emerald-600 dark:text-emerald-400', 'border' => 'border-emerald-200 dark:border-emerald-800', 'bar' => 'bg-emerald-500', 'badge' => 'bg-emerald-100 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-300'],
    ];
    $c = $colors[$section->color] ?? $colors['blue'];
@endphp

@section('content')
<!-- Breadcrumb + Header -->
<div class="mb-8">
    <div class="flex items-center gap-2 text-sm text-slate-500 dark:text-slate-400 mb-4">
        <a href="{{ route('peserta.learn.index') }}" class="hover:text-primary transition-colors">Belajar SKD</a>
        <span class="material-symbols-outlined text-xs">chevron_right</span>
        <span class="font-semibold {{ $c['text'] }}">{{ $section->name }}</span>
    </div>

    <div class="flex flex-wrap items-center justify-between gap-4">
        <div class="flex items-center gap-4">
            <div class="size-14 rounded-xl {{ $c['bg'] }} text-white flex items-center justify-center shadow-lg">
                <span class="material-symbols-outlined text-2xl">{{ $section->icon }}</span>
            </div>
            <div>
                <h2 class="text-2xl font-black text-slate-900 dark:text-white tracking-tight">{{ $section->name }}</h2>
                <p class="text-sm text-slate-500 dark:text-slate-400 mt-0.5">{{ $section->description }}</p>
            </div>
        </div>
        <a href="{{ route('peserta.learn.index') }}" class="text-sm font-medium text-slate-600 dark:text-slate-400 hover:text-primary transition-colors flex items-center gap-1">
            <span class="material-symbols-outlined text-lg">arrow_back</span>
            Kembali
        </a>
    </div>
</div>

<!-- Sub-topic List -->
<div class="space-y-4">
    @foreach($section->subTopics as $subTopic)
    @php
        $sp = $subTopicProgress[$subTopic->id];
    @endphp
    <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm hover:shadow-md transition-all overflow-hidden">
        <div class="p-5 md:p-6">
            <div class="flex flex-col md:flex-row md:items-center gap-4">
                <!-- Left: Info -->
                <div class="flex-1">
                    <div class="flex items-start gap-3">
                        <div class="size-10 rounded-xl {{ $c['light'] }} {{ $c['text'] }} flex items-center justify-center shrink-0 mt-0.5">
                            @if($sp['is_material_complete'])
                                <span class="material-symbols-outlined text-emerald-500">check_circle</span>
                            @else
                                <span class="font-black text-sm">{{ $loop->iteration }}</span>
                            @endif
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-slate-900 dark:text-white">{{ $subTopic->title }}</h3>
                            <p class="text-sm text-slate-500 dark:text-slate-400 mt-0.5">{{ $subTopic->description }}</p>
                            
                            <!-- Tags -->
                            <div class="flex flex-wrap gap-2 mt-3">
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-[11px] font-bold {{ $c['badge'] }}">
                                    <span class="material-symbols-outlined text-xs">menu_book</span>
                                    {{ $sp['total_materials'] }} Materi
                                </span>
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-[11px] font-bold bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400">
                                    <span class="material-symbols-outlined text-xs">quiz</span>
                                    {{ $sp['questions_count'] }} Soal
                                </span>
                                @if($sp['attempts'] > 0)
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-[11px] font-bold bg-amber-100 dark:bg-amber-900/30 text-amber-700 dark:text-amber-300">
                                    <span class="material-symbols-outlined text-xs">emoji_events</span>
                                    Best: {{ $sp['best_score'] }}%
                                </span>
                                @endif
                            </div>
                        </div>
                    </div>
                    
                    <!-- Progress bar -->
                    @if($sp['total_materials'] > 0)
                    <div class="mt-4 ml-13">
                        <div class="flex items-center justify-between mb-1">
                            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Materi: {{ $sp['completed_materials'] }}/{{ $sp['total_materials'] }}</span>
                            <span class="text-[10px] font-bold {{ $c['text'] }}">{{ $sp['material_progress'] }}%</span>
                        </div>
                        <div class="w-full bg-slate-100 dark:bg-slate-800 rounded-full h-1.5 overflow-hidden">
                            <div class="{{ $c['bar'] }} h-full rounded-full transition-all duration-500" style="width: {{ $sp['material_progress'] }}%"></div>
                        </div>
                    </div>
                    @endif
                </div>

                <!-- Right: Actions -->
                <div class="flex gap-2 md:flex-col md:items-end shrink-0">
                    <a href="{{ route('peserta.learn.material', [$section->slug, $subTopic->slug]) }}" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl {{ $c['bg'] }} text-white font-bold text-sm shadow-lg hover:opacity-90 transition-all">
                        <span class="material-symbols-outlined text-lg">menu_book</span>
                        Baca Materi
                    </a>
                    @if($sp['questions_count'] > 0)
                    <a href="{{ route('peserta.practice.start', [$section->slug, $subTopic->slug]) }}" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 font-bold text-sm hover:bg-slate-200 dark:hover:bg-slate-700 transition-all">
                        <span class="material-symbols-outlined text-lg">edit_note</span>
                        Latihan
                    </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
    @endforeach
</div>
@endsection
