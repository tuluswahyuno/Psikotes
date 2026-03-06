@extends('layouts.main')
@section('title', 'Edit Paket SKD')
@section('subtitle', 'Perbarui konfigurasi paket: ' . $skdPackage->title)

@section('content')
<div class="max-w-4xl">
    <form action="{{ route('admin.skd-packages.update', $skdPackage) }}" method="POST">
        @csrf @method('PUT')
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Left Column: Basic Info -->
            <div class="lg:col-span-1 space-y-6">
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                    <h3 class="text-sm font-bold text-gray-900 mb-4 border-b border-gray-50 pb-2">Informasi Dasar</h3>
                    <div class="space-y-4">
                        <div>
                            <label class="form-label" for="title">Judul Paket <span class="text-red-500">*</span></label>
                            <input type="text" name="title" id="title" value="{{ old('title', $skdPackage->title) }}" class="form-input" required>
                            @error('title') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="form-label" for="duration_minutes">Durasi Total (menit) <span class="text-red-500">*</span></label>
                            <input type="number" name="duration_minutes" id="duration_minutes" value="{{ old('duration_minutes', $skdPackage->duration_minutes) }}" class="form-input" required min="1">
                        </div>
                        <div>
                            <label class="form-label" for="description">Deskripsi</label>
                            <textarea name="description" id="description" rows="4" class="form-input">{{ old('description', $skdPackage->description) }}</textarea>
                        </div>
                        <label class="flex items-center gap-2 cursor-pointer mt-2">
                            <input type="checkbox" name="is_active" value="1" {{ old('is_active', $skdPackage->is_active) ? 'checked' : '' }} class="w-4 h-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                            <span class="text-sm text-gray-700 font-medium">Aktifkan Paket</span>
                        </label>
                    </div>
                </div>
            </div>

            <!-- Right Column: Sub-Test Mapping -->
            <div class="lg:col-span-2 space-y-6">
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                    <h3 class="text-sm font-bold text-gray-900 mb-4 border-b border-gray-50 pb-2">Pemetaan Sub-Tes SKD</h3>
                    
                    <div class="space-y-8 mt-4">
                        @php
                            $twk = $skdPackage->getTestByType('twk');
                            $tiu = $skdPackage->getTestByType('tiu');
                            $tkp = $skdPackage->getTestByType('tkp');
                        @endphp

                        <!-- TWK -->
                        <div class="p-4 rounded-xl bg-blue-50/50 border border-blue-100">
                            <div class="flex items-center gap-3 mb-4">
                                <span class="w-8 h-8 rounded-lg bg-blue-600 text-white flex items-center justify-center font-bold text-xs">TWK</span>
                                <h4 class="font-bold text-gray-900">Tes Wawasan Kebangsaan</h4>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="form-label text-blue-700" for="twk_test_id">Pilih Master Tes <span class="text-red-500">*</span></label>
                                    <select name="twk_test_id" id="twk_test_id" class="form-input" required>
                                        @foreach($tests as $test)
                                            <option value="{{ $test->id }}" {{ old('twk_test_id', $twk?->test_id) == $test->id ? 'selected' : '' }}>{{ $test->title }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="form-label text-blue-700" for="twk_passing_grade">Passing Grade <span class="text-red-500">*</span></label>
                                    <input type="number" name="twk_passing_grade" id="twk_passing_grade" value="{{ old('twk_passing_grade', $twk?->passing_grade) }}" class="form-input" required>
                                </div>
                            </div>
                        </div>

                        <!-- TIU -->
                        <div class="p-4 rounded-xl bg-amber-50/50 border border-amber-100">
                            <div class="flex items-center gap-3 mb-4">
                                <span class="w-8 h-8 rounded-lg bg-amber-500 text-white flex items-center justify-center font-bold text-xs">TIU</span>
                                <h4 class="font-bold text-gray-900">Tes Intelegensi Umum</h4>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="form-label text-amber-700" for="tiu_test_id">Pilih Master Tes <span class="text-red-500">*</span></label>
                                    <select name="tiu_test_id" id="tiu_test_id" class="form-input" required>
                                        @foreach($tests as $test)
                                            <option value="{{ $test->id }}" {{ old('tiu_test_id', $tiu?->test_id) == $test->id ? 'selected' : '' }}>{{ $test->title }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="form-label text-amber-700" for="tiu_passing_grade">Passing Grade <span class="text-red-500">*</span></label>
                                    <input type="number" name="tiu_passing_grade" id="tiu_passing_grade" value="{{ old('tiu_passing_grade', $tiu?->passing_grade) }}" class="form-input" required>
                                </div>
                            </div>
                        </div>

                        <!-- TKP -->
                        <div class="p-4 rounded-xl bg-purple-50/50 border border-purple-100">
                            <div class="flex items-center gap-3 mb-4">
                                <span class="w-8 h-8 rounded-lg bg-purple-600 text-white flex items-center justify-center font-bold text-xs">TKP</span>
                                <h4 class="font-bold text-gray-900">Tes Karakteristik Pribadi</h4>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="form-label text-purple-700" for="tkp_test_id">Pilih Master Tes <span class="text-red-500">*</span></label>
                                    <select name="tkp_test_id" id="tkp_test_id" class="form-input" required>
                                        @foreach($tests as $test)
                                            <option value="{{ $test->id }}" {{ old('tkp_test_id', $tkp?->test_id) == $test->id ? 'selected' : '' }}>{{ $test->title }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="form-label text-purple-700" for="tkp_passing_grade">Passing Grade <span class="text-red-500">*</span></label>
                                    <input type="number" name="tkp_passing_grade" id="tkp_passing_grade" value="{{ old('tkp_passing_grade', $tkp?->passing_grade) }}" class="form-input" required>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex items-center gap-3 pt-4">
                    <button type="submit" class="btn-primary px-8">Update Paket SKD</button>
                    <a href="{{ route('admin.skd-packages.show', $skdPackage) }}" class="btn-secondary">Batal</a>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection
