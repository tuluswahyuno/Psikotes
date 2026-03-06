@extends('layouts.main')
@section('title', 'Dashboard Admin')
@section('subtitle', 'Ringkasan data Psikotes & SKD CPNS')

@section('content')
<!-- Stats Cards Row 1 — Psikotes & SKD -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
    {{-- Psikotes Total --}}
    <div class="stat-card">
        <div class="flex items-center justify-between mb-4">
            <div class="w-12 h-12 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-xl flex items-center justify-center">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
            </div>
            <span class="text-xs font-medium text-indigo-600 bg-indigo-50 px-2 py-1 rounded-lg">Psikotes</span>
        </div>
        <p class="text-3xl font-bold text-gray-900">{{ $stats['total_tests'] }}</p>
        <p class="text-sm text-gray-500 mt-1">Total Tes Psikotes</p>
    </div>

    {{-- Psikotes Aktif --}}
    <div class="stat-card">
        <div class="flex items-center justify-between mb-4">
            <div class="w-12 h-12 bg-gradient-to-br from-emerald-500 to-teal-600 rounded-xl flex items-center justify-center">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <span class="text-xs font-medium text-emerald-600 bg-emerald-50 px-2 py-1 rounded-lg">Aktif</span>
        </div>
        <p class="text-3xl font-bold text-gray-900">{{ $stats['active_tests'] }}</p>
        <p class="text-sm text-gray-500 mt-1">Tes Psikotes Aktif</p>
    </div>

    {{-- SKD Total --}}
    <div class="stat-card">
        <div class="flex items-center justify-between mb-4">
            <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-cyan-600 rounded-xl flex items-center justify-center">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
            </div>
            <span class="text-xs font-medium text-blue-600 bg-blue-50 px-2 py-1 rounded-lg">SKD</span>
        </div>
        <p class="text-3xl font-bold text-gray-900">{{ $stats['total_skd'] }}</p>
        <p class="text-sm text-gray-500 mt-1">Total Paket SKD</p>
    </div>

    {{-- SKD Aktif --}}
    <div class="stat-card">
        <div class="flex items-center justify-between mb-4">
            <div class="w-12 h-12 bg-gradient-to-br from-cyan-500 to-sky-600 rounded-xl flex items-center justify-center">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            </div>
            <span class="text-xs font-medium text-cyan-600 bg-cyan-50 px-2 py-1 rounded-lg">Aktif</span>
        </div>
        <p class="text-3xl font-bold text-gray-900">{{ $stats['active_skd'] }}</p>
        <p class="text-sm text-gray-500 mt-1">Paket SKD Aktif</p>
    </div>
</div>

<!-- Stats Cards Row 2 — Peserta & Sessions -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    {{-- Total Peserta --}}
    <div class="stat-card">
        <div class="flex items-center justify-between mb-4">
            <div class="w-12 h-12 bg-gradient-to-br from-amber-500 to-orange-600 rounded-xl flex items-center justify-center">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197"/></svg>
            </div>
            <span class="text-xs font-medium text-amber-600 bg-amber-50 px-2 py-1 rounded-lg">Peserta</span>
        </div>
        <p class="text-3xl font-bold text-gray-900">{{ $stats['total_peserta'] }}</p>
        <p class="text-sm text-gray-500 mt-1">Total Peserta</p>
    </div>

    {{-- Sesi Psikotes --}}
    <div class="stat-card">
        <div class="flex items-center justify-between mb-4">
            <div class="w-12 h-12 bg-gradient-to-br from-rose-500 to-pink-600 rounded-xl flex items-center justify-center">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
            </div>
            <span class="text-xs font-medium text-rose-600 bg-rose-50 px-2 py-1 rounded-lg">Selesai</span>
        </div>
        <p class="text-3xl font-bold text-gray-900">{{ $stats['psikotes_sessions'] }}</p>
        <p class="text-sm text-gray-500 mt-1">Sesi Psikotes Selesai</p>
    </div>

    {{-- Sesi SKD --}}
    <div class="stat-card">
        <div class="flex items-center justify-between mb-4">
            <div class="w-12 h-12 bg-gradient-to-br from-violet-500 to-purple-600 rounded-xl flex items-center justify-center">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <span class="text-xs font-medium text-violet-600 bg-violet-50 px-2 py-1 rounded-lg">Selesai</span>
        </div>
        <p class="text-3xl font-bold text-gray-900">{{ $stats['skd_sessions'] }}</p>
        <p class="text-sm text-gray-500 mt-1">Sesi SKD Selesai</p>
    </div>

    {{-- Tingkat Kelulusan SKD --}}
    <div class="stat-card">
        <div class="flex items-center justify-between mb-4">
            <div class="w-12 h-12 bg-gradient-to-br from-green-500 to-emerald-600 rounded-xl flex items-center justify-center">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/></svg>
            </div>
            <span class="text-xs font-medium text-green-600 bg-green-50 px-2 py-1 rounded-lg">Kelulusan</span>
        </div>
        <p class="text-3xl font-bold text-gray-900">
            {{ $stats['skd_total_results'] > 0 ? round(($stats['skd_passed'] / $stats['skd_total_results']) * 100) : 0 }}%
        </p>
        <p class="text-sm text-gray-500 mt-1">Tingkat Kelulusan SKD</p>
    </div>
