<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

@if(auth()->user()->isAdmin())
{{-- =================== ADMIN HEAD =================== --}}
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Psikotes') }} — Admin</title>

    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
    
    <!-- AlpineJS for interactive logic -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        "primary": "#135bec",
                        "background-light": "#f6f6f8",
                        "background-dark": "#101622",
                    },
                    fontFamily: {
                        "display": ["Public Sans", "sans-serif"]
                    },
                    borderRadius: { "DEFAULT": "0.25rem", "lg": "0.5rem", "xl": "0.75rem", "full": "9999px" },
                },
            },
        }
    </script>
    <style>
        body { font-family: 'Public Sans', sans-serif; }
        .sidebar-scroll::-webkit-scrollbar { width: 4px; }
        .sidebar-scroll::-webkit-scrollbar-thumb { background: #e2e8f0; border-radius: 10px; }
    </style>
    @stack('styles')
</head>
<body class="bg-background-light dark:bg-background-dark text-slate-900 dark:text-slate-100 antialiased">
<div class="flex h-screen overflow-hidden">
    
    <!-- Sidebar -->
    <aside class="w-72 bg-white dark:bg-slate-900 border-r border-slate-200 dark:border-slate-800 flex flex-col shrink-0">
        <div class="p-6 flex items-center gap-3">
            <div class="size-10 bg-primary rounded-xl flex items-center justify-center text-white">
                <span class="material-symbols-outlined text-2xl">school</span>
            </div>
            <div>
                <h1 class="text-lg font-extrabold tracking-tight leading-none">{{ config('app.name', 'CPNS Prep') }}</h1>
                <p class="text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-widest mt-1">Admin Panel</p>
            </div>
        </div>
        
        <nav class="flex-1 overflow-y-auto px-4 py-4 sidebar-scroll space-y-2">
            <!-- Dashboard Active -->
            <a class="flex items-center gap-3 px-4 py-3 rounded-xl {{ request()->routeIs('admin.dashboard') ? 'bg-primary text-white shadow-lg shadow-primary/20' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800' }}" href="{{ route('admin.dashboard') }}">
                <span class="material-symbols-outlined">dashboard</span>
                <span class="font-semibold text-sm">Dashboard</span>
            </a>
            
            <!-- Manage Test -->
            <div class="space-y-1" x-data="{ open: {{ request()->routeIs('admin.tests.*') ? 'true' : 'false' }} }">
                <div @click="open = !open" class="flex items-center justify-between px-4 py-3 text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800 rounded-xl cursor-pointer">
                    <div class="flex items-center gap-3">
                        <span class="material-symbols-outlined">quiz</span>
                        <span class="font-semibold text-sm">Manage Test</span>
                    </div>
                    <span class="material-symbols-outlined text-sm" :class="open ? 'rotate-180' : ''">expand_more</span>
                </div>
                <div x-show="open" class="pl-12 pr-4 space-y-1">
                    <a class="block py-2 text-sm {{ request()->routeIs('admin.tests.index') || request()->routeIs('admin.tests.create') ? 'text-primary font-bold' : 'text-slate-500 hover:text-primary transition-colors' }}" href="{{ route('admin.tests.index') }}">List Test</a>
                </div>
            </div>
            
            <!-- Manage Participant -->
            <div class="space-y-1" x-data="{ open: {{ request()->routeIs('admin.users.*') ? 'true' : 'false' }} }">
                <div @click="open = !open" class="flex items-center justify-between px-4 py-3 text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800 rounded-xl cursor-pointer">
                    <div class="flex items-center gap-3">
                        <span class="material-symbols-outlined">group</span>
                        <span class="font-semibold text-sm">Manage Participant</span>
                    </div>
                    <span class="material-symbols-outlined text-sm" :class="open ? 'rotate-180' : ''">expand_more</span>
                </div>
                <div x-show="open" class="pl-12 pr-4 space-y-1">
                    <a class="block py-2 text-sm {{ request()->routeIs('admin.users.index') ? 'text-primary font-bold' : 'text-slate-500 hover:text-primary transition-colors' }}" href="{{ route('admin.users.index') }}">All Students</a>
                </div>
            </div>
            
            <!-- Result & Report -->
            <div class="space-y-1" x-data="{ open: {{ request()->routeIs('admin.results.*') ? 'true' : 'false' }} }">
                <div @click="open = !open" class="flex items-center justify-between px-4 py-3 text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800 rounded-xl cursor-pointer">
                    <div class="flex items-center gap-3">
                        <span class="material-symbols-outlined">analytics</span>
                        <span class="font-semibold text-sm">Result &amp; Report</span>
                    </div>
                    <span class="material-symbols-outlined text-sm" :class="open ? 'rotate-180' : ''">expand_more</span>
                </div>
                <div x-show="open" class="pl-12 pr-4 space-y-1">
                    <a class="block py-2 text-sm {{ request()->routeIs('admin.results.index') ? 'text-primary font-bold' : 'text-slate-500 hover:text-primary transition-colors' }}" href="{{ route('admin.results.index') }}">Performance Analytics</a>
                </div>
            </div>
            
            <!-- SKD Package -->
            <div class="space-y-1" x-data="{ open: {{ request()->routeIs('admin.skd-packages.*') ? 'true' : 'false' }} }">
                <div @click="open = !open" class="flex items-center justify-between px-4 py-3 text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800 rounded-xl cursor-pointer">
                    <div class="flex items-center gap-3">
                        <span class="material-symbols-outlined">inventory_2</span>
                        <span class="font-semibold text-sm">SKD Package</span>
                    </div>
                    <span class="material-symbols-outlined text-sm" :class="open ? 'rotate-180' : ''">expand_more</span>
                </div>
                <div x-show="open" class="pl-12 pr-4 space-y-1">
                    <a class="block py-2 text-sm {{ request()->routeIs('admin.skd-packages.index') || request()->routeIs('admin.skd-packages.create') ? 'text-primary font-bold' : 'text-slate-500 hover:text-primary transition-colors' }}" href="{{ route('admin.skd-packages.index') }}">Package Management</a>
                </div>
            </div>
            
            <!-- SKD Result -->
            <div class="space-y-1" x-data="{ open: {{ request()->routeIs('admin.skd-results.*') ? 'true' : 'false' }} }">
                <div @click="open = !open" class="flex items-center justify-between px-4 py-3 text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800 rounded-xl cursor-pointer">
                    <div class="flex items-center gap-3">
                        <span class="material-symbols-outlined">verified</span>
                        <span class="font-semibold text-sm">SKD Result</span>
                    </div>
                    <span class="material-symbols-outlined text-sm" :class="open ? 'rotate-180' : ''">expand_more</span>
                </div>
                <div x-show="open" class="pl-12 pr-4 space-y-1">
                    <a class="block py-2 text-sm {{ request()->routeIs('admin.skd-results.index') ? 'text-primary font-bold' : 'text-slate-500 hover:text-primary transition-colors' }}" href="{{ route('admin.skd-results.index') }}">Individual Results</a>
                </div>
            </div>
        </nav>
        
        <div class="p-4 border-t border-slate-200 dark:border-slate-800 space-y-2">
            <a class="flex items-center gap-3 px-4 py-3 text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800 rounded-xl transition-colors" href="{{ route('profile.edit') }}">
                <span class="material-symbols-outlined">settings</span>
                <span class="font-semibold text-sm">Settings</span>
            </a>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full flex items-center gap-3 px-4 py-3 text-red-500 hover:bg-red-50 dark:hover:bg-red-900/10 rounded-xl transition-colors">
                    <span class="material-symbols-outlined">logout</span>
                    <span class="font-semibold text-sm">Logout</span>
                </button>
            </form>
        </div>
    </aside>
    
    <!-- Main Content Area -->
    <main class="flex-1 overflow-y-auto">
        <!-- Header -->
        <header class="h-20 bg-white/80 dark:bg-slate-900/80 backdrop-blur-md border-b border-slate-200 dark:border-slate-800 sticky top-0 z-40 px-8 flex items-center justify-between">
            <div class="flex items-center gap-4 flex-1">
                <div class="relative w-96 hidden md:block">
                    <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-xl">search</span>
                    <input class="w-full pl-10 pr-4 py-2 bg-slate-100 dark:bg-slate-800 border-none rounded-lg text-sm focus:ring-2 focus:ring-primary/20 placeholder:text-slate-400" placeholder="Search..." type="text"/>
                </div>
            </div>
            <div class="flex items-center gap-6">
                <button class="relative text-slate-500 hover:text-primary transition-colors">
                    <span class="material-symbols-outlined">notifications</span>
                    <span class="absolute top-0 right-0 w-2 h-2 bg-red-500 rounded-full border-2 border-white dark:border-slate-900"></span>
                </button>
                <button class="text-slate-500 hover:text-primary transition-colors">
                    <span class="material-symbols-outlined">chat_bubble</span>
                </button>
                <div class="h-8 w-px bg-slate-200 dark:border-slate-800"></div>
                <div class="flex items-center gap-3">
                    <div class="text-right hidden sm:block">
                        <p class="text-sm font-bold leading-none">{{ auth()->user()->name }}</p>
                        <p class="text-xs text-slate-500 leading-none mt-1 capitalize">{{ auth()->user()->role }}</p>
                    </div>
                    <div class="w-10 h-10 rounded-full bg-primary/20 border-2 border-primary/10 overflow-hidden flex items-center justify-center text-primary font-bold">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    </div>
                </div>
            </div>
        </header>

        @if(session('success'))
            <div class="p-8 pb-0">
                <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 px-5 py-4 rounded-xl flex items-center gap-3" id="flash-success">
                    <span class="material-symbols-outlined text-emerald-500">check_circle</span>
                    <span class="text-sm font-medium">{{ session('success') }}</span>
                </div>
            </div>
            <script>setTimeout(() => document.getElementById('flash-success')?.remove(), 4000);</script>
        @endif
        @if(session('error'))
            <div class="p-8 pb-0">
                <div class="bg-red-50 border border-red-200 text-red-800 px-5 py-4 rounded-xl flex items-center gap-3">
                    <span class="material-symbols-outlined text-red-500">error</span>
                    <span class="text-sm font-medium">{{ session('error') }}</span>
                </div>
            </div>
        @endif

        <!-- Content Area -->
        @hasSection('page_header')
            @yield('page_header')
            <div class="flex-1 overflow-y-auto p-8">
                @yield('content')
            </div>
        @else
            <div class="p-8">
                <div class="flex flex-col gap-1 mb-8">
                    <h2 class="text-3xl font-extrabold tracking-tight">@yield('title')</h2>
                    <p class="text-slate-500 dark:text-slate-400">@yield('subtitle')</p>
                </div>
                
                @yield('content')
            </div>
        @endif
    </main>
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
