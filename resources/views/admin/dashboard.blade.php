@extends('layouts.main')
@section('title', 'System Overview')
@section('subtitle', 'Monitor your preparation metrics and participant performance across all platforms.')

@section('content')
<!-- Summary Cards -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <div class="bg-white dark:bg-slate-900 p-6 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-800">
        <div class="flex justify-between items-start mb-4">
            <div class="p-3 bg-blue-50 dark:bg-blue-900/20 text-blue-600 rounded-xl">
                <span class="material-symbols-outlined">psychology</span>
            </div>
        </div>
        <p class="text-slate-500 dark:text-slate-400 text-sm font-medium">Total Psychotests</p>
        <h3 class="text-2xl font-bold mt-1">{{ number_format($stats['total_tests'] ?? 0) }}</h3>
    </div>
    
    <div class="bg-white dark:bg-slate-900 p-6 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-800">
        <div class="flex justify-between items-start mb-4">
            <div class="p-3 bg-purple-50 dark:bg-purple-900/20 text-purple-600 rounded-xl">
                <span class="material-symbols-outlined">groups</span>
            </div>
        </div>
        <p class="text-slate-500 dark:text-slate-400 text-sm font-medium">Active Participants</p>
        <h3 class="text-2xl font-bold mt-1">{{ number_format($stats['total_peserta'] ?? 0) }}</h3>
    </div>
    
    <div class="bg-white dark:bg-slate-900 p-6 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-800">
        <div class="flex justify-between items-start mb-4">
            <div class="p-3 bg-orange-50 dark:bg-orange-900/20 text-orange-600 rounded-xl">
                <span class="material-symbols-outlined">inventory_2</span>
            </div>
        </div>
        <p class="text-slate-500 dark:text-slate-400 text-sm font-medium">Total SKD Packages</p>
        <h3 class="text-2xl font-bold mt-1">{{ number_format($stats['total_skd'] ?? 0) }}</h3>
    </div>
    
    <div class="bg-white dark:bg-slate-900 p-6 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-800">
        <div class="flex justify-between items-start mb-4">
            <div class="p-3 bg-emerald-50 dark:bg-emerald-900/20 text-emerald-600 rounded-xl">
                <span class="material-symbols-outlined">verified</span>
            </div>
        </div>
        @php $passRate = ($stats['skd_total_results'] ?? 0) > 0 ? round((($stats['skd_passed'] ?? 0) / $stats['skd_total_results']) * 100, 1) : 0; @endphp
        <p class="text-slate-500 dark:text-slate-400 text-sm font-medium">Overall Pass Rate (SKD)</p>
        <h3 class="text-2xl font-bold mt-1">{{ collect([100, $passRate])->min() }}%</h3>
    </div>
</div>

