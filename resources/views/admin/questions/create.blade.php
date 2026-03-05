@extends('layouts.main')
@section('title', 'Tambah Soal')
@section('subtitle', $test->title)

@section('content')
<div class="max-w-2xl">
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8">
        <form action="{{ route('admin.questions.store', $test) }}" method="POST" id="questionForm">
            @csrf
            <div class="space-y-6">
                <div>
                    <label class="form-label" for="question_text">Pertanyaan <span class="text-red-500">*</span></label>
                    <textarea name="question_text" id="question_text" rows="3" class="form-input" required placeholder="Tulis pertanyaan di sini...">{{ old('question_text') }}</textarea>
                    @error('question_text') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="form-label" for="type">Tipe</label>
                        <select name="type" id="type" class="form-input">
                            <option value="choice">Pilihan</option>
                            <option value="scale">Skala</option>
                        </select>
                    </div>
                    <div>
                        <label class="form-label" for="weight">Bobot</label>
                        <input type="number" name="weight" id="weight" value="{{ old('weight', 1) }}" class="form-input" min="1">
                    </div>
                </div>

                <!-- Options -->
                <div>
                    <div class="flex items-center justify-between mb-3">
                        <label class="form-label mb-0">Opsi Jawaban</label>
                        <button type="button" onclick="addOption()" class="text-sm text-indigo-600 hover:text-indigo-800 font-medium">+ Tambah Opsi</button>
                    </div>
                    <div id="optionsContainer" class="space-y-3">
                        <div class="option-row flex items-center gap-3 p-3 bg-gray-50 rounded-xl border border-gray-100">
                            <input type="text" name="options[0][option_text]" placeholder="Teks jawaban" class="form-input flex-1" required>
                            <input type="number" name="options[0][score]" placeholder="Skor" class="form-input w-20" value="0" required>
                            <label class="flex items-center gap-1 text-xs text-gray-500 whitespace-nowrap cursor-pointer">
                                <input type="checkbox" name="options[0][is_correct]" value="1" class="w-4 h-4 rounded border-gray-300 text-emerald-600">
                                Benar
                            </label>
                            <button type="button" onclick="this.closest('.option-row').remove()" class="text-red-400 hover:text-red-600 p-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                            </button>
                        </div>
                        <div class="option-row flex items-center gap-3 p-3 bg-gray-50 rounded-xl border border-gray-100">
                            <input type="text" name="options[1][option_text]" placeholder="Teks jawaban" class="form-input flex-1" required>
                            <input type="number" name="options[1][score]" placeholder="Skor" class="form-input w-20" value="0" required>
                            <label class="flex items-center gap-1 text-xs text-gray-500 whitespace-nowrap cursor-pointer">
                                <input type="checkbox" name="options[1][is_correct]" value="1" class="w-4 h-4 rounded border-gray-300 text-emerald-600">
                                Benar
                            </label>
                            <button type="button" onclick="this.closest('.option-row').remove()" class="text-red-400 hover:text-red-600 p-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex items-center gap-3 mt-8 pt-6 border-t border-gray-100">
                <button type="submit" class="btn-primary">Simpan Soal</button>
                <a href="{{ route('admin.tests.show', $test) }}" class="btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>

@section('scripts')
<script>
let optionIndex = 2;
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
