@extends('layouts.main')
@section('title', 'Assign Tes')
@section('subtitle', 'Assign tes ke ' . $user->name)

@section('content')
<div class="max-w-2xl">
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8">
        <form action="{{ route('admin.users.assign.store', $user) }}" method="POST">
            @csrf
            <div class="space-y-6">
                <div>
                    <label class="form-label">Pilih Tes</label>
                    <div class="space-y-2 max-h-80 overflow-y-auto">
                        @foreach($tests as $test)
                        <label class="flex items-center gap-3 p-3 rounded-xl border cursor-pointer transition-colors {{ in_array($test->id, $assignedTestIds) ? 'bg-indigo-50 border-indigo-200' : 'bg-gray-50 border-gray-100 hover:bg-gray-100' }}">
                            <input type="checkbox" name="test_ids[]" value="{{ $test->id }}" {{ in_array($test->id, $assignedTestIds) ? 'checked' : '' }}
                                class="w-4 h-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                            <div class="flex-1">
                                <p class="text-sm font-medium text-gray-900">{{ $test->title }}</p>
                                <p class="text-xs text-gray-500">{{ $test->category }} · {{ $test->duration_minutes }} menit · {{ $test->questions()->count() }} soal</p>
                            </div>
                        </label>
                        @endforeach
                    </div>
                    @error('test_ids') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="form-label" for="deadline">Deadline (opsional)</label>
                    <input type="datetime-local" name="deadline" id="deadline" class="form-input">
                </div>
            </div>

            <div class="flex items-center gap-3 mt-8 pt-6 border-t border-gray-100">
                <button type="submit" class="btn-primary">Simpan Assignment</button>
                <a href="{{ route('admin.users.index') }}" class="btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
