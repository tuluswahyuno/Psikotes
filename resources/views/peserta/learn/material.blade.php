@extends('layouts.main')
@section('title', $subTopic->title . ' – ' . $section->name)

@php
    $colors = [
        'blue' => ['bg' => 'bg-blue-500', 'text' => 'text-blue-600 dark:text-blue-400', 'light' => 'bg-blue-50 dark:bg-blue-900/20', 'border' => 'border-blue-200 dark:border-blue-800'],
        'purple' => ['bg' => 'bg-purple-500', 'text' => 'text-purple-600 dark:text-purple-400', 'light' => 'bg-purple-50 dark:bg-purple-900/20', 'border' => 'border-purple-200 dark:border-purple-800'],
        'emerald' => ['bg' => 'bg-emerald-500', 'text' => 'text-emerald-600 dark:text-emerald-400', 'light' => 'bg-emerald-50 dark:bg-emerald-900/20', 'border' => 'border-emerald-200 dark:border-emerald-800'],
    ];
    $c = $colors[$section->color] ?? $colors['blue'];
@endphp

@section('content')
<div class="max-w-6xl mx-auto">
    <!-- Breadcrumb -->
    <div class="flex items-center gap-2 text-sm text-slate-500 dark:text-slate-400 mb-6">
        <a href="{{ route('peserta.learn.index') }}" class="hover:text-primary transition-colors">Belajar SKD</a>
        <span class="material-symbols-outlined text-xs">chevron_right</span>
        <a href="{{ route('peserta.learn.section', $section->slug) }}" class="hover:text-primary transition-colors">{{ $section->name }}</a>
        <span class="material-symbols-outlined text-xs">chevron_right</span>
        <span class="font-semibold text-slate-700 dark:text-slate-300">{{ $subTopic->title }}</span>
    </div>

    <!-- Header -->
    <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm mb-6 overflow-hidden">
        <div class="h-1.5 {{ $c['bg'] }}"></div>
        <div class="p-6">
            <div class="flex items-center gap-4">
                <div class="size-12 rounded-xl {{ $c['bg'] }} text-white flex items-center justify-center shadow-lg">
                    <span class="material-symbols-outlined text-xl">{{ $section->icon }}</span>
                </div>
                <div>
                    <p class="text-xs font-bold {{ $c['text'] }} uppercase tracking-wider">{{ $section->name }}</p>
                    <h2 class="text-xl font-black text-slate-900 dark:text-white mt-0.5">{{ $subTopic->title }}</h2>
                    <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">{{ $subTopic->description }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Materials -->
    <div class="space-y-6">
        @forelse($materials as $material)
        <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm overflow-hidden" x-data="{ expanded: {{ $loop->first ? 'true' : 'false' }} }">
            <button @click="expanded = !expanded" class="w-full flex items-center justify-between p-5 md:p-6 text-left hover:bg-slate-50/50 dark:hover:bg-slate-800/50 transition-colors">
                <div class="flex items-center gap-3">
                    @if(in_array($material->id, $completedIds))
                        <div class="size-8 rounded-lg bg-emerald-100 dark:bg-emerald-900/30 text-emerald-600 flex items-center justify-center shrink-0">
                            <span class="material-symbols-outlined text-lg">check_circle</span>
                        </div>
                    @else
                        <div class="size-8 rounded-lg {{ $c['light'] }} {{ $c['text'] }} flex items-center justify-center shrink-0">
                            <span class="font-bold text-sm">{{ $loop->iteration }}</span>
                        </div>
                    @endif
                    <div>
                        <h3 class="font-bold text-slate-900 dark:text-white">{{ $material->title }}</h3>
                        @if(in_array($material->id, $completedIds))
                            <p class="text-[10px] font-medium text-emerald-600 uppercase tracking-wider mt-0.5">✅ Selesai dibaca</p>
                        @else
                            <p class="text-[10px] font-medium text-slate-400 uppercase tracking-wider mt-0.5">Belum dibaca</p>
                        @endif
                    </div>
                </div>
                <span class="material-symbols-outlined text-slate-400 transition-transform duration-200" :class="expanded ? 'rotate-180' : ''">expand_more</span>
            </button>

            <div x-show="expanded" x-transition x-cloak>
                <div class="px-5 md:px-6 pb-5 md:pb-6 border-t border-slate-100 dark:border-slate-800">
                    <!-- Material Content -->
                    <div class="prose prose-slate dark:prose-invert prose-sm max-w-none mt-5">
                        {!! $material->content !!}
                    </div>

                    <!-- Mark Complete Button -->
                    @if(!in_array($material->id, $completedIds))
                    <div class="mt-6 pt-4 border-t border-slate-100 dark:border-slate-800">
                        <form action="{{ route('peserta.learn.complete') }}" method="POST">
                            @csrf
                            <input type="hidden" name="material_id" value="{{ $material->id }}">
                            <button type="submit" class="inline-flex items-center gap-2 px-6 py-2.5 rounded-xl bg-emerald-500 hover:bg-emerald-600 text-white font-bold text-sm shadow-lg shadow-emerald-500/20 transition-all">
                                <span class="material-symbols-outlined text-lg">task_alt</span>
                                Tandai Selesai Dibaca
                            </button>
                        </form>
                    </div>
                    @endif
                </div>
            </div>
        </div>
        @empty
        <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 p-12 text-center">
            <span class="material-symbols-outlined text-5xl text-slate-300 dark:text-slate-600 mb-3 block">auto_stories</span>
            <p class="text-slate-500 dark:text-slate-400 font-medium">Materi belum tersedia untuk sub-topik ini.</p>
        </div>
        @endforelse
    </div>

    <!-- Bottom Navigation -->
    <div class="flex flex-col sm:flex-row items-center justify-between gap-4 mt-8 pt-6 border-t border-slate-200 dark:border-slate-800">
        <div class="flex gap-3">
            @if($prevSubTopic)
            <a href="{{ route('peserta.learn.material', [$section->slug, $prevSubTopic->slug]) }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 font-bold text-sm hover:bg-slate-200 dark:hover:bg-slate-700 transition-all">
                <span class="material-symbols-outlined text-lg">arrow_back</span>
                {{ Str::limit($prevSubTopic->title, 20) }}
            </a>
            @endif
        </div>
        
        <div class="flex gap-3">
            @if($questionsCount > 0)
            <a href="{{ route('peserta.practice.start', [$section->slug, $subTopic->slug]) }}" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-amber-500 hover:bg-amber-600 text-white font-bold text-sm shadow-lg shadow-amber-500/20 transition-all">
                <span class="material-symbols-outlined text-lg">edit_note</span>
                Latihan Soal ({{ $questionsCount }})
            </a>
            @endif

            @if($nextSubTopic)
            <a href="{{ route('peserta.learn.material', [$section->slug, $nextSubTopic->slug]) }}" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl {{ $c['bg'] }} text-white font-bold text-sm shadow-lg hover:opacity-90 transition-all">
                {{ Str::limit($nextSubTopic->title, 20) }}
                <span class="material-symbols-outlined text-lg">arrow_forward</span>
            </a>
            @endif
        </div>
    </div>
</div>
@endsection
