@extends('layouts.dashboard')

@section('title', 'Teacher Feedbacks')
@section('header_title', 'Teacher Feedbacks & Remarks')

@section('content')
<div class="space-y-6 animate-fade-in">
    <!-- Header -->
    <div>
        <h2 class="text-lg font-bold text-slate-900 dark:text-white">Academic Remarks List</h2>
        <p class="text-xs text-slate-400 mt-0.5">Read feedback, review guidelines, and notes posted by teachers on your performance.</p>
    </div>

    <!-- Feedback list cards -->
    <div class="space-y-4 max-w-4xl">
        @forelse($feedbacks as $fb)
            <div class="bg-white dark:bg-slate-950 p-6 rounded-2xl border border-slate-100 dark:border-slate-800 shadow-sm flex items-start gap-4 hover:shadow-md transition-shadow">
                <div class="flex-shrink-0 h-10 w-10 rounded-full bg-indigo-50 dark:bg-indigo-950/30 flex items-center justify-center text-indigo-650 dark:text-indigo-400 font-bold text-base">
                    {{ strtoupper(substr($fb->teacher->name ?? 'T', 0, 1)) }}
                </div>
                <div class="flex-1 min-w-0">
                    <div class="flex items-center justify-between gap-4">
                        <h4 class="text-sm font-bold text-slate-900 dark:text-white">
                            Teacher: <span class="text-indigo-600 dark:text-indigo-400">{{ $fb->teacher->name ?? 'N/A' }}</span>
                        </h4>
                        <span class="text-xxs font-medium text-slate-400">
                            {{ $fb->created_at ? $fb->created_at->diffForHumans() : '' }}
                        </span>
                    </div>
                    <p class="text-sm text-slate-650 dark:text-slate-350 italic mt-3 leading-relaxed">
                        "{{ $fb->remark }}"
                    </p>
                </div>
            </div>
        @empty
            <div class="bg-white dark:bg-slate-950 p-8 text-center text-slate-400 rounded-2xl border border-slate-100 dark:border-slate-800 shadow-sm text-xs">
                No feedback remarks logged by teachers. Keep working hard!
            </div>
        @endforelse
    </div>
</div>
@endsection
