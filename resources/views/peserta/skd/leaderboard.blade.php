<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Leaderboard | {{ config('app.name', 'Psikotes') }}</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
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
                        "display": ["Public Sans"]
                    },
                    borderRadius: {"DEFAULT": "0.25rem", "lg": "0.5rem", "xl": "0.75rem", "full": "9999px"},
                },
            },
        }
    </script>
</head>

@php
    $initials = collect(explode(' ', auth()->user()->name))->map(fn($s) => strtoupper(substr($s, 0, 1)))->take(2)->join('');
    $totalParticipants = $leaderboard->count();
    $myPercentile = $myRank ? round((1 - ($myRank->rank / max($totalParticipants, 1))) * 100, 1) : 0;

    // Compute stats for bar chart
    $avgScore = $totalParticipants > 0 ? round($leaderboard->avg('best_score')) : 0;
    $maxScore = $totalParticipants > 0 ? $leaderboard->max('best_score') : 0;
    $minScore = $totalParticipants > 0 ? $leaderboard->min('best_score') : 0;
    $myScore = $myRank ? $myRank->best_score : 0;

    // Bar heights (relative to max)
    $heightMax = $maxScore > 0 ? 95 : 0;
    $heightAvg = $maxScore > 0 ? round(($avgScore / $maxScore) * 95) : 0;
    $heightMy = $maxScore > 0 ? round(($myScore / $maxScore) * 95) : 0;
    $heightMin = $maxScore > 0 ? round(($minScore / $maxScore) * 95) : 0;
@endphp

<body class="bg-background-light dark:bg-background-dark font-display text-slate-900 dark:text-slate-100 antialiased">
<div class="relative flex h-auto min-h-screen w-full flex-col group/design-root overflow-x-hidden">
<div class="layout-container flex h-full grow flex-col">
<div class="px-4 md:px-20 lg:px-40 flex flex-1 justify-center py-5">
<div class="layout-content-container flex flex-col max-w-[1200px] flex-1">

<header class="flex items-center justify-between whitespace-nowrap border-b border-solid border-slate-200 dark:border-slate-800 px-4 py-4 mb-6">
    <div class="flex items-center gap-4">
        <div class="flex items-center justify-center size-10 rounded-lg bg-primary text-white">
            <span class="material-symbols-outlined">military_tech</span>
        </div>
        <h2 class="text-xl font-bold leading-tight tracking-tight">Leaderboard</h2>
    </div>
    <div class="flex flex-1 justify-end gap-4 items-center">
        <a class="hidden md:block text-slate-600 dark:text-slate-400 text-sm font-medium hover:text-primary transition-colors" href="{{ route('peserta.skd.results') }}">Riwayat Simulasi</a>
        <a href="{{ route('peserta.dashboard') }}" class="flex items-center justify-center rounded-lg h-10 bg-primary/10 text-primary hover:bg-primary/20 transition-all gap-2 text-sm font-bold px-4">
            <span class="material-symbols-outlined text-sm">arrow_back</span>
            <span>Kembali</span>
        </a>
    </div>
</header>

