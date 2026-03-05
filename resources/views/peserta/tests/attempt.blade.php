@extends('layouts.main')
@section('title', $test->title)

@section('content')
<div class="max-w-4xl mx-auto">
    <!-- Timer Bar -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4 mb-6 sticky top-20 z-10">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <span class="text-sm font-semibold text-gray-700">Sisa Waktu:</span>
                <div id="timer" class="text-2xl font-bold text-indigo-600 font-mono tabular-nums">--:--</div>
            </div>
            <div class="flex items-center gap-4">
                <span class="text-sm text-gray-500" id="answeredCount">0/{{ $questions->count() }} terjawab</span>
                <form action="{{ route('peserta.sessions.submit', $session) }}" method="POST" id="submitForm">
                    @csrf
                    <button type="button" class="btn-primary btn-submit-test">
                        Submit Tes
                    </button>
                </form>
            </div>
        </div>
        <div class="mt-3 w-full bg-gray-200 rounded-full h-2">
            <div id="progressBar" class="bg-gradient-to-r from-indigo-500 to-purple-600 h-2 rounded-full transition-all duration-500" style="width: 0%"></div>
        </div>
    </div>

    <!-- Questions -->
    <div class="space-y-6" id="questionsContainer">
        @foreach($questions as $index => $question)
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 question-card" data-question-id="{{ $question->id }}" id="question-{{ $question->id }}">
            <div class="flex items-start gap-4 mb-4">
                <span class="flex-shrink-0 w-10 h-10 bg-indigo-100 text-indigo-700 rounded-xl flex items-center justify-center text-sm font-bold">
                    {{ $index + 1 }}
                </span>
                <p class="text-sm font-medium text-gray-900 pt-2 flex-1">{{ $question->question_text }}</p>
            </div>

            <div class="ml-14 space-y-2">
                @foreach($question->options as $option)
                <label class="option-label flex items-center gap-3 p-3.5 rounded-xl border-2 cursor-pointer transition-all duration-200
                    {{ isset($existingAnswers[$question->id]) && $existingAnswers[$question->id] == $option->id ? 'border-indigo-500 bg-indigo-50' : 'border-gray-100 hover:border-indigo-200 hover:bg-indigo-50/50' }}"
                    data-question="{{ $question->id }}" data-option="{{ $option->id }}">
                    <input type="radio" name="question_{{ $question->id }}" value="{{ $option->id }}"
                        {{ isset($existingAnswers[$question->id]) && $existingAnswers[$question->id] == $option->id ? 'checked' : '' }}
                        class="w-4 h-4 text-indigo-600 border-gray-300 focus:ring-indigo-500"
                        onchange="saveAnswer({{ $question->id }}, {{ $option->id }})">
                    <span class="text-sm text-gray-700">{{ $option->option_text }}</span>
                </label>
                @endforeach
            </div>
        </div>
        @endforeach
    </div>

    <!-- Bottom Submit -->
    <div class="mt-8 text-center">
        <form action="{{ route('peserta.sessions.submit', $session) }}" method="POST">
            @csrf
            <button type="button" class="btn-primary px-12 py-3 text-base btn-submit-test">
                Submit Tes
            </button>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Timer
    const remainingSecondsObj = {!! json_encode($session->remaining_time) !!};
    let remainingSeconds = parseInt(remainingSecondsObj);
    const timerEl = document.getElementById('timer');
    const sessionId = {{ $session->id }};

    function updateTimer() {
        if (remainingSeconds <= 0) {
            timerEl.textContent = '00:00';
            timerEl.classList.add('text-red-600');
            // Auto submit
            document.getElementById('submitForm').submit();
            return;
        }
        const min = Math.floor(remainingSeconds / 60);
        const sec = remainingSeconds % 60;
        timerEl.textContent = `${String(min).padStart(2, '0')}:${String(sec).padStart(2, '0')}`;

        if (remainingSeconds <= 300) {
            timerEl.classList.remove('text-indigo-600');
            timerEl.classList.add('text-red-600');
        }

        remainingSeconds--;
    }

    updateTimer();
    setInterval(updateTimer, 1000);

    // Save answer via AJAX
    let answeredDict = {!! json_encode($existingAnswers) !!} || {};
    let answeredQuestions = new Set(Object.keys(answeredDict));

    function saveAnswer(questionId, optionId) {
        fetch(`/peserta/sessions/${sessionId}/answer`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json',
            },
            body: JSON.stringify({ question_id: questionId, option_id: optionId })
        })
        .then(res => res.json())
        .then(data => {
            if (data.redirect) {
                window.location.href = data.redirect;
                return;
            }
            answeredQuestions.add(String(questionId));
            updateProgress();

            // Visual feedback
            const card = document.getElementById(`question-${questionId}`);
            card.querySelectorAll('.option-label').forEach(label => {
                label.classList.remove('border-indigo-500', 'bg-indigo-50');
                label.classList.add('border-gray-100');
            });
            const selected = card.querySelector(`[data-option="${optionId}"]`);
            if (selected) {
                selected.classList.remove('border-gray-100');
                selected.classList.add('border-indigo-500', 'bg-indigo-50');
            }
        })
        .catch(err => console.error('Save failed:', err));
    }

    function updateProgress() {
        const total = {{ $questions->count() }};
        const answered = answeredQuestions.size;
        document.getElementById('answeredCount').textContent = `${answered}/${total} terjawab`;
        document.getElementById('progressBar').style.width = `${(answered / total) * 100}%`;
    }
    
    updateProgress();

    // SweetAlert2 for Submit
    const submitButtons = document.querySelectorAll('.btn-submit-test');
    submitButtons.forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            Swal.fire({
                title: 'Selesai Mengerjakan?',
                text: 'Pastikan semua soal telah terjawab. Jawaban Anda akan dinilai!',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#4f46e5',
                cancelButtonColor: '#ef4444',
                confirmButtonText: 'Ya, Kumpulkan!',
                cancelButtonText: 'Kembali',
                customClass: {
                    popup: 'rounded-2xl shadow-xl border border-gray-100',
                    title: 'text-xl font-bold text-gray-900',
                    htmlContainer: 'text-sm text-gray-600',
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    btn.closest('form').submit();
                }
            })
        });
    });
</script>
@endsection
