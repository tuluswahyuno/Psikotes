@extends('layouts.main')
@section('title', 'Buat Paket SKD')
@section('subtitle', 'Konfigurasikan paket SKD baru dengan sub-tes TWK, TIU, dan TKP')

@section('page_header')
<header class="bg-white/80 dark:bg-slate-900/80 backdrop-blur-md border-b border-slate-200 dark:border-slate-800 px-8 py-4 sticky top-0 z-10">
    <div class="max-w-7xl mx-auto flex items-center justify-between">
        <div>
            <h2 class="text-xl font-bold text-slate-900 dark:text-white tracking-tight">Add New Package</h2>
            <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">Konfigurasikan paket simulasi SKD komprehensif</p>
        </div>
        <a href="{{ route('admin.skd-packages.index') }}" class="flex items-center gap-2 text-sm font-medium text-slate-600 dark:text-slate-400 hover:text-primary transition-colors">
            <span class="material-symbols-outlined text-xl">arrow_back</span>
            Batal & Kembali
        </a>
    </div>
</header>
@endsection

@section('content')
<div class="max-w-7xl mx-auto mt-4 md:mt-0 pb-32">
    <form action="{{ route('admin.skd-packages.store') }}" method="POST">
        @csrf
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            <!-- Left Column: Basic Info -->
            <div class="lg:col-span-4 space-y-6">
                <!-- Instruction Card -->
                <div class="bg-primary/5 border border-primary/20 rounded-2xl p-6 dark:bg-primary/10 dark:border-primary/30">
                    <div class="flex items-center gap-3 mb-3 text-primary">
                        <span class="material-symbols-outlined text-2xl">info</span>
                        <h3 class="font-bold">Panduan Pembuatan</h3>
                    </div>
                    <p class="text-sm text-slate-600 dark:text-slate-400 leading-relaxed mb-4">
                        Paket simulasi SKD komprehensif merupakan penggabungan dari 3 jenis tes dasar. Anda harus memastikan bahwa Anda telah menyiapkan bank soal untuk materi TWK, TIU, dan TKP di menu Manage Test sebelumnya.
                    </p>
                    <ul class="text-xs space-y-2 text-slate-600 dark:text-slate-400 font-medium list-disc pl-4 marker:text-primary">
                        <li>Tentukan total waktu ujian (standar kompetensi BKN: 100 menit).</li>
                        <li>Sistem otomatis menjumlahkan total soal dari ketiga referensi sub-tes.</li>
                    </ul>
                </div>

                <!-- Basic Info Card -->
                <div class="bg-white dark:bg-slate-900 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-800 p-6 md:p-8 relative overflow-hidden">
                    <div class="absolute top-0 left-0 w-full h-1 bg-primary"></div>
                    <h3 class="text-lg font-bold text-slate-900 dark:text-white mb-6 flex items-center gap-2">
                        <span class="material-symbols-outlined text-primary">edit_document</span>
                        Informasi Dasar
                    </h3>
                    
                    <div class="space-y-6">
                        <div>
                            <label class="block text-xs uppercase tracking-widest font-bold text-slate-500 dark:text-slate-400 mb-2" for="title">Judul Paket Ujian <span class="text-rose-500">*</span></label>
                            <input type="text" name="title" id="title" value="{{ old('title') }}" class="w-full bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 text-slate-900 dark:text-white rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all px-4 py-3 placeholder-slate-400 font-medium" required placeholder="Contoh: Try Out Puncak Nasional">
                            @error('title') <p class="text-rose-500 text-xs mt-1 flex items-center gap-1 font-medium"><span class="material-symbols-outlined text-sm">error</span>{{ $message }}</p> @enderror
                        </div>
                        
                        <div>
                            <label class="block text-xs uppercase tracking-widest font-bold text-slate-500 dark:text-slate-400 mb-2" for="duration_minutes">Durasi Total <span class="text-rose-500">*</span></label>
                            <div class="relative flex items-center">
                                <span class="material-symbols-outlined absolute left-4 text-slate-400">schedule</span>
                                <input type="number" name="duration_minutes" id="duration_minutes" value="{{ old('duration_minutes', 100) }}" class="w-full pl-12 pr-4 py-3 bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 text-slate-900 dark:text-white rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all font-bold" required min="1">
                                <span class="absolute right-4 text-sm font-medium text-slate-400 pointer-events-none">Menit</span>
                            </div>
                        </div>
                        
                        <div>
                            <label class="block text-xs uppercase tracking-widest font-bold text-slate-500 dark:text-slate-400 mb-2" for="description">Deskripsi Pengantar Singkat</label>
                            <textarea name="description" id="description" rows="3" class="w-full bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 text-slate-900 dark:text-white rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all px-4 py-3 placeholder-slate-400 font-medium" placeholder="Keterangan singkat bagi peserta sebelum memulai...">{{ old('description') }}</textarea>
                        </div>
                        
                        <label class="flex items-center justify-between p-4 mt-2 rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800/50 cursor-pointer hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors">
                            <div>
                                <p class="text-sm font-bold text-slate-900 dark:text-white">Publikasikan Aktif</p>
                                <p class="text-[10px] text-slate-500">Buka akses di halaman peserta</p>
                            </div>
                            <div class="relative inline-block w-12 mr-2 align-middle select-none transition duration-200 ease-in">
                                <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }} class="toggle-checkbox absolute block w-6 h-6 rounded-full bg-white border-4 appearance-none cursor-pointer border-slate-200 dark:border-slate-600 checked:right-0 checked:border-primary peer transition-all" style="right: 1.5rem;"/>
                                <label for="is_active" class="toggle-label block overflow-hidden h-6 rounded-full bg-slate-200 dark:bg-slate-700 cursor-pointer peer-checked:bg-primary/20 transition-all"></label>
                            </div>
                        </label>
                    </div>
                </div>
            </div>

            <!-- Right Column: Sub-Test Mapping -->
            <div class="lg:col-span-8 space-y-6">
                <div class="bg-white dark:bg-slate-900 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-800 p-6 md:p-8 relative h-full flex flex-col">
                    <div class="absolute top-0 left-0 w-full h-1 bg-slate-200 dark:bg-slate-700"></div>
                    <div class="flex items-center justify-between border-b border-slate-200 dark:border-slate-800 pb-4 mb-6">
                        <h3 class="text-lg font-bold text-slate-900 dark:text-white flex items-center gap-2">
                            <span class="material-symbols-outlined text-slate-500">category</span>
                            Pemetaan Sub-Tes SKD
                        </h3>
                    </div>
                    
                    <div class="space-y-6 flex-1">
                        <!-- TWK -->
                        <div class="p-5 md:p-6 rounded-2xl bg-blue-50/50 dark:bg-blue-900/10 border border-blue-100 dark:border-blue-900/30 relative z-30 group hover:border-blue-300 dark:hover:border-blue-800 transition-colors">
                            <div class="absolute right-0 top-0 w-32 h-32 bg-blue-500/5 dark:bg-blue-500/10 rounded-full blur-2xl -mr-16 -mt-16 pointer-events-none transition-all group-hover:scale-110"></div>
                            
                            <div class="flex items-center gap-4 mb-6">
                                <span class="size-12 rounded-xl bg-blue-600 text-white flex items-center justify-center font-black text-sm shadow-lg shadow-blue-600/20">TWK</span>
                                <div>
                                    <h4 class="font-bold text-slate-900 dark:text-white text-lg">Tes Wawasan Kebangsaan</h4>
                                    <p class="text-xs font-medium text-blue-600 dark:text-blue-400 mt-0.5">Penguasaan pengetahuan & pilar negara</p>
                                </div>
                            </div>
                            
                            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 relative z-10">
                                <div class="md:col-span-3">
                                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-2 uppercase tracking-wide" for="twk_test_id">Pilih Master Tes <span class="text-rose-500">*</span></label>
                                    <div x-data="{ open: false, selected: '{{ old('twk_test_id') }}', selectedLabel: '-- Cari & Pilih Tes Referensi --' }" 
                                         x-init="
                                            @if(old('twk_test_id'))
                                               @php $oldTest = $tests->firstWhere('id', old('twk_test_id')); @endphp
                                               selectedLabel = '{{ $oldTest ? addslashes($oldTest->title) . ' (' . ($oldTest->questions_count ?? 0) . ' soal)' : '-- Cari & Pilih Tes Referensi --' }}';
                                            @endif
                                         "
                                         class="relative">
                                        <button type="button" @click="open = !open" @click.outside="open = false" class="w-full flex items-center justify-between gap-2 bg-white dark:bg-slate-800/80 border border-slate-200 dark:border-slate-700 text-slate-900 dark:text-white rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500/30 focus:border-blue-500 transition-all font-medium px-4 py-3 shadow-sm">
                                            <span x-text="selectedLabel" class="truncate pr-4 text-left"></span>
                                            <span class="material-symbols-outlined text-slate-400 pointer-events-none transition-transform duration-200" :class="open ? 'rotate-180' : ''">expand_more</span>
                                        </button>
                                        <div x-show="open" x-transition style="display: none;" class="absolute z-50 mt-1 w-full bg-white dark:bg-slate-800 rounded-xl shadow-lg border border-slate-200 dark:border-slate-700 py-1 max-h-60 overflow-y-auto">
                                            <button type="button" @click="selected = ''; selectedLabel = '-- Cari & Pilih Tes Referensi --'; open = false" class="w-full text-left px-4 py-3 text-sm transition-colors text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-700 border-b border-slate-100 dark:border-slate-800 italic">-- Cari & Pilih Tes Referensi --</button>
                                            @foreach($tests as $test)
                                                @php $label = $test->title . ' (' . ($test->questions_count ?? 0) . ' soal)'; @endphp
                                                <button type="button" @click="selected = '{{ $test->id }}'; selectedLabel = '{{ addslashes($label) }}'; open = false" class="w-full text-left px-4 py-3 text-sm transition-colors" :class="selected === '{{ $test->id }}' ? 'bg-blue-50/50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400 font-bold' : 'text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-700'">{{ $label }}</button>
                                            @endforeach
                                        </div>
                                        <input type="hidden" name="twk_test_id" required x-model="selected">
                                    </div>
                                </div>
                                <div class="md:col-span-1 border-l border-blue-200 dark:border-blue-800/50 pl-6 hidden md:block">
                                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-2 uppercase tracking-wide text-center" for="twk_passing_grade">Passing Grade</label>
                                    <input type="number" name="twk_passing_grade" id="twk_passing_grade" value="{{ old('twk_passing_grade', 65) }}" required class="w-full bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-blue-600 dark:text-blue-400 rounded-xl focus:ring-2 focus:ring-blue-500/30 focus:border-blue-500 transition-all px-4 py-3 font-black text-center shadow-sm text-lg block">
                                </div>
                                
                                <!-- Mobile form -->
                                <div class="md:hidden">
                                     <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-2 uppercase tracking-wide" for="twk_passing_grade_mobile">Passing Grade</label>
                                     <input type="number" name="twk_passing_grade" id="twk_passing_grade_mobile" value="{{ old('twk_passing_grade', 65) }}" required class="w-full bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-blue-600 dark:text-blue-400 rounded-xl focus:ring-2 focus:ring-blue-500/30 focus:border-blue-500 transition-all px-4 py-3 font-black text-center shadow-sm">
                                </div>
                            </div>
                        </div>

                        <!-- TIU -->
                        <div class="p-5 md:p-6 rounded-2xl bg-purple-50/50 dark:bg-purple-900/10 border border-purple-100 dark:border-purple-900/30 relative z-20 group hover:border-purple-300 dark:hover:border-purple-800 transition-colors">
                            <div class="absolute right-0 top-0 w-32 h-32 bg-purple-500/5 dark:bg-purple-500/10 rounded-full blur-2xl -mr-16 -mt-16 pointer-events-none transition-all group-hover:scale-110"></div>
                            
                            <div class="flex items-center gap-4 mb-6">
                                <span class="size-12 rounded-xl bg-purple-600 text-white flex items-center justify-center font-black text-sm shadow-lg shadow-purple-600/20">TIU</span>
                                <div>
                                    <h4 class="font-bold text-slate-900 dark:text-white text-lg">Tes Intelegensia Umum</h4>
                                    <p class="text-xs font-medium text-purple-600 dark:text-purple-400 mt-0.5">Penguasaan kemampuan verbal, logis, numerik</p>
                                </div>
                            </div>
                            
                            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 relative z-10">
                                <div class="md:col-span-3">
                                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-2 uppercase tracking-wide" for="tiu_test_id">Pilih Master Tes <span class="text-rose-500">*</span></label>
                                    <div x-data="{ open: false, selected: '{{ old('tiu_test_id') }}', selectedLabel: '-- Cari & Pilih Tes Referensi --' }" 
                                         x-init="
                                            @if(old('tiu_test_id'))
                                               @php $oldTest = $tests->firstWhere('id', old('tiu_test_id')); @endphp
                                               selectedLabel = '{{ $oldTest ? addslashes($oldTest->title) . ' (' . ($oldTest->questions_count ?? 0) . ' soal)' : '-- Cari & Pilih Tes Referensi --' }}';
                                            @endif
                                         "
                                         class="relative">
                                        <button type="button" @click="open = !open" @click.outside="open = false" class="w-full flex items-center justify-between gap-2 bg-white dark:bg-slate-800/80 border border-slate-200 dark:border-slate-700 text-slate-900 dark:text-white rounded-xl focus:outline-none focus:ring-2 focus:ring-purple-500/30 focus:border-purple-500 transition-all font-medium px-4 py-3 shadow-sm">
                                            <span x-text="selectedLabel" class="truncate pr-4 text-left"></span>
                                            <span class="material-symbols-outlined text-slate-400 pointer-events-none transition-transform duration-200" :class="open ? 'rotate-180' : ''">expand_more</span>
                                        </button>
                                        <div x-show="open" x-transition style="display: none;" class="absolute z-50 mt-1 w-full bg-white dark:bg-slate-800 rounded-xl shadow-lg border border-slate-200 dark:border-slate-700 py-1 max-h-60 overflow-y-auto">
                                            <button type="button" @click="selected = ''; selectedLabel = '-- Cari & Pilih Tes Referensi --'; open = false" class="w-full text-left px-4 py-3 text-sm transition-colors text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-700 border-b border-slate-100 dark:border-slate-800 italic">-- Cari & Pilih Tes Referensi --</button>
                                            @foreach($tests as $test)
                                                @php $label = $test->title . ' (' . ($test->questions_count ?? 0) . ' soal)'; @endphp
                                                <button type="button" @click="selected = '{{ $test->id }}'; selectedLabel = '{{ addslashes($label) }}'; open = false" class="w-full text-left px-4 py-3 text-sm transition-colors" :class="selected === '{{ $test->id }}' ? 'bg-purple-50/50 dark:bg-purple-900/20 text-purple-600 dark:text-purple-400 font-bold' : 'text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-700'">{{ $label }}</button>
                                            @endforeach
                                        </div>
                                        <input type="hidden" name="tiu_test_id" required x-model="selected">
                                    </div>
                                </div>
                                <div class="md:col-span-1 border-l border-purple-200 dark:border-purple-800/50 pl-6 hidden md:block">
                                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-2 uppercase tracking-wide text-center" for="tiu_passing_grade">Passing Grade</label>
                                    <input type="number" name="tiu_passing_grade" id="tiu_passing_grade" value="{{ old('tiu_passing_grade', 80) }}" required class="w-full bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-purple-600 dark:text-purple-400 rounded-xl focus:ring-2 focus:ring-purple-500/30 focus:border-purple-500 transition-all px-4 py-3 font-black text-center shadow-sm text-lg block">
                                </div>
                                
                                <div class="md:hidden">
                                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-2 uppercase tracking-wide" for="tiu_passing_grade_mobile">Passing Grade</label>
                                    <input type="number" name="tiu_passing_grade" id="tiu_passing_grade_mobile" value="{{ old('tiu_passing_grade', 80) }}" required class="w-full bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-purple-600 dark:text-purple-400 rounded-xl focus:ring-2 focus:ring-purple-500/30 focus:border-purple-500 transition-all px-4 py-3 font-black text-center shadow-sm">
                               </div>
                            </div>
                        </div>

                        <!-- TKP -->
                        <div class="p-5 md:p-6 rounded-2xl bg-emerald-50/50 dark:bg-emerald-900/10 border border-emerald-100 dark:border-emerald-900/30 relative z-10 group hover:border-emerald-300 dark:hover:border-emerald-800 transition-colors">
                            <div class="absolute right-0 top-0 w-32 h-32 bg-emerald-500/5 dark:bg-emerald-500/10 rounded-full blur-2xl -mr-16 -mt-16 pointer-events-none transition-all group-hover:scale-110"></div>
                            
                            <div class="flex items-center gap-4 mb-6">
                                <span class="size-12 rounded-xl bg-emerald-600 text-white flex items-center justify-center font-black text-sm shadow-lg shadow-emerald-600/20">TKP</span>
                                <div>
                                    <h4 class="font-bold text-slate-900 dark:text-white text-lg">Tes Karakteristik Pribadi</h4>
                                    <p class="text-xs font-medium text-emerald-600 dark:text-emerald-400 mt-0.5">Penguasaan standar perilaku pelayanan & empati</p>
                                </div>
                            </div>
                            
                            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 relative z-10">
                                <div class="md:col-span-3">
                                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-2 uppercase tracking-wide" for="tkp_test_id">Pilih Master Tes <span class="text-rose-500">*</span></label>
                                    <div x-data="{ open: false, selected: '{{ old('tkp_test_id') }}', selectedLabel: '-- Cari & Pilih Tes Referensi --' }" 
                                         x-init="
                                            @if(old('tkp_test_id'))
                                               @php $oldTest = $tests->firstWhere('id', old('tkp_test_id')); @endphp
                                               selectedLabel = '{{ $oldTest ? addslashes($oldTest->title) . ' (' . ($oldTest->questions_count ?? 0) . ' soal)' : '-- Cari & Pilih Tes Referensi --' }}';
                                            @endif
                                         "
                                         class="relative">
                                        <button type="button" @click="open = !open" @click.outside="open = false" class="w-full flex items-center justify-between gap-2 bg-white dark:bg-slate-800/80 border border-slate-200 dark:border-slate-700 text-slate-900 dark:text-white rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500/30 focus:border-emerald-500 transition-all font-medium px-4 py-3 shadow-sm">
                                            <span x-text="selectedLabel" class="truncate pr-4 text-left"></span>
                                            <span class="material-symbols-outlined text-slate-400 pointer-events-none transition-transform duration-200" :class="open ? 'rotate-180' : ''">expand_more</span>
                                        </button>
                                        <div x-show="open" x-transition style="display: none;" class="absolute z-50 mt-1 w-full bg-white dark:bg-slate-800 rounded-xl shadow-lg border border-slate-200 dark:border-slate-700 py-1 max-h-60 overflow-y-auto">
                                            <button type="button" @click="selected = ''; selectedLabel = '-- Cari & Pilih Tes Referensi --'; open = false" class="w-full text-left px-4 py-3 text-sm transition-colors text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-700 border-b border-slate-100 dark:border-slate-800 italic">-- Cari & Pilih Tes Referensi --</button>
                                            @foreach($tests as $test)
                                                @php $label = $test->title . ' (' . ($test->questions_count ?? 0) . ' soal)'; @endphp
                                                <button type="button" @click="selected = '{{ $test->id }}'; selectedLabel = '{{ addslashes($label) }}'; open = false" class="w-full text-left px-4 py-3 text-sm transition-colors" :class="selected === '{{ $test->id }}' ? 'bg-emerald-50/50 dark:bg-emerald-900/20 text-emerald-600 dark:text-emerald-400 font-bold' : 'text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-700'">{{ $label }}</button>
                                            @endforeach
                                        </div>
                                        <input type="hidden" name="tkp_test_id" required x-model="selected">
                                    </div>
                                </div>
                                <div class="md:col-span-1 border-l border-emerald-200 dark:border-emerald-800/50 pl-6 hidden md:block">
                                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-2 uppercase tracking-wide text-center" for="tkp_passing_grade">Passing Grade</label>
                                    <input type="number" name="tkp_passing_grade" id="tkp_passing_grade" value="{{ old('tkp_passing_grade', 166) }}" required class="w-full bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-emerald-600 dark:text-emerald-400 rounded-xl focus:ring-2 focus:ring-emerald-500/30 focus:border-emerald-500 transition-all px-4 py-3 font-black text-center shadow-sm text-lg block">
                                </div>
                                
                                <div class="md:hidden">
                                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-2 uppercase tracking-wide" for="tkp_passing_grade_mobile">Passing Grade</label>
                                    <input type="number" name="tkp_passing_grade" id="tkp_passing_grade_mobile" value="{{ old('tkp_passing_grade', 166) }}" required class="w-full bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-emerald-600 dark:text-emerald-400 rounded-xl focus:ring-2 focus:ring-emerald-500/30 focus:border-emerald-500 transition-all px-4 py-3 font-black text-center shadow-sm">
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="flex flex-col-reverse sm:flex-row items-center justify-end gap-3 mt-8 pt-6 border-t border-slate-200 dark:border-slate-800 shrink-0">
                        <a href="{{ route('admin.skd-packages.index') }}" class="w-full sm:w-auto px-6 py-3 text-slate-600 dark:text-slate-400 font-bold hover:text-slate-900 dark:hover:text-white transition-colors text-center">Batalkan</a>
                        <button type="submit" class="w-full sm:w-auto bg-primary hover:bg-primary/90 text-white px-8 py-3 rounded-xl font-bold flex items-center justify-center gap-2 transition-all shadow-lg shadow-primary/20">
                            <span class="material-symbols-outlined text-xl">rocket_launch</span>
                            Simpan Paket SKD
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<style>
/* Base styles for the custom toggle switch */
.toggle-checkbox:checked { right: 0; border-color: #135bec; }
.toggle-checkbox:checked + .toggle-label { background-color: rgba(19, 91, 236, 0.2); }
</style>
@endsection
