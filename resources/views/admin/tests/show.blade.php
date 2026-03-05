@extends('layouts.main')
@section('title', $test->title)
@section('subtitle', 'Detail tes dan daftar soal')

@section('actions')
    <a href="{{ route('admin.questions.create', $test) }}" class="btn-primary">
        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        Tambah Soal
    </a>
    <a href="{{ route('admin.tests.edit', $test) }}" class="btn-secondary">Edit Tes</a>
@endsection

@section('content')
<!-- Test Info -->
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mb-6">
    <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
        <div>
            <p class="text-xs text-gray-500 mb-1">Kategori</p>
            <p class="text-sm font-semibold text-gray-900">{{ $test->category ?: '-' }}</p>
        </div>
        <div>
            <p class="text-xs text-gray-500 mb-1">Tipe</p>
            <p class="text-sm font-semibold text-gray-900">{{ ucfirst(str_replace('_', ' ', $test->type)) }}</p>
        </div>
        <div>
            <p class="text-xs text-gray-500 mb-1">Durasi</p>
            <p class="text-sm font-semibold text-gray-900">{{ $test->duration_minutes }} menit</p>
        </div>
        <div>
            <p class="text-xs text-gray-500 mb-1">Status</p>
            @if($test->is_active)
                <span class="inline-flex items-center gap-1 text-xs font-medium text-emerald-700 bg-emerald-50 px-2.5 py-1 rounded-full">
                    <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full"></span> Aktif
                </span>
            @else
                <span class="inline-flex items-center gap-1 text-xs font-medium text-gray-500 bg-gray-100 px-2.5 py-1 rounded-full">Nonaktif</span>
            @endif
        </div>
    </div>
    @if($test->description)
        <p class="text-sm text-gray-600 mt-4 pt-4 border-t border-gray-100">{{ $test->description }}</p>
    @endif
</div>

<!-- Questions -->
<div class="space-y-4">
    <h3 class="text-lg font-bold text-gray-900">Daftar Soal ({{ $test->questions->count() }})</h3>

    @forelse($test->questions as $question)
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <div class="flex items-start justify-between mb-4">
            <div class="flex items-start gap-3 flex-1">
                <span class="flex-shrink-0 w-8 h-8 bg-indigo-100 text-indigo-700 rounded-lg flex items-center justify-center text-sm font-bold">{{ $question->order }}</span>
                <div class="flex-1">
                    <p class="text-sm font-medium text-gray-900">{{ $question->question_text }}</p>
                    <p class="text-xs text-gray-500 mt-1">Bobot: {{ $question->weight }} · Tipe: {{ $question->type }}</p>
                </div>
            </div>
            <div class="flex items-center gap-1 ml-4">
                <a href="{{ route('admin.questions.edit', [$test, $question]) }}" class="text-amber-600 hover:bg-amber-50 p-1.5 rounded-lg transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                </a>
                <form action="{{ route('admin.questions.destroy', [$test, $question]) }}" method="POST" onsubmit="return confirm('Hapus soal ini?')">
                    @csrf @method('DELETE')
                    <button class="text-red-500 hover:bg-red-50 p-1.5 rounded-lg transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                    </button>
                </form>
            </div>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-2 ml-11">
            @foreach($question->options as $option)
            <div class="flex items-center gap-2 px-3 py-2 rounded-lg {{ $option->is_correct ? 'bg-emerald-50 border border-emerald-200' : 'bg-gray-50 border border-gray-100' }}">
                <span class="text-xs {{ $option->is_correct ? 'text-emerald-600' : 'text-gray-400' }}">
                    @if($option->is_correct)
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                    @else
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/></svg>
                    @endif
                </span>
                <span class="text-sm {{ $option->is_correct ? 'text-emerald-800 font-medium' : 'text-gray-600' }}">{{ $option->option_text }}</span>
                <span class="text-xs text-gray-400 ml-auto">skor: {{ $option->score }}</span>
            </div>
            @endforeach
        </div>
    </div>
    @empty
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-12 text-center">
        <svg class="w-12 h-12 mx-auto mb-3 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        <p class="text-sm text-gray-500 mb-3">Belum ada soal untuk tes ini</p>
        <a href="{{ route('admin.questions.create', $test) }}" class="btn-primary">Tambah Soal Pertama</a>
    </div>
    @endforelse
</div>
@endsection
