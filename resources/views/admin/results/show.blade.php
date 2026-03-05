@extends('layouts.main')
@section('title', 'Detail Hasil')
@section('subtitle', $result->user->name . ' — ' . $result->test->title)

@section('content')
<!-- Score Card -->
<div class="bg-gradient-to-r from-indigo-600 to-purple-700 rounded-2xl p-8 text-white mb-6">
    <div class="grid grid-cols-3 gap-6">
        <div>
            <p class="text-indigo-200 text-sm mb-1">Peserta</p>
            <p class="text-xl font-bold">{{ $result->user->name }}</p>
            <p class="text-indigo-200 text-sm">{{ $result->user->email }}</p>
        </div>
        <div class="text-center">
            <p class="text-indigo-200 text-sm mb-1">Skor</p>
            <p class="text-5xl font-bold">{{ $result->total_score }}</p>
            <p class="text-indigo-200 text-sm mt-1">{{ $result->interpretation }}</p>
        </div>
        <div class="text-right">
            <p class="text-indigo-200 text-sm mb-1">Waktu Pengerjaan</p>
            <p class="text-xl font-bold">
                @if($result->session->finished_at && $result->session->started_at)
                    {{ $result->session->started_at->diffInMinutes($result->session->finished_at) }} menit
                @else
                    -
                @endif
            </p>
            <p class="text-indigo-200 text-sm">{{ $result->created_at->format('d M Y H:i') }}</p>
        </div>
    </div>
</div>

<!-- Answer Details -->
<div class="space-y-4">
    <h3 class="text-lg font-bold text-gray-900">Detail Jawaban</h3>
    @foreach($result->session->answers as $answer)
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
        <div class="flex items-start gap-3">
            <span class="flex-shrink-0 w-8 h-8 {{ $answer->option && $answer->option->is_correct ? 'bg-emerald-100 text-emerald-700' : 'bg-red-100 text-red-700' }} rounded-lg flex items-center justify-center text-sm font-bold">
                {{ $answer->question->order }}
            </span>
            <div class="flex-1">
                <p class="text-sm font-medium text-gray-900 mb-2">{{ $answer->question->question_text }}</p>
                <p class="text-sm mb-1">
                    <span class="text-gray-500">Jawaban Peserta: </span>
                    <span class="font-medium {{ $answer->option && $answer->option->is_correct ? 'text-emerald-600' : 'text-red-600' }}">
                        {{ $answer->option ? $answer->option->option_text : 'Tidak dijawab' }}
                    </span>
                </p>
                @if(!$answer->option || !$answer->option->is_correct)
                <p class="text-sm">
                    <span class="text-gray-500">Kunci Jawaban: </span>
                    <span class="font-medium text-emerald-600">
                        {{ $answer->question->options->where('is_correct', true)->first()->option_text ?? '-' }}
                    </span>
                </p>
                @endif
            </div>
            <span class="text-sm font-bold {{ $answer->score > 0 ? 'text-emerald-600' : 'text-red-500' }}">
                +{{ $answer->score }}
            </span>
        </div>
    </div>
    @endforeach
</div>
@endsection
