@extends('layouts.main')
@section('title', isset($practiceQuestion) ? 'Edit Soal Latihan' : 'Tambah Soal Latihan')

@section('content')
@php $isEdit = isset($practiceQuestion); @endphp

<div class="flex items-center gap-2 text-sm text-slate-500 dark:text-slate-400 mb-8 bg-white dark:bg-slate-900 p-3 px-5 rounded-xl border border-slate-200 dark:border-slate-800 shadow-sm w-fit">
    <a href="{{ route('admin.practice-questions.index') }}" class="hover:text-primary transition-colors font-semibold">Soal Latihan</a>
    <span class="material-symbols-outlined text-sm">chevron_right</span>
    <span class="font-bold text-primary">{{ $isEdit ? 'Edit Soal' : 'Tambah Soal' }}</span>
</div>

<div class="max-w-3xl">
    <h2 class="text-2xl font-extrabold text-slate-900 dark:text-white mb-6">
        {{ $isEdit ? 'Edit Soal Latihan' : 'Tambah Soal Latihan Baru' }}
    </h2>

    <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm overflow-hidden">
        <form action="{{ $isEdit ? route('admin.practice-questions.update', $practiceQuestion) : route('admin.practice-questions.store') }}" method="POST" class="p-6 space-y-5">
            @csrf
            @if($isEdit) @method('PUT') @endif

            @if($errors->any())
            <div class="p-4 bg-rose-50 dark:bg-rose-900/20 border border-rose-200 dark:border-rose-800 rounded-xl text-rose-600 dark:text-rose-400 text-sm">
                <ul class="list-disc list-inside space-y-1">
                    @foreach($errors->all() as $e) <li>{{ $e }}</li> @endforeach
                </ul>
            </div>
            @endif

            {{-- Sub-Topik --}}
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-1.5">Section <span class="text-rose-500">*</span></label>
                    <select id="section-select" class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-sm focus:ring-2 focus:ring-primary/30 outline-none">
                        <option value="">Pilih Section</option>
                        @foreach($sections as $section)
                            <option value="{{ $section->id }}" {{ (old('section_id', $isEdit ? $practiceQuestion->subTopic->section_id : '') == $section->id) ? 'selected' : '' }}>{{ $section->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-1.5">Sub-Topik <span class="text-rose-500">*</span></label>
                    <select name="sub_topic_id" id="subtopic-select" class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-sm focus:ring-2 focus:ring-primary/30 outline-none">
                        <option value="">Pilih Sub-Topik</option>
                        @foreach($sections as $section)
                            @foreach($section->subTopics as $st)
                                <option value="{{ $st->id }}" data-section="{{ $section->id }}" {{ old('sub_topic_id', $isEdit ? $practiceQuestion->sub_topic_id : '') == $st->id ? 'selected' : '' }}>{{ $st->title }}</option>
                            @endforeach
                        @endforeach
                    </select>
                    @error('sub_topic_id') <p class="text-rose-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            {{-- Question --}}
            <div>
                <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-1.5">Pertanyaan <span class="text-rose-500">*</span></label>
                <textarea name="question" rows="4" required class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-sm font-medium focus:ring-2 focus:ring-primary/30 outline-none transition-all" placeholder="Tulis pertanyaan di sini...">{{ old('question', $isEdit ? $practiceQuestion->question : '') }}</textarea>
                @error('question') <p class="text-rose-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            {{-- Options --}}
            <div class="space-y-3">
                <label class="block text-sm font-bold text-slate-700 dark:text-slate-300">Pilihan Jawaban <span class="text-rose-500">*</span></label>
                @foreach(['A','B','C','D','E'] as $opt)
                @php $fieldKey = 'option_' . strtolower($opt); $oldVal = old($fieldKey, $isEdit ? ($practiceQuestion->options[$opt] ?? '') : ''); @endphp
                <div class="flex items-center gap-3">
                    <span class="size-8 rounded-lg bg-slate-100 dark:bg-slate-800 font-black text-slate-600 dark:text-slate-400 text-sm flex items-center justify-center shrink-0">{{ $opt }}</span>
                    <input type="text" name="{{ $fieldKey }}" value="{{ $oldVal }}" required
                        class="flex-1 px-4 py-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-sm font-medium focus:ring-2 focus:ring-primary/30 outline-none transition-all"
                        placeholder="Pilihan {{ $opt }}...">
                </div>
                @endforeach
            </div>

            {{-- Correct Answer + Difficulty --}}
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-1.5">Kunci Jawaban <span class="text-rose-500">*</span></label>
                    <select name="correct_answer" required class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-sm focus:ring-2 focus:ring-primary/30 outline-none">
                        @foreach(['A','B','C','D','E'] as $opt)
                            <option value="{{ $opt }}" {{ old('correct_answer', $isEdit ? $practiceQuestion->correct_answer : '') == $opt ? 'selected' : '' }}>{{ $opt }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-1.5">Difficulty <span class="text-rose-500">*</span></label>
                    <select name="difficulty" required class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-sm focus:ring-2 focus:ring-primary/30 outline-none">
                        @foreach(['easy' => 'Easy (Mudah)', 'medium' => 'Medium (Sedang)', 'hard' => 'Hard (Sulit)'] as $val => $label)
                            <option value="{{ $val }}" {{ old('difficulty', $isEdit ? $practiceQuestion->difficulty : 'medium') == $val ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            {{-- Explanation --}}
            <div>
                <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-1.5">Pembahasan / Penjelasan</label>
                <textarea name="explanation" rows="4" class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-sm font-medium focus:ring-2 focus:ring-primary/30 outline-none transition-all" placeholder="Jelaskan mengapa jawaban tersebut benar...">{{ old('explanation', $isEdit ? $practiceQuestion->explanation : '') }}</textarea>
            </div>

            {{-- Tags --}}
            <div>
                <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-1.5">Tags <span class="text-xs font-normal text-slate-400">(pisah dengan koma)</span></label>
                <input type="text" name="tags" value="{{ old('tags', $isEdit && $practiceQuestion->tags ? implode(', ', $practiceQuestion->tags) : '') }}"
                    class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-sm font-medium focus:ring-2 focus:ring-primary/30 outline-none transition-all"
                    placeholder="analogi, deret-angka, UUD-1945">
            </div>

            <div class="flex gap-3 pt-2">
                <button type="submit" class="inline-flex items-center gap-2 px-6 py-2.5 bg-primary text-white font-bold rounded-xl shadow-lg shadow-primary/20 hover:bg-blue-700 transition-all text-sm">
                    <span class="material-symbols-outlined text-lg">save</span>
                    {{ $isEdit ? 'Simpan Perubahan' : 'Tambah Soal' }}
                </button>
                <a href="{{ route('admin.practice-questions.index') }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 font-bold rounded-xl hover:bg-slate-200 dark:hover:bg-slate-700 transition-all text-sm">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Filter sub-topic by section
    const sectionSelect = document.getElementById('section-select');
    const subtopicSelect = document.getElementById('subtopic-select');
    const allOptions = Array.from(subtopicSelect.querySelectorAll('option'));

    sectionSelect.addEventListener('change', function() {
        const sectionId = this.value;
        allOptions.forEach(opt => {
            if (!opt.value) return;
            opt.hidden = sectionId && opt.dataset.section !== sectionId;
        });
        // Reset if current selection is hidden
        if (subtopicSelect.selectedOptions[0]?.hidden) {
            subtopicSelect.value = '';
        }
    });

    // Trigger filter on load
    sectionSelect.dispatchEvent(new Event('change'));
</script>
@endpush