</div>

<!-- Charts Section -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
    {{-- Activity Chart --}}
    <div class="table-container lg:col-span-2 p-6">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-lg font-bold text-gray-900">Aktivitas Ujian (7 Hari Terakhir)</h3>
            <div class="flex items-center gap-4 text-xs font-medium">
                <span class="flex items-center gap-1.5"><span class="w-3 h-3 rounded-full bg-indigo-500"></span> Psikotes</span>
                <span class="flex items-center gap-1.5"><span class="w-3 h-3 rounded-full bg-blue-500"></span> SKD</span>
            </div>
        </div>
        <div class="relative h-72 w-full">
            <canvas id="activityChart"></canvas>
        </div>
    </div>

    {{-- SKD Distribution --}}
    <div class="table-container p-6">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-lg font-bold text-gray-900">Kelulusan SKD</h3>
        </div>
        <div class="relative h-72 w-full flex justify-center">
            <canvas id="skdDistributionChart"></canvas>
        </div>
    </div>
</div>

<!-- Recent Data Tables -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    {{-- Recent SKD Results --}}
    <div class="table-container">
        <div class="px-6 py-4 border-b border-gray-100">
            <h3 class="text-lg font-bold text-gray-900">Hasil SKD Terbaru</h3>
        </div>
        <div class="divide-y divide-gray-50">
            @forelse($recentSkdResults as $result)
                <div class="px-6 py-4 flex items-center justify-between hover:bg-gray-50 transition-colors">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 bg-gradient-to-br from-blue-400 to-cyan-500 rounded-lg flex items-center justify-center text-white text-xs font-bold">
                            {{ strtoupper(substr($result->user->name, 0, 1)) }}
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-gray-900">{{ $result->user->name }}</p>
                            <p class="text-xs text-gray-500">{{ $result->package->title ?? '-' }}</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <div class="flex items-center gap-2 mb-1">
                            <span class="text-[10px] font-bold px-1.5 py-0.5 rounded {{ $result->twk_passed ? 'bg-emerald-100 text-emerald-700' : 'bg-red-100 text-red-700' }}">TWK {{ $result->twk_score }}</span>
                            <span class="text-[10px] font-bold px-1.5 py-0.5 rounded {{ $result->tiu_passed ? 'bg-emerald-100 text-emerald-700' : 'bg-red-100 text-red-700' }}">TIU {{ $result->tiu_score }}</span>
                            <span class="text-[10px] font-bold px-1.5 py-0.5 rounded {{ $result->tkp_passed ? 'bg-emerald-100 text-emerald-700' : 'bg-red-100 text-red-700' }}">TKP {{ $result->tkp_score }}</span>
                        </div>
                        <span class="text-xs font-bold {{ $result->is_passed ? 'text-emerald-600' : 'text-red-600' }}">
                            {{ $result->is_passed ? '✓ LULUS' : '✗ TIDAK LULUS' }}
                        </span>
                    </div>
                </div>
            @empty
                <div class="px-6 py-8 text-center text-gray-400 text-sm">Belum ada hasil SKD</div>
            @endforelse
        </div>
    </div>

    {{-- Recent Peserta --}}
    <div class="table-container">
        <div class="px-6 py-4 border-b border-gray-100">
            <h3 class="text-lg font-bold text-gray-900">Peserta Terbaru</h3>
        </div>
        <div class="divide-y divide-gray-50">
            @forelse($recentPeserta as $user)
                <div class="px-6 py-4 flex items-center justify-between hover:bg-gray-50 transition-colors">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 bg-gradient-to-br from-emerald-400 to-teal-500 rounded-lg flex items-center justify-center text-white text-xs font-bold">
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-gray-900">{{ $user->name }}</p>
                            <p class="text-xs text-gray-500">{{ $user->email }}</p>
                        </div>
                    </div>
                    <span class="text-xs text-gray-400">{{ $user->created_at->diffForHumans() }}</span>
                </div>
            @empty
                <div class="px-6 py-8 text-center text-gray-400 text-sm">Belum ada peserta</div>
            @endforelse
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        if (typeof window.Chart !== 'undefined') {
            // ===== Activity Line Chart (Dual Dataset) =====
            const ctxActivity = document.getElementById('activityChart').getContext('2d');

            const gradientPsikotes = ctxActivity.createLinearGradient(0, 0, 0, 300);
            gradientPsikotes.addColorStop(0, 'rgba(99, 102, 241, 0.4)');
            gradientPsikotes.addColorStop(1, 'rgba(99, 102, 241, 0.0)');

            const gradientSkd = ctxActivity.createLinearGradient(0, 0, 0, 300);
            gradientSkd.addColorStop(0, 'rgba(59, 130, 246, 0.4)');
            gradientSkd.addColorStop(1, 'rgba(59, 130, 246, 0.0)');

            new window.Chart(ctxActivity, {
                type: 'line',
                data: {
                    labels: {!! json_encode($activityChart['labels']) !!},
                    datasets: [
                        {
                            label: 'Sesi Psikotes',
                            data: {!! json_encode($activityChart['psikotes']) !!},
                            borderColor: '#6366f1',
                            backgroundColor: gradientPsikotes,
                            borderWidth: 3,
                            pointBackgroundColor: '#ffffff',
                            pointBorderColor: '#6366f1',
                            pointBorderWidth: 2,
                            pointRadius: 4,
                            pointHoverRadius: 6,
                            fill: true,
                            tension: 0.4
                        },
                        {
                            label: 'Sesi SKD',
                            data: {!! json_encode($activityChart['skd']) !!},
                            borderColor: '#3b82f6',
                            backgroundColor: gradientSkd,
                            borderWidth: 3,
                            pointBackgroundColor: '#ffffff',
                            pointBorderColor: '#3b82f6',
                            pointBorderWidth: 2,
                            pointRadius: 4,
                            pointHoverRadius: 6,
                            fill: true,
                            tension: 0.4
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            backgroundColor: 'rgba(17, 24, 39, 0.9)',
                            titleFont: { family: "'Outfit', sans-serif", size: 13 },
                            bodyFont: { family: "'Outfit', sans-serif", size: 14 },
                            padding: 12,
                            cornerRadius: 8,
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: { precision: 0, font: { family: "'Outfit', sans-serif" } },
                            grid: { color: '#f3f4f6', borderDash: [5, 5] },
                            border: { display: false }
                        },
                        x: {
                            grid: { display: false },
                            ticks: { font: { family: "'Outfit', sans-serif" } },
                            border: { display: false }
                        }
                    },
                    interaction: { intersect: false, mode: 'index' },
                }
            });

            // ===== SKD Distribution Doughnut =====
            const ctxSkd = document.getElementById('skdDistributionChart').getContext('2d');
            new window.Chart(ctxSkd, {
                type: 'doughnut',
                data: {
                    labels: {!! json_encode($skdDistribution['labels']) !!},
                    datasets: [{
                        data: {!! json_encode($skdDistribution['data']) !!},
                        backgroundColor: {!! json_encode($skdDistribution['colors']) !!},
                        borderWidth: 0,
                        hoverOffset: 4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '75%',
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                padding: 20,
                                usePointStyle: true,
                                pointStyle: 'circle',
                                font: { family: "'Outfit', sans-serif", size: 12 }
                            }
                        },
                        tooltip: {
                            backgroundColor: 'rgba(17, 24, 39, 0.9)',
                            titleFont: { family: "'Outfit', sans-serif", size: 13 },
                            bodyFont: { family: "'Outfit', sans-serif", size: 14 },
                            padding: 12,
                            cornerRadius: 8,
                        }
                    }
                }
            });
        }
    });
</script>
@endsection
