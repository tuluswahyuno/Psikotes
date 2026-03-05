@extends('layouts.main')
@section('title', 'Dashboard Peserta')
@section('subtitle', 'Selamat datang, ' . auth()->user()->name)

@section('content')
<!-- Stats -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <div class="stat-card">
        <div class="flex items-center justify-between mb-4">
            <div class="w-12 h-12 bg-gradient-to-br from-amber-500 to-orange-600 rounded-xl flex items-center justify-center">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
        </div>
        <p class="text-3xl font-bold text-gray-900">{{ $pendingCount }}</p>
        <p class="text-sm text-gray-500 mt-1">Tes Menunggu</p>
    </div>
    <div class="stat-card">
        <div class="flex items-center justify-between mb-4">
            <div class="w-12 h-12 bg-gradient-to-br from-emerald-500 to-teal-600 rounded-xl flex items-center justify-center">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
        </div>
        <p class="text-3xl font-bold text-gray-900">{{ $completedCount }}</p>
        <p class="text-sm text-gray-500 mt-1">Tes Selesai</p>
    </div>
    <div class="stat-card">
        <div class="flex items-center justify-between mb-4">
            <div class="w-12 h-12 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-xl flex items-center justify-center">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"/></svg>
            </div>
        </div>
        <p class="text-3xl font-bold text-gray-900">{{ $pendingCount + $completedCount }}</p>
        <p class="text-sm text-gray-500 mt-1">Total Tes</p>
    </div>
</div>

<!-- Pending Tests -->
<h3 class="text-lg font-bold text-gray-900 mb-4">Tes yang Harus Dikerjakan</h3>
<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
    @forelse($assignments as $assignment)
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 hover:shadow-md transition-shadow">
        <div class="flex items-start justify-between mb-4">
            <div>
                <h4 class="text-lg font-bold text-gray-900">{{ $assignment->test->title }}</h4>
                <p class="text-sm text-gray-500 mt-1">{{ $assignment->test->category }}</p>
            </div>
            <span class="text-xs font-medium bg-amber-50 text-amber-700 px-2.5 py-1 rounded-full">Pending</span>
        </div>
        <p class="text-sm text-gray-600 mb-4 line-clamp-2">{{ $assignment->test->description }}</p>
        <div class="flex items-center gap-4 text-xs text-gray-500 mb-4">
            <span class="flex items-center gap-1">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                {{ $assignment->test->duration_minutes }} menit
            </span>
            <span class="flex items-center gap-1">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                {{ $assignment->test->questions()->count() }} soal
            </span>
            @if($assignment->deadline)
            <span class="flex items-center gap-1 {{ $assignment->deadline->isPast() ? 'text-red-500' : '' }}">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                Deadline: {{ $assignment->deadline->format('d M Y') }}
            </span>
            @endif
        </div>
        <a href="{{ route('peserta.tests.show', $assignment->test) }}" class="btn-primary w-full justify-center">
            Mulai Tes
        </a>
    </div>
    @empty
    <div class="col-span-2 bg-white rounded-2xl shadow-sm border border-gray-100 p-12 text-center">
        <svg class="w-12 h-12 mx-auto mb-3 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        <p class="text-sm text-gray-500">Tidak ada tes yang perlu dikerjakan saat ini.</p>
    </div>
    @endforelse
</div>
@endsection
