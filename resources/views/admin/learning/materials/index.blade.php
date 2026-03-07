@extends('layouts.main')
@section('title', 'Materi – ' . $subTopic->title)

@section('content')
@php
    $colors = [
        'blue'    => ['bg' => 'bg-blue-500',    'text' => 'text-blue-600'],
        'purple'  => ['bg' => 'bg-purple-500',  'text' => 'text-purple-600'],
        'emerald' => ['bg' => 'bg-emerald-500', 'text' => 'text-emerald-600'],
    ];
    $c = $colors[$section->color] ?? $colors['blue'];
@endphp

<div class="flex items-center gap-2 text-sm text-slate-500 dark:text-slate-400 mb-8 bg-white dark:bg-slate-900 p-3 px-5 rounded-xl border border-slate-200 dark:border-slate-800 shadow-sm w-fit">
    <a href="{{ route('admin.learning.sections') }}" class="hover:text-primary transition-colors font-semibold">Bank Materi</a>
    <span class="material-symbols-outlined text-sm">chevron_right</span>
    <a href="{{ route('admin.learning.sub-topics', $section) }}" class="hover:text-primary transition-colors {{ $c['text'] }} font-semibold">{{ $section->name }}</a>
    <span class="material-symbols-outlined text-sm">chevron_right</span>
    <span class="font-bold text-primary">{{ $subTopic->title }}</span>
</div>

<div class="flex flex-wrap items-center justify-between gap-4 mb-6">
    <div>
        <h2 class="text-2xl font-extrabold text-slate-900 dark:text-white">Materi: {{ $subTopic->title }}</h2>
        <p class="text-slate-500 dark:text-slate-400 mt-1">{{ $subTopic->description }}</p>
    </div>
    <a href="{{ route('admin.learning.materials.create', [$section, $subTopic]) }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-primary text-white font-bold rounded-xl shadow-lg shadow-primary/20 hover:bg-blue-700 transition-all text-sm">
        <span class="material-symbols-outlined text-lg">add</span>
        Tambah Materi
    </a>
</div>

@if(session('success'))
<div class="mb-6 p-4 bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-200 dark:border-emerald-800 rounded-xl text-emerald-700 dark:text-emerald-300 text-sm font-medium">
    ✅ {{ session('success') }}
</div>
@endif

<div class="space-y-4">
    @forelse($materials as $material)
    <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm overflow-hidden">
        <div class="flex items-center gap-4 p-5">
            <div class="size-10 rounded-xl {{ $c['bg'] }}/10 {{ $c['text'] }} flex items-center justify-center font-black text-sm shrink-0">
                {{ $loop->iteration }}
            </div>
            <div class="flex-1">
                <p class="font-bold text-slate-900 dark:text-white">{{ $material->title }}</p>
                <p class="text-xs text-slate-500 mt-0.5">Order: {{ $material->order }} • {{ Str::wordCount($material->content) }} kata</p>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('admin.learning.materials.edit', [$section, $subTopic, $material]) }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 font-bold rounded-lg text-xs hover:bg-slate-200 dark:hover:bg-slate-700 transition-all">
                    <span class="material-symbols-outlined text-sm">edit</span> Edit
                </a>
                <form action="{{ route('admin.learning.materials.destroy', [$section, $subTopic, $material]) }}" method="POST" onsubmit="return confirm('Hapus materi ini?')">
                    @csrf @method('DELETE')
                    <button type="submit" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-rose-50 dark:bg-rose-900/20 text-rose-600 font-bold rounded-lg text-xs hover:bg-rose-100 transition-all">
                        <span class="material-symbols-outlined text-sm">delete</span> Hapus
                    </button>
                </form>
            </div>
        </div>
    </div>
    @empty
    <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 p-12 text-center">
        <span class="material-symbols-outlined text-5xl text-slate-300 dark:text-slate-600 mb-3 block">auto_stories</span>
        <p class="text-slate-500 font-medium">Belum ada materi. Tambahkan materi pertama!</p>
    </div>
    @endforelse
</div>
@endsection
