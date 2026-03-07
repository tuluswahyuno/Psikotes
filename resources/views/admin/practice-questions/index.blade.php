@extends('layouts.main')
@section('title', 'Bank Soal Latihan')
@section('subtitle', $totalCount . ' soal tersedia • Kelola soal latihan per sub-topik SKD.')

@section('content')
<div class="flex flex-wrap items-center justify-between gap-4 mb-8">
    <div class="flex items-center gap-2 text-sm text-slate-500 dark:text-slate-400 bg-white dark:bg-slate-900 p-3 px-5 rounded-xl border border-slate-200 dark:border-slate-800 shadow-sm w-fit">
        <span class="font-bold text-primary">Bank Soal Latihan</span>
    </div>

    <div class="flex flex-wrap gap-3">
        <a href="{{ route('admin.practice-questions.import.form') }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-emerald-500 text-white font-bold rounded-xl shadow-lg shadow-emerald-500/20 hover:bg-emerald-600 hover:-translate-y-0.5 transition-all text-sm">
            <span class="material-symbols-outlined text-xl">upload_file</span>
            Import CSV
        </a>
        <a href="{{ route('admin.practice-questions.create') }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-primary text-white font-bold rounded-xl shadow-lg shadow-primary/30 hover:bg-blue-700 hover:-translate-y-0.5 transition-all text-sm">
            <span class="material-symbols-outlined text-xl">add</span>
            Tambah Soal
        </a>
    </div>
</div>

@if(session('success'))
<div class="mb-6 p-4 bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-200 dark:border-emerald-800 rounded-xl text-emerald-700 dark:text-emerald-300 text-sm font-bold flex items-center gap-2 shadow-sm">
    <span class="material-symbols-outlined text-xl">check_circle</span> {{ session('success') }}
</div>
@endif

@if(session('import_errors'))
<div class="mb-8 p-5 bg-rose-50 dark:bg-rose-900/20 border-l-4 border-rose-500 rounded-r-xl shadow-sm">
    <div class="flex items-center gap-2 text-rose-700 dark:text-rose-400 font-bold mb-3">
        <span class="material-symbols-outlined text-xl">warning</span> Sebagian data gagal diimpor:
    </div>
    <ul class="list-disc list-inside space-y-1.5 text-sm text-rose-600 dark:text-rose-300">
        @foreach(session('import_errors') as $err)
            <li>{{ $err }}</li>
        @endforeach
    </ul>
</div>
@endif

