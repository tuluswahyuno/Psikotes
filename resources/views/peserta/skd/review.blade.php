@extends('layouts.main')
@section('title', 'Review Pembahasan')

@section('content')
@php
    $questionsData = $questions->map(function($q) {
        return [
            'id' => $q->id,
            'number' => $q->number,
            'sub_test_type' => strtoupper($q->sub_test_type),
            'sub_test_label' => $q->sub_test_label,
            'text' => $q->question_text,
            'explanation' => $q->explanation ?? 'Tidak ada pembahasan detail untuk soal ini.',
            'status' => $q->status, // 'benar', 'salah', 'kosong'
            'user_answer_id' => $q->user_answer_id,
            'options' => $q->options->map(function($opt) {
                return [
                    'id' => $opt->id,
                    'text' => $opt->option_text,
                    'is_correct' => (bool)$opt->is_correct,
                    'points' => (int)$opt->score
                ];
            })->values()->all()
        ];
    })->values()->all();
@endphp

<div class="max-w-7xl mx-auto space-y-8" x-data="reviewData()">
    <!-- Breadcrumbs -->
    <nav class="flex flex-wrap items-center gap-2 mb-6">
        <a class="text-primary text-sm font-medium" href="{{ route('peserta.dashboard') }}">Dashboard</a>
        <span class="material-symbols-outlined text-slate-400 text-sm">chevron_right</span>
        <a class="text-primary text-sm font-medium" href="{{ route('peserta.skd.results.show', $skdResult) }}">Laporan Simulasi</a>
        <span class="material-symbols-outlined text-slate-400 text-sm">chevron_right</span>
        <span class="text-slate-500 dark:text-slate-400 text-sm font-medium">{{ $skdResult->package->title }}</span>
    </nav>

    <!-- Page Title Section -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-8">
        <div class="flex flex-col gap-1">
            <h1 class="text-slate-900 dark:text-white text-3xl font-black leading-tight tracking-tight">Review &amp; Pembahasan</h1>
            <p class="text-slate-500 dark:text-slate-400 text-lg">{{ $skdResult->package->title }} - {{ $skdResult->package->description ?? 'Simulasi Sesuai Standar CAT BKN' }}</p>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('peserta.skd.results.show', $skdResult) }}" class="flex items-center justify-center rounded-lg h-11 px-6 bg-slate-100 dark:bg-slate-800 text-slate-900 dark:text-white text-sm font-bold hover:bg-slate-200 transition-all border border-slate-200 dark:border-slate-700">
                <span class="material-symbols-outlined mr-2">arrow_back</span> Kembali
            </a>
            <button class="flex items-center justify-center rounded-lg h-11 px-6 bg-primary text-white text-sm font-bold shadow-lg shadow-primary/20 hover:bg-primary/90 transition-all" onclick="window.print()">
                Unduh PDF Pembahasan
            </button>
        </div>
    </div>

    <!-- Filters Section -->
    <div class="flex flex-wrap gap-3 mb-8 overflow-x-auto pb-2 scrollbar-hide">
        <button @click="setFilter('all')" 
                :class="filter === 'all' ? 'bg-primary text-white border-primary' : 'bg-white dark:bg-slate-800 border-slate-200 dark:border-slate-700 text-slate-700 dark:text-slate-200 hover:bg-slate-50'"
                class="flex h-10 shrink-0 items-center justify-center gap-x-2 rounded-full border px-5 transition-all">
            <span class="text-sm font-semibold">Semua Soal</span>
        </button>
        <button @click="setFilter('twk')" 
                :class="filter === 'twk' ? 'bg-primary text-white border-primary' : 'bg-white dark:bg-slate-800 border-slate-200 dark:border-slate-700 text-slate-700 dark:text-slate-200 hover:bg-slate-50'"
                class="flex h-10 shrink-0 items-center justify-center gap-x-2 rounded-full border px-5 transition-all">
            <span class="text-sm font-medium">TWK</span>
        </button>
        <button @click="setFilter('tiu')" 
                :class="filter === 'tiu' ? 'bg-primary text-white border-primary' : 'bg-white dark:bg-slate-800 border-slate-200 dark:border-slate-700 text-slate-700 dark:text-slate-200 hover:bg-slate-50'"
                class="flex h-10 shrink-0 items-center justify-center gap-x-2 rounded-full border px-5 transition-all">
            <span class="text-sm font-medium">TIU</span>
        </button>
        <button @click="setFilter('tkp')" 
                :class="filter === 'tkp' ? 'bg-primary text-white border-primary' : 'bg-white dark:bg-slate-800 border-slate-200 dark:border-slate-700 text-slate-700 dark:text-slate-200 hover:bg-slate-50'"
                class="flex h-10 shrink-0 items-center justify-center gap-x-2 rounded-full border px-5 transition-all">
            <span class="text-sm font-medium">TKP</span>
        </button>
        <button @click="setFilter('salah')" 
                :class="filter === 'salah' ? 'bg-rose-500 text-white border-rose-500' : 'bg-rose-50 border-rose-200 text-rose-600 dark:bg-rose-900/20 dark:border-rose-800 dark:text-rose-400 hover:bg-rose-100'"
                class="flex h-10 shrink-0 items-center justify-center gap-x-2 rounded-full border px-5 transition-all">
            <span class="text-sm font-medium">Hanya Salah</span>
        </button>
    </div>

    <!-- Empty State -->
    <template x-if="filteredQuestions.length === 0">
        <div class="p-12 text-center bg-white dark:bg-slate-900 rounded-xl border border-slate-200 dark:border-slate-800">
            <span class="material-symbols-outlined text-6xl text-slate-300">search_off</span>
            <h3 class="mt-4 text-xl font-bold">Tidak ada soal.</h3>
            <p class="text-slate-500">Kategori filter ini tidak memuat soal apa-apa.</p>
        </div>
    </template>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8" x-show="filteredQuestions.length > 0">
        <!-- Main Content: Question and Answer -->
        <div class="lg:col-span-8 space-y-6">
            
            <!-- Question Card -->
            <div class="bg-white dark:bg-slate-900 rounded-xl border border-slate-200 dark:border-slate-800 overflow-hidden shadow-sm">
                <div class="p-6 border-b border-slate-100 dark:border-slate-800 flex justify-between items-center">
                    <div class="flex items-center gap-3">
                        <span class="bg-primary/10 text-primary px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider" x-text="`Nomor ${currentQ.number}`"></span>
                        <span class="text-slate-400 dark:text-slate-500 text-xs font-medium" x-text="`• ${currentQ.sub_test_type} - ${currentQ.sub_test_label}`"></span>
                    </div>
                    <div class="flex items-center gap-2">
                        <!-- Status Badge -->
                        <span x-show="currentQ.status === 'benar'" class="flex items-center gap-1 text-emerald-500 font-semibold text-sm">
                            <span class="material-symbols-outlined text-sm">check_circle</span> Benar
                        </span>
                        <span x-show="currentQ.status === 'salah'" class="flex items-center gap-1 text-rose-500 font-semibold text-sm">
                            <span class="material-symbols-outlined text-sm">cancel</span> Salah
                        </span>
                        <span x-show="currentQ.status === 'kosong'" class="flex items-center gap-1 text-slate-400 font-semibold text-sm">
                            <span class="material-symbols-outlined text-sm">hourglass_empty</span> Kosong
                        </span>
                    </div>
                </div>
                <div class="p-8">
                    <div class="text-slate-800 dark:text-slate-100 text-lg leading-relaxed mb-8" x-html="currentQ.text"></div>
                    
                    <!-- Answer Options -->
                    <div class="space-y-4">
                        <template x-for="(opt, index) in currentQ.options" :key="opt.id">
                            <div class="flex items-center p-4 rounded-lg border-2 relative"
                                 :class="{
                                     'border-emerald-500 bg-emerald-50 dark:bg-emerald-900/10': isOptionCorrect(opt, currentQ.sub_test_type),
                                     'border-rose-500 bg-rose-50 dark:bg-rose-900/10': !isOptionCorrect(opt, currentQ.sub_test_type) && opt.id == currentQ.user_answer_id,
                                     'border-slate-100 dark:border-slate-800 bg-slate-50 dark:bg-slate-800/50': !isOptionCorrect(opt, currentQ.sub_test_type) && opt.id != currentQ.user_answer_id
                                 }">
                                 
                                <span class="w-8 h-8 rounded-full flex items-center justify-center font-bold mr-4 shrink-0"
                                      :class="{
                                          'bg-emerald-500 text-white': isOptionCorrect(opt, currentQ.sub_test_type),
                                          'bg-rose-500 text-white': !isOptionCorrect(opt, currentQ.sub_test_type) && opt.id == currentQ.user_answer_id,
                                          'bg-slate-200 dark:bg-slate-700 text-slate-700 dark:text-slate-300': !isOptionCorrect(opt, currentQ.sub_test_type) && opt.id != currentQ.user_answer_id
                                      }"
                                      x-text="String.fromCharCode(65 + index)">
                                </span>
                                
                                <span class="font-medium" 
                                      :class="{
                                          'text-emerald-900 dark:text-emerald-200': isOptionCorrect(opt, currentQ.sub_test_type),
                                          'text-rose-900 dark:text-rose-200': !isOptionCorrect(opt, currentQ.sub_test_type) && opt.id == currentQ.user_answer_id,
                                          'text-slate-700 dark:text-slate-300': !isOptionCorrect(opt, currentQ.sub_test_type) && opt.id != currentQ.user_answer_id
                                      }"
                                      x-html="opt.text">
                                </span>

                                <span x-show="!isOptionCorrect(opt, currentQ.sub_test_type) && opt.id == currentQ.user_answer_id" 
                                      class="absolute right-4 text-rose-500 font-bold text-[10px] md:text-xs">JAWABAN ANDA</span>
                                      
                                <span x-show="isOptionCorrect(opt, currentQ.sub_test_type)" 
                                      class="absolute right-4 text-emerald-500 font-bold text-[10px] md:text-xs uppercase tracking-tighter"
                                      x-text="currentQ.sub_test_type === 'TKP' ? `POIN ${opt.points}` : 'JAWABAN BENAR'">
                                </span>
                            </div>
                        </template>
                    </div>
                </div>
            </div>

            <!-- Discussion/Explanation Card -->
            <div class="bg-emerald-50 dark:bg-emerald-900/10 rounded-xl border border-emerald-200 dark:border-emerald-800 overflow-hidden shadow-sm">
                <div class="p-4 bg-emerald-100 dark:bg-emerald-900/30 flex items-center gap-2">
                    <span class="material-symbols-outlined text-emerald-600 dark:text-emerald-400">menu_book</span>
                    <h3 class="font-bold text-emerald-800 dark:text-emerald-300">Pembahasan Detail</h3>
                </div>
                <div class="p-6">
                    <div class="space-y-4 text-slate-700 dark:text-slate-300" x-html="currentQ.explanation"></div>
                </div>
            </div>

            <!-- Navigation Buttons -->
            <div class="flex justify-between pt-4">
                <button @click="prev()" :disabled="currentIndex === 0" 
                        class="flex items-center gap-2 font-bold transition-colors disabled:opacity-30" 
                        :class="currentIndex === 0 ? 'text-slate-300' : 'text-slate-500 hover:text-primary'">
                    <span class="material-symbols-outlined">chevron_left</span> Sebelumnya
                </button>
                <button @click="next()" :disabled="currentIndex === filteredQuestions.length - 1"
                        class="flex items-center gap-2 font-bold transition-colors disabled:opacity-30"
                        :class="currentIndex === filteredQuestions.length - 1 ? 'text-slate-300' : 'text-slate-500 hover:text-primary'">
                    Selanjutnya <span class="material-symbols-outlined">chevron_right</span>
                </button>
            </div>
        </div>

        <!-- Sidebar: Stats & Navigation -->
        <div class="lg:col-span-4 space-y-6">
            
            <!-- Result Summary Card -->
            <div class="bg-white dark:bg-slate-900 rounded-xl border border-slate-200 dark:border-slate-800 p-6 shadow-sm">
                <h3 class="font-bold text-slate-900 dark:text-white mb-4">Ringkasan Hasil</h3>
                <div class="grid grid-cols-3 gap-2 mb-6">
                    <div class="bg-emerald-50 dark:bg-emerald-900/20 p-3 rounded-lg text-center">
                        <p class="text-emerald-600 dark:text-emerald-400 font-black text-xl">{{ $stats['benar'] }}</p>
                        <p class="text-[10px] text-emerald-800 dark:text-emerald-300 font-bold uppercase">Benar</p>
                    </div>
                    <div class="bg-rose-50 dark:bg-rose-900/20 p-3 rounded-lg text-center">
                        <p class="text-rose-600 dark:text-rose-400 font-black text-xl">{{ $stats['salah'] }}</p>
                        <p class="text-[10px] text-rose-800 dark:text-rose-300 font-bold uppercase">Salah</p>
                    </div>
                    <div class="bg-slate-50 dark:bg-slate-800 p-3 rounded-lg text-center">
                        <p class="text-slate-600 dark:text-slate-400 font-black text-xl">{{ $stats['kosong'] }}</p>
                        <p class="text-[10px] text-slate-800 dark:text-slate-300 font-bold uppercase">Kosong</p>
                    </div>
                </div>
                
                <div class="space-y-3">
                    <div class="flex justify-between text-sm">
                        <span class="text-slate-500 dark:text-slate-400 font-medium">Total Nilai</span>
                        <span class="text-slate-900 dark:text-white font-bold">{{ $skdResult->total_score }} / 550</span>
                    </div>
                    @php $percentage = min(100, round(($skdResult->total_score / 550) * 100)); @endphp
                    <div class="w-full bg-slate-100 dark:bg-slate-800 h-2 rounded-full overflow-hidden">
                        <div class="bg-primary h-full" style="width: {{ $percentage }}%;"></div>
                    </div>
                    <p class="text-[11px] text-slate-400 text-center italic mt-2">
                        {{ $skdResult->is_passed ? 'Nilai Ambang Batas Terlampaui!' : 'Belum Memenuhi Ambang Batas.' }}
                    </p>
                </div>
            </div>

            <!-- Quick Navigation Grid -->
            <div class="bg-white dark:bg-slate-900 rounded-xl border border-slate-200 dark:border-slate-800 p-6 shadow-sm">
                <h3 class="font-bold text-slate-900 dark:text-white mb-4">Navigasi Soal <span class="text-xs font-normal text-slate-400 ml-1" x-text="`(${filteredQuestions.length})`"></span></h3>
                <div class="grid grid-cols-5 gap-2 max-h-60 overflow-y-auto pr-2">
                    <template x-for="(q, idx) in filteredQuestions" :key="q.id">
                        <button @click="currentIndex = idx"
                                class="aspect-square flex items-center justify-center rounded-lg text-xs font-bold hover:opacity-80 transition-opacity"
                                :class="{
                                    'bg-emerald-500 text-white': q.status === 'benar',
                                    'bg-rose-500 text-white': q.status === 'salah',
                                    'bg-slate-200 dark:bg-slate-700 text-slate-600 dark:text-slate-400': q.status === 'kosong',
                                    'ring-2 ring-primary ring-offset-2': currentIndex === idx
                                }"
                                x-text="q.number">
                        </button>
                    </template>
                </div>
                
                <div class="mt-6 flex flex-wrap gap-x-4 gap-y-2">
                    <div class="flex items-center gap-2">
                        <div class="size-3 rounded bg-emerald-500"></div>
                        <span class="text-[10px] font-medium text-slate-500 dark:text-slate-400 uppercase">Benar</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <div class="size-3 rounded bg-rose-500"></div>
                        <span class="text-[10px] font-medium text-slate-500 dark:text-slate-400 uppercase">Salah</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <div class="size-3 rounded bg-slate-200 dark:bg-slate-700"></div>
                        <span class="text-[10px] font-medium text-slate-500 dark:text-slate-400 uppercase">Kosong</span>
                    </div>
                </div>
            </div>

            <!-- Promo/Note Card -->
            <div class="bg-gradient-to-br from-primary to-blue-700 rounded-xl p-6 text-white shadow-lg overflow-hidden relative">
                <div class="relative z-10">
                    <h4 class="font-bold text-lg mb-2">Ingin Tingkatkan Skor?</h4>
                    <p class="text-sm text-white/80 mb-4 leading-relaxed">Pahami pembahasan tiap soal dengan teliti. Berlatih lagi dengan soal-soal lain.</p>
                    <a href="{{ route('peserta.skd.index') }}" class="inline-block bg-white text-primary font-bold px-4 py-2 rounded-lg text-xs shadow-md">Simulasi Lagi</a>
                </div>
                <span class="material-symbols-outlined absolute -bottom-6 -right-6 text-9xl text-white/10 rotate-12">rocket_launch</span>
            </div>

        </div>
    </div>
    </div>
</div>

@section('scripts')
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
<style>
    .scrollbar-hide::-webkit-scrollbar { display: none; }
    .scrollbar-hide { -ms-overflow-style: none; scrollbar-width: none; }
</style>
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('reviewData', () => ({
            allQuestions: @json($questionsData),
            filter: 'all',
            currentIndex: 0,

            get filteredQuestions() {
                return this.allQuestions.filter(q => {
                    if (this.filter === 'all') return true;
                    if (this.filter === 'salah') return q.status === 'salah';
                    if (this.filter === 'twk') return q.sub_test_type === 'TWK';
                    if (this.filter === 'tiu') return q.sub_test_type === 'TIU';
                    if (this.filter === 'tkp') return q.sub_test_type === 'TKP';
                    return true;
                });
            },

            get currentQ() {
                return this.filteredQuestions[this.currentIndex];
            },

            setFilter(f) {
                this.filter = f;
                this.currentIndex = 0;
            },

            next() {
                if (this.currentIndex < this.filteredQuestions.length - 1) {
                    this.currentIndex++;
                }
            },

            prev() {
                if (this.currentIndex > 0) {
                    this.currentIndex--;
                }
            },

            isOptionCorrect(opt, type) {
                if(type === 'TKP') {
                    return opt.points == 5; 
                }
                return opt.is_correct;
            }
        }));
    });
</script>
@endsection
@endsection
