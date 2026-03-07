@extends('layouts.main')
@section('title', 'Sub-Topik – ' . $section->name)

@section('content')
@php
    $colors = [
        'blue'    => ['bg' => 'bg-blue-500',    'text' => 'text-blue-600', 'light' => 'bg-blue-50 dark:bg-blue-900/20'],
        'purple'  => ['bg' => 'bg-purple-500',  'text' => 'text-purple-600', 'light' => 'bg-purple-50 dark:bg-purple-900/20'],
        'emerald' => ['bg' => 'bg-emerald-500', 'text' => 'text-emerald-600', 'light' => 'bg-emerald-50 dark:bg-emerald-900/20'],
    ];
    $c = $colors[$section->color] ?? $colors['blue'];
@endphp

<div class="flex items-center gap-2 text-sm text-slate-500 dark:text-slate-400 mb-8 bg-white dark:bg-slate-900 p-3 px-5 rounded-xl border border-slate-200 dark:border-slate-800 shadow-sm w-fit">
    <a href="{{ route('admin.learning.sections') }}" class="hover:text-primary transition-colors font-semibold">Bank Materi</a>
    <span class="material-symbols-outlined text-sm">chevron_right</span>
    <span class="font-bold text-primary">{{ $section->name }}</span>
</div>

<div class="flex flex-wrap items-center justify-between gap-4 mb-6">
    <div>
        <h2 class="text-2xl font-extrabold text-slate-900 dark:text-white">Sub-Topik: {{ $section->name }}</h2>
        <p class="text-slate-500 dark:text-slate-400 mt-1">Kelola sub-topik dan materi belajarnya.</p>
    </div>
    <a href="{{ route('admin.learning.sub-topics.create', $section) }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-primary text-white font-bold rounded-xl shadow-lg shadow-primary/20 hover:bg-blue-700 transition-all text-sm">
        <span class="material-symbols-outlined text-lg">add</span>
        Tambah Sub-Topik
    </a>
</div>

@if(session('success'))
<div class="mb-6 p-4 bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-200 dark:border-emerald-800 rounded-xl text-emerald-700 dark:text-emerald-300 text-sm font-medium">
    ✅ {{ session('success') }}
</div>
@endif

<div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm overflow-hidden">
    <div class="h-1.5 {{ $c['bg'] }}"></div>
    @if($subTopics->isEmpty())
    <div class="p-12 text-center">
        <span class="material-symbols-outlined text-5xl text-slate-300 dark:text-slate-600 mb-3 block">folder_open</span>
        <p class="text-slate-500 font-medium">Belum ada sub-topik. Tambahkan yang pertama!</p>
    </div>
    @else
    <table class="w-full">
        <thead class="bg-slate-50 dark:bg-slate-800/50 border-b border-slate-200 dark:border-slate-700">
            <tr>
                <th class="px-5 py-3.5 text-xs font-bold text-slate-500 uppercase text-left">Urutan</th>
                <th class="px-5 py-3.5 text-xs font-bold text-slate-500 uppercase text-left">Judul Sub-Topik</th>
                <th class="px-5 py-3.5 text-xs font-bold text-slate-500 uppercase text-center">Materi</th>
                <th class="px-5 py-3.5 text-xs font-bold text-slate-500 uppercase text-center">Soal Latihan</th>
                <th class="px-5 py-3.5 text-xs font-bold text-slate-500 uppercase text-right">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
            @foreach($subTopics as $subTopic)
            <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/30 transition-colors">
                <td class="px-5 py-4">
                    <span class="size-7 rounded-lg {{ $c['light'] }} {{ $c['text'] }} font-black text-sm flex items-center justify-center">{{ $subTopic->order }}</span>
                </td>
                <td class="px-5 py-4">
                    <p class="font-bold text-slate-900 dark:text-white">{{ $subTopic->title }}</p>
                    <p class="text-xs text-slate-500 mt-0.5">{{ Str::limit($subTopic->description, 60) }}</p>
                </td>
                <td class="px-5 py-4 text-center">
                    <a href="{{ route('admin.learning.materials', [$section, $subTopic]) }}" class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400 text-xs font-bold hover:bg-blue-100 transition-colors">
                        <span class="material-symbols-outlined text-sm">menu_book</span>
                        {{ $subTopic->materials_count }}
                    </a>
                </td>
                <td class="px-5 py-4 text-center">
                    <a href="{{ route('admin.practice-questions.index', ['sub_topic_id' => $subTopic->id]) }}" class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-amber-50 dark:bg-amber-900/20 text-amber-600 dark:text-amber-400 text-xs font-bold hover:bg-amber-100 transition-colors">
                        <span class="material-symbols-outlined text-sm">quiz</span>
                        {{ $subTopic->practice_questions_count }}
                    </a>
                </td>
                <td class="px-5 py-4">
                    <div class="flex items-center justify-end gap-2">
                        <a href="{{ route('admin.learning.sub-topics.edit', [$section, $subTopic]) }}" class="p-2 text-slate-500 hover:text-primary hover:bg-primary/10 rounded-lg transition-all">
                            <span class="material-symbols-outlined text-lg">edit</span>
                        </a>
                        <form action="{{ route('admin.learning.sub-topics.destroy', [$section, $subTopic]) }}" method="POST" onsubmit="return confirm('Hapus sub-topik ini beserta semua materinya?')">
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
    @endif
</div>
@endsection
