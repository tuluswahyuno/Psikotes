@extends('layouts.main')
@section('title', 'Edit Paket SKD')
@section('subtitle', 'Edit – ' . $skdPackage->title)

@section('page_header')
<header class="bg-white/80 dark:bg-slate-900/80 backdrop-blur-md border-b border-slate-200 dark:border-slate-800 px-8 py-4 sticky top-0 z-10">
    <div class="max-w-7xl mx-auto flex items-center justify-between">
        <div>
            <h2 class="text-xl font-bold text-slate-900 dark:text-white tracking-tight">Edit Paket SKD</h2>
            <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">{{ $skdPackage->title }}</p>
        </div>
        <a href="{{ route('admin.skd-packages.show', $skdPackage) }}" class="flex items-center gap-2 text-sm font-medium text-slate-600 dark:text-slate-400 hover:text-primary transition-colors">
            <span class="material-symbols-outlined text-xl">arrow_back</span>
            Batal & Kembali
        </a>
    </div>
</header>
@endsection

@section('content')
<div class="max-w-7xl mx-auto mt-4 md:mt-0 pb-32">
    <form action="{{ route('admin.skd-packages.update', $skdPackage) }}" method="POST">
        @csrf @method('PUT')
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            <!-- Left Column: Basic Info -->
            <div class="lg:col-span-4 space-y-6">
                <!-- Available Count Card -->
                <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 p-5">
                    <h4 class="text-xs font-black uppercase tracking-wider text-slate-500 mb-4">Soal Tersedia di Bank</h4>
                    <div class="space-y-3">
                        @foreach(['twk' => ['label' => 'TWK', 'color' => 'blue'], 'tiu' => ['label' => 'TIU', 'color' => 'purple'], 'tkp' => ['label' => 'TKP', 'color' => 'emerald']] as $slug => $info)
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                <span class="size-7 rounded-lg bg-{{ $info['color'] }}-500 text-white flex items-center justify-center text-[10px] font-black">{{ $info['label'] }}</span>
                                <span class="text-sm font-medium text-slate-700 dark:text-slate-300">{{ strtoupper($info['label']) }} Bank</span>
                            </div>
                            <span class="font-black text-{{ $info['color'] }}-600 dark:text-{{ $info['color'] }}-400 text-sm">{{ $availableQuestions[$slug] ?? 0 }} soal</span>
                        </div>
                        @endforeach
                    </div>
                </div>

                <!-- Basic Info Card -->
                <div class="bg-white dark:bg-slate-900 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-800 p-6 relative overflow-hidden">
                    <div class="absolute top-0 left-0 w-full h-1 bg-primary"></div>
                    <h3 class="text-base font-bold text-slate-900 dark:text-white mb-5 flex items-center gap-2">
                        <span class="material-symbols-outlined text-primary">edit_document</span>
                        Informasi Dasar
                    </h3>
                    <div class="space-y-5">
                        <div>
                            <label class="block text-xs uppercase tracking-widest font-bold text-slate-500 dark:text-slate-400 mb-2">Judul Paket <span class="text-rose-500">*</span></label>
                            <input type="text" name="title" value="{{ old('title', $skdPackage->title) }}" class="w-full bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 text-slate-900 dark:text-white rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all px-4 py-3 font-medium" required>
                            @error('title') <p class="text-rose-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-xs uppercase tracking-widest font-bold text-slate-500 dark:text-slate-400 mb-2">Durasi Total <span class="text-rose-500">*</span></label>
                            <div class="relative flex items-center">
                                <span class="material-symbols-outlined absolute left-4 text-slate-400">schedule</span>
                                <input type="number" name="duration_minutes" value="{{ old('duration_minutes', $skdPackage->duration_minutes) }}" class="w-full pl-12 pr-12 py-3 bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 text-slate-900 dark:text-white rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all font-bold" required min="1">
                                <span class="absolute right-4 text-sm font-medium text-slate-400 pointer-events-none">Menit</span>
                            </div>
                        </div>
                        <div>
                            <label class="block text-xs uppercase tracking-widest font-bold text-slate-500 dark:text-slate-400 mb-2">Deskripsi Singkat</label>
                            <textarea name="description" rows="3" class="w-full bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 text-slate-900 dark:text-white rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all px-4 py-3 font-medium">{{ old('description', $skdPackage->description) }}</textarea>
                        </div>
                        <label class="flex items-center justify-between p-4 rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800/50 cursor-pointer hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors">
                            <div>
                                <p class="text-sm font-bold text-slate-900 dark:text-white">Acak Soal</p>
                                <p class="text-[10px] text-slate-500">Setiap peserta mendapat urutan soal berbeda</p>
                            </div>
                            <input type="checkbox" name="randomize_questions" value="1" {{ old('randomize_questions', $skdPackage->randomize_questions) ? 'checked' : '' }} class="size-5 rounded accent-primary">
                        </label>
                        <label class="flex items-center justify-between p-4 rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800/50 cursor-pointer hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors">
                            <div>
                                <p class="text-sm font-bold text-slate-900 dark:text-white">Publikasikan Aktif</p>
                                <p class="text-[10px] text-slate-500">Buka akses di halaman peserta</p>
                            </div>
                            <input type="checkbox" name="is_active" value="1" {{ old('is_active', $skdPackage->is_active) ? 'checked' : '' }} class="size-5 rounded accent-primary">
                        </label>
                    </div>
                </div>
            </div>

            <!-- Right Column: Sub-Test Config -->
            <div class="lg:col-span-8">
                <div class="bg-white dark:bg-slate-900 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-800 p-6 relative overflow-hidden">
                    <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-blue-500 via-purple-500 to-emerald-500"></div>
                    <div class="flex items-center gap-3 mb-6">
                        <span class="material-symbols-outlined text-slate-500">category</span>
                        <h3 class="text-base font-bold text-slate-900 dark:text-white">Konfigurasi Sub-Tes SKD</h3>
                    </div>

                    @php
                        $subTests = [
                            'twk' => ['label' => 'Tes Wawasan Kebangsaan', 'desc' => 'Penguasaan pengetahuan & pilar negara', 'color' => 'blue'],
                            'tiu' => ['label' => 'Tes Intelegensi Umum', 'desc' => 'Kemampuan verbal, logis, dan numerik', 'color' => 'purple'],
                            'tkp' => ['label' => 'Tes Karakteristik Pribadi', 'desc' => 'Standar perilaku & penguasaan empati', 'color' => 'emerald'],
                        ];
                    @endphp

                    <div class="space-y-5">
                        @foreach($subTests as $slug => $cfg)
                        <div class="p-5 rounded-2xl bg-{{ $cfg['color'] }}-50/50 dark:bg-{{ $cfg['color'] }}-900/10 border border-{{ $cfg['color'] }}-100 dark:border-{{ $cfg['color'] }}-900/30">
                            <div class="flex items-center gap-3 mb-5">
                                <span class="size-10 rounded-xl bg-{{ $cfg['color'] }}-600 text-white flex items-center justify-center font-black text-xs shadow-lg">{{ strtoupper($slug) }}</span>
                                <div>
                                    <h4 class="font-bold text-slate-900 dark:text-white">{{ $cfg['label'] }}</h4>
                                    <p class="text-xs text-{{ $cfg['color'] }}-600 dark:text-{{ $cfg['color'] }}-400">{{ $cfg['desc'] }}</p>
                                </div>
                                <div class="ml-auto text-right">
                                    <span class="text-xs text-slate-500">Tersedia:</span>
                                    <span class="font-black text-{{ $cfg['color'] }}-600 ml-1">{{ $availableQuestions[$slug] ?? 0 }} soal</span>
                                </div>
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-2 uppercase tracking-wide">Jumlah Soal <span class="text-rose-500">*</span></label>
                                    <div class="relative flex items-center">
                                        <span class="material-symbols-outlined absolute left-3 text-slate-400 text-lg">quiz</span>
                                        <input type="number" name="{{ $slug }}_question_count" value="{{ old($slug . '_question_count', $skdPackage->{$slug . '_question_count'}) }}" required min="1"
                                            class="w-full pl-10 pr-12 py-3 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-{{ $cfg['color'] }}-700 dark:text-{{ $cfg['color'] }}-400 rounded-xl focus:ring-2 focus:ring-{{ $cfg['color'] }}-500/30 focus:border-{{ $cfg['color'] }}-500 transition-all font-black text-center shadow-sm">
                                        <span class="absolute right-3 text-xs font-medium text-slate-400 pointer-events-none">soal</span>
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-2 uppercase tracking-wide">Passing Grade <span class="text-rose-500">*</span></label>
                                    <div class="relative flex items-center">
                                        <span class="material-symbols-outlined absolute left-3 text-slate-400 text-lg">emoji_events</span>
                                        <input type="number" name="{{ $slug }}_passing_grade" value="{{ old($slug . '_passing_grade', $skdPackage->{$slug . '_passing_grade'}) }}" required min="0"
                                            class="w-full pl-10 pr-12 py-3 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-{{ $cfg['color'] }}-700 dark:text-{{ $cfg['color'] }}-400 rounded-xl focus:ring-2 focus:ring-{{ $cfg['color'] }}-500/30 focus:border-{{ $cfg['color'] }}-500 transition-all font-black text-center shadow-sm">
                                        <span class="absolute right-3 text-xs font-medium text-slate-400 pointer-events-none">poin</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <div class="flex flex-col-reverse sm:flex-row items-center justify-end gap-3 mt-8 pt-6 border-t border-slate-200 dark:border-slate-800">
                        <a href="{{ route('admin.skd-packages.show', $skdPackage) }}" class="w-full sm:w-auto px-6 py-3 text-slate-600 dark:text-slate-400 font-bold hover:text-slate-900 dark:hover:text-white transition-colors text-center">Batalkan</a>
                        <button type="submit" class="w-full sm:w-auto bg-primary hover:bg-primary/90 text-white px-8 py-3 rounded-xl font-bold flex items-center justify-center gap-2 transition-all shadow-lg shadow-primary/20 hover:-translate-y-0.5">
                            <span class="material-symbols-outlined text-xl">save</span>
                            Simpan Perubahan
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection
