@extends('layouts.main')
@section('title', 'Manage Test')
@section('subtitle', 'Daftar semua tes psikotes dan SKD dalam sistem')

@section('page_header')
<header class="bg-white dark:bg-slate-900 border-b border-slate-200 dark:border-slate-800 px-8 py-6">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 max-w-7xl mx-auto">
        <div>
            <h2 class="text-2xl font-bold text-slate-900 dark:text-white tracking-tight">Manage Test</h2>
            <p class="text-slate-500 dark:text-slate-400 text-sm mt-1">Daftar semua tes psikotes dan SKD dalam sistem</p>
        </div>
        <a href="{{ route('admin.tests.create') }}" class="flex items-center justify-center gap-2 px-5 py-2.5 bg-primary text-white text-sm font-bold rounded-lg hover:bg-primary/90 transition-all shadow-md">
            <span class="material-symbols-outlined text-xl">add</span>
            <span>Add New Test</span>
        </a>
    </div>
</header>
@endsection

@section('content')
<div class="max-w-7xl mx-auto space-y-6">
    <!-- Search and Filters -->
    <form method="GET" action="{{ route('admin.tests.index') }}" class="bg-white dark:bg-slate-900 p-4 rounded-xl border border-slate-200 dark:border-slate-800 flex flex-col md:flex-row gap-4 items-center">
        <div class="relative flex-1 w-full">
            <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-slate-400">search</span>
            <input name="search" value="{{ request('search') }}" class="w-full pl-10 pr-4 py-2 bg-slate-100 dark:bg-slate-800 border-none rounded-lg focus:ring-2 focus:ring-primary text-sm" placeholder="Search tests by name or category..." type="text"/>
            <!-- Hidden submit button if entered -->
            <button type="submit" class="hidden"></button>
        </div>
        <div class="flex gap-3 w-full md:w-auto">
            <!-- Category Dropdown Component -->
            <div x-data="{ open: false }" class="relative w-full md:w-auto">
                <button type="button" @click="open = !open" @click.outside="open = false" class="w-full flex items-center justify-between gap-2 px-4 py-2 bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 rounded-lg text-sm font-medium hover:bg-slate-200 dark:hover:bg-slate-700 transition-colors">
                    <span>{{ request('category') ?: 'Category' }}</span>
                    <span class="material-symbols-outlined text-lg leading-none transition-transform duration-200" :class="open ? 'rotate-180' : ''">expand_more</span>
                </button>
                <div x-show="open" x-transition style="display: none;" class="absolute z-50 mt-2 w-48 bg-white dark:bg-slate-800 rounded-xl shadow-lg border border-slate-200 dark:border-slate-700 py-1 right-0 md:left-0 md:right-auto overflow-hidden">
                    <button type="button" @click="$refs.catInput.value=''; $el.closest('form').submit()" class="w-full text-left px-4 py-2.5 text-sm transition-colors {{ !request('category') ? 'bg-primary/5 text-primary font-bold' : 'text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-700' }}">All Categories</button>
                    @php $cats = ['TIU', 'TWK', 'TKP', 'Logika', 'Kepribadian']; @endphp
                    @foreach($cats as $c)
                        <button type="button" @click="$refs.catInput.value='{{ $c }}'; $el.closest('form').submit()" class="w-full text-left px-4 py-2.5 text-sm transition-colors {{ request('category') == $c ? 'bg-primary/5 text-primary font-bold' : 'text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-700' }}">{{ $c }}</button>
                    @endforeach
                </div>
                <input type="hidden" name="category" x-ref="catInput" value="{{ request('category') }}">
            </div>

            <!-- Status Dropdown Component -->
            <div x-data="{ open: false }" class="relative w-full md:w-auto">
                <button type="button" @click="open = !open" @click.outside="open = false" class="w-full flex items-center justify-between gap-2 px-4 py-2 bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 rounded-lg text-sm font-medium hover:bg-slate-200 dark:hover:bg-slate-700 transition-colors">
                    <span>{{ request('status') === '1' ? 'Active' : (request('status') === '0' ? 'Inactive' : 'Status') }}</span>
                    <span class="material-symbols-outlined text-lg leading-none transition-transform duration-200" :class="open ? 'rotate-180' : ''">expand_more</span>
                </button>
                <div x-show="open" x-transition style="display: none;" class="absolute z-50 mt-2 w-48 bg-white dark:bg-slate-800 rounded-xl shadow-lg border border-slate-200 dark:border-slate-700 py-1 right-0 overflow-hidden">
                    <button type="button" @click="$refs.statusInput.value=''; $el.closest('form').submit()" class="w-full text-left px-4 py-2.5 text-sm transition-colors {{ request('status') === null ? 'bg-primary/5 text-primary font-bold' : 'text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-700' }}">All Status</button>
                    <button type="button" @click="$refs.statusInput.value='1'; $el.closest('form').submit()" class="w-full text-left px-4 py-2.5 text-sm transition-colors {{ request('status') === '1' ? 'bg-primary/5 text-primary font-bold' : 'text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-700' }}">Active</button>
                    <button type="button" @click="$refs.statusInput.value='0'; $el.closest('form').submit()" class="w-full text-left px-4 py-2.5 text-sm transition-colors {{ request('status') === '0' ? 'bg-primary/5 text-primary font-bold' : 'text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-700' }}">Inactive</button>
                </div>
                <input type="hidden" name="status" x-ref="statusInput" value="{{ request('status') }}">
            </div>
        </div>
    </form>

    <!-- Table Container -->
    <div class="bg-white dark:bg-slate-900 rounded-xl border border-slate-200 dark:border-slate-800 overflow-hidden shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead class="bg-slate-50 dark:bg-slate-800/50 border-b border-slate-200 dark:border-slate-800">
                    <tr>
                        <th class="px-6 py-4 text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Test Name</th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Category</th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Type</th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider text-center">Questions</th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Duration</th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider text-center">Status</th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                    @forelse($tests as $test)
                    <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/30 transition-colors">
                        <td class="px-6 py-5">
                            <span class="text-sm font-semibold text-slate-900 dark:text-white">{{ $test->title }}</span>
                        </td>
                        <td class="px-6 py-5">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-primary/10 text-primary">{{ $test->category ?: 'Umum' }}</span>
                        </td>
                        <td class="px-6 py-5">
                            <span class="text-sm text-slate-600 dark:text-slate-400">{{ ucfirst(str_replace('_', ' ', $test->type)) }}</span>
                        </td>
                        <td class="px-6 py-5 text-center">
                            <span class="text-sm text-slate-600 dark:text-slate-400">{{ $test->questions_count }}</span>
                        </td>
                        <td class="px-6 py-5">
                            <span class="text-sm text-slate-600 dark:text-slate-400">{{ $test->duration_minutes }} mins</span>
                        </td>
                        <td class="px-6 py-5 text-center">
                            @if($test->is_active)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-100 dark:bg-emerald-900/30 text-emerald-600 dark:text-emerald-400">Active</span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-slate-100 dark:bg-slate-800 text-slate-500 dark:text-slate-400">Inactive</span>
                            @endif
                        </td>
                        <td class="px-6 py-5 text-right">
                            <div class="flex justify-end gap-2">
                                <a href="{{ route('admin.tests.show', $test) }}" class="p-1.5 text-slate-400 hover:text-primary transition-colors" title="View"><span class="material-symbols-outlined text-xl">visibility</span></a>
                                <a href="{{ route('admin.tests.edit', $test) }}" class="p-1.5 text-slate-400 hover:text-primary transition-colors" title="Edit"><span class="material-symbols-outlined text-xl">edit</span></a>
                                <form action="{{ route('admin.tests.destroy', $test) }}" method="POST" class="inline" onsubmit="return confirm('Hapus tes ini secara permanen?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="p-1.5 text-slate-400 hover:text-red-500 transition-colors" title="Delete"><span class="material-symbols-outlined text-xl">delete</span></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center text-slate-500 dark:text-slate-400">
                            <span class="material-symbols-outlined text-5xl mb-3 block text-slate-300">search_off</span>
                            <span class="text-sm">Belum ada data Test. Mulai buat tes pertama!</span>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        @if($tests->hasPages())
        <div class="px-6 py-4 border-t border-slate-100 dark:border-slate-800 flex items-center justify-between">
            <span class="text-sm text-slate-500 dark:text-slate-400">
                Showing {{ $tests->firstItem() ?? 0 }} to {{ $tests->lastItem() ?? 0 }} of {{ $tests->total() }} tests
            </span>
            <div class="flex items-center gap-2">
                @if($tests->onFirstPage())
                    <button disabled class="p-1 rounded border border-slate-200 dark:border-slate-700 text-slate-400 disabled:opacity-50">
                        <span class="material-symbols-outlined text-lg">chevron_left</span>
                    </button>
                @else
                    <a href="{{ $tests->previousPageUrl() }}" class="p-1 rounded border border-slate-200 dark:border-slate-700 text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors">
                        <span class="material-symbols-outlined text-lg">chevron_left</span>
                    </a>
                @endif
                
                @php
                    $start = max(1, $tests->currentPage() - 2);
                    $end = min(max(1, $tests->lastPage()), $tests->currentPage() + 2);
                @endphp
                
                @for($i = $start; $i <= $end; $i++)
                    @if ($i == $tests->currentPage())
                        <button class="px-3 py-1 text-sm font-semibold rounded bg-primary text-white">{{ $i }}</button>
                    @else
                        <a href="{{ $tests->url($i) }}" class="px-3 py-1 text-sm font-medium rounded text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800">{{ $i }}</a>
                    @endif
                @endfor
                
                @if($tests->hasMorePages())
                    <a href="{{ $tests->nextPageUrl() }}" class="p-1 rounded border border-slate-200 dark:border-slate-700 text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors">
                        <span class="material-symbols-outlined text-lg">chevron_right</span>
                    </a>
                @else
                    <button disabled class="p-1 rounded border border-slate-200 dark:border-slate-700 text-slate-400 disabled:opacity-50">
                        <span class="material-symbols-outlined text-lg">chevron_right</span>
                    </button>
                @endif
            </div>
        </div>
        @endif
    </div>

    <!-- Statistics / Quick Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white dark:bg-slate-900 p-6 rounded-xl border border-slate-200 dark:border-slate-800 shadow-sm flex items-center gap-4">
            <div class="size-12 rounded-lg bg-blue-50 dark:bg-blue-900/20 flex items-center justify-center text-blue-600">
                <span class="material-symbols-outlined text-2xl">fact_check</span>
            </div>
            <div>
                <p class="text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Total Tests</p>
                <p class="text-2xl font-bold text-slate-900 dark:text-white">{{ \App\Models\Test::count() }}</p>
            </div>
        </div>
        <div class="bg-white dark:bg-slate-900 p-6 rounded-xl border border-slate-200 dark:border-slate-800 shadow-sm flex items-center gap-4">
            <div class="size-12 rounded-lg bg-emerald-50 dark:bg-emerald-900/20 flex items-center justify-center text-emerald-600">
                <span class="material-symbols-outlined text-2xl">check_circle</span>
            </div>
            <div>
                <p class="text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Active</p>
                <p class="text-2xl font-bold text-slate-900 dark:text-white">{{ \App\Models\Test::where('is_active', true)->count() }}</p>
            </div>
        </div>
        <div class="bg-white dark:bg-slate-900 p-6 rounded-xl border border-slate-200 dark:border-slate-800 shadow-sm flex items-center gap-4">
            <div class="size-12 rounded-lg bg-amber-50 dark:bg-amber-900/20 flex items-center justify-center text-amber-600">
                <span class="material-symbols-outlined text-2xl">pending</span>
            </div>
            <div>
                <p class="text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Draft/Inactive</p>
                <p class="text-2xl font-bold text-slate-900 dark:text-white">{{ \App\Models\Test::where('is_active', false)->count() }}</p>
            </div>
        </div>
    </div>
</div>
@endsection
