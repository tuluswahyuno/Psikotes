@extends('layouts.main')
@section('title', 'Kelola Bank Materi SKD')
@section('subtitle', 'Kelola materi belajar per section SKD – TWK, TIU, dan TKP.')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    @foreach($sections as $section)
    @php
        $colors = [
            'blue'    => [
                'bg'           => 'bg-blue-500',    
                'light'        => 'bg-blue-50 dark:bg-blue-900/20',    
                'text'         => 'text-blue-600',
                'border'       => 'border-blue-100 dark:border-blue-900/30',
                'shadow'       => 'shadow-blue-500/30',
                'hover_bg'     => 'hover:bg-blue-600',
            ],
            'purple'  => [
                'bg'           => 'bg-purple-500',  
                'light'        => 'bg-purple-50 dark:bg-purple-900/20',  
                'text'         => 'text-purple-600',
                'border'       => 'border-purple-100 dark:border-purple-900/30',
                'shadow'       => 'shadow-purple-500/30',
                'hover_bg'     => 'hover:bg-purple-600',
            ],
            'emerald' => [
                'bg'           => 'bg-emerald-500', 
                'light'        => 'bg-emerald-50 dark:bg-emerald-900/20', 
                'text'         => 'text-emerald-600',
                'border'       => 'border-emerald-100 dark:border-emerald-900/30',
                'shadow'       => 'shadow-emerald-500/30',
                'hover_bg'     => 'hover:bg-emerald-600',
            ],
        ];
        $c = $colors[$section->color] ?? $colors['blue'];
    @endphp
    <div class="bg-white dark:bg-slate-900 rounded-[1.5rem] border border-slate-200 dark:border-slate-800 shadow-sm overflow-hidden hover:shadow-xl transition-all duration-300 flex flex-col transform hover:-translate-y-1">
        <div class="h-2 {{ $c['bg'] }} w-full"></div>
        <div class="p-8 flex flex-col flex-1">
            <div class="flex items-start gap-4 mb-8">
                <div class="size-16 rounded-2xl {{ $c['bg'] }} text-white flex items-center justify-center shadow-lg {{ $c['shadow'] }} shrink-0">
                    <span class="material-symbols-outlined text-3xl">{{ $section->icon }}</span>
                </div>
                <div>
                    <h3 class="text-xl font-black text-slate-900 dark:text-white leading-tight mb-2">{{ $section->name }}</h3>
                    <p class="text-sm text-slate-500 leading-relaxed">{{ $section->description }}</p>
                </div>
            </div>
            
            <div class="grid grid-cols-2 gap-4 mb-8 mt-auto">
                <div class="text-center p-5 {{ $c['light'] }} rounded-2xl border {{ $c['border'] }}">
                    <p class="text-3xl font-black {{ $c['text'] }}">{{ $section->sub_topics_count }}</p>
                    <p class="text-xs font-bold text-slate-500 uppercase tracking-wider mt-1">Sub-Topik</p>
                </div>
                <div class="text-center p-5 bg-slate-50 dark:bg-slate-800/50 rounded-2xl border border-slate-100 dark:border-slate-800">
                    <p class="text-3xl font-black text-slate-700 dark:text-slate-300">{{ $section->materials_count ?? 0 }}</p>
                    <p class="text-xs font-bold text-slate-500 uppercase tracking-wider mt-1">Materi</p>
                </div>
            </div>
            
            <a href="{{ route('admin.learning.sub-topics', $section) }}" class="flex items-center justify-center gap-2 w-full py-3.5 {{ $c['bg'] }} {{ $c['hover_bg'] }} text-white font-bold rounded-xl text-sm transition-all shadow-md {{ $c['shadow'] }} group">
                Kelola Sub-Topik
                <span class="material-symbols-outlined text-base transition-transform group-hover:translate-x-1">arrow_forward</span>
            </a>
        </div>
    </div>
    @endforeach
</div>
@endsection