{{-- Smart Filter Bar (Alpine JS) --}}
<form method="GET" action="{{ route('admin.practice-questions.index') }}" class="bg-white dark:bg-slate-900 rounded-[1.5rem] border border-slate-200 dark:border-slate-800 shadow-xl shadow-slate-200/40 dark:shadow-none p-6 mb-8 relative z-20">
    <div class="flex flex-col md:flex-row gap-5">
        
        {{-- Search Soal --}}
        <div class="w-full md:w-5/12 lg:w-4/12 flex-shrink-0">
            <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 mb-2 uppercase tracking-wider">Cari Soal</label>
            <div class="relative">
                <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-slate-400">search</span>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Ketik potongan soal..."
                    class="w-full pl-11 pr-4 py-3 bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-xl text-sm font-medium focus:ring-4 focus:ring-primary/20 focus:border-primary outline-none transition-all placeholder:font-normal placeholder:text-slate-400">
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5 w-full">
            {{-- Alpine Dropdown: Section --}}
            @php 
                $currentSection = collect($sections)->firstWhere('id', request('section_id'));
                $sectionLabel = $currentSection ? $currentSection->name : 'Semua Section';
            @endphp
            <div x-data="{ open: false, selected: '{{ request('section_id') }}', label: '{{ $sectionLabel }}' }" class="relative">
                <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 mb-2 uppercase tracking-wider">Kategori SKD</label>
                <input type="hidden" name="section_id" :value="selected">
                <button type="button" @click="open = !open" @click.away="open = false" 
                    class="w-full flex items-center justify-between px-4 py-3 bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-xl text-sm font-medium hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors focus:ring-4 focus:ring-primary/20 focus:border-primary outline-none text-left">
                    <span x-text="label" class="text-slate-700 dark:text-slate-300 truncate font-semibold"></span>
                    <span class="material-symbols-outlined text-slate-400 text-lg transition-transform duration-300" :class="open ? 'rotate-180' : ''">expand_more</span>
                </button>
                
                <div x-show="open" x-transition.opacity.duration.200ms style="display: none;" 
                    class="absolute left-0 right-0 top-full mt-2 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl shadow-xl overflow-hidden z-50">
                    <div class="max-h-60 overflow-y-auto sidebar-scroll p-1.5 focus:outline-none" tabindex="-1">
                        <button type="button" @click="selected = ''; label = 'Semua Section'; open = false" 
                            class="w-full text-left px-3 py-2 text-sm rounded-lg hover:bg-slate-50 dark:hover:bg-slate-700/50 transition-colors"
                            :class="selected == '' ? 'bg-primary/10 text-primary font-bold' : 'text-slate-700 dark:text-slate-300 font-medium'">
                            Semua Section
                        </button>
                        @foreach($sections as $section)
                        <button type="button" @click="selected = '{{ $section->id }}'; label = '{{ addslashes($section->name) }}'; open = false" 
                            class="w-full text-left px-3 py-2 text-sm rounded-lg hover:bg-slate-50 dark:hover:bg-slate-700/50 transition-colors"
                            :class="selected == '{{ $section->id }}' ? 'bg-primary/10 text-primary font-bold' : 'text-slate-700 dark:text-slate-300 font-medium'">
                            {{ $section->name }}
                        </button>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- Alpine Dropdown: Sub-Topik --}}
            @php 
                $stLabel = 'Semua Sub-Topik';
                if(request('sub_topic_id')) {
                    foreach($sections as $s) {
                        $st = collect($s->subTopics)->firstWhere('id', request('sub_topic_id'));
                        if($st) { $stLabel = $st->title; break; }
                    }
                }
            @endphp
            <div x-data="{ open: false, selected: '{{ request('sub_topic_id') }}', label: '{{ addslashes($stLabel) }}' }" class="relative">
                <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 mb-2 uppercase tracking-wider">Sub-Topik</label>
                <input type="hidden" name="sub_topic_id" :value="selected">
                <button type="button" @click="open = !open" @click.away="open = false" 
                    class="w-full flex items-center justify-between px-4 py-3 bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-xl text-sm font-medium hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors focus:ring-4 focus:ring-primary/20 focus:border-primary outline-none text-left">
                    <span x-text="label" class="text-slate-700 dark:text-slate-300 truncate font-semibold"></span>
                    <span class="material-symbols-outlined text-slate-400 text-lg transition-transform duration-300" :class="open ? 'rotate-180' : ''">expand_more</span>
                </button>
                
                <div x-show="open" x-transition.opacity.duration.200ms style="display: none;" 
                    class="absolute left-0 right-0 top-full mt-2 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl shadow-xl overflow-hidden z-50">
                    <div class="max-h-60 overflow-y-auto sidebar-scroll p-1.5 focus:outline-none" tabindex="-1">
                        <button type="button" @click="selected = ''; label = 'Semua Sub-Topik'; open = false" 
                            class="w-full text-left px-3 py-2 text-sm rounded-lg hover:bg-slate-50 dark:hover:bg-slate-700/50 transition-colors"
                            :class="selected == '' ? 'bg-primary/10 text-primary font-bold' : 'text-slate-700 dark:text-slate-300 font-medium'">
                            Semua Sub-Topik
                        </button>
                        @foreach($sections as $section)
                            <div class="px-3 py-1.5 mt-2 text-[10px] font-black tracking-wider text-slate-400 uppercase border-b border-slate-100 dark:border-slate-700">{{ $section->name }}</div>
                            @foreach($section->subTopics as $st)
                            <button type="button" @click="selected = '{{ $st->id }}'; label = '{{ addslashes($st->title) }}'; open = false" 
                                class="w-full text-left px-3 py-2 text-sm rounded-lg pl-5 hover:bg-slate-50 dark:hover:bg-slate-700/50 transition-colors"
                                :class="selected == '{{ $st->id }}' ? 'bg-primary/10 text-primary font-bold' : 'text-slate-600 dark:text-slate-400 font-medium'">
                                {{ $st->title }}
                            </button>
                            @endforeach
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- Alpine Dropdown: Difficulty --}}
            @php
                $diffs = ['' => 'Semua Tingkat', 'easy' => 'Tingkat Mudah', 'medium' => 'Tingkat Sedang', 'hard' => 'Tingkat Sulit'];
                $reqDiff = request('difficulty', '');
                $diffLabel = $diffs[$reqDiff] ?? 'Semua Tingkat';
            @endphp
            <div x-data="{ open: false, selected: '{{ $reqDiff }}', label: '{{ $diffLabel }}' }" class="relative">
                <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 mb-2 uppercase tracking-wider">Kesulitan</label>
                <input type="hidden" name="difficulty" :value="selected">
                <button type="button" @click="open = !open" @click.away="open = false" 
                    class="w-full flex items-center justify-between px-4 py-3 bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-xl text-sm font-medium hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors focus:ring-4 focus:ring-primary/20 focus:border-primary outline-none text-left">
                    <span x-text="label" class="text-slate-700 dark:text-slate-300 truncate font-semibold"></span>
                    <span class="material-symbols-outlined text-slate-400 text-lg transition-transform duration-300" :class="open ? 'rotate-180' : ''">expand_more</span>
                </button>
                
                <div x-show="open" x-transition.opacity.duration.200ms style="display: none;" 
                    class="absolute left-0 right-0 top-full mt-2 min-w-max bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl shadow-xl overflow-hidden z-50">
                    <div class="p-1.5 focus:outline-none" tabindex="-1">
                        @foreach($diffs as $val => $lbl)
                        <button type="button" @click="selected = '{{ $val }}'; label = '{{ $lbl }}'; open = false" 
                            class="w-full text-left px-3 py-2 text-sm rounded-lg hover:bg-slate-50 dark:hover:bg-slate-700/50 transition-colors flex items-center gap-2"
                            :class="selected == '{{ $val }}' ? 'bg-primary/10 text-primary font-bold' : 'text-slate-700 dark:text-slate-300 font-medium'">
                            {{ $lbl }}
                        </button>
                        @endforeach
                    </div>
                </div>
            </div>
            
        </div>
    </div>

    {{-- Filter Actions --}}
    <div class="mt-6 flex flex-wrap gap-3 items-center border-t border-slate-100 dark:border-slate-800 pt-6">
        <button type="submit" class="inline-flex items-center gap-2 px-6 py-3 bg-slate-900 dark:bg-white text-white dark:text-slate-900 font-extrabold rounded-xl shadow-lg shadow-slate-900/20 dark:shadow-white/20 text-sm hover:shadow-xl hover:-translate-y-0.5 transition-all">
            <span class="material-symbols-outlined text-lg">filter_alt</span> Terapkan Filter
        </button>
        @if(request()->hasAny(['search','section_id','sub_topic_id','difficulty']) && array_filter(request()->only(['search','section_id','sub_topic_id','difficulty'])))
        <a href="{{ route('admin.practice-questions.index') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-rose-50 dark:bg-rose-900/20 text-rose-600 dark:text-rose-400 font-bold rounded-xl text-sm hover:bg-rose-100 dark:hover:bg-rose-900/40 transition-all">
            <span class="material-symbols-outlined text-lg">close</span> Bersihkan Filter
        </a>
        @endif
    </div>
