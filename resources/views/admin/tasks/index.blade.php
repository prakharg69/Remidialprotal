@extends('layouts.dashboard')

@section('title', 'Remedial Tasks')
@section('header_title', 'All Assigned Remedial Tasks')

@section('content')
<div class="space-y-6 animate-fade-in">
    <div>
        <h2 class="text-lg font-bold text-slate-900 dark:text-white">Remedial Tasks</h2>
        <p class="text-xs text-slate-400 mt-0.5">Overview of active capacity building tasks assigned by teachers to slow learners.</p>
    </div>

    <div class="bg-white dark:bg-slate-950 rounded-2xl border border-slate-100 dark:border-slate-800 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-slate-100 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-900/20 text-xxs font-semibold uppercase tracking-wider text-slate-400 dark:text-slate-500">
                        <th class="py-4 px-6">Student Name</th>
                        <th class="py-4 px-6">Task Title</th>
                        <th class="py-4 px-6">Description</th>
                        <th class="py-4 px-6">Status</th>
                        <th class="py-4 px-6">Assigned Date</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800 text-sm">
                    @forelse($tasks as $task)
                        <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-900/10 transition-colors">
                            <td class="py-4 px-6 font-semibold text-slate-900 dark:text-white">
                                {{ $task->student->name ?? 'N/A' }}
                            </td>
                             <td class="py-4 px-6 font-semibold text-indigo-600 dark:text-indigo-400">
                                 <div class="flex items-center gap-2">
                                     <span>{{ $task->title }}</span>
                                     @if($task->subject)
                                         <span class="inline-flex items-center rounded-md bg-indigo-500/10 px-1.5 py-0.5 text-xxs font-bold text-indigo-400 border border-indigo-500/20 uppercase tracking-wider">{{ $task->subject->code }}</span>
                                     @endif
                                 </div>
                             </td>
                            <td class="py-4 px-6 text-slate-500 dark:text-slate-400 max-w-xs truncate">
                                {{ $task->description }}
                            </td>
                            <td class="py-4 px-6">
                                @if($task->status === 'completed')
                                    <span class="inline-flex items-center rounded-lg bg-emerald-50 dark:bg-emerald-950/20 px-2.5 py-0.5 text-xs font-semibold text-emerald-700 dark:text-emerald-400 border border-emerald-100 dark:border-emerald-900/30">
                                        Completed
                                    </span>
                                @else
                                    <span class="inline-flex items-center rounded-lg bg-amber-50 dark:bg-amber-950/20 px-2.5 py-0.5 text-xs font-semibold text-amber-700 dark:text-amber-400 border border-amber-100 dark:border-amber-900/30">
                                        Pending
                                    </span>
                                @endif
                            </td>
                            <td class="py-4 px-6 text-slate-400 dark:text-slate-500">
                                {{ $task->created_at ? $task->created_at->format('M d, Y') : 'N/A' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-12 text-center text-slate-400 dark:text-slate-500 text-xs">
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
