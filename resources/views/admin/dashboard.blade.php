@extends('layouts.main')
@section('title', 'Dashboard Admin')
@section('subtitle', 'Ringkasan status sistem psikotes')

@section('content')
<!-- Stats Cards -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <div class="stat-card">
        <div class="flex items-center justify-between mb-4">
            <div class="w-12 h-12 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-xl flex items-center justify-center">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
            </div>
            <span class="text-xs font-medium text-indigo-600 bg-indigo-50 px-2 py-1 rounded-lg">Total</span>
        </div>
        <p class="text-3xl font-bold text-gray-900">{{ $stats['total_tests'] }}</p>
        <p class="text-sm text-gray-500 mt-1">Total Tes</p>
    </div>
    <div class="stat-card">
        <div class="flex items-center justify-between mb-4">
            <div class="w-12 h-12 bg-gradient-to-br from-emerald-500 to-teal-600 rounded-xl flex items-center justify-center">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <span class="text-xs font-medium text-emerald-600 bg-emerald-50 px-2 py-1 rounded-lg">Aktif</span>
        </div>
        <p class="text-3xl font-bold text-gray-900">{{ $stats['active_tests'] }}</p>
        <p class="text-sm text-gray-500 mt-1">Tes Aktif</p>
    </div>
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
    <div class="stat-card">
        <div class="flex items-center justify-between mb-4">
            <div class="w-12 h-12 bg-gradient-to-br from-rose-500 to-pink-600 rounded-xl flex items-center justify-center">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2z"/></svg>
            </div>
            <span class="text-xs font-medium text-rose-600 bg-rose-50 px-2 py-1 rounded-lg">Selesai</span>
        </div>
        <p class="text-3xl font-bold text-gray-900">{{ $stats['total_sessions'] }}</p>
        <p class="text-sm text-gray-500 mt-1">Sesi Selesai</p>
    </div>
</div>

<!-- Charts Section -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
    <div class="table-container lg:col-span-2 p-6">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-lg font-bold text-gray-900">Aktivitas Ujian (7 Hari Terakhir)</h3>
        </div>
        <div class="relative h-72 w-full">
            <canvas id="activityChart"></canvas>
        </div>
    </div>
    <div class="table-container p-6">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-lg font-bold text-gray-900">Distribusi Nilai</h3>
        </div>
        <div class="relative h-72 w-full flex justify-center">
            <canvas id="scoreDistributionChart"></canvas>
        </div>
    </div>
</div>

<!-- Recent Results -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <div class="table-container">
        <div class="px-6 py-4 border-b border-gray-100">
            <h3 class="text-lg font-bold text-gray-900">Hasil Terbaru</h3>
        </div>
        <div class="divide-y divide-gray-50">
            @forelse($stats['recent_results'] as $result)
                <div class="px-6 py-4 flex items-center justify-between hover:bg-gray-50 transition-colors">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 bg-gradient-to-br from-indigo-400 to-purple-500 rounded-lg flex items-center justify-center text-white text-xs font-bold">
                            {{ strtoupper(substr($result->user->name, 0, 1)) }}
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-gray-900">{{ $result->user->name }}</p>
                            <p class="text-xs text-gray-500">{{ $result->test->title }}</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="text-sm font-bold {{ $result->total_score >= 75 ? 'text-emerald-600' : ($result->total_score >= 60 ? 'text-amber-600' : 'text-red-600') }}">{{ $result->total_score }}%</p>
                        <p class="text-xs text-gray-500">{{ $result->interpretation }}</p>
                    </div>
                </div>
            @empty
                <div class="px-6 py-8 text-center text-gray-400 text-sm">Belum ada hasil tes</div>
            @endforelse
        </div>
    </div>

    <div class="table-container">
        <div class="px-6 py-4 border-b border-gray-100">
            <h3 class="text-lg font-bold text-gray-900">Peserta Terbaru</h3>
        </div>
        <div class="divide-y divide-gray-50">
            @forelse($stats['recent_peserta'] as $user)
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
            const ctxActivity = document.getElementById('activityChart').getContext('2d');
            const activityGradient = ctxActivity.createLinearGradient(0, 0, 0, 400);
            activityGradient.addColorStop(0, 'rgba(99, 102, 241, 0.5)'); // Indigo 500
            activityGradient.addColorStop(1, 'rgba(99, 102, 241, 0.0)');

            new window.Chart(ctxActivity, {
                type: 'line',
                data: {
                    labels: {!! json_encode($activityChart['labels']) !!},
                    datasets: [{
                        label: 'Sesi Ujian Selesai',
                        data: {!! json_encode($activityChart['data']) !!},
                        borderColor: '#6366f1', // Indigo 500
                        backgroundColor: activityGradient,
                        borderWidth: 3,
                        pointBackgroundColor: '#ffffff',
                        pointBorderColor: '#6366f1',
                        pointBorderWidth: 2,
                        pointRadius: 4,
                        pointHoverRadius: 6,
                        fill: true,
                        tension: 0.4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            backgroundColor: 'rgba(17, 24, 39, 0.9)', // Gray 900
                            titleFont: { family: "'Outfit', sans-serif", size: 13 },
                            bodyFont: { family: "'Outfit', sans-serif", size: 14 },
                            padding: 12,
                            cornerRadius: 8,
                            displayColors: false,
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

            const ctxScore = document.getElementById('scoreDistributionChart').getContext('2d');
            new window.Chart(ctxScore, {
                type: 'doughnut',
                data: {
                    labels: {!! json_encode($scoreDistribution['labels']) !!},
                    datasets: [{
                        data: {!! json_encode($scoreDistribution['data']) !!},
                        backgroundColor: {!! json_encode($scoreDistribution['colors']) !!},
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
