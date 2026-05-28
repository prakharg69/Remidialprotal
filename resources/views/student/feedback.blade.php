@extends('layouts.dashboard')

@section('title', 'Teacher Feedbacks')
@section('header_title', 'Teacher Feedbacks & Remarks')

@section('content')
<div class="space-y-6 animate-fade-in">
    <!-- Header -->
    <div>
        <h2 class="text-lg font-bold text-zinc-900 dark:text-white">Academic Remarks List</h2>
        <p class="text-xs text-zinc-550 dark:text-zinc-400 mt-0.5">Read feedback, review guidelines, and notes posted by teachers on your performance.</p>
    </div>

    <!-- Feedback list cards -->
    <div class="space-y-4 max-w-4xl">
        @forelse($feedbacks as $fb)
            <div class="bg-white dark:bg-zinc-900 p-6 rounded-2xl border border-zinc-200 dark:border-zinc-800 shadow-sm flex items-start gap-4 hover:shadow-md transition-shadow">
                <div class="flex-shrink-0 h-10 w-10 rounded-full bg-indigo-50 dark:bg-indigo-500/10 flex items-center justify-center text-indigo-700 dark:text-indigo-400 font-bold text-base border border-indigo-200 dark:border-indigo-500/20">
                    {{ strtoupper(substr($fb->teacher->name ?? 'T', 0, 1)) }}
                </div>
                <div class="flex-1 min-w-0">
                    <div class="flex items-center justify-between gap-4">
                        <h4 class="text-sm font-bold text-zinc-900 dark:text-white">
                            Teacher: <span class="text-indigo-600 dark:text-indigo-400 font-extrabold">{{ $fb->teacher->name ?? 'N/A' }}</span>
                        </h4>
                        <span class="text-xxs font-semibold text-zinc-455 dark:text-zinc-500">
                            {{ $fb->created_at ? $fb->created_at->diffForHumans() : '' }}
                        </span>
                    </div>
                    <p class="text-sm text-zinc-600 dark:text-zinc-450 italic mt-3 leading-relaxed">
                        "{{ $fb->remark }}"
                    </p>
                </div>
            </div>
        @empty
            <div class="bg-white dark:bg-zinc-900 p-8 text-center text-zinc-500 dark:text-zinc-400 rounded-2xl border border-zinc-200 dark:border-zinc-800 shadow-sm text-xs">
                No feedback remarks logged by teachers. Keep working hard!
            </div>
        @endforelse
    </div>
</div>
@endsection
