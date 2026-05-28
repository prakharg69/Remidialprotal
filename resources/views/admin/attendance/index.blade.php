@extends('layouts.dashboard')

@section('title', 'Attendance Records')
@section('header_title', 'All Daily Attendance Logs')

@section('content')
<div class="space-y-6 animate-fade-in">
    <!-- Header -->
    <div>
        <h2 class="text-lg font-bold text-zinc-900 dark:text-white">Attendance Logs</h2>
        <p class="text-xs text-zinc-500 dark:text-zinc-400 mt-0.5">Global overview of student daily attendance entries in the system.</p>
    </div>

    <!-- Data Table -->
    <div class="bg-white dark:bg-zinc-900 rounded-2xl border border-zinc-200 dark:border-zinc-800 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-zinc-200 dark:border-zinc-800 bg-zinc-50/50 dark:bg-zinc-950/20 text-xxs font-semibold uppercase tracking-wider text-zinc-500 dark:text-zinc-400">
                        <th class="py-4 px-6">Student Name</th>
                        <th class="py-4 px-6">Date</th>
                        <th class="py-4 px-6">Status Badge</th>
                        <th class="py-4 px-6">Log Updated</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-200 dark:divide-zinc-800 text-sm">
                    @forelse($attendance as $att)
                        <tr class="hover:bg-zinc-50/50 dark:hover:bg-zinc-800/10 transition-colors">
                            <td class="py-4 px-6 font-bold text-zinc-900 dark:text-white">
                                {{ $att->student->name ?? 'N/A' }}
                            </td>
                            <td class="py-4 px-6 text-zinc-700 dark:text-zinc-350 font-medium">
                                {{ $att->date }}
                            </td>
                            <td class="py-4 px-6">
                                @if($att->status === 'present')
                                    <span class="inline-flex items-center rounded-lg bg-emerald-50 dark:bg-emerald-500/10 px-2.5 py-0.5 text-xs font-semibold text-emerald-700 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-500/20">
                                        Present
                                    </span>
                                @else
                                    <span class="inline-flex items-center rounded-lg bg-rose-50 dark:bg-rose-500/10 px-2.5 py-0.5 text-xs font-semibold text-rose-700 dark:text-rose-400 border border-rose-200 dark:border-rose-500/20">
                                        Absent
                                    </span>
                                @endif
                            </td>
                            <td class="py-4 px-6 text-zinc-400 dark:text-zinc-500">
                                {{ $att->updated_at ? $att->updated_at->format('M d, Y, h:i A') : 'N/A' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="py-12 text-center text-zinc-500 dark:text-zinc-400 text-xs">
                                No attendance records marked in the database yet.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