<div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
    <!-- Sidebar -->
    <aside class="lg:col-span-3 flex flex-col gap-6">
        <!-- Your Rank Card -->
        <div class="bg-white dark:bg-slate-900 p-6 rounded-xl shadow-sm border border-slate-200 dark:border-slate-800">
            <div class="flex flex-col items-center text-center">
                <div class="size-20 bg-primary/10 rounded-full flex items-center justify-center mb-4 border-4 border-white dark:border-slate-800 shadow-sm text-primary text-2xl font-black">
                    {{ $initials }}
                </div>
                <h3 class="font-bold text-lg">Peringkat Anda</h3>
                <p class="text-primary text-3xl font-black mt-1">#{{ $myRank ? $myRank->rank : '-' }}</p>
                <p class="text-slate-500 text-xs mt-1">
                    @if($myRank)
                        Top {{ $myPercentile }}% dari {{ $totalParticipants }} peserta
                    @else
                        Belum ada data
                    @endif
                </p>
            </div>
            <div class="mt-6 pt-6 border-t border-slate-100 dark:border-slate-800 flex flex-col gap-3">
                <div class="flex justify-between items-center text-sm">
                    <span class="text-slate-500">Total Skor</span>
                    <span class="font-bold">{{ $myRank ? $myRank->best_score : '-' }}</span>
                </div>
                <div class="flex justify-between items-center text-sm">
                    <span class="text-slate-500">Persentil</span>
                    <span class="font-bold">{{ $myRank ? $myPercentile.'%' : '-' }}</span>
                </div>
            </div>
        </div>

        <!-- Navigation -->
        <div class="bg-white dark:bg-slate-900 p-2 rounded-xl shadow-sm border border-slate-200 dark:border-slate-800">
            <nav class="flex flex-col gap-1">
                <a class="flex items-center gap-3 px-4 py-3 rounded-lg bg-primary/10 text-primary font-semibold" href="{{ route('peserta.skd.leaderboard') }}">
                    <span class="material-symbols-outlined">leaderboard</span>
                    <span class="text-sm">Peringkat Global</span>
                </a>
                <a class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-slate-50 dark:hover:bg-slate-800 text-slate-600 dark:text-slate-400 transition-colors font-medium" href="{{ route('peserta.skd.results') }}">
                    <span class="material-symbols-outlined">history</span>
                    <span class="text-sm">Riwayat Pribadi</span>
                </a>
                <a class="flex items-center gap-3 px-4 py-3 rounded-lg hover:bg-slate-50 dark:hover:bg-slate-800 text-slate-600 dark:text-slate-400 transition-colors font-medium" href="{{ route('peserta.skd.index') }}">
                    <span class="material-symbols-outlined">quiz</span>
                    <span class="text-sm">Simulasi SKD</span>
                </a>
            </nav>
        </div>

        <!-- Filter Paket -->
        <div class="bg-white dark:bg-slate-900 p-4 rounded-xl shadow-sm border border-slate-200 dark:border-slate-800">
            <h4 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-3">Filter Paket</h4>
            <form method="GET" action="{{ route('peserta.skd.leaderboard') }}">
                <select name="package_id" onchange="this.form.submit()"
                        class="w-full rounded-lg border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 px-3 py-2 text-sm focus:ring-primary focus:border-primary">
                    <option value="">Semua Paket</option>
                    @foreach($packages as $pkg)
                        <option value="{{ $pkg->id }}" {{ $selectedPackageId == $pkg->id ? 'selected' : '' }}>{{ $pkg->title }}</option>
                    @endforeach
                </select>
            </form>
        </div>
    </aside>

    <!-- Main Content -->
    <main class="lg:col-span-9 flex flex-col gap-6">
        <!-- Score Comparison -->
        <div class="bg-white dark:bg-slate-900 p-6 rounded-xl shadow-sm border border-slate-200 dark:border-slate-800">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
                <div>
                    <h2 class="text-xl font-bold">Perbandingan Skor</h2>
                    <p class="text-slate-500 text-sm">Performa Anda dibandingkan peserta lain</p>
                </div>
            </div>

            <!-- Radial Score in Center -->
            <div class="flex flex-col items-center mb-8">
                @php
                    $radius = 70;
                    $circumference = 2 * M_PI * $radius;
                    $scorePercent = $maxScore > 0 ? ($myScore / 550) * 100 : 0;
                    $strokeDashoffset = $circumference - ($scorePercent / 100) * $circumference;
                @endphp
                <div class="relative">
                    <svg width="180" height="180" viewBox="0 0 180 180">
                        <circle cx="90" cy="90" r="{{ $radius }}" fill="none" stroke="#e2e8f0" stroke-width="12" />
                        <circle cx="90" cy="90" r="{{ $radius }}" fill="none" stroke="url(#scoreGradient)" stroke-width="12"
                                stroke-linecap="round"
                                stroke-dasharray="{{ $circumference }}"
                                stroke-dashoffset="{{ $strokeDashoffset }}"
                                transform="rotate(-90 90 90)"
                                style="transition: stroke-dashoffset 1s ease-in-out;" />
                        <defs>
                            <linearGradient id="scoreGradient" x1="0%" y1="0%" x2="100%" y2="100%">
                                <stop offset="0%" stop-color="#135bec" />
                                <stop offset="100%" stop-color="#60a5fa" />
                            </linearGradient>
                        </defs>
                    </svg>
                    <div class="absolute inset-0 flex flex-col items-center justify-center">
                        <span class="text-3xl font-black text-primary">{{ $myScore }}</span>
                        <span class="text-[10px] text-slate-400 font-semibold uppercase tracking-wider">Skor Anda</span>
                    </div>
                </div>
            </div>

            <!-- Horizontal Comparison Bars -->
            <div class="space-y-5">
                <!-- Your Score -->
                <div class="group">
                    <div class="flex justify-between items-center mb-1.5">
                        <div class="flex items-center gap-2">
                            <div class="size-2.5 rounded-full bg-primary"></div>
                            <span class="text-sm font-semibold text-slate-700 dark:text-slate-300">Skor Anda</span>
                        </div>
                        <span class="text-sm font-bold text-primary">{{ $myScore }}</span>
                    </div>
                    <div class="w-full bg-slate-100 dark:bg-slate-800 h-3 rounded-full overflow-hidden">
                        <div class="h-full rounded-full bg-gradient-to-r from-primary to-blue-400 transition-all duration-700 group-hover:shadow-lg group-hover:shadow-primary/20" style="width: {{ $maxScore > 0 ? round($myScore / 550 * 100) : 0 }}%;"></div>
                    </div>
                </div>

                <!-- Average Score -->
                <div class="group">
                    <div class="flex justify-between items-center mb-1.5">
                        <div class="flex items-center gap-2">
                            <div class="size-2.5 rounded-full bg-amber-400"></div>
                            <span class="text-sm font-semibold text-slate-700 dark:text-slate-300">Rata-rata</span>
                        </div>
                        <span class="text-sm font-bold text-amber-500">{{ $avgScore }}</span>
                    </div>
                    <div class="w-full bg-slate-100 dark:bg-slate-800 h-3 rounded-full overflow-hidden">
                        <div class="h-full rounded-full bg-gradient-to-r from-amber-400 to-yellow-300 transition-all duration-700 group-hover:shadow-lg group-hover:shadow-amber-400/20" style="width: {{ $maxScore > 0 ? round($avgScore / 550 * 100) : 0 }}%;"></div>
                    </div>
                </div>

                <!-- Highest Score -->
                <div class="group">
                    <div class="flex justify-between items-center mb-1.5">
                        <div class="flex items-center gap-2">
                            <div class="size-2.5 rounded-full bg-emerald-500"></div>
                            <span class="text-sm font-semibold text-slate-700 dark:text-slate-300">Tertinggi</span>
                        </div>
                        <span class="text-sm font-bold text-emerald-500">{{ $maxScore }}</span>
                    </div>
                    <div class="w-full bg-slate-100 dark:bg-slate-800 h-3 rounded-full overflow-hidden">
                        <div class="h-full rounded-full bg-gradient-to-r from-emerald-500 to-teal-400 transition-all duration-700 group-hover:shadow-lg group-hover:shadow-emerald-500/20" style="width: {{ $maxScore > 0 ? round($maxScore / 550 * 100) : 0 }}%;"></div>
                    </div>
                </div>

                <!-- Lowest Score -->
                <div class="group">
                    <div class="flex justify-between items-center mb-1.5">
                        <div class="flex items-center gap-2">
                            <div class="size-2.5 rounded-full bg-rose-400"></div>
                            <span class="text-sm font-semibold text-slate-700 dark:text-slate-300">Terendah</span>
                        </div>
                        <span class="text-sm font-bold text-rose-400">{{ $minScore }}</span>
                    </div>
                    <div class="w-full bg-slate-100 dark:bg-slate-800 h-3 rounded-full overflow-hidden">
                        <div class="h-full rounded-full bg-gradient-to-r from-rose-400 to-pink-300 transition-all duration-700 group-hover:shadow-lg group-hover:shadow-rose-400/20" style="width: {{ $maxScore > 0 ? round($minScore / 550 * 100) : 0 }}%;"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Top Participants Table -->
        <div class="bg-white dark:bg-slate-900 rounded-xl shadow-sm border border-slate-200 dark:border-slate-800 overflow-hidden">
            <div class="p-6 border-b border-slate-100 dark:border-slate-800 flex justify-between items-center">
                <h2 class="text-xl font-bold">Peserta Teratas</h2>
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
                                <div class="flex items-center justify-center size-7 rounded-full bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 font-bold text-xs">{{ $entry->rank }}</div>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center gap-3">
                                    @php
                                        $eInitials = collect(explode(' ', $entry->name))->map(fn($s) => strtoupper(substr($s, 0, 1)))->take(2)->join('');
                                    @endphp
                                    <div class="size-8 rounded-full flex items-center justify-center text-xs font-bold {{ $entry->user_id === auth()->id() ? 'bg-primary text-white' : 'bg-slate-200 dark:bg-slate-800 text-slate-600 dark:text-slate-400' }}">
                                        {{ $eInitials }}
                                    </div>
                                    <span class="font-medium {{ $entry->user_id === auth()->id() ? 'text-primary' : '' }}">
                                        {{ $entry->name }}
                                        @if($entry->user_id === auth()->id())
                                            <span class="text-[10px] bg-primary/10 text-primary px-2 py-0.5 rounded-full ml-1">Anda</span>
                                        @endif
                                    </span>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-600 dark:text-slate-400">{{ $entry->best_twk }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-600 dark:text-slate-400">{{ $entry->best_tiu }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-600 dark:text-slate-400">{{ $entry->best_tkp }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-right font-bold text-primary">{{ $entry->best_score }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-6 py-16 text-center">
                                <span class="material-symbols-outlined text-5xl text-slate-300 block mb-3">emoji_events</span>
                                <p class="text-slate-400 italic">Belum ada data peserta. Jadilah yang pertama!</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="p-4 border-t border-slate-100 dark:border-slate-800 flex justify-between items-center bg-slate-50 dark:bg-slate-800/30">
                <p class="text-xs text-slate-500">Menampilkan {{ $leaderboard->count() }} peserta</p>
            </div>
        </div>
    </main>
</div>

</div>
</div>
</div>
</div>
</body>
</html>
