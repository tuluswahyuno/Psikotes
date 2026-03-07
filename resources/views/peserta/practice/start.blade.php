@extends('layouts.main')
@section('title', 'Latihan: ' . $subTopic->title)

@php
    $colors = [
        'blue' => ['bg' => 'bg-blue-500', 'text' => 'text-blue-600 dark:text-blue-400', 'light' => 'bg-blue-50 dark:bg-blue-900/20'],
        'purple' => ['bg' => 'bg-purple-500', 'text' => 'text-purple-600 dark:text-purple-400', 'light' => 'bg-purple-50 dark:bg-purple-900/20'],
        'emerald' => ['bg' => 'bg-emerald-500', 'text' => 'text-emerald-600 dark:text-emerald-400', 'light' => 'bg-emerald-50 dark:bg-emerald-900/20'],
    ];
    $c = $colors[$section->color] ?? $colors['blue'];
    $totalQ = $questions->count();
    $lastIndex = $totalQ - 1;
@endphp

@section('content')
<div class="max-w-6xl mx-auto" x-data="{
    currentIndex: 0,
    answers: {},
    submitting: false,
    totalQuestions: {{ $totalQ }},
    next() { if (this.currentIndex < {{ $lastIndex }}) this.currentIndex++; },
    prev() { if (this.currentIndex > 0) this.currentIndex--; },
    isLast() { return this.currentIndex === {{ $lastIndex }}; }
}">
    <!-- Breadcrumb -->
    <div class="flex items-center gap-2 text-sm text-slate-500 dark:text-slate-400 mb-8 bg-white dark:bg-slate-900 p-3 px-5 rounded-xl border border-slate-200 dark:border-slate-800 shadow-sm w-fit">
        <a href="{{ route('peserta.learn.index') }}" class="hover:text-primary transition-colors font-semibold">Belajar SKD</a>
        <span class="material-symbols-outlined text-sm">chevron_right</span>
        <a href="{{ route('peserta.learn.section', $section->slug) }}" class="hover:text-primary transition-colors font-semibold">{{ $section->name }}</a>
        <span class="material-symbols-outlined text-sm">chevron_right</span>
        <span class="font-bold text-primary">Latihan Soal</span>
    </div>

    <!-- Header Card -->
    <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm mb-6 overflow-hidden">
        <div class="h-1.5 {{ $c['bg'] }}"></div>
        <div class="p-5 md:p-6">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="size-10 rounded-xl {{ $c['bg'] }} text-white flex items-center justify-center">
                        <span class="material-symbols-outlined">edit_note</span>
                    </div>
                    <div>
                        <h2 class="text-lg font-black text-slate-900 dark:text-white">Latihan: {{ $subTopic->title }}</h2>
                        <p class="text-xs text-slate-500">{{ $totalQ }} soal • {{ $section->name }}</p>
                    </div>
                </div>
                <div class="text-right hidden md:block">
                    <p class="text-sm font-bold {{ $c['text'] }}">Soal <span x-text="currentIndex + 1"></span>/{{ $totalQ }}</p>
                    <div class="w-32 bg-slate-100 dark:bg-slate-800 rounded-full h-1.5 mt-1 overflow-hidden">
                        <div class="{{ $c['bg'] }} h-full rounded-full transition-all duration-300" :style="'width: ' + ((currentIndex + 1) / totalQuestions * 100) + '%'"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quiz Form -->
    <form action="{{ route('peserta.practice.submit') }}" method="POST">
        @csrf
        <input type="hidden" name="sub_topic_id" value="{{ $subTopic->id }}">

        @foreach($questions as $index => $question)
        <div x-show="currentIndex === {{ $index }}" x-transition class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm mb-6 overflow-hidden">
            <div class="p-5 md:p-6">
                <!-- Question Number Badge -->
                <div class="flex items-center gap-2 mb-4">
                    <span class="inline-flex items-center gap-1 px-3 py-1 rounded-lg text-xs font-bold {{ $c['light'] }} {{ $c['text'] }}">
                        Soal {{ $index + 1 }}
                    </span>
                </div>
                
                <!-- Question Text -->
                <p class="text-lg font-semibold text-slate-900 dark:text-white mb-6 leading-relaxed">{{ $question->question }}</p>
                
                <!-- Options -->
                <div class="space-y-3">
                    @foreach($question->options as $key => $option)
                    <label class="flex items-start gap-3 p-4 rounded-xl border-2 cursor-pointer transition-all duration-200"
                           :class="answers['q{{ $question->id }}'] === '{{ $key }}' ? '{{ str_replace('bg-', 'border-', $c['bg']) }} {{ $c['light'] }}' : 'border-slate-200 dark:border-slate-700 hover:border-slate-300 dark:hover:border-slate-600 hover:bg-slate-50 dark:hover:bg-slate-800/50'">
                        <input type="radio" name="answers[{{ $question->id }}]" value="{{ $key }}" x-model="answers['q{{ $question->id }}']" class="mt-1 text-primary focus:ring-primary/20">
                        <div>
                            <span class="inline-flex items-center justify-center size-6 rounded-md text-xs font-black mr-2"
                                  :class="answers['q{{ $question->id }}'] === '{{ $key }}' ? '{{ $c['bg'] }} text-white' : 'bg-slate-100 dark:bg-slate-800 text-slate-500'">{{ $key }}</span>
                            <span class="text-sm font-medium text-slate-700 dark:text-slate-300">{{ $option }}</span>
                        </div>
                    </label>
                    @endforeach
                </div>
            </div>
        </div>
        @endforeach

        <!-- Navigation -->
        <div class="flex items-center justify-between">
            <div>
                <button type="button" @click="prev()" x-show="currentIndex > 0" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 font-bold text-sm hover:bg-slate-200 dark:hover:bg-slate-700 transition-all">
                    <span class="material-symbols-outlined text-lg">arrow_back</span>
                    Sebelumnya
                </button>
            </div>

            <div class="flex gap-3">
                <button type="button" @click="next()" x-show="currentIndex < {{ $lastIndex }}" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl {{ $c['bg'] }} text-white font-bold text-sm shadow-lg hover:opacity-90 transition-all">
                    Selanjutnya
                    <span class="material-symbols-outlined text-lg">arrow_forward</span>
                </button>

                <button type="submit" x-show="currentIndex === {{ $lastIndex }}" style="display: none;" class="inline-flex items-center gap-2 px-6 py-2.5 rounded-xl bg-emerald-500 hover:bg-emerald-600 text-white font-bold text-sm shadow-lg shadow-emerald-500/20 transition-all">
                    <span class="material-symbols-outlined text-lg">send</span>
                    <span>Submit Jawaban</span>
                </button>
            </div>
        </div>
    </form>

    <!-- Question Navigator (Bottom) -->
    <div class="mt-6 bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm p-4">
        <p class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-3">Navigasi Soal</p>
        <div class="flex flex-wrap gap-2">
            @foreach($questions as $index => $question)
            <button type="button" @click="currentIndex = {{ $index }}" class="size-9 rounded-lg text-xs font-bold transition-all flex items-center justify-center"
                    :class="currentIndex === {{ $index }} ? '{{ $c['bg'] }} text-white shadow-lg' : (answers['q{{ $question->id }}'] ? 'bg-emerald-100 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-300 border border-emerald-300 dark:border-emerald-700' : 'bg-slate-100 dark:bg-slate-800 text-slate-500 hover:bg-slate-200 dark:hover:bg-slate-700')">
                {{ $index + 1 }}
            </button>
            @endforeach
        </div>
    </div>
</div>
@endsection
