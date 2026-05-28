@extends('layouts.dashboard')

@section('title', 'Feedback Directory')
@section('header_title', 'All Teacher Feedback Remarks')

@section('content')
<div class="space-y-6 animate-fade-in">
    <div>
        <h2 class="text-lg font-bold text-slate-900 dark:text-white">Feedback Directory</h2>
        <p class="text-xs text-slate-400 mt-0.5">Overview of all registered comments and feedback posted by teachers on student progress.</p>
    </div>

    <div class="bg-white dark:bg-slate-950 rounded-2xl border border-slate-100 dark:border-slate-800 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-slate-100 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-900/20 text-xxs font-semibold uppercase tracking-wider text-slate-400 dark:text-slate-500">
                        <th class="py-4 px-6">Student Name</th>
                        <th class="py-4 px-6">Teacher Name</th>
                        <th class="py-4 px-6">Feedback / Remark</th>
                        <th class="py-4 px-6">Recorded Date</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800 text-sm">
                    @forelse($feedbacks as $fb)
                        <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-900/10 transition-colors">
                            <td class="py-4 px-6 font-semibold text-slate-900 dark:text-white">
                                {{ $fb->student->name ?? 'N/A' }}
                            </td>
                            <td class="py-4 px-6 text-indigo-650 dark:text-indigo-400 font-semibold">
                                {{ $fb->teacher->name ?? 'N/A' }}
                            </td>
                            <td class="py-4 px-6 text-slate-650 dark:text-slate-300 italic max-w-sm whitespace-normal">
                                "{{ $fb->remark }}"
                            </td>
                            <td class="py-4 px-6 text-slate-400 dark:text-slate-500">
                                {{ $fb->created_at ? $fb->created_at->format('M d, Y, h:i A') : 'N/A' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="py-12 text-center text-slate-400 dark:text-slate-500 text-xs">
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
