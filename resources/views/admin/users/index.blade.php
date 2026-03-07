@extends('layouts.main')
@section('title', 'Manage Participant')
@section('subtitle', 'Daftar peserta dan statistik hasil')

@section('page_header')
<header class="bg-white dark:bg-slate-900 border-b border-slate-200 dark:border-slate-800 px-8 py-4 sticky top-0 z-10">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 max-w-7xl mx-auto">
        <div class="flex flex-1 items-center gap-6">
            <h2 class="text-xl font-bold text-slate-900 dark:text-white tracking-tight whitespace-nowrap">Manage Participant</h2>
            <form method="GET" action="{{ route('admin.users.index') }}" class="hidden md:block w-full max-w-sm">
                <div class="relative group">
                    <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-primary transition-colors">search</span>
                    <input name="search" value="{{ request('search') }}" class="w-full pl-10 pr-4 py-2 bg-slate-100 dark:bg-slate-800 border-transparent focus:border-primary focus:ring-0 rounded-lg text-sm transition-all" placeholder="Search participants..." type="text"/>
                    <button type="submit" class="hidden"></button>
                </div>
            </form>
        </div>
        <div class="flex items-center gap-4">
            <a href="{{ route('admin.users.create') }}" class="bg-primary hover:bg-primary/90 text-white px-4 py-2 rounded-lg text-sm font-semibold flex items-center justify-center gap-2 shadow-sm transition-all">
                <span class="material-symbols-outlined text-sm">person_add</span>
                <span>Add New Participant</span>
            </a>
        </div>
    </div>
</header>
@endsection

