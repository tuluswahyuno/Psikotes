@extends('layouts.main')
@section('title', 'Riwayat Hasil SKD')
@section('subtitle', 'Daftar skor simulasi SKD seluruh peserta periode berjalan')

@section('page_header')
<header class="bg-white/80 dark:bg-slate-900/80 backdrop-blur-md border-b border-slate-200 dark:border-slate-800 px-8 py-4 sticky top-0 z-10 flex flex-col md:flex-row md:items-center justify-between gap-4">
    <h2 class="text-xl font-bold text-slate-900 dark:text-white tracking-tight hidden md:block">Riwayat Hasil SKD</h2>
    
    <form method="GET" action="{{ route('admin.skd-results.index') }}" class="w-full md:max-w-md">
        <div class="relative group">
            <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-primary transition-colors">search</span>
            <input name="search" value="{{ request('search') }}" class="w-full pl-10 pr-4 py-2 bg-slate-100 dark:bg-slate-800 border-transparent focus:border-primary focus:ring-0 rounded-lg text-sm transition-all shadow-sm" placeholder="Cari nama peserta atau paket..." type="text"/>
            <button type="submit" class="hidden"></button>
        </div>
    </form>
</header>
@endsection

@section('content')
<div class="max-w-7xl mx-auto space-y-8 mt-2 md:mt-0">
    <!-- Title Section -->
    <div class="flex flex-col md:flex-row items-start md:items-end justify-between gap-4">
        <div class="md:hidden">
            <h1 class="text-3xl font-extrabold text-slate-900 dark:text-white tracking-tight">Riwayat Hasil SKD</h1>
            <p class="text-slate-500 dark:text-slate-400 mt-1">Daftar skor simulasi SKD seluruh peserta periode berjalan</p>
        </div>
        <div>
            <!-- Added wrapper to maintain flex-between on larger screens while hiding text on mobile -->
            <div class="hidden md:block">
                <h1 class="text-3xl font-extrabold text-slate-900 dark:text-white tracking-tight">Riwayat Hasil SKD</h1>
                <p class="text-slate-500 dark:text-slate-400 mt-1">Daftar skor simulasi SKD seluruh peserta periode berjalan</p>
            </div>
        </div>
        <button class="flex items-center gap-2 bg-primary hover:bg-primary/90 text-white px-4 py-2.5 rounded-lg text-sm font-semibold transition-all shadow-lg shadow-primary/20 whitespace-nowrap">
            <span class="material-symbols-outlined text-sm">download</span>
            Export Data
        </button>
    </div>

    <!-- Statistics Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-white dark:bg-slate-900 p-6 rounded-xl border border-slate-200 dark:border-slate-800 shadow-sm transition-transform hover:-translate-y-1 duration-300">
            <div class="flex justify-between items-start mb-4">
                <div class="p-2 bg-blue-50 dark:bg-blue-900/30 text-blue-600 rounded-lg">
                    <span class="material-symbols-outlined">assignment</span>
                </div>
            </div>
            <p class="text-slate-500 text-sm font-medium">Ujian Hari Ini</p>
            <h3 class="text-2xl font-bold mt-1 text-slate-900 dark:text-white">{{ number_format($stats['today']) }}</h3>
        </div>
        <div class="bg-white dark:bg-slate-900 p-6 rounded-xl border border-slate-200 dark:border-slate-800 shadow-sm transition-transform hover:-translate-y-1 duration-300">
            <div class="flex justify-between items-start mb-4">
                <div class="p-2 bg-emerald-50 dark:bg-emerald-900/30 text-emerald-600 rounded-lg">
                    <span class="material-symbols-outlined">verified</span>
                </div>
            </div>
            <p class="text-slate-500 text-sm font-medium">Rata-rata Kelulusan</p>
            <h3 class="text-2xl font-bold mt-1 text-slate-900 dark:text-white">{{ $stats['pass_rate'] }}%</h3>
        </div>
        <div class="bg-white dark:bg-slate-900 p-6 rounded-xl border border-slate-200 dark:border-slate-800 shadow-sm transition-transform hover:-translate-y-1 duration-300">
            <div class="flex justify-between items-start mb-4">
                <div class="p-2 bg-amber-50 dark:bg-amber-900/30 text-amber-600 rounded-lg">
                    <span class="material-symbols-outlined">group</span>
                </div>
            </div>
            <p class="text-slate-500 text-sm font-medium">Total Peserta Aktif</p>
            <h3 class="text-2xl font-bold mt-1 text-slate-900 dark:text-white">{{ number_format($stats['active_participants']) }}</h3>
        </div>
        <div class="bg-white dark:bg-slate-900 p-6 rounded-xl border border-slate-200 dark:border-slate-800 shadow-sm transition-transform hover:-translate-y-1 duration-300">
            <div class="flex justify-between items-start mb-4">
                <div class="p-2 bg-primary/10 text-primary rounded-lg">
                    <span class="material-symbols-outlined">trending_up</span>
                </div>
                <!-- <span class="text-[10px] font-bold text-primary bg-primary/10 px-2 py-1 rounded">High</span> -->
            </div>
            <p class="text-slate-500 text-sm font-medium">Skor Tertinggi</p>
            <h3 class="text-2xl font-bold mt-1 text-slate-900 dark:text-white">{{ $stats['highest_score'] }}</h3>
        </div>
    </div>

    <!-- Table Container -->
    <div class="bg-white dark:bg-slate-900 rounded-xl border border-slate-200 dark:border-slate-800 overflow-hidden shadow-sm">
        <form method="GET" action="{{ route('admin.skd-results.index') }}" class="p-4 border-b border-slate-200 dark:border-slate-800 flex flex-col sm:flex-row justify-between items-center gap-4 bg-slate-50/50 dark:bg-slate-800/20">
            @if(request('search'))
                <input type="hidden" name="search" value="{{ request('search') }}">
            @endif
            <div class="flex flex-wrap gap-3 w-full sm:w-auto">
                <!-- Package Filter (AlpineJS) -->
                <div x-data="{ open: false }" class="relative w-full sm:w-auto min-w-[150px]">
                    <button type="button" @click="open = !open" @click.outside="open = false" class="w-full flex items-center justify-between gap-2 px-4 py-2 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-700 dark:text-slate-300 rounded-lg text-xs font-semibold hover:bg-slate-50 dark:hover:bg-slate-700 transition-colors shadow-sm focus:outline-none focus:ring-2 focus:ring-primary/20">
                        <span class="truncate pr-2">
                            @php
                                $activePkg = $packages->firstWhere('id', request('package_id'));
                            @endphp
                            {{ $activePkg ? $activePkg->title : 'Semua Paket' }}
                        </span>
                        <span class="material-symbols-outlined text-base leading-none transition-transform duration-200" :class="open ? 'rotate-180' : ''">expand_more</span>
                    </button>
                    <div x-show="open" x-transition style="display: none;" class="absolute z-50 mt-1 w-56 bg-white dark:bg-slate-800 rounded-xl shadow-lg border border-slate-200 dark:border-slate-700 py-1 right-0 sm:left-0 sm:right-auto overflow-hidden">
                        <button type="button" @click="$refs.pkgInput.value=''; $el.closest('form').submit()" class="w-full text-left px-4 py-2.5 text-xs transition-colors {{ !request('package_id') ? 'bg-primary/5 text-primary dark:bg-primary/10 dark:text-primary font-bold' : 'text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-700' }}">Semua Paket</button>
                        @foreach($packages as $pkg)
                            <button type="button" @click="$refs.pkgInput.value='{{ $pkg->id }}'; $el.closest('form').submit()" class="w-full text-left px-4 py-2.5 text-xs transition-colors truncate {{ request('package_id') == $pkg->id ? 'bg-primary/5 text-primary dark:bg-primary/10 dark:text-primary font-bold' : 'text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-700' }}">{{ $pkg->title }}</button>
                        @endforeach
                    </div>
                    <input type="hidden" name="package_id" x-ref="pkgInput" value="{{ request('package_id') }}">
                </div>

                <!-- Status Filter (AlpineJS) -->
                <div x-data="{ open: false }" class="relative w-full sm:w-auto min-w-[130px]">
                    <button type="button" @click="open = !open" @click.outside="open = false" class="w-full flex items-center justify-between gap-2 px-4 py-2 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-700 dark:text-slate-300 rounded-lg text-xs font-semibold hover:bg-slate-50 dark:hover:bg-slate-700 transition-colors shadow-sm focus:outline-none focus:ring-2 focus:ring-primary/20">
                        <span>
                            {{ request('status') === 'lulus' ? 'LULUS' : (request('status') === 'tidak_lulus' ? 'TIDAK LULUS' : 'Semua Status') }}
                        </span>
                        <span class="material-symbols-outlined text-base leading-none transition-transform duration-200" :class="open ? 'rotate-180' : ''">expand_more</span>
                    </button>
                    <div x-show="open" x-transition style="display: none;" class="absolute z-50 mt-1 w-40 bg-white dark:bg-slate-800 rounded-xl shadow-lg border border-slate-200 dark:border-slate-700 py-1 right-0 sm:left-auto overflow-hidden">
                        <button type="button" @click="$refs.statusInput.value=''; $el.closest('form').submit()" class="w-full text-left px-4 py-2.5 text-xs transition-colors {{ request('status') === null ? 'bg-primary/5 text-primary dark:bg-primary/10 dark:text-primary font-bold' : 'text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-700' }}">Semua Status</button>
                        <button type="button" @click="$refs.statusInput.value='lulus'; $el.closest('form').submit()" class="w-full text-left px-4 py-2.5 text-xs transition-colors {{ request('status') === 'lulus' ? 'bg-emerald-50 dark:bg-emerald-900/30 text-emerald-600 dark:text-emerald-400 font-bold' : 'text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-700' }}">LULUS</button>
                        <button type="button" @click="$refs.statusInput.value='tidak_lulus'; $el.closest('form').submit()" class="w-full text-left px-4 py-2.5 text-xs transition-colors {{ request('status') === 'tidak_lulus' ? 'bg-rose-50 dark:bg-rose-900/30 text-rose-600 dark:text-rose-400 font-bold' : 'text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-700' }}">TIDAK LULUS</button>
                    </div>
                    <input type="hidden" name="status" x-ref="statusInput" value="{{ request('status') }}">
                </div>
            </div>
            <p class="text-xs text-slate-500 whitespace-nowrap">Menampilkan {{ $results->firstItem() ?? 0 }}-{{ $results->lastItem() ?? 0 }} dari {{ $results->total() }} data</p>
        </form>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse min-w-[800px]">
                <thead>
                    <tr class="bg-slate-50 dark:bg-slate-800/50 text-slate-500 uppercase text-[11px] font-bold tracking-wider">
                        <th class="px-6 py-4">Peserta</th>
                        <th class="px-6 py-4">Paket SKD</th>
                        <th class="px-6 py-4 text-center">TWK</th>
                        <th class="px-6 py-4 text-center">TIU</th>
                        <th class="px-6 py-4 text-center">TKP</th>
                        <th class="px-6 py-4 text-center">Total</th>
                        <th class="px-6 py-4 text-center">Status</th>
                        <th class="px-6 py-4">Waktu Ujian</th>
                        <th class="px-6 py-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                    @forelse($results as $res)
                    <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/30 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center gap-3">
                                <div class="size-9 rounded-full bg-blue-100 dark:bg-blue-900/50 flex items-center justify-center text-blue-600 font-bold text-xs ring-1 ring-blue-500/20 shadow-inner">
                                    {{ strtoupper(substr($res->user->name, 0, 2)) }}
                                </div>
                                <div>
                                    <p class="text-sm font-semibold text-slate-900 dark:text-slate-50">{{ $res->user->name }}</p>
                                    <p class="text-xs text-slate-500">{{ $res->user->email }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-sm font-medium text-slate-600 dark:text-slate-400">
                            {{ $res->package->title ?? 'N/A' }}
                        </td>
                        <td class="px-6 py-4 text-center">
                            @if(!$res->twk_passed)
                                <span class="text-sm font-bold text-rose-600 bg-rose-50 dark:bg-rose-900/30 px-2 py-0.5 rounded">{{ $res->twk_score }}</span>
                            @else
                                <span class="text-sm font-bold text-slate-700 dark:text-slate-200">{{ $res->twk_score }}</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-center">
                            @if(!$res->tiu_passed)
                                <span class="text-sm font-bold text-rose-600 bg-rose-50 dark:bg-rose-900/30 px-2 py-0.5 rounded">{{ $res->tiu_score }}</span>
                            @else
                                <span class="text-sm font-bold text-slate-700 dark:text-slate-200">{{ $res->tiu_score }}</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-center">
                            @if(!$res->tkp_passed)
                                <span class="text-sm font-bold text-rose-600 bg-rose-50 dark:bg-rose-900/30 px-2 py-0.5 rounded">{{ $res->tkp_score }}</span>
                            @else
                                <span class="text-sm font-bold text-slate-700 dark:text-slate-200">{{ $res->tkp_score }}</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-center">
                            @if($res->is_passed)
                                <span class="text-sm font-extrabold text-primary">{{ $res->total_score }}</span>
                            @else
                                <span class="text-sm font-extrabold text-slate-700 dark:text-slate-200">{{ $res->total_score }}</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-center whitespace-nowrap">
                            @if($res->is_passed)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-400 ring-1 ring-inset ring-emerald-500/20 uppercase tracking-widest">
                                LULUS
                            </span>
                            @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-rose-100 dark:bg-rose-900/30 text-rose-700 dark:text-rose-400 ring-1 ring-inset ring-rose-500/20 uppercase tracking-widest">
                                TIDAK LULUS
                            </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <p class="text-xs font-medium text-slate-700 dark:text-slate-300">{{ $res->created_at->format('d M Y') }}</p>
                            <p class="text-[10px] text-slate-500">{{ $res->created_at->format('H:i') }} WIB</p>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-1">
                                <a href="{{ route('admin.skd-results.show', $res) }}" class="p-1.5 text-slate-400 hover:text-primary transition-colors flex items-center justify-center rounded-lg hover:bg-slate-100 dark:hover:bg-slate-800" title="Lihat Detail">
                                    <span class="material-symbols-outlined text-lg">visibility</span>
                                </a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="px-6 py-12 text-center text-slate-500 dark:text-slate-400">
                            <span class="material-symbols-outlined text-4xl mb-3 block text-slate-300 dark:text-slate-600">receipt_long</span>
                            <span class="text-sm font-medium">Belum ada riwayat hasil simulasi.</span>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($results->hasPages())
        <div class="px-6 py-4 border-t border-slate-200 dark:border-slate-800 flex flex-col sm:flex-row items-center justify-between gap-4 bg-slate-50/30 dark:bg-slate-800/10">
            <div class="flex items-center gap-2">
                @if($results->onFirstPage())
                    <button class="p-1.5 rounded border border-slate-300 dark:border-slate-700 text-slate-400 disabled:opacity-50" disabled>
                        <span class="material-symbols-outlined text-sm block">chevron_left</span>
                    </button>
                @else
                    <a href="{{ $results->previousPageUrl() }}" class="p-1.5 rounded border border-slate-300 dark:border-slate-700 hover:bg-slate-100 dark:hover:bg-slate-800 text-slate-600 dark:text-slate-400">
                        <span class="material-symbols-outlined text-sm block">chevron_left</span>
                    </a>
                @endif
                
                <div class="flex gap-1">
                    @php
                        $start = max(1, $results->currentPage() - 2);
                        $end = min(max(1, $results->lastPage()), $results->currentPage() + 2);
                    @endphp
                    
                    @if($start > 1)
                        <a href="{{ $results->url(1) }}" class="size-8 rounded flex items-center justify-center text-xs font-medium text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800">1</a>
                        @if($start > 2)
                            <span class="size-8 flex items-center justify-center text-slate-400 text-xs">...</span>
                        @endif
                    @endif
                    
                    @for($i = $start; $i <= $end; $i++)
                        @if ($i == $results->currentPage())
                            <button class="size-8 rounded flex items-center justify-center text-xs font-bold bg-primary text-white shadow-sm">{{ $i }}</button>
                        @else
                            <a href="{{ $results->url($i) }}" class="size-8 rounded flex items-center justify-center text-xs font-medium text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800">{{ $i }}</a>
                        @endif
                    @endfor
                    
                    @if($end < $results->lastPage())
                        @if($end < $results->lastPage() - 1)
                            <span class="size-8 flex items-center justify-center text-slate-400 text-xs">...</span>
                        @endif
                        <a href="{{ $results->url($results->lastPage()) }}" class="size-8 rounded flex items-center justify-center text-xs font-medium text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800">{{ $results->lastPage() }}</a>
                    @endif
                </div>

                @if($results->hasMorePages())
                    <a href="{{ $results->nextPageUrl() }}" class="p-1.5 rounded border border-slate-300 dark:border-slate-700 hover:bg-slate-100 dark:hover:bg-slate-800 text-slate-600 dark:text-slate-400">
                        <span class="material-symbols-outlined text-sm block">chevron_right</span>
                    </a>
                @else
                    <button class="p-1.5 rounded border border-slate-300 dark:border-slate-700 text-slate-400 disabled:opacity-50" disabled>
                        <span class="material-symbols-outlined text-sm block">chevron_right</span>
                    </button>
                @endif
            </div>

            <div class="flex items-center gap-2 text-xs text-slate-500">
                <span>Halaman</span>
                <input class="size-8 px-1 text-center border border-slate-200 dark:border-slate-700 rounded bg-white dark:bg-slate-900 text-xs text-slate-700 dark:text-slate-300" type="text" value="{{ $results->currentPage() }}" disabled/>
                <span>dari {{ $results->lastPage() }}</span>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
