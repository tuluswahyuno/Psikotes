@extends('layouts.main')
@section('title', $test->title)
@section('subtitle', 'Informasi tes sebelum memulai')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <!-- Header -->
        <div class="bg-gradient-to-r from-indigo-600 to-purple-700 px-8 py-8 text-white">
            <h2 class="text-2xl font-bold mb-2">{{ $test->title }}</h2>
            <p class="text-indigo-200">{{ $test->category }}</p>
        </div>

        <!-- Details -->
        <div class="p-8">
            @if($test->description)
                <div class="mb-6">
                    <h3 class="text-sm font-semibold text-gray-900 mb-2">Deskripsi</h3>
                    <p class="text-sm text-gray-600 leading-relaxed">{{ $test->description }}</p>
                </div>
            @endif

            <div class="grid grid-cols-3 gap-4 mb-6">
                <div class="bg-gray-50 rounded-xl p-4 text-center">
                    <p class="text-2xl font-bold text-indigo-600">{{ $test->questions()->count() }}</p>
                    <p class="text-xs text-gray-500 mt-1">Soal</p>
                </div>
                <div class="bg-gray-50 rounded-xl p-4 text-center">
                    <p class="text-2xl font-bold text-indigo-600">{{ $test->duration_minutes }}</p>
                    <p class="text-xs text-gray-500 mt-1">Menit</p>
                </div>
                <div class="bg-gray-50 rounded-xl p-4 text-center">
                    <p class="text-2xl font-bold text-indigo-600">{{ ucfirst(str_replace('_', ' ', $test->type)) }}</p>
                    <p class="text-xs text-gray-500 mt-1">Tipe</p>
                </div>
            </div>

            <div class="bg-amber-50 border border-amber-200 rounded-xl p-4 mb-6">
                <div class="flex gap-3">
                    <svg class="w-5 h-5 text-amber-500 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                    <div>
                        <p class="text-sm font-semibold text-amber-800 mb-1">Perhatian</p>
                        <ul class="text-xs text-amber-700 space-y-1 list-disc list-inside">
                            <li>Setelah memulai, timer akan berjalan dan tidak bisa dihentikan</li>
                            <li>Jawaban disimpan otomatis setiap kali Anda memilih opsi</li>
                            <li>Tes akan otomatis ter-submit saat waktu habis</li>
                            <li>Pastikan koneksi internet Anda stabil</li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="flex items-center gap-3">
                @if($existingSession)
                    <a href="{{ route('peserta.tests.attempt', [$test, $existingSession]) }}" class="btn-primary flex-1 justify-center">
                        Lanjutkan Tes
                    </a>
                @else
                    <form action="{{ route('peserta.tests.start', $test) }}" method="POST" class="flex-1" id="startTestForm">
                        @csrf
                        <button type="button" class="btn-primary w-full justify-center" id="btnMulaiTes">
                            Mulai Tes Sekarang
                        </button>
                    </form>
                @endif
                <a href="{{ route('peserta.dashboard') }}" class="btn-secondary flex-1 justify-center">Kembali</a>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const btnMulaiTes = document.getElementById('btnMulaiTes');
        if (btnMulaiTes) {
            btnMulaiTes.addEventListener('click', function(e) {
                e.preventDefault();
                Swal.fire({
                    title: 'Mulai Tes Sekarang?',
                    text: 'Setelah dimulai, timer akan berjalan dan tidak bisa dihentikan!',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#4f46e5',
                    cancelButtonColor: '#ef4444',
                    confirmButtonText: 'Ya, Mulai Tes!',
                    cancelButtonText: 'Batal',
                    customClass: {
                        popup: 'rounded-2xl shadow-xl border border-gray-100',
                        title: 'text-xl font-bold text-gray-900',
                        htmlContainer: 'text-sm text-gray-600',
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        document.getElementById('startTestForm').submit();
                    }
                })
            });
        }
    });
</script>
@endsection
