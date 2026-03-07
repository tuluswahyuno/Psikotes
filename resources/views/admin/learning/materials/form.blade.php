@extends('layouts.main')
@section('title', isset($material) ? 'Edit Materi' : 'Tambah Materi Baru')
@section('subtitle', isset($material) ? 'Ubah konten materi pembelajaran untuk sub-topik ini.' : 'Buat konten materi pembelajaran baru untuk diakses oleh peserta.')

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css"/>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/quill/1.3.7/quill.snow.css"/>
<style>
    .ql-editor { min-height: 400px; font-size: 16px; font-family: 'Public Sans', sans-serif; line-height: 1.7; color: #334155; }
    .dark .ql-editor { color: #cbd5e1; }
    .ql-toolbar.ql-snow { border-radius: 16px 16px 0 0; border-color: #e2e8f0; padding: 12px 16px; background-color: #f8fafc; }
    .dark .ql-toolbar.ql-snow { border-color: #1e293b; background-color: #0f172a; }
    .ql-container.ql-snow { border-radius: 0 0 16px 16px; border-color: #e2e8f0; }
    .dark .ql-container.ql-snow { border-color: #1e293b; }
    .ql-toolbar.ql-snow .ql-picker, .ql-toolbar.ql-snow .ql-stroke { color: #475569; stroke: #475569; }
    .dark .ql-toolbar.ql-snow .ql-picker, .dark .ql-toolbar.ql-snow .ql-stroke { color: #94a3b8; stroke: #94a3b8; }
    .ql-snow .ql-picker-options { background-color: #fff; border-color: #e2e8f0; border-radius: 8px; box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1); }
    .dark .ql-snow .ql-picker-options { background-color: #1e293b; border-color: #334155; }
</style>
@endpush

@section('content')
@php
    $colors = [
        'blue'    => ['bg' => 'bg-blue-500',    'text' => 'text-blue-600', 'light' => 'bg-blue-50 dark:bg-blue-900/20'],
        'purple'  => ['bg' => 'bg-purple-500',  'text' => 'text-purple-600', 'light' => 'bg-purple-50 dark:bg-purple-900/20'],
        'emerald' => ['bg' => 'bg-emerald-500', 'text' => 'text-emerald-600', 'light' => 'bg-emerald-50 dark:bg-emerald-900/20'],
    ];
    $c = $colors[$section->color] ?? $colors['blue'];
    $isEdit = isset($material);
@endphp

<div class="flex items-center gap-2 text-sm text-slate-500 dark:text-slate-400 mb-8 bg-white dark:bg-slate-900 p-3 px-5 rounded-xl border border-slate-200 dark:border-slate-800 shadow-sm w-fit">
    <a href="{{ route('admin.learning.sections') }}" class="hover:text-primary transition-colors font-semibold">Bank Materi</a>
    <span class="material-symbols-outlined text-sm">chevron_right</span>
    <a href="{{ route('admin.learning.sub-topics', $section) }}" class="hover:text-primary transition-colors {{ $c['text'] }} font-semibold">{{ $section->name }}</a>
    <span class="material-symbols-outlined text-sm">chevron_right</span>
    <a href="{{ route('admin.learning.materials', [$section, $subTopic]) }}" class="hover:text-primary transition-colors font-semibold text-slate-700 dark:text-slate-300">{{ $subTopic->title }}</a>
    <span class="material-symbols-outlined text-sm">chevron_right</span>
    <span class="font-bold text-primary">{{ $isEdit ? 'Edit Materi' : 'Tambah Materi Baru' }}</span>
</div>

<div class="max-w-5xl">
    <div class="bg-white dark:bg-slate-900 rounded-[1.5rem] border border-slate-200 dark:border-slate-800 shadow-xl shadow-slate-200/40 dark:shadow-none overflow-hidden flex flex-col">
        <div class="h-2 {{ $c['bg'] }} w-full"></div>
        <form action="{{ $isEdit ? route('admin.learning.materials.update', [$section, $subTopic, $material]) : route('admin.learning.materials.store', [$section, $subTopic]) }}" method="POST" class="p-8 space-y-8" id="material-form">
            @csrf
            @if($isEdit) @method('PUT') @endif

            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div class="md:col-span-3">
                    <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">Judul Materi <span class="text-rose-500">*</span></label>
                    <input type="text" name="title" value="{{ old('title', $material->title ?? '') }}" required
                        class="w-full px-5 py-3.5 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-base font-semibold focus:ring-4 focus:ring-primary/20 focus:border-primary outline-none transition-all placeholder:text-slate-400 placeholder:font-normal shadow-sm"
                        placeholder="Masukkan judul materi di sini...">
                    @error('title') <p class="text-rose-500 text-xs mt-2 font-medium flex items-center gap-1"><span class="material-symbols-outlined text-sm">error</span> {{ $message }}</p> @enderror
                </div>
                
                <div class="md:col-span-1">
                    <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">Urutan Tampil</label>
                    <div class="relative">
                        <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-slate-400">format_list_numbered</span>
                        <input type="number" name="order" value="{{ old('order', $material->order ?? '') }}" min="1"
                            class="w-full pl-12 pr-5 py-3.5 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-base font-semibold focus:ring-4 focus:ring-primary/20 focus:border-primary outline-none transition-all shadow-sm"
                            placeholder="Opsional">
                    </div>
                </div>
            </div>

            <div>
                <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2 flex items-center gap-2">
                    <span class="material-symbols-outlined text-slate-400">edit_note</span>
                    Konten Materi <span class="text-rose-500">*</span>
                </label>
                <div class="rounded-2xl overflow-hidden shadow-sm border border-slate-200 dark:border-slate-700 transition-all hover:border-slate-300 dark:hover:border-slate-600 focus-within:border-primary focus-within:ring-4 focus-within:ring-primary/20">
                    <div id="quill-editor" class="bg-white dark:bg-slate-900 border-none">{!! old('content', $material->content ?? '') !!}</div>
                </div>
                <input type="hidden" name="content" id="content-input">
                @error('content') <p class="text-rose-500 text-xs mt-2 font-medium flex items-center gap-1"><span class="material-symbols-outlined text-sm">error</span> {{ $message }}</p> @enderror
            </div>

            <div class="flex flex-wrap gap-4 pt-6 border-t border-slate-100 dark:border-slate-800">
                <button type="submit" class="inline-flex items-center gap-2 px-8 py-3.5 bg-primary text-white font-bold rounded-xl shadow-lg shadow-primary/30 hover:bg-blue-700 hover:-translate-y-0.5 transition-all text-sm group">
                    <span class="material-symbols-outlined text-xl transition-transform group-hover:scale-110">{{ $isEdit ? 'save' : 'done_all' }}</span>
                    {{ $isEdit ? 'Simpan Perubahan' : 'Publish Materi' }}
                </button>
                <a href="{{ route('admin.learning.materials', [$section, $subTopic]) }}" class="inline-flex items-center gap-2 px-6 py-3.5 bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 font-bold rounded-xl hover:bg-slate-200 dark:hover:bg-slate-700 transition-all text-sm">
                    Kembali
                </a>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/quill/1.3.7/quill.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const quill = new Quill('#quill-editor', {
            theme: 'snow',
            placeholder: 'Mulai menulis materi di sini...',
            modules: {
                toolbar: [
                    [{ header: [1, 2, 3, 4, false] }],
                    ['bold', 'italic', 'underline', 'strike'],
                    [{ 'color': [] }, { 'background': [] }],
                    [{ list: 'ordered' }, { list: 'bullet' }, { 'align': [] }],
                    ['blockquote', 'code-block'],
                    ['link', 'image', 'video'],
                    ['clean']
                ]
            }
        });

        document.getElementById('material-form').addEventListener('submit', function() {
            document.getElementById('content-input').value = quill.root.innerHTML;
        });
    });
</script>
@endsection
