@extends('layouts.main')
@section('title', 'Hasil Tes')
@section('subtitle', $result->test->title)

@section('content')
<div class="max-w-3xl mx-auto">
    <!-- Score Card -->
    <div class="bg-gradient-to-r from-indigo-600 to-purple-700 rounded-2xl p-8 text-white mb-6">
        <div class="text-center">
            <p class="text-indigo-200 text-sm mb-2">Skor Anda</p>
            <p class="text-6xl font-bold mb-2">{{ $result->total_score }}</p>
            <span class="inline-block bg-white/20 text-white text-sm font-semibold px-4 py-1.5 rounded-full backdrop-blur-sm">
                {{ $result->interpretation }}
            </span>
            <div class="mt-4 flex items-center justify-center gap-6 text-indigo-200 text-sm">
                <span>{{ $result->test->title }}</span>
                <span>•</span>
                <span>{{ $result->created_at->format('d M Y H:i') }}</span>
                @if($result->session->finished_at && $result->session->started_at)
                <span>•</span>
                <span>{{ $result->session->started_at->diffInMinutes($result->session->finished_at) }} menit</span>
                @endif
            </div>
        </div>
    </div>

    <!-- Answer Details -->
    <h3 class="text-lg font-bold text-gray-900 mb-4">Detail Jawaban</h3>
    <div class="space-y-3">
        @foreach($result->session->answers as $answer)
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 flex items-start gap-3">
            <span class="flex-shrink-0 w-8 h-8 {{ $answer->option && $answer->option->is_correct ? 'bg-emerald-100 text-emerald-700' : 'bg-red-100 text-red-700' }} rounded-lg flex items-center justify-center text-xs font-bold">
                @if($answer->option && $answer->option->is_correct)
                    ✓
                @else
                    ✗
                @endif
            </span>
            <div class="flex-1 min-w-0">
                <p class="text-sm text-gray-900 font-medium">{{ $answer->question->question_text }}</p>
                <p class="text-xs text-gray-500 mt-1">
                    Jawaban Anda:
                    <span class="{{ $answer->option && $answer->option->is_correct ? 'text-emerald-600' : 'text-red-600' }} font-semibold">
                        {{ $answer->option ? $answer->option->option_text : 'Tidak dijawab' }}
                    </span>
                </p>
            </div>
            <span class="text-sm font-bold {{ $answer->score > 0 ? 'text-emerald-600' : 'text-red-500' }}">+{{ $answer->score }}</span>
        </div>
        @endforeach
    </div>

    <div class="mt-6 text-center">
        <a href="{{ route('peserta.results.index') }}" class="btn-secondary">Kembali ke Riwayat</a>
    </div>
</div>
@endsection