</form>

<div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm overflow-hidden">
    @if($questions->isEmpty())
    <div class="p-12 text-center">
        <span class="material-symbols-outlined text-5xl text-slate-300 dark:text-slate-600 mb-3 block">quiz</span>
        <p class="text-slate-500 font-medium">Belum ada soal ditemukan.</p>
    </div>
    @else
    <table class="w-full">
        <thead class="bg-slate-50 dark:bg-slate-800/50 border-b border-slate-200 dark:border-slate-700">
            <tr>
                <th class="px-5 py-3.5 text-xs font-bold text-slate-500 uppercase text-left">#</th>
                <th class="px-5 py-3.5 text-xs font-bold text-slate-500 uppercase text-left">Pertanyaan</th>
                <th class="px-5 py-3.5 text-xs font-bold text-slate-500 uppercase text-left">Sub-Topik</th>
                <th class="px-5 py-3.5 text-xs font-bold text-slate-500 uppercase text-center">Difficulty</th>
                <th class="px-5 py-3.5 text-xs font-bold text-slate-500 uppercase text-center">Kunci</th>
                <th class="px-5 py-3.5 text-xs font-bold text-slate-500 uppercase text-right">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
            @foreach($questions as $q)
            @php
                $diffColor = ['easy' => 'bg-emerald-100 text-emerald-700', 'medium' => 'bg-amber-100 text-amber-700', 'hard' => 'bg-rose-100 text-rose-700'][$q->difficulty] ?? 'bg-slate-100 text-slate-700';
            @endphp
            <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/30 transition-colors">
                <td class="px-5 py-4 text-xs font-bold text-slate-400">{{ $questions->firstItem() + $loop->index }}</td>
                <td class="px-5 py-4 max-w-xs">
                    <p class="text-sm font-semibold text-slate-800 dark:text-white line-clamp-2">{{ $q->question }}</p>
                    @if($q->tags)
                    <div class="flex flex-wrap gap-1 mt-1.5">
                        @foreach($q->tags as $tag)
                        <span class="px-1.5 py-0.5 bg-slate-100 dark:bg-slate-800 text-slate-500 rounded text-[10px] font-medium">{{ $tag }}</span>
                        @endforeach
                    </div>
                    @endif
                </td>
                <td class="px-5 py-4">
                    <p class="text-xs font-bold text-slate-700 dark:text-slate-300">{{ $q->subTopic->title }}</p>
                    <p class="text-[10px] text-slate-400">{{ $q->subTopic->section->name }}</p>
                </td>
                <td class="px-5 py-4 text-center">
                    <span class="px-2.5 py-1 rounded-full text-xs font-bold {{ $diffColor }}">{{ ucfirst($q->difficulty) }}</span>
                </td>
                <td class="px-5 py-4 text-center">
                    <span class="size-8 rounded-lg bg-primary/10 text-primary font-black text-sm flex items-center justify-center mx-auto">{{ $q->correct_answer }}</span>
                </td>
                <td class="px-5 py-4">
                    <div class="flex items-center justify-end gap-2">
                        <a href="{{ route('admin.practice-questions.edit', $q) }}" class="p-2 text-slate-500 hover:text-primary hover:bg-primary/10 rounded-lg transition-all">
                            <span class="material-symbols-outlined text-lg">edit</span>
                        </a>
                        <form action="{{ route('admin.practice-questions.destroy', $q) }}" method="POST" onsubmit="return confirm('Hapus soal ini?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="p-2 text-slate-500 hover:text-rose-500 hover:bg-rose-50 dark:hover:bg-rose-900/20 rounded-lg transition-all">
                                <span class="material-symbols-outlined text-lg">delete</span>
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <div class="px-5 py-4 border-t border-slate-100 dark:border-slate-800">
        {{ $questions->links() }}
    </div>
    @endif
</div>
@endsection