@section('content')
<div class="max-w-7xl mx-auto space-y-6">
    <!-- Summary Stats -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white dark:bg-slate-900 p-6 rounded-xl border border-slate-200 dark:border-slate-800 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 rounded-lg bg-primary/10 text-primary flex items-center justify-center">
                <span class="material-symbols-outlined text-2xl">group</span>
            </div>
            <div>
                <p class="text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Total Participants</p>
                <div class="flex items-baseline gap-2">
                    <span class="text-2xl font-bold text-slate-900 dark:text-white">{{ number_format($stats['total']) }}</span>
                </div>
            </div>
        </div>
        <div class="bg-white dark:bg-slate-900 p-6 rounded-xl border border-slate-200 dark:border-slate-800 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 rounded-lg bg-amber-500/10 text-amber-500 flex items-center justify-center">
                <span class="material-symbols-outlined text-2xl">bolt</span>
            </div>
            <div>
                <p class="text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Active Today</p>
                <div class="flex items-baseline gap-2">
                    <span class="text-2xl font-bold text-slate-900 dark:text-white">{{ number_format($stats['active_today']) }}</span>
                </div>
            </div>
        </div>
        <div class="bg-white dark:bg-slate-900 p-6 rounded-xl border border-slate-200 dark:border-slate-800 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 rounded-lg bg-emerald-500/10 text-emerald-500 flex items-center justify-center">
                <span class="material-symbols-outlined text-2xl">person_add_alt</span>
            </div>
            <div>
                <p class="text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">New Registrations</p>
                <div class="flex items-baseline gap-2">
                    <span class="text-2xl font-bold text-slate-900 dark:text-white">{{ number_format($stats['new_registrations']) }}</span>
                    <span class="text-xs font-semibold text-emerald-500 flex items-center">
                        <span class="material-symbols-outlined text-xs">calendar_today</span> 7 Hari Terakhir
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Mobile Search (only on small screens, since hidden on md up) -->
    <form method="GET" action="{{ route('admin.users.index') }}" class="block md:hidden">
        <div class="relative group">
            <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-primary transition-colors">search</span>
            <input name="search" value="{{ request('search') }}" class="w-full pl-10 pr-4 py-2 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 focus:border-primary focus:ring-0 rounded-lg text-sm transition-all" placeholder="Search participants..." type="text"/>
            <button type="submit" class="hidden"></button>
        </div>
    </form>

    <!-- Data Table -->
    <div class="bg-white dark:bg-slate-900 rounded-xl border border-slate-200 dark:border-slate-800 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 dark:bg-slate-800/50 border-b border-slate-200 dark:border-slate-800">
                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Participant Name</th>
                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Email / ID</th>
                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Group</th>
                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 text-center">Tests</th>
                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Avg. Score</th>
                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 text-center">Status</th>
                        <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                    @forelse($users as $user)
                    <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/30 transition-colors">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full overflow-hidden bg-primary/10 flex items-center justify-center text-primary font-bold shadow-inner">
                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                </div>
                                <div class="font-semibold text-slate-900 dark:text-white">{{ $user->name }}</div>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-sm text-slate-600 dark:text-slate-400">
                            {{ $user->email }}<br/>
                            <span class="text-xs opacity-60">ID: {{ str_pad($user->id, 6, "0", STR_PAD_LEFT) }}</span>
                        </td>
                        <td class="px-6 py-4">
                            <!-- Group is placeholder as no DB column strictly aligns with it -->
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300">
                                Umum
                            </span>
                        </td>
                        <td class="px-6 py-4 font-medium text-center text-slate-900 dark:text-white">
                            {{ $user->test_assignments_count }}
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-2">
                                @if($user->results_avg_score_percentage !== null)
                                    @php $avg = number_format($user->results_avg_score_percentage, 1); @endphp
                                    <span class="font-bold text-slate-900 dark:text-white">{{ $avg }}</span>
                                    <div class="w-16 h-1.5 bg-slate-100 dark:bg-slate-800 rounded-full overflow-hidden">
                                        <div class="h-full {{ $avg >= 85 ? 'bg-emerald-500' : ($avg >= 60 ? 'bg-amber-500' : 'bg-red-500') }} rounded-full" style="width: {{ min(100, max(0, $avg)) }}%;"></div>
                                    </div>
                                @else
                                    <span class="font-bold text-slate-400">N/A</span>
                                @endif
                            </div>
                        </td>
                        <td class="px-6 py-4 text-center">
                            @if($user->test_assignments_count > 0 && $user->results_avg_score_percentage !== null)
                                <div class="inline-flex items-center justify-center gap-1.5 text-emerald-500 font-semibold text-sm">
                                    <span class="w-2 h-2 rounded-full bg-emerald-500"></span> Active
                                </div>
                            @elseif($user->test_assignments_count > 0)
                                <div class="inline-flex items-center justify-center gap-1.5 text-amber-500 font-semibold text-sm">
                                    <span class="w-2 h-2 rounded-full bg-amber-500"></span> Pending
                                </div>
                            @else
                                <div class="inline-flex items-center justify-center gap-1.5 text-slate-400 font-semibold text-sm">
                                    <span class="w-2 h-2 rounded-full bg-slate-400"></span> Inactive
                                </div>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('admin.users.assign', $user) }}" class="p-2 hover:bg-slate-100 dark:hover:bg-slate-800 rounded-lg text-slate-500 transition-colors" title="View & Assign Tests">
                                    <span class="material-symbols-outlined text-xl">assignment_add</span>
                                </a>
                                <a href="{{ route('admin.users.edit', $user) }}" class="p-2 hover:bg-slate-100 dark:hover:bg-slate-800 rounded-lg text-primary transition-colors" title="Edit">
                                    <span class="material-symbols-outlined text-xl">edit</span>
                                </a>
                                <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="inline-block" onsubmit="return confirm('Hapus peserta ini secara permanen?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="p-2 hover:bg-red-50 dark:hover:bg-red-900/10 rounded-lg text-red-500 transition-colors" title="Delete">
                                        <span class="material-symbols-outlined text-xl">delete</span>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center text-slate-500 dark:text-slate-400">
                            <span class="material-symbols-outlined text-5xl mb-3 block text-slate-300">group_off</span>
                            <span class="text-sm">Belum ada peserta yang mendaftar atau ditemukan.</span>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($users->hasPages())
        <div class="px-6 py-4 border-t border-slate-100 dark:border-slate-800 flex items-center justify-between">
            <span class="text-sm text-slate-500 dark:text-slate-400">
                Showing {{ $users->firstItem() ?? 0 }} to {{ $users->lastItem() ?? 0 }} of {{ $users->total() }} results
            </span>
            <div class="flex items-center gap-2">
                @if($users->onFirstPage())
                    <button disabled class="p-2 border border-slate-200 dark:border-slate-800 rounded-lg text-slate-400 disabled:opacity-50">
                        <span class="material-symbols-outlined text-xl">chevron_left</span>
                    </button>
                @else
                    <a href="{{ $users->previousPageUrl() }}" class="p-2 border border-slate-200 dark:border-slate-800 rounded-lg text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800 transition-all">
                        <span class="material-symbols-outlined text-xl">chevron_left</span>
                    </a>
                @endif
                
                @php
                    $start = max(1, $users->currentPage() - 2);
                    $end = min(max(1, $users->lastPage()), $users->currentPage() + 2);
                @endphp
                
                @for($i = $start; $i <= $end; $i++)
                    @if ($i == $users->currentPage())
                        <button class="w-10 h-10 flex items-center justify-center rounded-lg bg-primary text-white font-bold">{{ $i }}</button>
                    @else
                        <a href="{{ $users->url($i) }}" class="w-10 h-10 flex items-center justify-center rounded-lg text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors">{{ $i }}</a>
                    @endif
                @endfor
                
                @if($users->hasMorePages())
                    <a href="{{ $users->nextPageUrl() }}" class="p-2 border border-slate-200 dark:border-slate-800 rounded-lg text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800 transition-all">
                        <span class="material-symbols-outlined text-xl">chevron_right</span>
                    </a>
                @else
                    <button disabled class="p-2 border border-slate-200 dark:border-slate-800 rounded-lg text-slate-400 disabled:opacity-50">
                        <span class="material-symbols-outlined text-xl">chevron_right</span>
                    </button>
                @endif
            </div>
        </div>
        @endif
    </div>

    <!-- Footer Summary Tracker -->
    @if($users->total() > 0)
    <div class="pt-4 border-t border-slate-200 dark:border-slate-800 flex flex-wrap gap-8">
        <div class="flex items-center gap-2">
            <div class="size-3 rounded-full bg-emerald-500"></div>
            <span class="text-xs font-semibold uppercase text-slate-500 tracking-wider">High Performers (85+)</span>
            <span class="text-sm font-bold ml-1 text-slate-900 dark:text-white">{{ number_format($stats['high_performers']) }}</span>
        </div>
        <div class="flex items-center gap-2">
            <div class="size-3 rounded-full bg-amber-500"></div>
            <span class="text-xs font-semibold uppercase text-slate-500 tracking-wider">Mid Performers (60-84)</span>
            <span class="text-sm font-bold ml-1 text-slate-900 dark:text-white">{{ number_format($stats['mid_performers']) }}</span>
        </div>
        <div class="flex items-center gap-2">
            <div class="size-3 rounded-full bg-red-500"></div>
            <span class="text-xs font-semibold uppercase text-slate-500 tracking-wider">Low Performers (<60)</span>
            <span class="text-sm font-bold ml-1 text-slate-900 dark:text-white">{{ number_format($stats['low_performers']) }}</span>
        </div>
    </div>
    @endif
</div>
@endsection
