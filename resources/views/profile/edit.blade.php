@extends('layouts.main')

@section('title', 'Pengaturan Akun')

@section('content')
<div class="max-w-4xl mx-auto space-y-8">

    <!-- Header info -->
    <div>
        <h2 class="text-3xl font-black text-slate-900 dark:text-white tracking-tight">Pengaturan Akun</h2>
        <p class="text-slate-500 dark:text-slate-400 mt-1">Perbarui informasi profil dan kata sandi Anda di sini.</p>
    </div>

    <!-- Update Profile Information Form -->
    <div class="bg-white dark:bg-slate-900 shadow-sm border border-slate-200 dark:border-slate-800 rounded-xl overflow-hidden">
        <div class="px-6 py-5 border-b border-slate-100 dark:border-slate-800">
            <h3 class="text-lg font-bold text-slate-900 dark:text-white">Informasi Profil</h3>
            <p class="text-sm text-slate-500">Perbarui nama lengkap dan alamat email akun Anda.</p>
        </div>
        
        <form method="post" action="{{ route('profile.update') }}" class="p-6">
            @csrf
            @method('patch')

            <!-- Avatar placeholder visual (Static for now, since no backend logic for photo upload originally) -->
            <div class="flex items-center gap-4 mb-6">
                @php $initials = collect(explode(' ', auth()->user()->name))->map(fn($s) => strtoupper(substr($s, 0, 1)))->take(2)->join(''); @endphp
                <div class="h-16 w-16 bg-primary/10 rounded-full flex items-center justify-center text-primary text-xl font-bold border-2 border-primary/20">
                    {{ $initials }}
                </div>
                <div>
                    <h4 class="font-bold text-slate-900 dark:text-white">{{ $user->name }}</h4>
                    <p class="text-sm text-slate-500">{{ $user->email }}</p>
                </div>
            </div>

            <div class="space-y-4 max-w-xl">
                <div>
                    <label for="name" class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1">Nama Lengkap</label>
                    <input id="name" name="name" type="text" value="{{ old('name', $user->name) }}" required autofocus autocomplete="name"
                           class="w-full rounded-lg border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800/50 focus:ring-primary focus:border-primary">
                    @error('name')
                        <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="email" class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1">Email</label>
                    <input id="email" name="email" type="email" value="{{ old('email', $user->email) }}" required autocomplete="username"
                           class="w-full rounded-lg border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800/50 focus:ring-primary focus:border-primary">
                    @error('email')
                        <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                    @enderror

                    @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                        <div class="mt-2 text-sm text-amber-600 bg-amber-50 dark:bg-amber-900/30 p-2 rounded-lg">
                            Alamat email Anda belum diverifikasi.
                            <button form="send-verification" class="font-bold hover:underline">
                                Klik di sini untuk mengirim ulang email verifikasi.
                            </button>
                        </div>
                    @endif
                </div>

                <div class="flex items-center gap-4 pt-2">
                    <button type="submit" class="bg-primary hover:bg-primary/90 text-white font-semibold py-2 px-6 rounded-lg transition-colors">
                        Simpan Perubahan
                    </button>
                    @if (session('status') === 'profile-updated')
                        <p class="text-sm text-emerald-600 font-bold flex items-center gap-1">
                            <span class="material-symbols-outlined text-sm">check_circle</span> Disimpan.
                        </p>
                    @endif
                </div>
            </div>
        </form>
    </div>

    <!-- Update Password Form -->
    <div class="bg-white dark:bg-slate-900 shadow-sm border border-slate-200 dark:border-slate-800 rounded-xl overflow-hidden">
        <div class="px-6 py-5 border-b border-slate-100 dark:border-slate-800">
            <h3 class="text-lg font-bold text-slate-900 dark:text-white">Ubah Kata Sandi</h3>
            <p class="text-sm text-slate-500">Pastikan akun Anda menggunakan kata sandi acak yang panjang agar tetap aman.</p>
        </div>

        <form method="post" action="{{ route('password.update') }}" class="p-6">
            @csrf
            @method('put')

            <div class="space-y-4 max-w-xl">
                <div>
                    <label for="update_password_current_password" class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1">Kata Sandi Saat Ini</label>
                    <input id="update_password_current_password" name="current_password" type="password" autocomplete="current-password"
                           class="w-full rounded-lg border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800/50 focus:ring-primary focus:border-primary">
                    @if($errors->updatePassword->has('current_password'))
                        <p class="text-sm text-red-500 mt-1">{{ $errors->updatePassword->first('current_password') }}</p>
                    @endif
                </div>

                <div>
                    <label for="update_password_password" class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1">Kata Sandi Baru</label>
                    <input id="update_password_password" name="password" type="password" autocomplete="new-password"
                           class="w-full rounded-lg border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800/50 focus:ring-primary focus:border-primary">
                    @if($errors->updatePassword->has('password'))
                        <p class="text-sm text-red-500 mt-1">{{ $errors->updatePassword->first('password') }}</p>
                    @endif
                </div>

                <div>
                    <label for="update_password_password_confirmation" class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1">Konfirmasi Kata Sandi</label>
                    <input id="update_password_password_confirmation" name="password_confirmation" type="password" autocomplete="new-password"
                           class="w-full rounded-lg border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800/50 focus:ring-primary focus:border-primary">
                    @if($errors->updatePassword->has('password_confirmation'))
                        <p class="text-sm text-red-500 mt-1">{{ $errors->updatePassword->first('password_confirmation') }}</p>
                    @endif
                </div>

                <div class="flex items-center gap-4 pt-2">
                    <button type="submit" class="bg-slate-800 dark:bg-slate-100 hover:bg-slate-700 dark:hover:bg-white text-white dark:text-slate-900 font-semibold py-2 px-6 rounded-lg transition-colors">
                        Perbarui Kata Sandi
                    </button>

                    @if (session('status') === 'password-updated')
                        <p class="text-sm text-emerald-600 font-bold flex items-center gap-1">
                            <span class="material-symbols-outlined text-sm">check_circle</span> Diperbarui.
                        </p>
                    @endif
                </div>
            </div>
        </form>
    </div>

</div>

<!-- Hidden form for email verification -->
<form id="send-verification" method="post" action="{{ route('verification.send') }}" class="hidden">
    @csrf
</form>

@endsection
