@extends('layouts.dashboard')

@section('title', 'My Attendance')
@section('header_title', 'My Attendance Logs')

@section('content')
<div class="space-y-6 animate-fade-in">
    <!-- Header -->
    <div>
        <h2 class="text-lg font-bold text-slate-900 dark:text-white">Attendance Logs</h2>
        <p class="text-xs text-slate-400 mt-0.5">Below is a list of all your recorded attendance entries in the system.</p>
    </div>

    <!-- Attendance Performance card -->
    <div class="max-w-md bg-white dark:bg-slate-950 p-6 rounded-2xl border border-slate-100 dark:border-slate-800 shadow-sm flex items-center gap-6">
        <div class="relative flex items-center justify-center">
            <!-- Circular Progress Ring (SVG) -->
            <svg class="h-20 w-20 transform -rotate-90">
                <circle cx="40" cy="40" r="34" stroke-width="6" stroke="currentColor" class="text-slate-100 dark:text-slate-900" fill="transparent" />
                <circle cx="40" cy="40" r="34" stroke-width="6" stroke="currentColor" class="text-indigo-600" fill="transparent" 
                        stroke-dasharray="213.6" 
                        stroke-dashoffset="{{ 213.6 - (213.6 * $attendancePercentage) / 100 }}" />
            </svg>
            <span class="absolute text-sm font-bold text-slate-900 dark:text-white">{{ $attendancePercentage }}%</span>
        </div>
        <div>
            <h3 class="text-lg font-bold text-slate-900 dark:text-white">Overall Attendance Rate</h3>
            <p class="text-xs text-slate-400 mt-1">Recorded: {{ $presentDays }} days present out of {{ $totalDays }} days marked.</p>
            <span class="inline-flex items-center rounded-lg {{ $attendancePercentage < 75 ? 'bg-amber-50 dark:bg-amber-950/20 text-amber-700 dark:text-amber-400 border border-amber-100 dark:border-amber-900/30' : 'bg-emerald-50 dark:bg-emerald-950/20 text-emerald-700 dark:text-emerald-400 border border-emerald-100 dark:border-emerald-900/30' }} px-2 py-0.5 text-xxs font-semibold mt-2.5">
                {{ $attendancePercentage < 75 ? 'Low Attendance' : 'Attendance Adequate' }}
            </span>
        </div>
    </div>

    <!-- Data Table -->
    <div class="bg-white dark:bg-slate-950 rounded-2xl border border-slate-100 dark:border-slate-800 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-slate-100 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-900/20 text-xxs font-semibold uppercase tracking-wider text-slate-400 dark:text-slate-500">
                        <th class="py-4 px-6">Attendance Date</th>
                        <th class="py-4 px-6">Status Badge</th>
                        <th class="py-4 px-6">Record Registered</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800 text-sm">
                    @forelse($attendanceLogs as $log)
                        <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-900/10 transition-colors">
                            <td class="py-4 px-6 text-slate-700 dark:text-slate-300 font-semibold">
                                {{ $log->date }}
                            </td>
                            <td class="py-4 px-6">
                                @if($log->status === 'present')
                                    <span class="inline-flex items-center rounded-lg bg-emerald-50 dark:bg-emerald-950/20 px-2.5 py-0.5 text-xs font-semibold text-emerald-700 dark:text-emerald-400 border border-emerald-100 dark:border-emerald-900/30">
                                        Present
                                    </span>
                                @else
                                    <span class="inline-flex items-center rounded-lg bg-rose-50 dark:bg-rose-950/20 px-2.5 py-0.5 text-xs font-semibold text-rose-700 dark:text-rose-455 border border-rose-100 dark:border-rose-900/30">
                                        Absent
                                    </span>
                                @endif
                            </td>
                            <td class="py-4 px-6 text-slate-400 dark:text-slate-500">
                                {{ $log->created_at ? $log->created_at->format('M d, Y, h:i A') : 'N/A' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="py-12 text-center text-slate-400 dark:text-slate-500 text-xs">
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
