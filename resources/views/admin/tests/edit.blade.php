@extends('layouts.main')
@section('title', 'Edit Tes')
@section('subtitle', $test->title)

@section('content')
<div class="max-w-2xl">
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8">
        <form action="{{ route('admin.tests.update', $test) }}" method="POST">
            @csrf @method('PUT')
            <div class="space-y-6">
                <div>
                    <label class="form-label" for="title">Nama Tes <span class="text-red-500">*</span></label>
                    <input type="text" name="title" id="title" value="{{ old('title', $test->title) }}" class="form-input" required>
                    @error('title') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="form-label" for="category">Kategori</label>
                        <input type="text" name="category" id="category" value="{{ old('category', $test->category) }}" class="form-input">
                    </div>
                    <div>
                        <label class="form-label" for="type">Tipe Tes <span class="text-red-500">*</span></label>
                        <select name="type" id="type" class="form-input" required>
                            <option value="multiple_choice" {{ old('type', $test->type) == 'multiple_choice' ? 'selected' : '' }}>Pilihan Ganda</option>
                            <option value="likert" {{ old('type', $test->type) == 'likert' ? 'selected' : '' }}>Skala Likert</option>
                            <option value="personality" {{ old('type', $test->type) == 'personality' ? 'selected' : '' }}>Kepribadian</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label class="form-label" for="description">Deskripsi</label>
                    <textarea name="description" id="description" rows="3" class="form-input">{{ old('description', $test->description) }}</textarea>
                </div>

                <div>
                    <label class="form-label" for="duration_minutes">Durasi (menit) <span class="text-red-500">*</span></label>
                    <input type="number" name="duration_minutes" id="duration_minutes" value="{{ old('duration_minutes', $test->duration_minutes) }}" class="form-input" required min="1" max="300">
                </div>

                <div class="flex items-center gap-6">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', $test->is_active) ? 'checked' : '' }} class="w-4 h-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                        <span class="text-sm text-gray-700">Aktif</span>
                    </label>
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="randomize_questions" value="1" {{ old('randomize_questions', $test->randomize_questions) ? 'checked' : '' }} class="w-4 h-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                        <span class="text-sm text-gray-700">Acak Urutan Soal</span>
                    </label>
                </div>
            </div>

            <div class="flex items-center gap-3 mt-8 pt-6 border-t border-gray-100">
                <button type="submit" class="btn-primary">Simpan Perubahan</button>
                <a href="{{ route('admin.tests.show', $test) }}" class="btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
