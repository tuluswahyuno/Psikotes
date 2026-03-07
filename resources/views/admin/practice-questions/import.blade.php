@extends('layouts.main')
@section('title', 'Import Soal dari CSV')
@section('subtitle', 'Tambahkan soal secara massal menggunakan file format CSV dengan struktur yang telah ditentukan.')

@section('content')
<div class="flex items-center gap-2 text-sm text-slate-500 dark:text-slate-400 mb-8 bg-white dark:bg-slate-900 p-3 px-5 rounded-xl border border-slate-200 dark:border-slate-800 shadow-sm w-fit">
    <a href="{{ route('admin.practice-questions.index') }}" class="hover:text-primary transition-colors font-semibold">Bank Soal Latihan</a>
    <span class="material-symbols-outlined text-sm">chevron_right</span>
    <span class="font-bold text-primary">Import CSV</span>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    
    {{-- Main Upload Form (Kiri / 2 kolom) --}}
    <div class="lg:col-span-2">
        <div class="bg-white dark:bg-slate-900 rounded-[1.5rem] border border-slate-200 dark:border-slate-800 shadow-xl shadow-slate-200/40 dark:shadow-none overflow-hidden">
            <div class="p-8 border-b border-slate-100 dark:border-slate-800 flex items-center justify-between">
                <div>
                    <h3 class="text-xl font-black text-slate-900 dark:text-white">Upload File CSV</h3>
                    <p class="text-sm text-slate-500 mt-1">Pilih atau letakkan file CSV Anda di area bawah ini.</p>
                </div>
                <div class="size-12 rounded-2xl bg-emerald-50 dark:bg-emerald-900/20 text-emerald-600 flex items-center justify-center">
                    <span class="material-symbols-outlined text-2xl">cloud_upload</span>
                </div>
            </div>

            <form action="{{ route('admin.practice-questions.import') }}" method="POST" enctype="multipart/form-data" class="p-8">
                @csrf
                
                @if(session('import_errors'))
                <div class="mb-8 p-5 bg-rose-50 dark:bg-rose-900/20 border-l-4 border-rose-500 rounded-r-xl">
                    <div class="flex items-center gap-2 text-rose-700 dark:text-rose-400 font-bold mb-3">
                        <span class="material-symbols-outlined">warning</span> Sebagian data gagal diimpor:
                    </div>
                    <ul class="list-disc list-inside space-y-1.5 text-sm text-rose-600 dark:text-rose-300">
                        @foreach(session('import_errors') as $err)
                            <li>{{ $err }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif

                @error('file') 
                <div class="mb-6 p-4 bg-rose-50 border border-rose-200 rounded-xl text-rose-600 text-sm flex items-center gap-2">
                    <span class="material-symbols-outlined text-rose-500">error</span> {{ $message }}
                </div>
                @enderror

                <div class="mb-8" 
                    x-data="{ 
                        isDropping: false, 
                        fileName: '',
                        handleDrop(e) {
                            this.isDropping = false;
                            if (e.dataTransfer.files.length > 0) {
                                document.getElementById('csv-file').files = e.dataTransfer.files;
                                this.fileName = e.dataTransfer.files[0].name;
                            }
                        }
                    }">
                    <label 
                        @dragover.prevent="isDropping = true" 
                        @dragleave.prevent="isDropping = false" 
                        @drop.prevent="handleDrop($event)"
                        :class="isDropping ? 'border-emerald-500 bg-emerald-50/50 dark:bg-emerald-900/20' : 'border-slate-300 dark:border-slate-700 bg-slate-50 dark:bg-slate-800/50'"
                        class="group flex flex-col items-center justify-center w-full h-64 border-2 border-dashed hover:border-emerald-500 hover:bg-emerald-50/50 dark:hover:bg-emerald-900/10 rounded-2xl cursor-pointer transition-all duration-300" for="csv-file">
                        
                        <div 
                            :class="isDropping ? 'scale-110 bg-emerald-100 dark:bg-emerald-900/40 text-emerald-600' : 'bg-slate-100 dark:bg-slate-800 text-slate-400 group-hover:scale-110 group-hover:bg-emerald-100 dark:group-hover:bg-emerald-900/40 group-hover:text-emerald-600'"
                            class="size-20 rounded-full flex items-center justify-center mb-4 transition-all duration-300">
                            <span class="material-symbols-outlined text-4xl transition-colors" x-text="fileName ? 'task' : 'upload_file'"></span>
                        </div>
                        
                        <h4 class="text-lg font-bold mb-1 transition-colors" 
                            :class="(fileName || isDropping) ? 'text-emerald-600 dark:text-emerald-400' : 'text-slate-700 dark:text-slate-300'"
                            x-text="fileName ? 'File Dipilih' : (isDropping ? 'Lepaskan File Disini' : 'Klik atau Drag & Drop')"></h4>
                        
                        <p class="text-sm font-medium text-slate-500" x-text="fileName ? fileName : 'Pilih file berformat .csv'"></p>
                        
                        <span class="text-xs font-semibold mt-4 px-3 py-1 rounded-full transition-colors"
                            :class="fileName ? 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-300' : 'bg-slate-200 text-slate-500 dark:bg-slate-700 dark:text-slate-400'">Maks. Ukuran 5MB</span>
                        
                        <input id="csv-file" name="file" type="file" accept=".csv" class="hidden" 
                            @change="
                                const file = $event.target.files[0];
                                if(file) fileName = file.name;
                            ">
                    </label>
                </div>

                <div class="flex gap-4">
                    <button type="submit" class="flex-1 inline-flex justify-center items-center gap-2 px-6 py-4 bg-emerald-500 text-white font-black rounded-xl shadow-lg shadow-emerald-500/30 hover:bg-emerald-600 hover:-translate-y-0.5 transition-all text-sm group">
                        <span class="material-symbols-outlined text-xl transition-transform group-hover:scale-110">upload</span>
                        Mulai Proses Import
                    </button>
                    <a href="{{ route('admin.practice-questions.index') }}" class="inline-flex justify-center items-center gap-2 px-8 py-4 bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 font-bold rounded-xl hover:bg-slate-200 dark:hover:bg-slate-700 transition-all text-sm">
                        Batal
                    </a>
                </div>
            </form>
        </div>
    </div>

    {{-- Sidebar Aturan & Template (Kanan / 1 kolom) --}}
    <div class="lg:col-span-1 space-y-6">
        
        {{-- Unduh Template --}}
        <div class="bg-gradient-to-br from-primary to-blue-700 rounded-[1.5rem] p-8 shadow-xl shadow-primary/30 relative overflow-hidden group">
            <div class="absolute -right-6 -top-6 size-32 bg-white/10 rounded-full blur-2xl group-hover:scale-150 transition-transform duration-700"></div>
            <div class="relative z-10">
                <div class="size-12 rounded-xl bg-white/20 text-white flex items-center justify-center mb-5 backdrop-blur-md">
                    <span class="material-symbols-outlined text-2xl">table_view</span>
                </div>
                <h3 class="text-xl font-black text-white mb-2">Panduan & Template</h3>
                <p class="text-blue-100 text-sm mb-6 leading-relaxed">
                    Pastikan Anda menggunakan template CSV standar kami agar kolom dapat terbaca dengan sempurna oleh sistem (Pemisah semicolon ;).
                </p>
                <a href="{{ route('admin.practice-questions.template.download') }}" class="w-full inline-flex justify-center items-center gap-2 px-5 py-3.5 bg-white text-primary font-black rounded-xl hover:bg-blue-50 transition-colors shadow-md text-sm">
                    <span class="material-symbols-outlined">download</span>
                    Unduh Template CSV
                </a>
            </div>
        </div>

        {{-- Aturan Kolom --}}
        <div class="bg-amber-50 dark:bg-amber-900/10 border border-amber-200 dark:border-amber-800/50 rounded-[1.5rem] p-6">
            <div class="flex items-center gap-3 mb-4">
                <div class="size-8 rounded-full bg-amber-200 dark:bg-amber-800/50 text-amber-700 dark:text-amber-400 flex items-center justify-center shrink-0">
                    <span class="material-symbols-outlined text-sm">rule</span>
                </div>
                <h3 class="font-black text-amber-800 dark:text-amber-400">Aturan Pengisian</h3>
            </div>
            
            <div class="space-y-4">
                <div class="bg-white/60 dark:bg-slate-900/40 p-3 rounded-lg border border-amber-100 dark:border-amber-800/30">
                    <p class="text-xs font-bold text-amber-800 dark:text-amber-500 mb-2">Section & Sub-Topik Valid</p>
                    <p class="text-[11px] text-amber-700 dark:text-amber-200/70 mb-3 leading-relaxed">Anda sekarang boleh mengetik judul aslinya (contoh: "Logika / Penalaran") atau slug-nya secara bebas (huruf besar/kecil tidak masalah).</p>
                    
                    <div x-data="{ expanded: false }" class="bg-white dark:bg-slate-800 rounded border border-amber-200 dark:border-amber-700/50 overflow-hidden">
                        <button @click="expanded = !expanded" type="button" class="w-full flex items-center justify-between p-2.5 bg-amber-100/50 dark:bg-amber-900/40 hover:bg-amber-100 dark:hover:bg-amber-900/60 transition-colors text-left">
                            <span class="text-xs font-bold text-amber-900 dark:text-amber-400">Lihat Daftar Lengkap</span>
                            <span class="material-symbols-outlined text-sm text-amber-600 transition-transform" :class="expanded ? 'rotate-180' : ''">expand_more</span>
                        </button>
                        <div x-show="expanded" x-collapse.duration.300ms>
                            <div class="p-3 text-[11px] max-h-48 overflow-y-auto sidebar-scroll space-y-3">
                                @forelse($sections as $s)
                                    <div>
                                        <p class="font-bold text-slate-800 dark:text-slate-200 border-b border-slate-100 dark:border-slate-700 pb-1 mb-1.5">{{ $s->name }}</p>
                                        <ul class="space-y-1 text-slate-600 dark:text-slate-400 pl-2 list-disc list-inside">
                                            @forelse($s->subTopics as $st)
                                                <li>{{ $st->title }}</li>
                                            @empty
                                                <li class="italic text-slate-400">Belum ada sub-topik</li>
                                            @endforelse
                                        </ul>
                                    </div>
                                @empty
                                    <p class="text-slate-500 italic">Data bank materi kosong.</p>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white/60 dark:bg-slate-900/40 p-3 rounded-lg border border-amber-100 dark:border-amber-800/30">
                    <p class="text-xs font-bold text-amber-800 dark:text-amber-500 mb-1">Kunci Jawaban</p>
                    <p class="text-xs text-amber-700 dark:text-amber-200/70">Wajib diisi abjad: <strong>A, B, C, D, atau E</strong>.</p>
                </div>
                <div class="bg-white/60 dark:bg-slate-900/40 p-3 rounded-lg border border-amber-100 dark:border-amber-800/30">
                    <p class="text-xs font-bold text-amber-800 dark:text-amber-500 mb-1">Tingkat Kesulitan</p>
                    <p class="text-xs text-amber-700 dark:text-amber-200/70">Pilih antara: <strong>easy, medium, atau hard</strong>.</p>
                </div>
            </div>
            <p class="text-[11px] text-amber-600/80 dark:text-amber-500/60 mt-4 italic text-center">
                *Baris yang tidak memenuhi aturan akan dilaporkan gagal.
            </p>
        </div>

    </div>
</div>
@endsection
