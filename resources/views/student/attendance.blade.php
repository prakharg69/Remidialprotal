@extends('layouts.dashboard')

@section('title', 'My Attendance')
@section('header_title', 'My Attendance Logs')

@section('content')
<div class="space-y-6 animate-fade-in">
    <!-- Header -->
    <div>
        <h2 class="text-lg font-bold text-zinc-900 dark:text-white">Attendance Logs</h2>
        <p class="text-xs text-zinc-500 dark:text-zinc-400 mt-0.5">Below is a list of all your recorded attendance entries in the system.</p>
    </div>

    <!-- Attendance Performance card -->
    <div class="max-w-md bg-white dark:bg-zinc-900 p-6 rounded-2xl border border-zinc-200 dark:border-zinc-800 shadow-sm flex items-center gap-6">
        <div class="relative flex items-center justify-center">
            <!-- Circular Progress Ring (SVG) -->
            <svg class="h-20 w-20 transform -rotate-90">
                <circle cx="40" cy="40" r="34" stroke-width="6" stroke="currentColor" class="text-zinc-100 dark:text-zinc-800" fill="transparent" />
                <circle cx="40" cy="40" r="34" stroke-width="6" stroke="currentColor" class="text-indigo-600 dark:text-indigo-400" fill="transparent" 
                        stroke-dasharray="213.6" 
                        stroke-dashoffset="{{ 213.6 - (213.6 * $attendancePercentage) / 100 }}" />
            </svg>
            <span class="absolute text-sm font-bold text-zinc-900 dark:text-white">{{ $attendancePercentage }}%</span>
        </div>
        <div>
            <h3 class="text-lg font-bold text-zinc-900 dark:text-white">Overall Attendance Rate</h3>
            <p class="text-xs text-zinc-500 dark:text-zinc-400 mt-1">Recorded: {{ $presentDays }} days present out of {{ $totalDays }} days marked.</p>
            <span class="inline-flex items-center rounded-lg {{ $attendancePercentage < 75 ? 'bg-amber-50 dark:bg-amber-950/20 text-amber-700 dark:text-amber-400 border border-amber-250 dark:border-amber-900/30' : 'bg-emerald-50 dark:bg-emerald-950/20 text-emerald-700 dark:text-emerald-450 border border-emerald-250 dark:border-emerald-900/30' }} px-2 py-0.5 text-xxs font-semibold mt-2.5">
                {{ $attendancePercentage < 75 ? 'Low Attendance' : 'Attendance Adequate' }}
            </span>
        </div>
    </div>

    <!-- Data Table -->
    <div class="bg-white dark:bg-zinc-900 rounded-2xl border border-zinc-200 dark:border-zinc-800 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-zinc-200 dark:border-zinc-800 bg-zinc-50/50 dark:bg-zinc-950/20 text-xxs font-semibold uppercase tracking-wider text-zinc-550 dark:text-zinc-400">
                        <th class="py-4 px-6">Attendance Date</th>
                        <th class="py-4 px-6">Status Badge</th>
                        <th class="py-4 px-6">Record Registered</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-200 dark:divide-zinc-800 text-sm">
                    @forelse($attendanceLogs as $log)
                        <tr class="hover:bg-zinc-50/50 dark:hover:bg-zinc-800/10 transition-colors">
                            <td class="py-4 px-6 text-zinc-700 dark:text-zinc-300 font-semibold">
                                {{ $log->date }}
                            </td>
                            <td class="py-4 px-6">
                                @if($log->status === 'present')
                                    <span class="inline-flex items-center rounded-lg bg-emerald-50 dark:bg-emerald-500/10 px-2.5 py-0.5 text-xs font-semibold text-emerald-700 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-500/20">
                                        Present
                                    </span>
                                @else
                                    <span class="inline-flex items-center rounded-lg bg-rose-50 dark:bg-rose-500/10 px-2.5 py-0.5 text-xs font-semibold text-rose-700 dark:text-rose-455 border border-rose-200 dark:border-rose-500/20">
                                        Absent
                                    </span>
                                @endif
                            </td>
                            <td class="py-4 px-6 text-zinc-455 dark:text-zinc-500">
                                {{ $log->created_at ? $log->created_at->format('M d, Y, h:i A') : 'N/A' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="py-12 text-center text-zinc-500 dark:text-zinc-400 text-xs">
                                No attendance records marked yet.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
