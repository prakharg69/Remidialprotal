@extends('layouts.dashboard')

@section('title', 'Remedial Tasks')
@section('header_title', 'All Assigned Remedial Tasks')

@section('content')
<div class="space-y-6 animate-fade-in">
    <div>
        <h2 class="text-lg font-bold text-zinc-900 dark:text-white">Remedial Tasks</h2>
        <p class="text-xs text-zinc-500 dark:text-zinc-400 mt-0.5">Overview of active capacity building tasks assigned by teachers to slow learners.</p>
    </div>

    <div class="bg-white dark:bg-zinc-900 rounded-2xl border border-zinc-200 dark:border-zinc-800 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-zinc-200 dark:border-zinc-800 bg-zinc-50/50 dark:bg-zinc-950/20 text-xxs font-semibold uppercase tracking-wider text-zinc-500 dark:text-zinc-400">
                        <th class="py-4 px-6">Student Name</th>
                        <th class="py-4 px-6">Task Title</th>
                        <th class="py-4 px-6">Description</th>
                        <th class="py-4 px-6">Status</th>
                        <th class="py-4 px-6">Assigned Date</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-200 dark:divide-zinc-800 text-sm">
                    @forelse($tasks as $task)
                        <tr class="hover:bg-zinc-50/50 dark:hover:bg-zinc-800/10 transition-colors">
                            <td class="py-4 px-6 font-bold text-zinc-900 dark:text-white">
                                {{ $task->student->name ?? 'N/A' }}
                            </td>
                             <td class="py-4 px-6 font-extrabold text-indigo-600 dark:text-indigo-400">
                                 <div class="flex items-center gap-2">
                                     <span>{{ $task->title }}</span>
                                     @if($task->subject)
                                         <span class="inline-flex items-center rounded-md bg-indigo-50 dark:bg-indigo-500/10 px-1.5 py-0.5 text-xxs font-bold text-indigo-700 dark:text-indigo-400 border border-indigo-200 dark:border-indigo-500/20 uppercase tracking-wider font-extrabold">{{ $task->subject->code }}</span>
                                     @endif
                                 </div>
                             </td>
                            <td class="py-4 px-6 text-zinc-600 dark:text-zinc-400 max-w-xs truncate italic leading-relaxed">
                                {{ $task->description }}
                            </td>
                            <td class="py-4 px-6">
                                @if($task->status === 'completed')
                                    <span class="inline-flex items-center rounded-lg bg-emerald-50 dark:bg-emerald-500/10 px-2.5 py-0.5 text-xs font-semibold text-emerald-700 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-500/20">
                                        Completed
                                    </span>
                                @else
                                    <span class="inline-flex items-center rounded-lg bg-amber-50 dark:bg-amber-500/10 px-2.5 py-0.5 text-xs font-semibold text-amber-700 dark:text-amber-500 border border-amber-200 dark:border-amber-500/20">
                                        Pending
                                    </span>
                                @endif
                            </td>
                            <td class="py-4 px-6 text-zinc-400 dark:text-zinc-500 font-semibold">
                                {{ $task->created_at ? $task->created_at->format('M d, Y') : 'N/A' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-12 text-center text-zinc-500 dark:text-zinc-400 text-xs">
                                No remedial tasks assigned in the database yet.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
