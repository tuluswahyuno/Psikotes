<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

@if(auth()->user()->isAdmin())
{{-- =================== ADMIN HEAD =================== --}}
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Psikotes') }} — @yield('title', 'Dashboard')</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=outfit:300,400,500,600,700,800&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @stack('styles')
</head>
<body class="font-sans antialiased bg-[#F3F4F6]" style="font-family: 'Outfit', sans-serif;">
    <div class="min-h-screen flex">
        <aside class="w-72 bg-[#0B0F19] text-gray-400 flex flex-col fixed h-full z-30 border-r border-[#1C2333] shadow-2xl">
            <div class="px-6 py-6 border-b border-[#1C2333] bg-[#0B0F19]/50 backdrop-blur-xl">
                <div class="flex items-center gap-3 group cursor-pointer">
                    <div class="w-12 h-12 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-xl flex items-center justify-center shadow-lg">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" /></svg>
                    </div>
                    <div>
                        <h1 class="text-xl font-bold text-white tracking-wide">Psikotes<span class="text-indigo-400">App</span></h1>
                        <p class="text-xs text-gray-500 font-medium">Admin Panel</p>
                    </div>
                </div>
            </div>
            <nav class="flex-1 px-4 py-6 space-y-2 overflow-y-auto">
                <div class="px-3 pb-3 text-xs font-bold text-gray-500 uppercase tracking-wider">Main Menu</div>
                <a href="{{ route('admin.dashboard') }}" class="sidebar-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
                    <span>Dashboard</span>
                </a>
                <a href="{{ route('admin.tests.index') }}" class="sidebar-link {{ request()->routeIs('admin.tests.*') ? 'active' : '' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                    <span>Kelola Tes</span>
                </a>
                <a href="{{ route('admin.users.index') }}" class="sidebar-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197"/></svg>
                    <span>Kelola Peserta</span>
                </a>
                <a href="{{ route('admin.results.index') }}" class="sidebar-link {{ request()->routeIs('admin.results.*') ? 'active' : '' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                    <span>Hasil & Laporan</span>
                </a>
                <div class="px-3 pt-5 pb-3 text-xs font-bold text-gray-500 uppercase tracking-wider">SKD CPNS</div>
                <a href="{{ route('admin.skd-packages.index') }}" class="sidebar-link {{ request()->routeIs('admin.skd-packages.*') ? 'active' : '' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                    <span>Paket SKD</span>
                </a>
                <a href="{{ route('admin.skd-results.index') }}" class="sidebar-link {{ request()->routeIs('admin.skd-results.*') ? 'active' : '' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/></svg>
                    <span>Hasil SKD</span>
                </a>
            </nav>
            <div class="p-4 border-t border-[#1C2333] bg-[#090C15]">
                <div class="flex items-center gap-3 px-3 py-3 rounded-xl bg-white/5 border border-white/10 mb-3">
                    <div class="w-10 h-10 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-lg flex items-center justify-center text-white text-sm font-bold">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-semibold text-white truncate">{{ auth()->user()->name }}</p>
                        <p class="text-xs text-indigo-400 capitalize">{{ auth()->user()->role }}</p>
                    </div>
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full flex items-center justify-center gap-2 py-2.5 rounded-lg text-sm font-medium text-red-400 hover:text-white hover:bg-red-500/20 border border-transparent transition-all">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                        Keluar
                    </button>
                </form>
            </div>
        </aside>

        <div class="flex-1 ml-72">
            <header class="bg-white/80 backdrop-blur-lg border-b border-gray-100 sticky top-0 z-20">
                <div class="px-8 py-4 flex items-center justify-between">
                    <div>
                        <h2 class="text-xl font-bold text-gray-900">@yield('title', 'Dashboard')</h2>
                        @hasSection('subtitle')
                            <p class="text-sm text-gray-500 mt-0.5">@yield('subtitle')</p>
                        @endif
                    </div>
                    <div class="flex items-center gap-3">@yield('actions')</div>
                </div>
            </header>

            @if(session('success'))
                <div class="mx-8 mt-6">
                    <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 px-5 py-4 rounded-xl flex items-center gap-3" id="flash-success">
                        <svg class="w-5 h-5 text-emerald-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                        <span class="text-sm font-medium">{{ session('success') }}</span>
                    </div>
                </div>
                <script>setTimeout(() => document.getElementById('flash-success')?.remove(), 4000);</script>
            @endif
            @if(session('error'))
                <div class="mx-8 mt-6">
                    <div class="bg-red-50 border border-red-200 text-red-800 px-5 py-4 rounded-xl flex items-center gap-3">
                        <svg class="w-5 h-5 text-red-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/></svg>
                        <span class="text-sm font-medium">{{ session('error') }}</span>
                    </div>
                </div>
            @endif
            <main class="p-8">@yield('content')</main>
        </div>
    </div>
    @yield('scripts')
</body>

