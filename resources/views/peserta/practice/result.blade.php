@extends('layouts.main')
@section('title', 'Review Hasil Latihan – ' . $attempt->subTopic->title)

@push('styles')
<style>
    .circular-progress {
        background: radial-gradient(closest-side, white 79%, transparent 80% 100%),
                    conic-gradient(#135bec {{ $attempt->score ?? 0 }}%, #e2e8f0 0);
    }
    .dark .circular-progress {
        background: radial-gradient(closest-side, #0f172a 79%, transparent 80% 100%),
                    conic-gradient(#135bec {{ $attempt->score ?? 0 }}%, #1e293b 0);
    }
</style>
@endpush

@php
    $section = $attempt->subTopic->section;
    $isPassed = $attempt->score >= 65;
    $benarPerc = $attempt->total_questions > 0 ? round(($attempt->correct_answers / $attempt->total_questions) * 100) : 0;
    $salahPerc = $attempt->total_questions > 0 ? round((($attempt->total_questions - $attempt->correct_answers) / $attempt->total_questions) * 100) : 0;
@endphp

@section('content')
<div class="w-full max-w-7xl mx-auto">
    <!-- Title & Actions -->
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 mb-8">
        <div>
            <h1 class="text-3xl font-extrabold text-slate-900 dark:text-white leading-tight">Review Hasil Latihan</h1>
            <p class="text-slate-500 mt-1 flex items-center gap-2">
                <span class="material-symbols-outlined text-lg">description</span>
                SKD - {{ $section->name }} • {{ $attempt->subTopic->title }}
            </p>
        </div>
        <div class="flex flex-wrap gap-3">
            <a href="{{ route('peserta.dashboard') }}" class="flex items-center gap-2 px-5 py-2.5 bg-slate-200 dark:bg-slate-800 hover:bg-slate-300 dark:hover:bg-slate-700 font-bold rounded-lg transition-all text-slate-800 dark:text-slate-200">
                <span class="material-symbols-outlined text-lg">home</span>
                Dashboard
            </a>
            <a href="{{ route('peserta.practice.start', [$section->slug, $attempt->subTopic->slug]) }}" class="flex items-center gap-2 px-5 py-2.5 bg-primary text-white hover:bg-blue-700 font-bold rounded-lg shadow-lg shadow-primary/20 transition-all">
                <span class="material-symbols-outlined text-lg">refresh</span>
                Ulangi Latihan
            </a>
        </div>
    </div>

    <!-- Score Overview Card -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 mb-12">
        <div class="lg:col-span-4 bg-white dark:bg-slate-900 p-8 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-800 flex flex-col items-center justify-center text-center">
            <div class="relative w-40 h-40 circular-progress rounded-full flex items-center justify-center mb-6 shadow-sm">
                <div class="flex flex-col items-center">
                    <span class="text-4xl font-black text-primary leading-none">{{ number_format($attempt->score, 0) }}</span>
                    <span class="text-slate-500 font-bold text-[10px] mt-1 uppercase tracking-[0.2em]">Skor Akhir</span>
                </div>
            </div>
            @if($isPassed)
            <div class="flex items-center gap-2 px-4 py-1.5 bg-emerald-100 dark:bg-emerald-900/40 text-emerald-600 dark:text-emerald-400 rounded-full text-sm font-bold border border-emerald-200 dark:border-emerald-800">
                <span class="material-symbols-outlined text-lg">verified</span>
                Lulus Ambang Batas
            </div>
            @else
            <div class="flex items-center gap-2 px-4 py-1.5 bg-rose-100 dark:bg-rose-900/40 text-rose-600 dark:text-rose-400 rounded-full text-sm font-bold border border-rose-200 dark:border-rose-800">
                <span class="material-symbols-outlined text-lg">cancel</span>
                Belum Lulus
            </div>
            @endif
        </div>
        
        <div class="lg:col-span-8 flex flex-col gap-6">
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div class="bg-white dark:bg-slate-900 p-6 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm">
                    <p class="text-slate-500 text-sm font-medium mb-1 uppercase">TOTAL SOAL</p>
                    <p class="text-3xl font-bold dark:text-white">{{ $attempt->total_questions }}</p>
                </div>
                <div class="bg-white dark:bg-slate-900 p-6 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm">
                    <p class="text-slate-500 text-sm font-medium mb-1 uppercase">BENAR</p>
                    <div class="flex items-baseline gap-2">
                        <p class="text-3xl font-bold text-emerald-600 dark:text-emerald-500">{{ $attempt->correct_answers }}</p>
                        <span class="text-emerald-600 dark:text-emerald-500 text-sm font-semibold">+{{ $benarPerc }}%</span>
                    </div>
                </div>
                <div class="bg-white dark:bg-slate-900 p-6 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm">
                    <p class="text-slate-500 text-sm font-medium mb-1 uppercase">SALAH</p>
                    <div class="flex items-baseline gap-2">
                        <p class="text-3xl font-bold text-rose-600 dark:text-rose-500">{{ $attempt->total_questions - $attempt->correct_answers }}</p>
                        <span class="text-rose-600 dark:text-rose-500 text-sm font-semibold">-{{ $salahPerc }}%</span>
                    </div>
                </div>
            </div>
            <div class="flex-1 bg-primary/5 dark:bg-primary/10 p-6 rounded-2xl border border-primary/20 flex gap-4">
                <span class="material-symbols-outlined text-primary text-3xl">emoji_events</span>
                <div>
                    @if($isPassed)
                        <h4 class="font-bold text-primary mb-1">Kerja bagus!</h4>
                        <p class="text-slate-700 dark:text-slate-300 text-sm leading-relaxed">
                            Kamu sudah melampaui rata-rata ambang batas. Pertahankan performamu dan fokus baca materi tambahan untuk simulasi berikutnya.
                        </p>
                    @else
                        <h4 class="font-bold text-primary mb-1">Terus Berusaha!</h4>
                        <p class="text-slate-700 dark:text-slate-300 text-sm leading-relaxed">
                            Nilaimu belum mencapai target. Cek kembali materi yang masih kurang dipahami.
                        </p>
                    @endif
                    <a href="{{ route('peserta.learn.material', [$section->slug, $attempt->subTopic->slug]) }}" class="inline-flex mt-4 text-primary text-sm font-bold flex-row items-center gap-1 hover:gap-2 transition-all group">
                        Baca Materi {{ Str::limit($attempt->subTopic->title, 15) }} 
                        <span class="material-symbols-outlined text-sm group-hover:translate-x-1 transition-transform">arrow_forward</span>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Question Discussion Section -->
    <div class="space-y-6" x-data="{ filter: 'all' }">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <h3 class="text-xl font-bold flex items-center gap-2 dark:text-white">
                <span class="material-symbols-outlined text-primary">forum</span>
                Pembahasan Jawaban
            </h3>
            <div class="flex gap-2">
                <button @click="filter = 'all'" :class="filter === 'all' ? 'bg-slate-200 dark:bg-slate-800 text-slate-800 dark:text-white' : 'bg-white dark:bg-slate-900 border-slate-200 dark:border-slate-800 text-slate-600 dark:text-slate-400'" class="px-4 py-1.5 border hover:bg-slate-100 hover:dark:bg-slate-800 rounded-lg text-xs font-bold transition-all">Semua ({{ $attempt->total_questions }})</button>
                <button @click="filter = 'correct'" :class="filter === 'correct' ? 'bg-emerald-100 dark:bg-emerald-900/40 text-emerald-700 dark:text-emerald-300 border-emerald-300 dark:border-emerald-600' : 'bg-emerald-50 dark:bg-emerald-900/10 text-emerald-600 dark:text-emerald-400 border-emerald-100 dark:border-emerald-900/40 hover:bg-emerald-100 hover:dark:bg-emerald-900/20'" class="px-4 py-1.5 border rounded-lg text-xs font-bold transition-all">Benar ({{ $attempt->correct_answers }})</button>
                <button @click="filter = 'wrong'" :class="filter === 'wrong' ? 'bg-rose-100 dark:bg-rose-900/40 text-rose-700 dark:text-rose-300 border-rose-300 dark:border-rose-600' : 'bg-rose-50 dark:bg-rose-900/10 text-rose-600 dark:text-rose-400 border-rose-100 dark:border-rose-900/40 hover:bg-rose-100 hover:dark:bg-rose-900/20'" class="px-4 py-1.5 border rounded-lg text-xs font-bold transition-all">Salah ({{ $attempt->total_questions - $attempt->correct_answers }})</button>
            </div>
        </div>

        @foreach($attempt->answers as $index => $answer)
        @php
            $question = $answer->question;
        @endphp
        <div x-show="filter === 'all' || (filter === 'correct' && {{ $answer->is_correct ? 'true' : 'false' }}) || (filter === 'wrong' && {{ !$answer->is_correct ? 'true' : 'false' }})" 
             x-transition x-data="{ open: false }" 
             class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm overflow-hidden">
            <button @click="open = !open" class="w-full flex items-center justify-between p-4 md:p-5 text-left hover:bg-slate-50/50 dark:hover:bg-slate-800/50 transition-colors">
                <div class="flex items-center gap-3">
                    @if($answer->is_correct)
                        <div class="size-8 rounded-lg bg-emerald-100 dark:bg-emerald-900/30 text-emerald-600 flex items-center justify-center shrink-0">
                            <span class="material-symbols-outlined text-lg">check_circle</span>
                        </div>
                    @else
                        <div class="size-8 rounded-lg bg-rose-100 dark:bg-rose-900/30 text-rose-600 flex items-center justify-center shrink-0">
                            <span class="material-symbols-outlined text-lg">cancel</span>
                        </div>
                    @endif
                    <div>
                        <p class="text-sm font-bold text-slate-900 dark:text-white">Soal {{ $index + 1 }}</p>
                        <p class="text-xs text-slate-500 line-clamp-1">{{ Str::limit($question->question, 60) }}</p>
                    </div>
                </div>
                <span class="material-symbols-outlined text-slate-400 transition-transform duration-200" :class="open ? 'rotate-180' : ''">expand_more</span>
            </button>

            <div x-show="open" x-transition x-cloak class="px-4 md:px-5 pb-4 md:pb-5 border-t border-slate-100 dark:border-slate-800">
                <p class="text-sm font-semibold text-slate-900 dark:text-white mt-4 mb-4">{{ $question->question }}</p>

                <div class="space-y-2 mb-4">
                    @foreach($question->options as $key => $option)
                    <div class="flex items-start gap-2 p-3 rounded-lg text-sm
                        @if($key === $question->correct_answer) bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-200 dark:border-emerald-800
                        @elseif($key === $answer->user_answer && !$answer->is_correct) bg-rose-50 dark:bg-rose-900/20 border border-rose-200 dark:border-rose-800
                        @else bg-slate-50 dark:bg-slate-800/50 border border-transparent @endif">
                        <span class="font-bold shrink-0 mt-0.5
                            @if($key === $question->correct_answer) text-emerald-600
                            @elseif($key === $answer->user_answer && !$answer->is_correct) text-rose-600
                            @else text-slate-400 @endif">{{ $key }}.</span>
                        <span class="@if($key === $question->correct_answer) text-emerald-700 dark:text-emerald-300 font-semibold
                            @elseif($key === $answer->user_answer && !$answer->is_correct) text-rose-700 dark:text-rose-300 line-through
                            @else text-slate-600 dark:text-slate-400 @endif">{{ $option }}</span>
                        @if($key === $question->correct_answer)
                            <span class="material-symbols-outlined text-emerald-500 text-sm ml-auto shrink-0">check_circle</span>
                        @elseif($key === $answer->user_answer && !$answer->is_correct)
                            <span class="material-symbols-outlined text-rose-500 text-sm ml-auto shrink-0">cancel</span>
                        @endif
                    </div>
                    @endforeach
                </div>

                @if($question->explanation)
                <div class="bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-800 rounded-xl p-4">
                    <p class="text-xs font-bold text-amber-700 dark:text-amber-300 uppercase tracking-wider mb-1 flex items-center gap-1">
                        <span class="material-symbols-outlined text-sm">lightbulb</span>
                        Pembahasan
                    </p>
                    <p class="text-sm text-amber-800 dark:text-amber-200">{{ $question->explanation }}</p>
                </div>
                @endif
            </div>
        </div>
        @endforeach

        <div class="flex justify-center pt-8 pb-4">
            <a href="{{ route('peserta.learn.material', [$section->slug, $attempt->subTopic->slug]) }}" class="px-8 py-3 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl font-bold hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors shadow-sm text-slate-800 dark:text-slate-200 block">
                Pelajari Lagi Sub-topik Ini
            </a>
        </div>
    </div>
</div>
@endsection
