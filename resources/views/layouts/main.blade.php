<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Psikotes') }} — @yield('title', 'Dashboard')</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=outfit:300,400,500,600,700,800&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    </head>
    <body class="font-sans antialiased bg-[#F3F4F6]" style="font-family: 'Outfit', sans-serif;">
        <div class="min-h-screen flex">
            <!-- Sidebar -->
            <aside class="w-72 bg-[#0B0F19] text-gray-400 flex flex-col fixed h-full z-30 border-r border-[#1C2333] shadow-2xl">
                <!-- Logo -->
                <div class="px-6 py-6 border-b border-[#1C2333] bg-[#0B0F19]/50 backdrop-blur-xl">
                    <div class="flex items-center gap-3 group cursor-pointer">
                        <div class="w-12 h-12 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-xl flex items-center justify-center shadow-lg transform transition-all duration-300 group-hover:scale-105 group-hover:shadow-indigo-500/30">
                            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
                            </svg>
                        </div>
                        <div>
                            <h1 class="text-xl font-bold text-white tracking-wide">Psikotes<span class="text-indigo-400">App</span></h1>
                            <p class="text-xs text-gray-500 font-medium">Evaluation System</p>
                        </div>
                    </div>
                </div>

                <!-- Navigation -->
                <nav class="flex-1 px-4 py-6 space-y-2 overflow-y-auto custom-scrollbar">
                    <div class="px-3 pb-3 text-xs font-bold text-gray-500 uppercase tracking-wider">
                        Main Menu
                    </div>
                    @if(auth()->user()->isAdmin())
                        <a href="{{ route('admin.dashboard') }}" class="sidebar-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
                            <span class="truncate">Dashboard</span>
                        </a>
                        <a href="{{ route('admin.tests.index') }}" class="sidebar-link {{ request()->routeIs('admin.tests.*') ? 'active' : '' }}">
                            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                            <span class="truncate">Kelola Tes</span>
                        </a>
                        <a href="{{ route('admin.users.index') }}" class="sidebar-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z"/></svg>
                            <span class="truncate">Kelola Peserta</span>
                        </a>
                        <a href="{{ route('admin.results.index') }}" class="sidebar-link {{ request()->routeIs('admin.results.*') ? 'active' : '' }}">
                            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                            <span class="truncate">Hasil & Laporan</span>
                        </a>
                    @else
                        <a href="{{ route('peserta.dashboard') }}" class="sidebar-link {{ request()->routeIs('peserta.dashboard') ? 'active' : '' }}">
                            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
                            <span class="truncate">Dashboard</span>
                        </a>
                        <a href="{{ route('peserta.results.index') }}" class="sidebar-link {{ request()->routeIs('peserta.results.*') ? 'active' : '' }}">
                            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                            <span class="truncate">Hasil Saya</span>
                        </a>
                    @endif
                </nav>

                <!-- User Section -->
                <div class="p-4 border-t border-[#1C2333] bg-[#090C15]">
                    <div class="flex items-center gap-3 px-3 py-3 rounded-xl bg-white/5 border border-white/10 mb-3 hover:bg-white/10 transition-colors">
                        <div class="w-10 h-10 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-lg flex items-center justify-center text-white text-sm font-bold shadow-inner">
                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-semibold text-white truncate">{{ auth()->user()->name }}</p>
                            <p class="text-xs text-indigo-400 capitalize">{{ auth()->user()->role }}</p>
                        </div>
                    </div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full flex items-center justify-center gap-2 py-2.5 rounded-lg text-sm font-medium text-red-400 hover:text-white hover:bg-red-500/20 hover:border-red-500/30 border border-transparent transition-all duration-200">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                            Keluar
                        </button>
                    </form>
                </div>
            </aside>

            <!-- Main Content -->
            <div class="flex-1 ml-72">
                <!-- Top Bar -->
                <header class="bg-white/80 backdrop-blur-lg border-b border-gray-100 sticky top-0 z-20">
                    <div class="px-8 py-4 flex items-center justify-between">
                        <div>
                            <h2 class="text-xl font-bold text-gray-900">@yield('title', 'Dashboard')</h2>
                            @hasSection('subtitle')
                                <p class="text-sm text-gray-500 mt-0.5">@yield('subtitle')</p>
                            @endif
                        </div>
                        <div class="flex items-center gap-3">
                            @yield('actions')
                        </div>
                    </div>
                </header>

                <!-- Flash Messages -->
                @if(session('success'))
                    <div class="mx-8 mt-6">
                        <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 px-5 py-4 rounded-xl flex items-center gap-3" id="flash-success">
                            <svg class="w-5 h-5 text-emerald-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                            <span class="text-sm font-medium">{{ session('success') }}</span>
                        </div>
                    </div>
                    <script>setTimeout(() => document.getElementById('flash-success')?.remove(), 4000);</script>
                @endif

                @if(session('error'))
                    <div class="mx-8 mt-6">
                        <div class="bg-red-50 border border-red-200 text-red-800 px-5 py-4 rounded-xl flex items-center gap-3">
                            <svg class="w-5 h-5 text-red-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/></svg>
                            <span class="text-sm font-medium">{{ session('error') }}</span>
                        </div>
                    </div>
                @endif

                <!-- Page Content -->
                <main class="p-8">
                    @yield('content')
                </main>
            </div>
        </div>

        @yield('scripts')
    </body>
</html>