@else
{{-- =================== PESERTA HEAD =================== --}}
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Psikotes') }} — @yield('title', 'Dashboard')</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        "primary": "#135bec",
                        "background-light": "#f6f6f8",
                        "background-dark": "#101622",
                        "success": "#07883b",
                        "danger": "#d32f2f",
                    },
                    fontFamily: {
                        "display": ["Public Sans", "sans-serif"]
                    },
                    borderRadius: {"DEFAULT": "0.25rem", "lg": "0.5rem", "xl": "0.75rem", "full": "9999px"},
                },
            },
        }
    </script>
    <style>
        body { font-family: 'Public Sans', sans-serif; }
        .material-symbols-outlined { font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24; }
    </style>
    @stack('styles')
</head>
<body class="bg-background-light dark:bg-background-dark text-slate-900 dark:text-slate-100 font-display">
<div class="flex min-h-screen">
    <!-- Sticky SideNavBar -->
    <aside class="sticky top-0 h-screen w-64 flex flex-col bg-white dark:bg-slate-900 border-r border-slate-200 dark:border-slate-800 p-4 shrink-0">
        <div class="flex items-center gap-3 mb-8 px-2">
            <div class="h-10 w-10 bg-primary rounded-lg flex items-center justify-center text-white">
                <span class="material-symbols-outlined text-2xl">school</span>
            </div>
            <div class="flex flex-col">
                <h1 class="text-slate-900 dark:text-white text-base font-bold leading-none">{{ config('app.name', 'Prep Master') }}</h1>
                <p class="text-slate-500 dark:text-slate-400 text-xs font-medium">CPNS {{ date('Y') }}</p>
            </div>
        </div>
        <nav class="flex flex-col gap-1 grow">
            <a class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-colors {{ request()->routeIs('peserta.dashboard') ? 'bg-primary text-white' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800' }}" href="{{ route('peserta.dashboard') }}">
                <span class="material-symbols-outlined">dashboard</span>
                <span class="text-sm font-semibold">Dashboard</span>
            </a>
            <a class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-colors {{ request()->routeIs('peserta.skd.index', 'peserta.skd.show', 'peserta.skd.attempt') ? 'bg-primary text-white' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800' }}" href="{{ route('peserta.skd.index') }}">
                <span class="material-symbols-outlined">description</span>
                <span class="text-sm font-medium">Simulasi SKD</span>
            </a>
            <a class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-colors {{ request()->routeIs('peserta.results.*', 'peserta.tests.*') ? 'bg-primary text-white' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800' }}" href="{{ route('peserta.results.index') }}">
                <span class="material-symbols-outlined">psychology</span>
                <span class="text-sm font-medium">Psikotes</span>
            </a>
            <a class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-colors {{ request()->routeIs('peserta.skd.results', 'peserta.skd.results.show') ? 'bg-primary text-white' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800' }}" href="{{ route('peserta.skd.results') }}">
                <span class="material-symbols-outlined">bar_chart</span>
                <span class="text-sm font-medium">Riwayat Hasil</span>
            </a>
            <a class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-colors {{ request()->routeIs('peserta.skd.leaderboard') ? 'bg-primary text-white' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800' }}" href="{{ route('peserta.skd.leaderboard') }}">
                <span class="material-symbols-outlined">leaderboard</span>
                <span class="text-sm font-medium">Leaderboard</span>
            </a>
        </nav>
        
        <div class="p-4 mt-auto border-t border-slate-200 dark:border-slate-800">
            <div class="bg-primary/5 rounded-xl p-4 mb-4">
                <p class="text-xs font-bold text-primary uppercase tracking-wider mb-1">Upgrade Akun</p>
                <p class="text-xs text-slate-500 dark:text-slate-400 mb-3">Akses semua fitur premium &amp; soal-soal HOTS.</p>
                <button class="w-full bg-primary text-white text-xs font-bold py-2 px-4 rounded-lg hover:bg-primary/90 transition-all">Upgrade Pro</button>
            </div>
            <a class="flex items-center gap-3 px-3 py-2.5 mb-1 rounded-lg transition-colors {{ request()->routeIs('profile.edit') ? 'bg-primary text-white' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800' }}" href="{{ route('profile.edit') }}">
                <span class="material-symbols-outlined">settings</span>
                <span class="text-sm font-medium">Pengaturan</span>
            </a>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors w-full">
                    <span class="material-symbols-outlined">logout</span>
                    <span class="text-sm font-medium">Keluar</span>
                </button>
            </form>
        </div>
    </aside>

    <!-- Main Content Area -->
    <main class="flex-1 p-8 w-full">
        @if(session('success'))
            <div class="mb-6">
                <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 px-5 py-4 rounded-xl flex items-center gap-3" id="flash-success">
                    <span class="material-symbols-outlined text-emerald-500">check_circle</span>
                    <span class="text-sm font-medium">{{ session('success') }}</span>
                </div>
            </div>
            <script>setTimeout(() => document.getElementById('flash-success')?.remove(), 4000);</script>
        @endif
        @if(session('error'))
            <div class="mb-6">
                <div class="bg-red-50 border border-red-200 text-red-800 px-5 py-4 rounded-xl flex items-center gap-3">
                    <span class="material-symbols-outlined text-red-500">error</span>
                    <span class="text-sm font-medium">{{ session('error') }}</span>
                </div>
            </div>
        @endif
        @yield('content')
    </main>
</div>
@yield('scripts')
</body>
@endif
</html>
