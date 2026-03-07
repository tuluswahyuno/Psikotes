@extends('layouts.main')
@section('title', 'Tambah Peserta Baru')
@section('subtitle', 'Daftarkan partisipan baru ke dalam sistem ujian')

@section('page_header')
<header class="bg-white/80 dark:bg-slate-900/80 backdrop-blur-md border-b border-slate-200 dark:border-slate-800 px-8 py-4 sticky top-0 z-10">
    <div class="max-w-7xl mx-auto flex items-center justify-between">
        <div>
            <h2 class="text-xl font-bold text-slate-900 dark:text-white tracking-tight">Tambah Peserta</h2>
            <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">Daftarkan pengguna baru untuk mengikuti ujian</p>
        </div>
        <a href="{{ route('admin.users.index') }}" class="flex items-center gap-2 text-sm font-medium text-slate-600 dark:text-slate-400 hover:text-primary transition-colors">
            <span class="material-symbols-outlined text-xl">arrow_back</span>
            Batal & Kembali
        </a>
    </div>
</header>
@endsection

@section('content')
<div class="max-w-4xl mx-auto mt-4 md:mt-0">
    <div class="grid grid-cols-1 md:grid-cols-12 gap-8">
        
        <!-- Left Panel: Instruction/Tip -->
        <div class="md:col-span-4 space-y-6">
            <div class="bg-primary/5 border border-primary/20 rounded-2xl p-6 dark:bg-primary/10 dark:border-primary/30">
                <div class="flex items-center gap-3 mb-3 text-primary">
                    <span class="material-symbols-outlined text-2xl">person_add</span>
                    <h3 class="font-bold">Info Registrasi</h3>
                </div>
                <p class="text-sm text-slate-600 dark:text-slate-400 leading-relaxed mb-4">
                    Peserta yang didaftarkan pada formulir ini akan otomatis terdaftar dengan hak akses sebagai <strong>Peserta Ujian</strong> (bukan Admin).
                </p>
                <ul class="text-xs space-y-3 text-slate-600 dark:text-slate-400 font-medium list-none mt-4">
                    <li class="flex items-start gap-2">
                        <span class="material-symbols-outlined text-primary text-base">check_circle</span>
                        Pastikan email yang digunakan aktif.
                    </li>
                    <li class="flex items-start gap-2">
                        <span class="material-symbols-outlined text-primary text-base">check_circle</span>
                        Password minimal 8 karakter.
                    </li>
                    <li class="flex items-start gap-2">
                        <span class="material-symbols-outlined text-primary text-base">check_circle</span>
                        Peserta dapat login segera setelah akun berhasil dibuat.
                    </li>
                </ul>
            </div>
            
            <div class="hidden md:block">
                <img src="https://ui-avatars.com/api/?name=New+User&background=135bec&color=fff&size=200&rounded=true" alt="User Illustration" class="w-full max-w-[150px] mx-auto opacity-50 grayscale hover:grayscale-0 transition-all duration-500">
            </div>
        </div>

        <!-- Right Panel: Form -->
        <div class="md:col-span-8">
            <div class="bg-white dark:bg-slate-900 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-800 p-6 md:p-8 relative overflow-hidden">
                <div class="absolute top-0 left-0 w-full h-1 bg-primary"></div>
                
                <h3 class="text-lg font-bold text-slate-900 dark:text-white mb-6 flex items-center gap-2 border-b border-slate-200 dark:border-slate-800 pb-4">
                    <span class="material-symbols-outlined text-primary">manage_accounts</span>
                    Detail Informasi Akun
                </h3>
                
                <form action="{{ route('admin.users.store') }}" method="POST">
                    @csrf
                    <div class="space-y-6">
                        
                        <!-- Nama Lengkap -->
                        <div>
                            <label class="block text-xs uppercase tracking-widest font-bold text-slate-500 dark:text-slate-400 mb-2" for="name">Nama Lengkap <span class="text-rose-500">*</span></label>
                            <div class="relative flex items-center">
                                <span class="material-symbols-outlined absolute left-4 text-slate-400">badge</span>
                                <input type="text" name="name" id="name" value="{{ old('name') }}" class="w-full pl-12 pr-4 py-3 bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 text-slate-900 dark:text-white rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all placeholder-slate-400 font-medium" required placeholder="Contoh: Budi Santoso">
                            </div>
                            @error('name') <p class="text-rose-500 text-xs mt-2 font-medium flex items-center gap-1"><span class="material-symbols-outlined text-sm">error</span>{{ $message }}</p> @enderror
                        </div>

                        <!-- Email -->
                        <div>
                            <label class="block text-xs uppercase tracking-widest font-bold text-slate-500 dark:text-slate-400 mb-2" for="email">Alamat Email <span class="text-rose-500">*</span></label>
                            <div class="relative flex items-center">
                                <span class="material-symbols-outlined absolute left-4 text-slate-400">mail</span>
                                <input type="email" name="email" id="email" value="{{ old('email') }}" class="w-full pl-12 pr-4 py-3 bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 text-slate-900 dark:text-white rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all placeholder-slate-400 font-medium" required placeholder="budi@email.com">
                            </div>
                            @error('email') <p class="text-rose-500 text-xs mt-2 font-medium flex items-center gap-1"><span class="material-symbols-outlined text-sm">error</span>{{ $message }}</p> @enderror
                        </div>

                        <!-- Hak Akses (Role) - Visual Custom Dropdown Using AlpineJS -->
                        <!-- Based on user request to include stunning dropdowns -->
                        <div>
                            <label class="block text-xs uppercase tracking-widest font-bold text-slate-500 dark:text-slate-400 mb-2">Hak Akses / Peran</label>
                            <div x-data="{ open: false }" class="relative">
                                <button type="button" @click="open = !open" @click.outside="open = false" class="w-full flex items-center justify-between gap-2 px-4 py-3 bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 text-slate-900 dark:text-white rounded-xl focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all font-medium cursor-pointer shadow-sm">
                                    <div class="flex items-center gap-3">
                                        <div class="size-6 rounded-md bg-emerald-100 dark:bg-emerald-900/30 text-emerald-600 flex items-center justify-center">
                                            <span class="material-symbols-outlined text-sm block">school</span>
                                        </div>
                                        <span>Peserta (Default)</span>
                                    </div>
                                    <span class="material-symbols-outlined text-slate-400 pointer-events-none transition-transform duration-200" :class="open ? 'rotate-180' : ''">expand_more</span>
                                </button>
                                
                                <div x-show="open" x-transition style="display: none;" class="absolute z-50 mt-1 w-full bg-white dark:bg-slate-800 rounded-xl shadow-lg shadow-slate-200/50 dark:shadow-none border border-slate-200 dark:border-slate-700 py-2 overflow-hidden">
                                     <!-- Only showing Peserta visually according to business logic, but maintaining interaction -->
                                    <button type="button" @click="open = false" class="w-full flex items-center gap-3 px-4 py-3 text-left bg-primary/5 text-primary dark:bg-primary/10 font-bold transition-colors">
                                        <div class="size-8 rounded-lg bg-emerald-100 dark:bg-emerald-900/30 text-emerald-600 flex items-center justify-center">
                                            <span class="material-symbols-outlined text-base">school</span>
                                        </div>
                                        <div>
                                            <span class="block">Peserta (User Ujian)</span>
                                            <span class="text-[10px] font-normal text-slate-500 dark:text-slate-400">Hak akses mengikuti & melihat hasil tes</span>
                                        </div>
                                    </button>
                                </div>
                                <!-- This input handles backend req visually though it's hardcoded at controller -->
                                <input type="hidden" name="role" value="peserta">
                            </div>
                        </div>

                        <!-- Passwords Grid -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6 pt-6 border-t border-slate-200 dark:border-slate-800 border-dashed">
                            <div>
                                <label class="block text-xs uppercase tracking-widest font-bold text-slate-500 dark:text-slate-400 mb-2" for="password">Kata Sandi <span class="text-rose-500">*</span></label>
                                <div class="relative flex items-center">
                                    <span class="material-symbols-outlined absolute left-4 text-slate-400">lock</span>
                                    <input type="password" name="password" id="password" class="w-full pl-12 pr-4 py-3 bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 text-slate-900 dark:text-white rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all font-medium" required minlength="8" placeholder="Minimal 8 karakter">
                                </div>
                                @error('password') <p class="text-rose-500 text-xs mt-2 font-medium flex items-center gap-1"><span class="material-symbols-outlined text-sm">error</span>{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label class="block text-xs uppercase tracking-widest font-bold text-slate-500 dark:text-slate-400 mb-2" for="password_confirmation">Konfirmasi Sandi <span class="text-rose-500">*</span></label>
                                <div class="relative flex items-center">
                                    <span class="material-symbols-outlined absolute left-4 text-slate-400">password</span>
                                    <input type="password" name="password_confirmation" id="password_confirmation" class="w-full pl-12 pr-4 py-3 bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 text-slate-900 dark:text-white rounded-xl focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all font-medium" required placeholder="Ulangi kata sandi">
                                </div>
                            </div>
                        </div>

                    </div>

                    <div class="flex flex-col-reverse sm:flex-row items-center justify-end gap-3 mt-8 pt-6 border-t border-slate-200 dark:border-slate-800 shrink-0">
                        <a href="{{ route('admin.users.index') }}" class="w-full sm:w-auto px-6 py-3 text-slate-600 dark:text-slate-400 font-bold hover:text-slate-900 dark:hover:text-white transition-colors text-center">Batal</a>
                        <button type="submit" class="w-full sm:w-auto bg-primary hover:bg-primary/90 text-white px-8 py-3 rounded-xl font-bold flex items-center justify-center gap-2 transition-all shadow-lg shadow-primary/20">
                            <span class="material-symbols-outlined text-xl">person_add</span>
                            Daftarkan Peserta
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
