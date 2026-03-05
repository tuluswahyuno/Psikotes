<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-white">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=outfit:400,500,600,700&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-gray-900 antialiased h-screen overflow-hidden" style="font-family: 'Outfit', sans-serif;">
        <div class="h-full flex">
            <!-- Left Side: Form -->
            <div class="flex-1 flex flex-col justify-center py-12 px-4 sm:px-6 lg:flex-none lg:px-20 xl:px-24 w-full lg:w-1/2 overflow-y-auto">
                <div class="mx-auto w-full max-w-sm lg:w-96">
                    <div>
                        <a href="/" class="flex items-center gap-3 group">
                            <div class="w-12 h-12 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-xl flex items-center justify-center shadow-lg shadow-indigo-200 transform transition-transform group-hover:scale-105">
                                <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
                                </svg>
                            </div>
                            <span class="text-2xl font-bold bg-clip-text text-transparent bg-gradient-to-r from-indigo-600 to-purple-600">Psikotes App</span>
                        </a>
                    </div>

                    <div class="mt-10">
                        {{ $slot }}
                    </div>
                </div>
            </div>

            <!-- Right Side: Image/Gradient -->
            <div class="hidden lg:block relative w-0 flex-1 h-full">
                <div class="absolute inset-0 h-full w-full bg-[#0B0F19] overflow-hidden">
                    <!-- Glassmorphism decorative elements -->
                    <div class="absolute -top-[20%] -right-[10%] w-[70%] h-[70%] rounded-full bg-indigo-600 blur-[120px] opacity-40"></div>
                    <div class="absolute top-[40%] -left-[10%] w-[50%] h-[50%] rounded-full bg-purple-600 blur-[120px] opacity-40"></div>
                    <div class="absolute -bottom-[10%] -right-[10%] w-[60%] h-[60%] rounded-full bg-blue-600 blur-[120px] opacity-30"></div>
                    
                    <div class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] opacity-10"></div>
                    
                    <div class="absolute inset-0 flex flex-col justify-center px-16 lg:px-24 z-10">
                        <div class="max-w-xl">
                            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/10 border border-white/20 backdrop-blur-md mb-6">
                                <span class="flex h-2 w-2 rounded-full bg-green-400"></span>
                                <span class="text-sm font-medium text-white/90">Platform Psikotes Modern</span>
                            </div>
                            <h1 class="text-4xl lg:text-5xl font-bold text-white leading-tight mb-6">
                                Evaluasi Kinerja dengan Akurat & Terukur.
                            </h1>
                            <p class="text-lg text-white/70 leading-relaxed max-w-lg mb-10">
                                Sistem ujian digital yang dirancang untuk mempermudah penilaian dan seleksi peserta secara otomatis, cepat, dan transparan.
                            </p>
                            
                            <!-- Floating Card Animation -->
                            <div class="relative bg-white/10 backdrop-blur-xl border border-white/20 p-6 rounded-2xl shadow-2xl overflow-hidden transform transition-all hover:-translate-y-2 hover:shadow-indigo-500/20 duration-500 max-w-md">
                                <div class="absolute top-0 right-0 w-32 h-32 bg-indigo-500/30 rounded-full blur-3xl"></div>
                                <div class="flex items-center gap-4 mb-4 relative z-10">
                                    <div class="flex-shrink-0 w-12 h-12 bg-white/20 rounded-full flex items-center justify-center">
                                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
                                    </div>
                                    <div>
                                        <div class="text-white font-semibold">Analitik Kompetensi</div>
                                        <div class="text-white/60 text-sm">Real-time scoring system</div>
                                    </div>
                                </div>
                                <div class="w-full bg-white/10 rounded-full h-2 relative z-10">
                                    <div class="bg-gradient-to-r from-indigo-400 to-purple-400 h-2 rounded-full" style="width: 85%"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>
