@extends('layouts.main')
@section('title', 'Leaderboard')

@section('content')
@php
    $initials = collect(explode(' ', auth()->user()->name))->map(fn($s) => strtoupper(substr($s, 0, 1)))->take(2)->join('');
    $totalParticipants = $leaderboard->count();
    $myPercentile = $myRank ? round((1 - ($myRank->rank / max($totalParticipants, 1))) * 100, 1) : 0;

    $avgScore = $totalParticipants > 0 ? round($leaderboard->avg('best_score')) : 0;
    $maxScore = $totalParticipants > 0 ? $leaderboard->max('best_score') : 0;
    $minScore = $totalParticipants > 0 ? $leaderboard->min('best_score') : 0;
    $myScore = $myRank ? $myRank->best_score : 0;
@endphp

<!-- Breadcrumbs -->
<nav class="flex flex-wrap items-center gap-2 mb-6">
    <a class="text-primary text-sm font-medium hover:underline" href="{{ route('peserta.dashboard') }}">Dashboard</a>
    <span class="material-symbols-outlined text-slate-400 text-sm">chevron_right</span>
    <a class="text-primary text-sm font-medium hover:underline" href="{{ route('peserta.skd.results') }}">Riwayat Ujian</a>
    <span class="material-symbols-outlined text-slate-400 text-sm">chevron_right</span>
    <span class="text-slate-500 dark:text-slate-400 text-sm font-medium">Leaderboard</span>
</nav>

<!-- Header -->
<div class="flex flex-wrap items-center justify-between gap-4 mb-8">
    <div>
        <h2 class="text-3xl font-black text-slate-900 dark:text-white tracking-tight flex items-center gap-3">
            <span class="material-symbols-outlined text-amber-500 text-4xl">emoji_events</span>
            Leaderboard SKD
        </h2>
        <p class="text-slate-500 dark:text-slate-400 mt-1">Papan peringkat berdasarkan skor tertinggi seluruh peserta simulasi.</p>
    </div>
    <form method="GET" action="{{ route('peserta.skd.leaderboard') }}">
        <select name="package_id" onchange="this.form.submit()"
                class="h-11 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 px-4 text-sm font-medium focus:ring-2 focus:ring-primary focus:border-primary">
            <option value="">Semua Paket</option>
            @foreach($packages as $pkg)
                <option value="{{ $pkg->id }}" {{ $selectedPackageId == $pkg->id ? 'selected' : '' }}>{{ $pkg->title }}</option>
            @endforeach
        </select>
    </form>
</div>

<!-- Your Rank Card -->
@if($myRank)
<div class="bg-primary/5 border border-primary/20 rounded-xl p-6 mb-8 flex flex-col md:flex-row items-center justify-between gap-4">
    <div class="flex items-center gap-4">
        <div class="size-14 rounded-full bg-primary text-white flex items-center justify-center text-xl font-black">
            {{ $initials }}
        </div>
        <div>
            <p class="text-sm text-slate-500">Posisi Anda saat ini</p>
            <h3 class="text-2xl font-black text-primary">#{{ $myRank->rank }} <span class="text-base font-semibold text-slate-600">dari {{ $totalParticipants }} peserta</span></h3>
        </div>
    </div>
    <div class="flex gap-6 text-center">
        <div><p class="text-xs font-bold text-slate-400 uppercase">TWK</p><p class="text-lg font-black">{{ $myRank->best_twk }}</p></div>
        <div><p class="text-xs font-bold text-slate-400 uppercase">TIU</p><p class="text-lg font-black">{{ $myRank->best_tiu }}</p></div>
        <div><p class="text-xs font-bold text-slate-400 uppercase">TKP</p><p class="text-lg font-black">{{ $myRank->best_tkp }}</p></div>
        <div class="border-l border-primary/20 pl-6"><p class="text-xs font-bold text-slate-400 uppercase">Total</p><p class="text-lg font-black text-primary">{{ $myRank->best_score }}</p></div>
    </div>
</div>
@endif