<!-- Charts Section -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
    <!-- Weekly Participation Grouped Bar Chart -->
    <div class="bg-white dark:bg-slate-900 p-6 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-800">
        <div class="flex items-center justify-between mb-8">
            <div>
                <h4 class="text-lg font-bold">Weekly Participation</h4>
                <p class="text-sm text-slate-500">Psychotest vs. SKD (Last 7 Days)</p>
            </div>
            <div class="flex gap-4">
                <div class="flex items-center gap-2">
                    <span class="w-3 h-3 bg-primary rounded-full"></span>
                    <span class="text-xs font-medium text-slate-500">Psychotest</span>
                </div>
                <div class="flex items-center gap-2">
                    <span class="w-3 h-3 bg-slate-300 dark:bg-slate-700 rounded-full"></span>
                    <span class="text-xs font-medium text-slate-500">SKD</span>
                </div>
            </div>
        </div>
        <div class="h-64 flex items-end justify-between gap-2 px-2">
            @php 
              $chartMax = max(max($activityChart['psikotes'] ?? [0]), max($activityChart['skd'] ?? [0]), 1); 
              $daysOfWeek = ['MON','TUE','WED','THU','FRI','SAT','SUN'];
            @endphp
            @foreach($activityChart['labels'] as $idx => $label)
            <!-- Column {{ $idx }} -->
            <div class="flex-1 flex flex-col items-center gap-2 h-full justify-end group">
                <div class="w-full flex justify-center gap-1 h-[70%] items-end">
                    <div class="w-4 bg-primary rounded-t-sm" style="height: {{ ($activityChart['psikotes'][$idx] / $chartMax) * 100 }}%"></div>
                    <div class="w-4 bg-slate-200 dark:bg-slate-800 rounded-t-sm" style="height: {{ ($activityChart['skd'][$idx] / $chartMax) * 100 }}%"></div>
                </div>
                <span class="text-[10px] font-bold text-slate-400">{{ strtoupper(substr($label, 0, 3) ?? $daysOfWeek[$idx]) }}</span>
            </div>
            @endforeach
        </div>
    </div>
    
    <!-- Success Trends Line Chart -->
    <div class="bg-white dark:bg-slate-900 p-6 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-800">
        <div class="flex items-center justify-between mb-8">
            <div>
                <h4 class="text-lg font-bold">Success Trends</h4>
                <p class="text-sm text-slate-500">Average passing rate over 6 months</p>
            </div>
            <button class="text-xs font-bold text-primary flex items-center gap-1 border border-primary/20 px-3 py-1.5 rounded-lg hover:bg-primary/5 transition-colors">
                Export Data <span class="material-symbols-outlined text-sm">download</span>
            </button>
        </div>
        <div class="h-64 relative">
            <svg class="w-full h-full overflow-visible" preserveAspectRatio="none" viewBox="0 0 100 40">
                <defs>
                    <linearGradient id="lineGradient" x1="0%" x2="0%" y1="0%" y2="100%">
                        <stop offset="0%" stop-color="rgba(19, 91, 236, 0.2)"></stop>
                        <stop offset="100%" stop-color="rgba(19, 91, 236, 0)"></stop>
                    </linearGradient>
                </defs>
                <path d="M0,35 Q15,25 30,28 T60,15 T100,5" fill="none" stroke="#135bec" stroke-linecap="round" stroke-width="1.5"></path>
                <path d="M0,35 Q15,25 30,28 T60,15 T100,5 V40 H0 Z" fill="url(#lineGradient)"></path>
                <!-- Dots -->
                <circle cx="0" cy="35" fill="#135bec" r="1.5"></circle>
                <circle cx="30" cy="28" fill="#135bec" r="1.5"></circle>
                <circle cx="60" cy="15" fill="#135bec" r="1.5"></circle>
                <circle cx="100" cy="5" fill="#135bec" r="1.5"></circle>
            </svg>
            <div class="absolute bottom-0 w-full flex justify-between px-0.5 mt-2">
                <span class="text-[10px] font-bold text-slate-400">JAN</span>
                <span class="text-[10px] font-bold text-slate-400">FEB</span>
                <span class="text-[10px] font-bold text-slate-400">MAR</span>
                <span class="text-[10px] font-bold text-slate-400">APR</span>
                <span class="text-[10px] font-bold text-slate-400">MAY</span>
                <span class="text-[10px] font-bold text-slate-400">JUN</span>
            </div>
        </div>
    </div>
</div>

<!-- Latest Results Table -->
<div class="bg-white dark:bg-slate-900 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-800 overflow-hidden">
    <div class="p-6 border-b border-slate-100 dark:border-slate-800 flex items-center justify-between">
        <div>
            <h4 class="text-lg font-bold">Latest SKD Results</h4>
            <p class="text-sm text-slate-500">Recently completed tests and their performance status.</p>
        </div>
        <a href="{{ route('admin.skd-results.index') }}" class="text-primary font-semibold text-sm hover:underline">View All Participants</a>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-left">
            <thead>
                <tr class="bg-slate-50/50 dark:bg-slate-800/50">
                    <th class="px-6 py-4 text-xs font-bold text-slate-400 uppercase tracking-wider">Participant</th>
                    <th class="px-6 py-4 text-xs font-bold text-slate-400 uppercase tracking-wider">Test Category</th>
                    <th class="px-6 py-4 text-xs font-bold text-slate-400 uppercase tracking-wider">Date</th>
                    <th class="px-6 py-4 text-xs font-bold text-slate-400 uppercase tracking-wider">Score</th>
                    <th class="px-6 py-4 text-xs font-bold text-slate-400 uppercase tracking-wider text-right">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                @forelse($recentSkdResults as $result)
                <tr class="hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors">
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-full bg-primary/20 text-primary font-bold flex items-center justify-center text-xs">
                                {{ strtoupper(substr($result->user->name, 0, 1)) }}
                            </div>
                            <span class="text-sm font-semibold">{{ $result->user->name }}</span>
                        </div>
                    </td>
                    <td class="px-6 py-4 text-sm text-slate-600 dark:text-slate-400">{{ $result->package->title ?? 'SKD' }}</td>
                    <td class="px-6 py-4 text-sm text-slate-600 dark:text-slate-400">{{ $result->created_at->format('M d, Y') }}</td>
                    <td class="px-6 py-4 text-sm font-bold">{{ $result->total_score }} / 550</td>
                    <td class="px-6 py-4 text-right">
                        @if($result->is_passed)
                            <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-700 dark:bg-emerald-900/40 dark:text-emerald-400">PASSED</span>
                        @else
                            <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-rose-100 text-rose-700 dark:bg-rose-900/40 dark:text-rose-400">FAILED</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-8 text-center text-slate-500">No results found yet.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
