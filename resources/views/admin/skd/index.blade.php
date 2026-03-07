@extends('layouts.main')
@section('title', 'Manajemen Paket SKD')
@section('subtitle', 'Kelola paket simulasi SKD CPNS (TWK, TIU, TKP)')

@section('page_header')
<div class="px-8 pt-8">
    <div class="max-w-7xl mx-auto flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h2 class="text-3xl font-black text-slate-900 dark:text-slate-50 tracking-tight">Manajemen Paket SKD</h2>
            <p class="text-slate-500 mt-1">Kelola paket simulasi SKD CPNS (TWK, TIU, TKP)</p>
        </div>
        <a href="{{ route('admin.skd-packages.create') }}" class="bg-primary hover:bg-primary/90 text-white px-6 py-2.5 rounded-lg flex items-center gap-2 font-bold text-sm transition-all shadow-lg shadow-primary/20">
            <span class="material-symbols-outlined text-lg">add_circle</span>
            Add New Package
        </a>
    </div>
</div>
@endsection

@section('content')
<div class="max-w-7xl mx-auto space-y-6 lg:space-y-8 mt-2 md:mt-0">

    <!-- Search / Top Actions -->
    <div class="md:hidden mb-4">
        <form method="GET" action="{{ route('admin.skd-packages.index') }}" class="relative w-full group">
            <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-lg group-focus-within:text-primary transition-colors">search</span>
            <input name="search" value="{{ request('search') }}" class="w-full pl-10 pr-4 py-2 rounded-lg bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 focus:border-primary focus:ring-0 text-sm transition-all shadow-sm" placeholder="Search packages..." type="text"/>
            @if(request('status'))
                <input type="hidden" name="status" value="{{ request('status') }}">
            @endif
        </form>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white dark:bg-slate-900 p-6 rounded-xl border border-slate-200 dark:border-slate-800 shadow-sm transition-transform hover:-translate-y-1 duration-300">
            <div class="flex justify-between items-start mb-4">
                <div class="p-2 bg-primary/10 rounded-lg text-primary">
                    <span class="material-symbols-outlined">inventory_2</span>
                </div>
            </div>
            <p class="text-slate-500 text-sm font-medium">Total Packages</p>
            <p class="text-2xl font-black text-slate-900 dark:text-white mt-1">{{ number_format($stats['total']) }}</p>
        </div>
        
        <div class="bg-white dark:bg-slate-900 p-6 rounded-xl border border-slate-200 dark:border-slate-800 shadow-sm transition-transform hover:-translate-y-1 duration-300">
            <div class="flex justify-between items-start mb-4">
                <div class="p-2 bg-green-100 dark:bg-green-900/30 rounded-lg text-green-600 dark:text-green-400">
                    <span class="material-symbols-outlined">task_alt</span>
                </div>
            </div>
            <p class="text-slate-500 text-sm font-medium">Active Packages</p>
            <p class="text-2xl font-black text-slate-900 dark:text-white mt-1">{{ number_format($stats['active']) }}</p>
        </div>
        
        <div class="bg-white dark:bg-slate-900 p-6 rounded-xl border border-slate-200 dark:border-slate-800 shadow-sm transition-transform hover:-translate-y-1 duration-300">
            <div class="flex justify-between items-start mb-4">
                <div class="p-2 bg-amber-100 dark:bg-amber-900/30 rounded-lg text-amber-600 dark:text-amber-500">
                    <span class="material-symbols-outlined">groups</span>
                </div>
            </div>
            <p class="text-slate-500 text-sm font-medium">Total Participants</p>
            <p class="text-2xl font-black text-slate-900 dark:text-white mt-1">{{ number_format($stats['participants']) }}</p>
        </div>
    </div>

    <!-- Filters -->
    <div class="flex overflow-x-auto border-b border-slate-200 dark:border-slate-800 whitespace-nowrap scrollbar-hide">
        <a href="{{ route('admin.skd-packages.index', ['search' => request('search')]) }}" class="px-6 py-3 border-b-2 font-bold text-sm transition-colors {{ request('status') === null ? 'border-primary text-primary' : 'border-transparent text-slate-500 hover:text-slate-700 dark:hover:text-slate-300' }}">Semua Paket</a>
        
        <a href="{{ route('admin.skd-packages.index', ['status' => 'active', 'search' => request('search')]) }}" class="px-6 py-3 border-b-2 font-medium text-sm transition-colors {{ request('status') === 'active' ? 'border-primary text-primary font-bold' : 'border-transparent text-slate-500 hover:text-slate-700 dark:hover:text-slate-300' }}">Aktif</a>
        
        <a href="{{ route('admin.skd-packages.index', ['status' => 'draft', 'search' => request('search')]) }}" class="px-6 py-3 border-b-2 font-medium text-sm transition-colors {{ request('status') === 'draft' ? 'border-primary text-primary font-bold' : 'border-transparent text-slate-500 hover:text-slate-700 dark:hover:text-slate-300' }}">Draft</a>
    </div>

    <!-- Table Container -->
    <div class="bg-white dark:bg-slate-900 rounded-xl border border-slate-200 dark:border-slate-800 shadow-sm overflow-hidden mt-4">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead class="bg-slate-50 dark:bg-slate-800/50 border-b border-slate-200 dark:border-slate-800">
                    <tr>
                        <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Nama Paket</th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Sub-Tes</th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Durasi</th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider text-center">Peserta</th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 dark:divide-slate-800">
                    @forelse($packages as $package)
                    <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/30 transition-colors">
                        <td class="px-6 py-5">
                            <div class="flex flex-col">
                                <span class="text-sm font-bold text-slate-900 dark:text-slate-50">{{ $package->title }}</span>
                                <span class="text-xs text-slate-400 mt-1">Created: {{ $package->created_at->format('d M Y') }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-5">
                            <div class="flex flex-wrap gap-1.5 min-w-[max-content]">
                                @forelse($package->packageTests as $pt)
                                    @if($pt->sub_test_type == 'twk')
                                        <span class="px-2 py-0.5 rounded-full bg-blue-100 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 text-[10px] font-bold uppercase ring-1 ring-inset ring-blue-500/10">{{ $pt->sub_test_type }}</span>
                                    @elseif($pt->sub_test_type == 'tiu')
                                        <span class="px-2 py-0.5 rounded-full bg-purple-100 dark:bg-purple-900/30 text-purple-600 dark:text-purple-400 text-[10px] font-bold uppercase ring-1 ring-inset ring-purple-500/10">{{ $pt->sub_test_type }}</span>
                                    @else
                                        <span class="px-2 py-0.5 rounded-full bg-emerald-100 dark:bg-emerald-900/30 text-emerald-600 dark:text-emerald-400 text-[10px] font-bold uppercase ring-1 ring-inset ring-emerald-500/10">{{ $pt->sub_test_type }}</span>
                                    @endif
                                @empty
                                    <span class="text-xs text-slate-400 italic">No Tests</span>
                                @endforelse
                            </div>
                        </td>
                        <td class="px-6 py-5">
                            <div class="flex items-center gap-1.5 text-sm text-slate-600 dark:text-slate-300">
                                <span class="material-symbols-outlined text-slate-400 text-base">schedule</span>
                                {{ $package->duration_minutes }} Menit
                            </div>
                        </td>
                        <td class="px-6 py-5">
                            @if($package->is_active)
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-green-100 dark:bg-green-950/30 text-green-700 dark:text-green-400 text-xs font-bold ring-1 ring-inset ring-green-500/10">
                                <span class="w-1.5 h-1.5 rounded-full bg-green-600"></span>
                                Aktif
                            </span>
                            @else
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 text-xs font-bold ring-1 ring-inset ring-slate-500/10">
                                <span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span>
                                Draft
                            </span>
                            @endif
                        </td>
                        <td class="px-6 py-5 text-sm font-medium text-slate-900 dark:text-white text-center">
                            {{ number_format($package->results_count) }}
                        </td>
                        <td class="px-6 py-5 text-right w-32">
                            <div class="flex justify-end gap-1">
                                <a href="{{ route('admin.skd-packages.show', $package) }}" class="p-2 text-slate-400 hover:text-primary hover:bg-primary/10 rounded-lg transition-colors" title="View Detail">
                                    <span class="material-symbols-outlined text-xl">visibility</span>
                                </a>
                                <a href="{{ route('admin.skd-packages.edit', $package) }}" class="p-2 text-slate-400 hover:text-amber-500 hover:bg-amber-50 dark:hover:bg-amber-500/10 rounded-lg transition-colors" title="Edit Package">
                                    <span class="material-symbols-outlined text-xl">edit</span>
                                </a>
                                <form action="{{ route('admin.skd-packages.destroy', $package) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus paket SKD ini secara permanen?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="p-2 text-slate-400 hover:text-red-500 hover:bg-red-50 dark:hover:bg-red-500/10 rounded-lg transition-colors" title="Delete">
                                        <span class="material-symbols-outlined text-xl">delete</span>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-slate-500 dark:text-slate-400">
                            <span class="material-symbols-outlined text-5xl mb-3 block text-slate-300 dark:text-slate-600">inventory_2</span>
                            <span class="text-sm font-medium block">Belum ada paket SKD yang dibuat.</span>
                            <a href="{{ route('admin.skd-packages.create') }}" class="mt-2 inline-block text-primary text-sm font-bold hover:underline transition-all">Mulai buat paket baru &rarr;</a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($packages->hasPages())
        <div class="px-6 py-4 bg-slate-50 dark:bg-slate-800/50 flex flex-col md:flex-row items-center justify-between gap-4 border-t border-slate-200 dark:border-slate-800">
            <p class="text-sm text-slate-500 dark:text-slate-400 text-center md:text-left">
                Menampilkan {{ $packages->firstItem() ?? 0 }}-{{ $packages->lastItem() ?? 0 }} dari {{ $packages->total() }} paket
            </p>
            <div class="flex gap-1">
                @if($packages->onFirstPage())
                    <button class="p-2 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-400 disabled:opacity-50" disabled>
                        <span class="material-symbols-outlined text-sm flex items-center justify-center">chevron_left</span>
                    </button>
                @else
                    <a href="{{ $packages->previousPageUrl() }}" class="p-2 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors flex items-center justify-center">
                        <span class="material-symbols-outlined text-sm">chevron_left</span>
                    </a>
                @endif
                
                @php
                    $start = max(1, $packages->currentPage() - 2);
                    $end = min(max(1, $packages->lastPage()), $packages->currentPage() + 2);
                @endphp
                
                @for($i = $start; $i <= $end; $i++)
                    @if ($i == $packages->currentPage())
                        <button class="px-3.5 py-1.5 rounded-lg bg-primary text-white text-sm font-bold flex items-center justify-center">{{ $i }}</button>
                    @else
                        <a href="{{ $packages->url($i) }}" class="px-3.5 py-1.5 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-600 dark:text-slate-400 text-sm hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors flex items-center justify-center">{{ $i }}</a>
                    @endif
                @endfor
                
                @if($packages->hasMorePages())
                    <a href="{{ $packages->nextPageUrl() }}" class="p-2 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 hover:text-slate-700 transition-colors flex items-center justify-center">
                        <span class="material-symbols-outlined text-sm flex">chevron_right</span>
                    </a>
                @else
                    <button class="p-2 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-400 disabled:opacity-50" disabled>
                        <span class="material-symbols-outlined text-sm flex items-center justify-center">chevron_right</span>
                    </button>
                @endif
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
