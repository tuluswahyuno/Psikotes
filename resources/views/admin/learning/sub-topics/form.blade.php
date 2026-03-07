@extends('layouts.main')
@section('title', isset($subTopic) ? 'Edit Sub-Topik' : 'Tambah Sub-Topik')

@section('content')
@php
    $colors = [
        'blue'    => ['bg' => 'bg-blue-500',    'text' => 'text-blue-600'],
        'purple'  => ['bg' => 'bg-purple-500',  'text' => 'text-purple-600'],
        'emerald' => ['bg' => 'bg-emerald-500', 'text' => 'text-emerald-600'],
    ];
    $c = $colors[$section->color] ?? $colors['blue'];
    $isEdit = isset($subTopic);
@endphp

<div class="flex items-center gap-2 text-sm text-slate-500 dark:text-slate-400 mb-8 bg-white dark:bg-slate-900 p-3 px-5 rounded-xl border border-slate-200 dark:border-slate-800 shadow-sm w-fit">
    <a href="{{ route('admin.learning.sections') }}" class="hover:text-primary transition-colors font-semibold">Bank Materi</a>
    <span class="material-symbols-outlined text-sm">chevron_right</span>
    <a href="{{ route('admin.learning.sub-topics', $section) }}" class="hover:text-primary transition-colors {{ $c['text'] }} font-semibold">{{ $section->name }}</a>
    <span class="material-symbols-outlined text-sm">chevron_right</span>
    <span class="font-bold text-primary">{{ $isEdit ? 'Edit Sub-Topik' : 'Tambah Sub-Topik' }}</span>
</div>

<div class="max-w-2xl">
    <h2 class="text-2xl font-extrabold text-slate-900 dark:text-white mb-6">
        {{ $isEdit ? 'Edit Sub-Topik' : 'Tambah Sub-Topik Baru' }}
    </h2>

    <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm overflow-hidden">
        <div class="h-1.5 {{ $c['bg'] }}"></div>
        <form action="{{ $isEdit ? route('admin.learning.sub-topics.update', [$section, $subTopic]) : route('admin.learning.sub-topics.store', $section) }}" method="POST" class="p-6 space-y-5">
            @csrf
            @if($isEdit) @method('PUT') @endif

            <div>
                <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-1.5">Judul Sub-Topik <span class="text-rose-500">*</span></label>
                <input type="text" name="title" value="{{ old('title', $subTopic->title ?? '') }}" required
                    class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-sm font-medium focus:ring-2 focus:ring-primary/30 focus:border-primary outline-none transition-all"
                    placeholder="Contoh: Pancasila">
                @error('title') <p class="text-rose-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-1.5">Deskripsi</label>
                <textarea name="description" rows="3"
                    class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-sm font-medium focus:ring-2 focus:ring-primary/30 focus:border-primary outline-none transition-all"
                    placeholder="Deskripsi singkat tentang sub-topik ini...">{{ old('description', $subTopic->description ?? '') }}</textarea>
            </div>

            <div>
                <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-1.5">Urutan</label>
                <input type="number" name="order" value="{{ old('order', $subTopic->order ?? '') }}" min="1"
                    class="w-32 px-4 py-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-sm font-medium focus:ring-2 focus:ring-primary/30 focus:border-primary outline-none transition-all"
                    placeholder="1">
            </div>

            <div class="flex gap-3 pt-2">
                <button type="submit" class="inline-flex items-center gap-2 px-6 py-2.5 bg-primary text-white font-bold rounded-xl shadow-lg shadow-primary/20 hover:bg-blue-700 transition-all text-sm">
                    <span class="material-symbols-outlined text-lg">{{ $isEdit ? 'save' : 'add' }}</span>
                    {{ $isEdit ? 'Simpan Perubahan' : 'Tambah Sub-Topik' }}
                </button>
                <a href="{{ route('admin.learning.sub-topics', $section) }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 font-bold rounded-xl hover:bg-slate-200 dark:hover:bg-slate-700 transition-all text-sm">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
