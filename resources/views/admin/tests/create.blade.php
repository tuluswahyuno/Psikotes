@extends('layouts.main')
@section('title', 'Buat Tes Baru')
@section('subtitle', 'Tambahkan tes psikotes baru ke sistem')

@section('page_header')
<header class="bg-white/80 dark:bg-slate-900/80 backdrop-blur-md border-b border-slate-200 dark:border-slate-800 px-8 py-4 sticky top-0 z-10">
    <div class="max-w-7xl mx-auto flex items-center justify-between">
        <div>
            <h2 class="text-xl font-bold text-slate-900 dark:text-white tracking-tight">Add New Test</h2>
            <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">Tambahkan tes psikotes dasar atau komponen SKD</p>
        </div>
        <a href="{{ route('admin.tests.index') }}" class="flex items-center gap-2 text-sm font-medium text-slate-600 dark:text-slate-400 hover:text-primary transition-colors">
            <span class="material-symbols-outlined text-xl">arrow_back</span>
            Batal & Kembali
        </a>
    </div>
</header>
@endsection

@section('content')
<div class="max-w-4xl mx-auto space-y-8 mt-4 md:mt-0">
    <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm overflow-hidden relative">
        <div class="absolute top-0 left-0 w-full h-1 bg-primary"></div>
        <div class="p-6 md:p-8 border-b border-slate-200 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-800/20">
            <div class="flex items-center gap-4">
                <div class="size-12 rounded-xl bg-primary/10 text-primary flex items-center justify-center">
                    <span class="material-symbols-outlined text-2xl">post_add</span>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-slate-900 dark:text-white">Informasi Tes</h3>
                    <p class="text-xs md:text-sm text-slate-500 mt-1">Lengkapi detail dasar ujian yang akan dikerjakan peserta.</p>
                </div>
            </div>
        </div>

        <div class="p-6 md:p-8">
            <form action="{{ route('admin.tests.store') }}" method="POST">
                @csrf
                <div class="space-y-6 md:space-y-8">
                    <!-- Basic Info -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="md:col-span-2">
                            <label class="block text-xs uppercase tracking-widest font-bold text-slate-500 dark:text-slate-400 mb-2" for="title">Nama Tes <span class="text-rose-500">*</span></label>
                            <input type="text" name="title" id="title" value="{{ old('title') }}" required placeholder="Contoh: Tes Intelegensia Umum (TIU) Dasar" class="w-full bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 text-slate-900 dark:text-white rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all px-4 py-3 placeholder-slate-400 font-medium">
                            @error('title') <p class="text-rose-500 text-xs mt-2 font-medium flex items-center gap-1"><span class="material-symbols-outlined text-sm">error</span>{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-xs uppercase tracking-widest font-bold text-slate-500 dark:text-slate-400 mb-2" for="category">Kategori Label</label>
                            <input type="text" name="category" id="category" value="{{ old('category') }}" placeholder="Contoh: Logika, TIU, TWK" class="w-full bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 text-slate-900 dark:text-white rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all px-4 py-3 placeholder-slate-400 font-medium">
                        </div>

                        <div>
                            <label class="block text-xs uppercase tracking-widest font-bold text-slate-500 dark:text-slate-400 mb-2" for="type">Tipe Pertanyaan <span class="text-rose-500">*</span></label>
                            <div class="relative" x-data="{ open: false, selected: '{{ old('type', 'multiple_choice') }}', get selectedLabel() { return this.selected === 'multiple_choice' ? 'Pilihan Ganda (ABCD)' : (this.selected === 'likert' ? 'Skala Likert (Setuju/Tidak)' : 'Kepribadian (1-5 Poin)'); } }">
                                <button type="button" @click="open = !open" @click.outside="open = false" class="w-full flex items-center justify-between gap-2 bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 text-slate-900 dark:text-white rounded-xl focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all px-4 py-3 font-medium shadow-sm">
                                    <span x-text="selectedLabel" class="truncate pr-4 text-left"></span>
                                    <span class="material-symbols-outlined text-slate-400 pointer-events-none transition-transform duration-200" :class="open ? 'rotate-180' : ''">expand_more</span>
                                </button>
                                <div x-show="open" x-transition style="display: none;" class="absolute z-50 mt-1 w-full bg-white dark:bg-slate-800 rounded-xl shadow-lg border border-slate-200 dark:border-slate-700 py-1 overflow-hidden">
                                    <button type="button" @click="selected = 'multiple_choice'; open = false" class="w-full text-left px-4 py-3 text-sm transition-colors" :class="selected === 'multiple_choice' ? 'bg-primary/5 text-primary dark:bg-primary/10 font-bold' : 'text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-700'">Pilihan Ganda (ABCD)</button>
                                    <button type="button" @click="selected = 'likert'; open = false" class="w-full text-left px-4 py-3 text-sm transition-colors border-t border-slate-100 dark:border-slate-800/50" :class="selected === 'likert' ? 'bg-primary/5 text-primary dark:bg-primary/10 font-bold' : 'text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-700'">Skala Likert (Setuju/Tidak)</button>
                                    <button type="button" @click="selected = 'personality'; open = false" class="w-full text-left px-4 py-3 text-sm transition-colors border-t border-slate-100 dark:border-slate-800/50" :class="selected === 'personality' ? 'bg-primary/5 text-primary dark:bg-primary/10 font-bold' : 'text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-700'">Kepribadian (1-5 Poin)</button>
                                </div>
                                <input type="hidden" name="type" required x-model="selected">
                            </div>
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-xs uppercase tracking-widest font-bold text-slate-500 dark:text-slate-400 mb-2" for="description">Deskripsi / Panduan</label>
                            <textarea name="description" id="description" rows="3" placeholder="Tuliskan petunjuk singkat atau penjelasan mengenai tes ini untuk dibaca peserta..." class="w-full bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 text-slate-900 dark:text-white rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all px-4 py-3 placeholder-slate-400 font-medium">{{ old('description') }}</textarea>
                        </div>
                    </div>

                    <hr class="border-slate-200 dark:border-slate-800 border-dashed">

                    <!-- Settings -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div>
                            <label class="block text-xs uppercase tracking-widest font-bold text-slate-500 dark:text-slate-400 mb-2" for="duration_minutes">Durasi Waktu <span class="text-rose-500">*</span></label>
                            <div class="relative flex items-center">
                                <span class="material-symbols-outlined absolute left-4 text-slate-400">schedule</span>
                                <input type="number" name="duration_minutes" id="duration_minutes" value="{{ old('duration_minutes', 60) }}" required min="1" max="300" class="w-full pl-12 pr-4 py-3 bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 text-slate-900 dark:text-white rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all font-bold">
                                <span class="absolute right-4 text-sm font-medium text-slate-400 pointer-events-none">Menit</span>
                            </div>
                        </div>

                        <div class="space-y-4">
                            <label class="block text-xs uppercase tracking-widest font-bold text-slate-500 dark:text-slate-400 mb-2">Konfigurasi Sistem</label>
                            <label class="flex items-center justify-between p-4 rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800/50 cursor-pointer hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors">
                                <div class="flex items-center gap-3">
                                    <div class="size-8 rounded-lg bg-emerald-100 dark:bg-emerald-900/30 text-emerald-600 flex items-center justify-center">
                                        <span class="material-symbols-outlined text-lg">public</span>
                                    </div>
                                    <div>
                                        <p class="text-sm font-bold text-slate-900 dark:text-white">Status Publikasi</p>
                                        <p class="text-[10px] text-slate-500">Tes dapat langsung di-assign ke peserta</p>
                                    </div>
                                </div>
                                <div class="relative inline-block w-12 mr-2 align-middle select-none transition duration-200 ease-in">
                                    <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }} class="toggle-checkbox absolute block w-6 h-6 rounded-full bg-white border-4 appearance-none cursor-pointer border-slate-200 dark:border-slate-600 checked:right-0 checked:border-primary peer transition-all" style="right: 1.5rem;"/>
                                    <label for="is_active" class="toggle-label block overflow-hidden h-6 rounded-full bg-slate-200 dark:bg-slate-700 cursor-pointer peer-checked:bg-primary/20 transition-all"></label>
                                </div>
                            </label>
                            
                            <label class="flex items-center justify-between p-4 rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800/50 cursor-pointer hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors">
                                <div class="flex items-center gap-3">
                                    <div class="size-8 rounded-lg bg-amber-100 dark:bg-amber-900/30 text-amber-600 flex items-center justify-center">
                                        <span class="material-symbols-outlined text-lg">shuffle</span>
                                    </div>
                                    <div>
                                        <p class="text-sm font-bold text-slate-900 dark:text-white">Acak Urutan Soal</p>
                                        <p class="text-[10px] text-slate-500">Pertanyaan diacak untuk setiap peserta</p>
                                    </div>
                                </div>
                                <div class="relative inline-block w-12 mr-2 align-middle select-none transition duration-200 ease-in">
                                    <input type="checkbox" name="randomize_questions" id="randomize_questions" value="1" {{ old('randomize_questions') ? 'checked' : '' }} class="toggle-checkbox absolute block w-6 h-6 rounded-full bg-white border-4 appearance-none cursor-pointer border-slate-200 dark:border-slate-600 checked:right-0 checked:border-primary peer transition-all" style="right: 1.5rem;"/>
                                    <label for="randomize_questions" class="toggle-label block overflow-hidden h-6 rounded-full bg-slate-200 dark:bg-slate-700 cursor-pointer peer-checked:bg-primary/20 transition-all"></label>
                                </div>
                            </label>
                        </div>
                    </div>
                </div>

                <div class="flex flex-col-reverse md:flex-row items-center justify-end gap-3 mt-8 pt-6 border-t border-slate-200 dark:border-slate-800">
                    <a href="{{ route('admin.tests.index') }}" class="w-full md:w-auto px-6 py-3 text-slate-600 dark:text-slate-400 font-bold hover:text-slate-900 dark:hover:text-white transition-colors text-center">Batal</a>
                    <button type="submit" class="w-full md:w-auto bg-primary hover:bg-primary/90 text-white px-8 py-3 rounded-xl font-bold flex items-center justify-center gap-2 transition-all shadow-lg shadow-primary/20">
                        <span class="material-symbols-outlined text-xl">save</span>
                        Simpan & Lanjut ke Soal
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
/* Base styles for the custom toggle switch */
.toggle-checkbox:checked { right: 0; border-color: #135bec; }
.toggle-checkbox:checked + .toggle-label { background-color: rgba(19, 91, 236, 0.2); }
</style>
@endsection
