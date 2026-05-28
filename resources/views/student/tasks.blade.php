@extends('layouts.dashboard')

@section('title', 'Remedial Tasks')
@section('header_title', 'My Remedial Tasks')

@section('content')
<div class="space-y-6 animate-fade-in">
    <!-- Header -->
    <div>
        <h2 class="text-lg font-bold text-zinc-900 dark:text-white">Remedial Tasks Checklist</h2>
        <p class="text-xs text-zinc-550 dark:text-zinc-400 mt-0.5">Assigned remedial exercises. Complete these tasks to build capacity and improve subject comprehension.</p>
    </div>

    <!-- Data Table -->
    <div class="bg-white dark:bg-zinc-900 rounded-2xl border border-zinc-200 dark:border-zinc-800 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-zinc-200 dark:border-zinc-800 bg-zinc-50/50 dark:bg-zinc-950/20 text-xxs font-semibold uppercase tracking-wider text-zinc-550 dark:text-zinc-400">
                        <th class="py-4 px-6">Task Title & Details</th>
                        <th class="py-4 px-6">Class</th>
                        <th class="py-4 px-6">Status</th>
                        <th class="py-4 px-6">Assigned Date</th>
                        <th class="py-4 px-6 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-200 dark:divide-zinc-800 text-sm">
                    @forelse($tasks as $task)
                        <tr class="hover:bg-zinc-50/50 dark:hover:bg-zinc-850/10 transition-colors">
                            <!-- Task Details -->
                            <td class="py-4 px-6 max-w-sm whitespace-normal">
                                <div class="flex items-center gap-2">
                                    <span class="block font-bold text-indigo-600 dark:text-indigo-400 font-extrabold">{{ $task->title }}</span>
                                    @if($task->subject)
                                        <span class="inline-flex items-center rounded-md bg-indigo-55 dark:bg-indigo-500/10 px-1.5 py-0.5 text-xxs font-bold text-indigo-700 dark:text-indigo-400 border border-indigo-200 dark:border-indigo-500/20 uppercase">{{ $task->subject->code }}</span>
                                    @endif
                                </div>
                                <span class="block text-xs text-zinc-500 dark:text-zinc-400 mt-1 italic">{{ $task->description }}</span>
                            </td>
                            <!-- Class Column -->
                            <td class="py-4 px-6 text-zinc-900 dark:text-zinc-200 font-medium">
                                {{ $task->student->class_code ?? 'N/A' }}
                            </td>
                            <!-- Status Badge -->
                            <td class="py-4 px-6">
                                @if($task->status === 'completed')
                                    <span class="inline-flex items-center rounded-lg bg-emerald-50 dark:bg-emerald-500/10 px-2.5 py-0.5 text-xs font-semibold text-emerald-700 dark:text-emerald-450 border border-emerald-200 dark:border-emerald-500/20">
                                        Completed
                                    </span>
                                @else
                                    <span class="inline-flex items-center rounded-lg bg-amber-50 dark:bg-amber-500/10 px-2.5 py-0.5 text-xs font-semibold text-amber-700 dark:text-amber-400 border border-amber-200 dark:border-amber-500/20">
                                        Pending Action
                                    </span>
                                @endif
                            </td>
                            <!-- Date Assigned -->
                            <td class="py-4 px-6 text-zinc-455 dark:text-zinc-500">
                                {{ $task->created_at ? $task->created_at->format('M d, Y') : 'N/A' }}
                            </td>
                            <!-- Actions -->
                            <td class="py-4 px-6 text-right">
                                @if($task->status === 'pending')
                                    <a href="{{ route('student.tasks.show', $task->id) }}" class="inline-flex items-center rounded-xl bg-indigo-50 hover:bg-indigo-100 text-indigo-750 border border-indigo-200 dark:bg-indigo-500/10 dark:hover:bg-indigo-500/20 dark:text-indigo-400 dark:border-indigo-500/20 px-3.5 py-2 text-xs font-black shadow-sm transition-all duration-150 uppercase tracking-wider">
                                        Solve Task
                                    </a>
                                @else
                                    <span class="inline-flex items-center text-xs font-semibold text-emerald-600 dark:text-emerald-450 gap-1.5 justify-end">
                                        <svg class="h-4.5 w-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                        Done
                                    </span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-12 text-center text-zinc-500 dark:text-zinc-400 text-xs">
                                Wonderful! No remedial tasks are currently assigned to you.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
