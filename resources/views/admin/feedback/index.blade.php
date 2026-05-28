@extends('layouts.dashboard')

@section('title', 'Feedback Directory')
@section('header_title', 'All Teacher Feedback Remarks')

@section('content')
<div class="space-y-6 animate-fade-in">
    <!-- Header -->
    <div>
        <h2 class="text-lg font-bold text-zinc-900 dark:text-white">Feedback Directory</h2>
        <p class="text-xs text-zinc-500 dark:text-zinc-400 mt-0.5">Overview of all registered comments and feedback posted by teachers on student progress.</p>
    </div>

    <!-- Data Table -->
    <div class="bg-white dark:bg-zinc-900 rounded-2xl border border-zinc-200 dark:border-zinc-800 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-zinc-200 dark:border-zinc-800 bg-zinc-50/50 dark:bg-zinc-950/20 text-xxs font-semibold uppercase tracking-wider text-zinc-500 dark:text-zinc-400">
                        <th class="py-4 px-6">Student Name</th>
                        <th class="py-4 px-6">Teacher Name</th>
                        <th class="py-4 px-6">Feedback / Remark</th>
                        <th class="py-4 px-6">Recorded Date</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-200 dark:divide-zinc-800 text-sm">
                    @forelse($feedbacks as $fb)
                        <tr class="hover:bg-zinc-50/50 dark:hover:bg-zinc-800/10 transition-colors">
                            <td class="py-4 px-6 font-bold text-zinc-900 dark:text-white">
                                {{ $fb->student->name ?? 'N/A' }}
                            </td>
                            <td class="py-4 px-6 text-indigo-600 dark:text-indigo-400 font-extrabold">
                                {{ $fb->teacher->name ?? 'N/A' }}
                            </td>
                            <td class="py-4 px-6 text-zinc-700 dark:text-zinc-300 italic max-w-sm whitespace-normal leading-relaxed">
                                "{{ $fb->remark }}"
                            </td>
                            <td class="py-4 px-6 text-zinc-400 dark:text-zinc-500 font-semibold">
                                {{ $fb->created_at ? $fb->created_at->format('M d, Y, h:i A') : 'N/A' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="py-12 text-center text-zinc-500 dark:text-zinc-400 text-xs">
                                No feedback remarks found in the database.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
