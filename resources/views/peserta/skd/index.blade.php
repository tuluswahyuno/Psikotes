@extends('layouts.main')
@section('title', 'Simulasi SKD CPNS')
@section('subtitle', 'Daftar paket simulasi Seleksi Kompetensi Dasar berbasis CAT')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    @forelse($packages as $package)
        @php
            $sessionId = null;
            foreach($activeSessions as $id => $pkgId) {
                if($pkgId == $package->id) $sessionId = $id;
            }
        @endphp
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden flex flex-col group hover:shadow-md transition-shadow">
            <div class="h-2 bg-gradient-to-r 
                @if(in_array($package->id, $completedPackageIds)) from-emerald-400 to-teal-500 
                @elseif($sessionId) from-amber-400 to-orange-500
                @else from-indigo-500 to-purple-600 @endif"></div>
            
            <div class="p-6 flex-1">
                <div class="flex justify-between items-start mb-4">
                    <h3 class="text-lg font-black text-gray-900 leading-tight group-hover:text-indigo-600 transition-colors">{{ $package->title }}</h3>
                    @if(in_array($package->id, $completedPackageIds))
                        <span class="w-6 h-6 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                        </span>
                    @endif
                </div>
                
                <p class="text-sm text-gray-500 mb-6 line-clamp-2 leading-relaxed">{{ $package->description ?: 'Simulasi lengkap TWK, TIU, dan TKP sesuai standar BKN.' }}</p>
                
                <div class="space-y-3 mb-8">
                    <div class="flex items-center gap-3 text-xs font-bold text-gray-400 uppercase tracking-widest">
                        <svg class="w-4 h-4 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        {{ $package->duration_minutes }} Menit Durasi
                    </div>
                    <div class="flex items-center gap-3 text-xs font-bold text-gray-400 uppercase tracking-widest">
                        <svg class="w-4 h-4 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        {{ $package->total_questions }} Butir Soal
                    </div>
                </div>

                <div class="flex gap-2 mb-6">
                    @foreach($package->packageTests as $pt)
                    <div class="px-2 py-1 rounded bg-gray-50 border border-gray-100 text-[9px] font-black text-gray-500 uppercase">{{ $pt->sub_test_type }}</div>
                    @endforeach
                </div>
            </div>

            <div class="p-6 bg-gray-50/50 border-t border-gray-50">
                @if($sessionId)
                    <a href="{{ route('peserta.skd.attempt', [$package, $sessionId]) }}" class="btn-primary w-full justify-center bg-amber-500 hover:bg-amber-600 border-amber-600 shadow-amber-200">
                        Lanjutkan Tes
                    </a>
                @elseif(in_array($package->id, $completedPackageIds))
                    <div class="flex gap-2">
                        <a href="{{ route('peserta.skd.show', $package) }}" class="btn-secondary flex-1 justify-center text-[10px] py-2 px-0">Mulai Lagi</a>
                        <a href="{{ route('peserta.skd.results') }}" class="btn-primary flex-1 justify-center text-[10px] py-2 px-0">Hasil Saya</a>
                    </div>
                @else
                    <a href="{{ route('peserta.skd.show', $package) }}" class="btn-primary w-full justify-center">
                        Ikuti Simulasi
                    </a>
                @endif
            </div>
        </div>
    @empty
        <div class="col-span-full py-20 text-center bg-white rounded-3xl border border-dashed border-gray-200">
            <svg class="w-16 h-16 text-gray-200 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
            <p class="text-gray-500 font-medium">Belum ada simulasi SKD yang tersedia saat ini.</p>
        </div>
    @endforelse
</div>
@endsection