<div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
    <!-- Score Comparison -->
    <div class="lg:col-span-4">
        <div class="bg-white dark:bg-slate-900 p-6 rounded-xl border border-slate-200 dark:border-slate-800 shadow-sm">
            <h3 class="text-lg font-bold mb-6">Perbandingan Skor</h3>

            <!-- Radial Score -->
            <div class="flex flex-col items-center mb-6">
                @php
                    $radius = 60;
                    $circumference = 2 * M_PI * $radius;
                    $scorePercent = $maxScore > 0 ? ($myScore / 550) * 100 : 0;
                    $strokeDashoffset = $circumference - ($scorePercent / 100) * $circumference;
                @endphp
                <div class="relative">
                    <svg width="150" height="150" viewBox="0 0 150 150">
                        <circle cx="75" cy="75" r="{{ $radius }}" fill="none" stroke="#e2e8f0" stroke-width="10" />
                        <circle cx="75" cy="75" r="{{ $radius }}" fill="none" stroke="url(#scoreGrad)" stroke-width="10"
                                stroke-linecap="round" stroke-dasharray="{{ $circumference }}" stroke-dashoffset="{{ $strokeDashoffset }}"
                                transform="rotate(-90 75 75)" style="transition: stroke-dashoffset 1s ease-in-out;" />
                        <defs><linearGradient id="scoreGrad" x1="0%" y1="0%" x2="100%" y2="100%"><stop offset="0%" stop-color="#135bec" /><stop offset="100%" stop-color="#60a5fa" /></linearGradient></defs>
                    </svg>
                    <div class="absolute inset-0 flex flex-col items-center justify-center">
                        <span class="text-2xl font-black text-primary">{{ $myScore }}</span>
                        <span class="text-[9px] text-slate-400 font-semibold uppercase">Skor Anda</span>
                    </div>
                </div>
            </div>

            <div class="space-y-4">
                <div class="group"><div class="flex justify-between text-sm mb-1"><span class="flex items-center gap-2"><span class="size-2 rounded-full bg-primary"></span><span class="font-semibold text-slate-700">Anda</span></span><span class="font-bold text-primary">{{ $myScore }}</span></div><div class="w-full bg-slate-100 h-2.5 rounded-full"><div class="h-full rounded-full bg-gradient-to-r from-primary to-blue-400" style="width: {{ $maxScore > 0 ? round($myScore / 550 * 100) : 0 }}%;"></div></div></div>
                <div class="group"><div class="flex justify-between text-sm mb-1"><span class="flex items-center gap-2"><span class="size-2 rounded-full bg-amber-400"></span><span class="font-semibold text-slate-700">Rata-rata</span></span><span class="font-bold text-amber-500">{{ $avgScore }}</span></div><div class="w-full bg-slate-100 h-2.5 rounded-full"><div class="h-full rounded-full bg-gradient-to-r from-amber-400 to-yellow-300" style="width: {{ $maxScore > 0 ? round($avgScore / 550 * 100) : 0 }}%;"></div></div></div>
                <div class="group"><div class="flex justify-between text-sm mb-1"><span class="flex items-center gap-2"><span class="size-2 rounded-full bg-emerald-500"></span><span class="font-semibold text-slate-700">Tertinggi</span></span><span class="font-bold text-emerald-500">{{ $maxScore }}</span></div><div class="w-full bg-slate-100 h-2.5 rounded-full"><div class="h-full rounded-full bg-gradient-to-r from-emerald-500 to-teal-400" style="width: {{ $maxScore > 0 ? round($maxScore / 550 * 100) : 0 }}%;"></div></div></div>
                <div class="group"><div class="flex justify-between text-sm mb-1"><span class="flex items-center gap-2"><span class="size-2 rounded-full bg-rose-400"></span><span class="font-semibold text-slate-700">Terendah</span></span><span class="font-bold text-rose-400">{{ $minScore }}</span></div><div class="w-full bg-slate-100 h-2.5 rounded-full"><div class="h-full rounded-full bg-gradient-to-r from-rose-400 to-pink-300" style="width: {{ $maxScore > 0 ? round($minScore / 550 * 100) : 0 }}%;"></div></div></div>
            </div>
        </div>
    </div>

    <!-- Ranking Table -->
    <div class="lg:col-span-8">
        <div class="bg-white dark:bg-slate-900 rounded-xl border border-slate-200 dark:border-slate-800 overflow-hidden shadow-sm">
            <div class="p-6 border-b border-slate-100 dark:border-slate-800">
                <h3 class="text-lg font-bold">Peserta Teratas</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50 dark:bg-slate-800/50">
                            <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Rank</th>
                            <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Nama</th>
                            <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">TWK</th>
                            <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">TIU</th>
                            <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">TKP</th>
                            <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider text-right">Total</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                        @forelse($leaderboard as $entry)
                        <tr class="hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors {{ $entry->user_id === auth()->id() ? 'bg-primary/5' : '' }}">
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($entry->rank === 1)
                                <div class="flex items-center justify-center size-7 rounded-full bg-yellow-400/20 text-yellow-600 font-bold text-xs">1</div>
                                @elseif($entry->rank === 2)
                                <div class="flex items-center justify-center size-7 rounded-full bg-slate-300/30 text-slate-500 font-bold text-xs">2</div>
                                @elseif($entry->rank === 3)
                                <div class="flex items-center justify-center size-7 rounded-full bg-orange-400/20 text-orange-600 font-bold text-xs">3</div>
                                @else
                                <div class="flex items-center justify-center size-7 rounded-full bg-slate-100 dark:bg-slate-800 text-slate-600 font-bold text-xs">{{ $entry->rank }}</div>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center gap-3">
                                    @php $eI = collect(explode(' ', $entry->name))->map(fn($s) => strtoupper(substr($s, 0, 1)))->take(2)->join(''); @endphp
                                    <div class="size-8 rounded-full flex items-center justify-center text-xs font-bold {{ $entry->user_id === auth()->id() ? 'bg-primary text-white' : 'bg-slate-200 text-slate-600' }}">{{ $eI }}</div>
                                    <span class="font-medium {{ $entry->user_id === auth()->id() ? 'text-primary' : '' }}">
                                        {{ $entry->name }}
                                        @if($entry->user_id === auth()->id())
                                            <span class="text-[10px] bg-primary/10 text-primary px-2 py-0.5 rounded-full ml-1">Anda</span>
                                        @endif
                                    </span>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm text-slate-600">{{ $entry->best_twk }}</td>
                            <td class="px-6 py-4 text-sm text-slate-600">{{ $entry->best_tiu }}</td>
                            <td class="px-6 py-4 text-sm text-slate-600">{{ $entry->best_tkp }}</td>
                            <td class="px-6 py-4 text-right font-bold text-primary">{{ $entry->best_score }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-6 py-16 text-center">
                                <span class="material-symbols-outlined text-5xl text-slate-300 block mb-3">emoji_events</span>
                                <p class="text-slate-400 italic">Belum ada data peserta.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="p-4 border-t border-slate-100 dark:border-slate-800 bg-slate-50 dark:bg-slate-800/30">
                <p class="text-xs text-slate-500">Menampilkan {{ $leaderboard->count() }} peserta</p>
            </div>
        </div>
    </div>
</div>
@endsection
