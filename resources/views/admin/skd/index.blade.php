@extends('layouts.main')
@section('title', 'Manajemen Paket SKD')
@section('subtitle', 'Kelola paket simulasi SKD CPNS (TWK, TIU, TKP)')

@section('actions')
<a href="{{ route('admin.skd-packages.create') }}" class="btn-primary">
    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
    Buat Paket Baru
</a>
@endsection

@section('content')
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <table class="w-full text-left border-collapse">
        <thead>
            <tr class="bg-gray-50/50 border-b border-gray-100">
                <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Nama Paket</th>
                <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Sub-Tes (TWK / TIU / TKP)</th>
                <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider text-center">Durasi</th>
                <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider text-center">Status</th>
                <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider text-center">Peserta</th>
                <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider text-right">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse($packages as $package)
            <tr class="hover:bg-gray-50/50 transition-colors">
                <td class="px-6 py-4">
                    <p class="text-sm font-bold text-gray-900">{{ $package->title }}</p>
                    <p class="text-xs text-gray-500 truncate max-w-xs">{{ $package->description }}</p>
                </td>
                <td class="px-6 py-4">
                    <div class="flex gap-1.5">
                        @foreach($package->packageTests as $pt)
                        <span class="text-[10px] font-bold px-2 py-0.5 rounded uppercase 
                            @if($pt->sub_test_type == 'twk') bg-blue-50 text-blue-600 
                            @elseif($pt->sub_test_type == 'tiu') bg-amber-50 text-amber-600 
                            @else bg-purple-50 text-purple-600 @endif">
                            {{ $pt->sub_test_type }}
                        </span>
                        @endforeach
                    </div>
                </td>
                <td class="px-6 py-4 text-center">
                    <span class="text-sm text-gray-600">{{ $package->duration_minutes }}m</span>
                </td>
                <td class="px-6 py-4 text-center">
                    <span class="px-2.5 py-1 text-xs font-medium rounded-full {{ $package->is_active ? 'bg-emerald-50 text-emerald-700' : 'bg-red-50 text-red-700' }}">
                        {{ $package->is_active ? 'Aktif' : 'Nonaktif' }}
                    </span>
                </td>
                <td class="px-6 py-4 text-center text-sm text-gray-600">
                    {{ $package->results_count }}
                </td>
                <td class="px-6 py-4 text-right">
                    <div class="flex items-center justify-end gap-2">
                        <a href="{{ route('admin.skd-packages.show', $package) }}" class="p-2 text-gray-400 hover:text-indigo-600 transition-colors" title="Detail">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                        </a>
                        <a href="{{ route('admin.skd-packages.edit', $package) }}" class="p-2 text-gray-400 hover:text-amber-600 transition-colors" title="Edit">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-5M16.5 3.5a2.121 2.121 0 113 3L12 15l-4 1 1-4 8.5-8.5z"/></svg>
                        </a>
                        <form action="{{ route('admin.skd-packages.destroy', $package) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus paket ini?')" class="inline">
                            @csrf @method('DELETE')
                            <button type="submit" class="p-2 text-gray-400 hover:text-red-600 transition-colors" title="Hapus">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="px-6 py-12 text-center">
                    <div class="flex flex-col items-center">
                        <svg class="w-12 h-12 text-gray-200 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/></svg>
                        <p class="text-sm text-gray-500">Belum ada paket SKD yang dibuat.</p>
                        <a href="{{ route('admin.skd-packages.create') }}" class="mt-4 text-indigo-600 text-sm font-bold hover:underline">Mulai buat paket baru &rarr;</a>
                    </div>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
    @if($packages->hasPages())
    <div class="px-6 py-4 bg-gray-50/50 border-t border-gray-100">
        {{ $packages->links() }}
    </div>
    @endif
</div>
@endsection
