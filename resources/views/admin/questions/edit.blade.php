@extends('layouts.main')
@section('title', 'Edit Soal')
@section('subtitle', $test->title)

@section('content')
<div class="max-w-2xl">
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8">
        <form action="{{ route('admin.questions.update', [$test, $question]) }}" method="POST">
            @csrf @method('PUT')
            <div class="space-y-6">
                <div>
                    <label class="form-label" for="question_text">Pertanyaan <span class="text-red-500">*</span></label>
                    <textarea name="question_text" id="question_text" rows="3" class="form-input" required>{{ old('question_text', $question->question_text) }}</textarea>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="form-label" for="type">Tipe</label>
                        <select name="type" id="type" class="form-input">
                            <option value="choice" {{ $question->type == 'choice' ? 'selected' : '' }}>Pilihan</option>
                            <option value="scale" {{ $question->type == 'scale' ? 'selected' : '' }}>Skala</option>
                        </select>
                    </div>
                    <div>
                        <label class="form-label" for="weight">Bobot</label>
                        <input type="number" name="weight" id="weight" value="{{ old('weight', $question->weight) }}" class="form-input" min="1">
                    </div>
                </div>

                <div>
                    <div class="flex items-center justify-between mb-3">
                        <label class="form-label mb-0">Opsi Jawaban</label>
                        <button type="button" onclick="addOption()" class="text-sm text-indigo-600 hover:text-indigo-800 font-medium">+ Tambah Opsi</button>
                    </div>
                    <div id="optionsContainer" class="space-y-3">
                        @foreach($question->options as $i => $option)
                        <div class="option-row flex items-center gap-3 p-3 bg-gray-50 rounded-xl border border-gray-100">
                            <input type="hidden" name="options[{{ $i }}][id]" value="{{ $option->id }}">
                            <input type="text" name="options[{{ $i }}][option_text]" value="{{ $option->option_text }}" class="form-input flex-1" required>
                            <input type="number" name="options[{{ $i }}][score]" value="{{ $option->score }}" class="form-input w-20" required>
                            <label class="flex items-center gap-1 text-xs text-gray-500 whitespace-nowrap cursor-pointer">
                                <input type="checkbox" name="options[{{ $i }}][is_correct]" value="1" {{ $option->is_correct ? 'checked' : '' }} class="w-4 h-4 rounded border-gray-300 text-emerald-600">
                                Benar
                            </label>
                            <button type="button" onclick="this.closest('.option-row').remove()" class="text-red-400 hover:text-red-600 p-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                            </button>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="flex items-center gap-3 mt-8 pt-6 border-t border-gray-100">
                <button type="submit" class="btn-primary">Simpan Perubahan</button>
                <a href="{{ route('admin.tests.show', $test) }}" class="btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>

@section('scripts')
<script>
let optionIndex = {{ $question->options->count() }};
function addOption() {
    const container = document.getElementById('optionsContainer');
    const html = `
        <div class="option-row flex items-center gap-3 p-3 bg-gray-50 rounded-xl border border-gray-100">
            <input type="text" name="options[${optionIndex}][option_text]" placeholder="Teks jawaban" class="form-input flex-1" required>
            <input type="number" name="options[${optionIndex}][score]" placeholder="Skor" class="form-input w-20" value="0" required>
            <label class="flex items-center gap-1 text-xs text-gray-500 whitespace-nowrap cursor-pointer">
                <input type="checkbox" name="options[${optionIndex}][is_correct]" value="1" class="w-4 h-4 rounded border-gray-300 text-emerald-600">
                Benar
            </label>
            <button type="button" onclick="this.closest('.option-row').remove()" class="text-red-400 hover:text-red-600 p-1">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>`;
    container.insertAdjacentHTML('beforeend', html);
    optionIndex++;
}
</script>
@endsection
@endsection
