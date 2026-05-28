@extends('layouts.dashboard')

@section('title', 'My Assessment Marks')
@section('header_title', 'My Assessment Marks')

@section('content')
<div class="space-y-6 animate-fade-in">
    <!-- Header -->
    <div>
        <h2 class="text-lg font-bold text-zinc-900 dark:text-white">Subject Assessment Details</h2>
        <p class="text-xs text-zinc-500 dark:text-zinc-400 mt-0.5">Below is a list of all your recorded subject assessment marks.</p>
    </div>

    <!-- Macro performance statistics cards -->
    @php
        $remedialCount = $assessments->filter(fn($a) => $a->percentage < 40)->count();
        $clearedCount = $assessments->filter(fn($a) => $a->percentage >= 40)->count();
    @endphp
    <div class="grid grid-cols-1 gap-5 sm:grid-cols-3">
        <div class="bg-white dark:bg-zinc-900 p-5 rounded-2xl border border-zinc-200 dark:border-zinc-800 shadow-sm">
            <span class="text-xxs font-bold uppercase tracking-wider text-zinc-500 dark:text-zinc-455">Total Subjects</span>
            <div class="flex items-baseline justify-between mt-2">
                <h3 class="text-2xl font-black text-zinc-800 dark:text-white">{{ $assessments->count() }}</h3>
                <span class="text-xxs font-semibold text-emerald-700 dark:text-emerald-500 bg-emerald-50 dark:bg-emerald-500/10 px-2 py-0.5 rounded-lg border border-emerald-200 dark:border-emerald-500/20">{{ $clearedCount }} Clear</span>
            </div>
        </div>
        
        <div class="bg-white dark:bg-zinc-900 p-5 rounded-2xl border border-zinc-200 dark:border-zinc-800 shadow-sm">
            <span class="text-xxs font-bold uppercase tracking-wider text-zinc-500 dark:text-zinc-455">Overall Average</span>
            <div class="flex items-baseline justify-between mt-2">
                <h3 class="text-2xl font-black tracking-tight {{ $overallAverage < 40 ? 'text-rose-600 dark:text-rose-455' : 'text-emerald-600 dark:text-emerald-400' }}">
                    {{ $overallAverage }}%
                </h3>
                <span class="text-xxs font-semibold text-zinc-500 dark:text-zinc-400">Target: 40%</span>
            </div>
        </div>

        <div class="bg-white dark:bg-zinc-900 p-5 rounded-2xl border border-zinc-200 dark:border-zinc-800 shadow-sm">
            <span class="text-xxs font-bold uppercase tracking-wider text-zinc-500 dark:text-zinc-455">Remedial Backlogs</span>
            <div class="flex items-baseline justify-between mt-2">
                <h3 class="text-2xl font-black tracking-tight {{ $remedialCount > 0 ? 'text-rose-600 dark:text-rose-455' : 'text-zinc-500 dark:text-zinc-450' }}">
                    {{ $remedialCount }}
                </h3>
                <span class="text-xxs font-semibold {{ $remedialCount > 0 ? 'text-rose-700 bg-rose-50 dark:text-rose-500/10 px-2 py-0.5 rounded-lg border border-rose-200 dark:border-rose-500/20' : 'text-zinc-600 bg-zinc-100 dark:text-zinc-400 dark:bg-zinc-800 px-2 py-0.5 rounded-lg border border-zinc-200 dark:border-zinc-700' }}">
                    {{ $remedialCount > 0 ? 'Action Required' : 'On Track' }}
                </span>
            </div>
        </div>
    </div>

    <!-- Data Table -->
    <div class="bg-white dark:bg-zinc-900 rounded-2xl border border-zinc-200 dark:border-zinc-800 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-zinc-200 dark:border-zinc-800 bg-zinc-50/50 dark:bg-zinc-950/20 text-xxs font-semibold uppercase tracking-wider text-zinc-550 dark:text-zinc-400">
                        <th class="py-4 px-6">Subject</th>
                        <th class="py-4 px-6 text-center">CA1 (30)</th>
                        <th class="py-4 px-6 text-center">CA2 (30)</th>
                        <th class="py-4 px-6 text-center">End Term (40)</th>
                        <th class="py-4 px-6 text-center">Total (100)</th>
                        <th class="py-4 px-6">Remedial Resource</th>
                        <th class="py-4 px-6 text-center">Remedial Task</th>
                        <th class="py-4 px-6">Date Recorded</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-200 dark:divide-zinc-800 text-sm">
                    @forelse($assessments as $ast)
                        @php
                            // Check if there is an active remedial task for this subject
                            $subjectTask = $tasks->where('subject_id', $ast->subject_id)->first();
                        @endphp
                        <tr class="hover:bg-zinc-50/50 dark:hover:bg-zinc-850/10 transition-colors">
                            <td class="py-4 px-6 font-bold">
                                <span class="block text-indigo-600 dark:text-indigo-400">{{ $ast->subjectRelation->code ?? 'N/A' }}</span>
                                <span class="block text-zinc-550 dark:text-zinc-455 font-medium text-xxs mt-0.5">{{ $ast->subjectRelation->name ?? $ast->subject }}</span>
                            </td>
                            <td class="py-4 px-6 text-center font-medium text-zinc-700 dark:text-zinc-300">
                                {{ is_null($ast->ca1) ? '—' : $ast->ca1 }}
                            </td>
                            <td class="py-4 px-6 text-center font-medium text-zinc-700 dark:text-zinc-300">
                                {{ is_null($ast->ca2) ? '—' : $ast->ca2 }}
                            </td>
                            <td class="py-4 px-6 text-center font-medium text-zinc-700 dark:text-zinc-300">
                                {{ is_null($ast->end_term) ? '—' : $ast->end_term }}
                            </td>
                            <td class="py-4 px-6 text-center font-bold">
                                <span class="inline-block px-2.5 py-0.5 rounded-lg border {{ $ast->percentage < 40 ? 'text-rose-700 bg-rose-50 dark:text-rose-455 dark:bg-rose-500/10 border-rose-200 dark:border-rose-500/20' : 'text-emerald-700 bg-emerald-50 dark:text-emerald-400 dark:bg-emerald-500/10 border-emerald-200 dark:border-emerald-500/20' }}">
                                    {{ $ast->obtained }} / {{ $ast->max_possible }} ({{ $ast->percentage }}%)
                                </span>
                            </td>
                            <td class="py-4 px-6 text-xs text-zinc-500 dark:text-zinc-455 leading-normal max-w-xs truncate" title="{{ $ast->remedial_resource ?? 'None' }}">
                                @if($ast->remedial_resource)
                                    <span class="italic font-medium">"{{ $ast->remedial_resource }}"</span>
                                @else
                                    <span class="text-zinc-400 dark:text-zinc-600">—</span>
                                @endif
                            </td>
                            <td class="py-4 px-6 text-center">
                                @if($ast->percentage < 40)
                                    @if($subjectTask)
                                        @if($subjectTask->status === 'completed')
                                            <span class="inline-flex items-center gap-1 rounded-xl bg-emerald-50 dark:bg-emerald-500/10 border border-emerald-200 dark:border-emerald-500/20 px-2.5 py-1 text-xxs font-black text-emerald-700 dark:text-emerald-500 uppercase tracking-wider">
                                                <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                                Solved
                                            </span>
                                        @else
                                            <a href="{{ route('student.tasks.show', $subjectTask->id) }}" class="inline-flex items-center gap-1.5 rounded-xl bg-indigo-50 hover:bg-indigo-100 text-indigo-750 border border-indigo-200 dark:bg-indigo-500/10 dark:hover:bg-indigo-500/20 dark:text-indigo-400 dark:border-indigo-500/20 font-black text-xxs py-1.5 px-3.5 shadow-sm transition-all duration-150 uppercase tracking-wider">
                                                Solve Task
                                                <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                                            </a>
                                        @endif
                                    @else
                                        <span class="text-zinc-500 dark:text-zinc-600 text-xxs font-semibold uppercase tracking-wider">Pending Task</span>
                                    @endif
                                @else
                                    <span class="inline-flex items-center rounded-lg bg-emerald-50 dark:bg-emerald-500/10 border border-emerald-200 dark:border-emerald-500/20 px-2.5 py-0.5 text-xxs font-bold text-emerald-700 dark:text-emerald-450 uppercase">
                                        Clear
                                    </span>
                                @endif
                            </td>
                            <td class="py-4 px-6 text-zinc-455 dark:text-zinc-500">
                                {{ $ast->created_at ? $ast->created_at->format('M d, Y') : 'N/A' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="py-12 text-center text-zinc-500 dark:text-zinc-400 text-xs">
                                No assessment marks recorded yet.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
