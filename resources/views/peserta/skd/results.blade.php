@extends('layouts.main')
@section('title', 'Riwayat Hasil SKD')

@section('content')
<div class="space-y-8">

    <!-- Header & Ekspor -->
    <div class="flex items-center justify-between border-b border-slate-200 dark:border-slate-800 pb-4">
        <h1 class="text-2xl font-black text-slate-900 dark:text-white">Riwayat Hasil SKD</h1>
        <button onclick="window.print()" class="flex items-center gap-2 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 px-4 py-2 rounded-lg text-sm font-bold shadow-sm hover:bg-slate-50 dark:hover:bg-slate-700 transition-colors">
            <span class="material-symbols-outlined text-lg">download</span>
            Ekspor Laporan
        </button>
    </div>

    <!-- Summary Metrics -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white dark:bg-slate-900 p-6 rounded-xl border border-slate-200 dark:border-slate-800 shadow-sm flex items-center gap-5">
            <div class="h-14 w-14 rounded-full bg-primary/10 flex items-center justify-center text-primary">
                <span class="material-symbols-outlined text-3xl">assignment</span>
            </div>
            <div>
                <p class="text-sm font-medium text-slate-500 dark:text-slate-400">Total Simulasi</p>
                <p class="text-3xl font-bold">{{ $totalSimulasi }}</p>
                <p class="text-xs font-semibold text-emerald-600 mt-1 flex items-center gap-1">
                    <span class="material-symbols-outlined text-xs">trending_up</span> {{ $trendTotal }}
                </p>
            </div>
        </div>
        <div class="bg-white dark:bg-slate-900 p-6 rounded-xl border border-slate-200 dark:border-slate-800 shadow-sm flex items-center gap-5">
            <div class="h-14 w-14 rounded-full bg-amber-100 dark:bg-amber-900/30 flex items-center justify-center text-amber-600 dark:text-amber-500">
                <span class="material-symbols-outlined text-3xl">emoji_events</span>
            </div>
            <div>
                <p class="text-sm font-medium text-slate-500 dark:text-slate-400">Skor Tertinggi</p>
                <p class="text-3xl font-bold">{{ $skorTertinggi }}</p>
                <p class="text-xs font-semibold text-emerald-600 mt-1 flex items-center gap-1">
                    <span class="material-symbols-outlined text-xs">trending_up</span> {{ $trendSkor }}
                </p>
            </div>
        </div>
        <div class="bg-white dark:bg-slate-900 p-6 rounded-xl border border-slate-200 dark:border-slate-800 shadow-sm flex items-center gap-5">
            <div class="h-14 w-14 rounded-full bg-emerald-100 dark:bg-emerald-900/30 flex items-center justify-center text-emerald-600 dark:text-emerald-500">
                <span class="material-symbols-outlined text-3xl">check_circle</span>
            </div>
            <div>
                <p class="text-sm font-medium text-slate-500 dark:text-slate-400">Rata-rata Kelulusan</p>
                <p class="text-3xl font-bold">{{ $rataKelulusan }}%</p>
                <p class="text-xs font-semibold text-emerald-600 mt-1 flex items-center gap-1">
                    <span class="material-symbols-outlined text-xs">trending_up</span> {{ $trendLulus }}
                </p>
            </div>
        </div>
    </div>

    <!-- Trend Chart -->
    @if($trendResults->count() > 1)
    <div class="bg-white dark:bg-slate-900 p-8 rounded-xl border border-slate-200 dark:border-slate-800 shadow-sm">
        <div class="flex flex-wrap items-center justify-between mb-8 gap-4">
            <div>
                <h2 class="text-lg font-bold">Tren Perkembangan Skor</h2>
                <p class="text-sm text-slate-500 dark:text-slate-400">Visualisasi 7 simulasi terakhir</p>
            </div>
            <div class="flex items-center gap-4 text-xs font-semibold">
                <div class="flex items-center gap-2"><div class="w-3 h-3 rounded-full bg-primary"></div><span>Total Skor</span></div>
                <div class="flex items-center gap-2"><div class="w-3 h-3 rounded-full bg-slate-300"></div><span>Passing Grade (311)</span></div>
            </div>
        </div>

        @php
            $maxPts = 7;
            $count = $trendResults->count();
            // Data point coordinates calculation
            // Let x go from 0 to 800, y go from 0 to 200. Max score = 550.
            // Y = 200 - (score / 550 * 200). Note: Passing grade (311) is Y ~ 87
            $points = [];
            foreach($trendResults as $idx => $tRes) {
                // Determine spacing. If $count is < 2, avoid div by 0.
                $x = $count > 1 ? ($idx / ($count - 1)) * 800 : 400;
                $y = 200 - ($tRes->total_score / 550 * 200);
                $points[] = "$x,$y";
            }
            $pathData = "M" . implode(" L", $points);
            $areaData = $pathData . " L800,200 L0,200 Z";
            if($count > 1) {
                $areaData = "M0,200 L0," . (200 - ($trendResults[0]->total_score / 550 * 200)) . " " . substr($pathData, strpos($pathData, ' ')+1) . " L800,200 Z"; // close area cleanly
            }
            $passY = 200 - (311 / 550 * 200);   
        @endphp

        <div class="h-64 relative">
            <svg class="w-full h-full overflow-visible" preserveAspectRatio="none" viewBox="0 0 800 200">
                <defs>
                    <linearGradient id="scoreGradient" x1="0%" x2="0%" y1="0%" y2="100%">
                        <stop offset="0%" stop-color="#135bec" stop-opacity="0.25"></stop>
                        <stop offset="100%" stop-color="#135bec" stop-opacity="0"></stop>
                    </linearGradient>
                </defs>

                <!-- Passing Grade Reference Line -->
                <line stroke="#cbd5e1" stroke-dasharray="6" stroke-width="2" x1="0" x2="800" y1="{{ $passY }}" y2="{{ $passY }}"></line>
                
                <!-- Chart Area -->
                <path d="{{ $areaData }}" fill="url(#scoreGradient)"></path>
                
                <!-- Chart Line -->
                <path d="{{ $pathData }}" fill="none" stroke="#135bec" stroke-linecap="round" stroke-linejoin="round" stroke-width="3"></path>
                
                <!-- Chart Points -->
                @foreach($trendResults as $idx => $tRes)
                    @php 
                        $x = $count > 1 ? ($idx / ($count - 1)) * 800 : 400; 
                        $y = 200 - ($tRes->total_score / 550 * 200);
                    @endphp
                    <circle cx="{{ $x }}" cy="{{ $y }}" fill="#fff" r="6" stroke="#135bec" stroke-width="3">
                        <title>Skor: {{ $tRes->total_score }} ({{ $tRes->created_at->format('d M') }})</title>
                    </circle>
                @endforeach
            </svg>
        </div>

        <div class="flex justify-between mt-6">
            @foreach($trendResults as $idx => $tRes)
                <p class="text-xs font-bold text-slate-400">Sim {{ $totalSimulasi - $count + $idx + 1 }}</p>
            @endforeach
            @for($i = $count; $i < max($count, 2); $i++) 
                <!-- Spacer for layout if only 1 item, though this block is behind if(count > 1) -->
            @endfor
        </div>
    </div>
    @endif

    <!-- Table -->
    <div class="bg-white dark:bg-slate-900 rounded-xl border border-slate-200 dark:border-slate-800 shadow-sm overflow-hidden">
        <div class="p-6 border-b border-slate-200 dark:border-slate-800 flex flex-col md:flex-row md:items-center justify-between gap-4">
            <h2 class="text-lg font-bold">Detail Riwayat Latihan</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 dark:bg-slate-800/50">
                        <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Tanggal & Waktu</th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Paket Simulasi</th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider text-center">TWK</th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider text-center">TIU</th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider text-center">TKP</th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider text-center">Total</th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                    @forelse($results as $res)
                        <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/30 transition-colors">
                            <td class="px-6 py-4">
                                <div class="text-sm font-semibold">{{ $res->created_at->format('d M Y') }}</div>
                                <div class="text-xs text-slate-500">{{ $res->created_at->format('H:i') }} WIB</div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm font-bold text-primary">{{ $res->package->title ?? 'Simulasi SKD' }}</div>
                            </td>
                            <td class="px-6 py-4 text-center text-sm font-bold {{ $res->twk_passed ? 'text-slate-900 dark:text-white' : 'text-red-500' }}">{{ $res->twk_score }}</td>
                            <td class="px-6 py-4 text-center text-sm font-bold {{ $res->tiu_passed ? 'text-slate-900 dark:text-white' : 'text-red-500' }}">{{ $res->tiu_score }}</td>
                            <td class="px-6 py-4 text-center text-sm font-bold {{ $res->tkp_passed ? 'text-slate-900 dark:text-white' : 'text-red-500' }}">{{ $res->tkp_score }}</td>
                            <td class="px-6 py-4 text-center">
                                <span class="text-base font-black text-slate-900 dark:text-white">{{ $res->total_score }}</span>
                            </td>
                            <td class="px-6 py-4">
                                @if($res->is_passed)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-emerald-100/60 text-emerald-700 dark:bg-emerald-900/40 dark:text-emerald-400">LULUS</span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-red-100/60 text-red-700 dark:bg-red-900/40 dark:text-red-400">GAGAL</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right">
                                <a href="{{ route('peserta.skd.results.show', $res) }}" class="text-primary text-sm font-bold hover:underline">Detail</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-6 py-16 text-center text-slate-500">
                                <span class="material-symbols-outlined text-4xl block mb-2 opacity-50">history_edu</span>
                                Anda belum memiliki riwayat simulasi yang selesai.<br>
                                <a href="{{ route('peserta.skd.index') }}" class="text-primary font-bold hover:underline mt-2 inline-block">Mulai Simulasi Sekarang</a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($results->hasPages())
        <div class="p-6 border-t border-slate-200 dark:border-slate-800 flex flex-col md:flex-row items-center justify-between gap-4">
            <p class="text-sm text-slate-500 dark:text-slate-400">Halaman {{ $results->currentPage() }} dari {{ $results->lastPage() }}</p>
            <div class="flex items-center gap-2">
                {{ $results->links('pagination::tailwind') }}
            </div>
        </div>
        @endif
        
    </div>

</div>
@endsection
