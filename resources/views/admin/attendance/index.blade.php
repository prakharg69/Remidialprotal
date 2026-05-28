@extends('layouts.dashboard')

@section('title', 'Attendance Records')
@section('header_title', 'All Daily Attendance Logs')

@section('content')
<div class="space-y-6 animate-fade-in">
    <div>
        <h2 class="text-lg font-bold text-slate-900 dark:text-white">Attendance Logs</h2>
        <p class="text-xs text-slate-400 mt-0.5">Global overview of student daily attendance entries in the system.</p>
    </div>

    <div class="bg-white dark:bg-slate-950 rounded-2xl border border-slate-100 dark:border-slate-800 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-slate-100 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-900/20 text-xxs font-semibold uppercase tracking-wider text-slate-400 dark:text-slate-500">
                        <th class="py-4 px-6">Student Name</th>
                        <th class="py-4 px-6">Date</th>
                        <th class="py-4 px-6">Status Badge</th>
                        <th class="py-4 px-6">Log Updated</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800 text-sm">
                    @forelse($attendance as $att)
                        <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-900/10 transition-colors">
                            <td class="py-4 px-6 font-semibold text-slate-900 dark:text-white">
                                {{ $att->student->name ?? 'N/A' }}
                            </td>
                            <td class="py-4 px-6 text-slate-600 dark:text-slate-350 font-medium">
                                {{ $att->date }}
                            </td>
                            <td class="py-4 px-6">
                                @if($att->status === 'present')
                                    <span class="inline-flex items-center rounded-lg bg-emerald-50 dark:bg-emerald-950/20 px-2.5 py-0.5 text-xs font-semibold text-emerald-700 dark:text-emerald-400 border border-emerald-100 dark:border-emerald-900/30">
                                        Present
                                    </span>
                                @else
                                    <span class="inline-flex items-center rounded-lg bg-rose-50 dark:bg-rose-950/20 px-2.5 py-0.5 text-xs font-semibold text-rose-700 dark:text-rose-400 border border-rose-100 dark:border-rose-900/30">
                                        Absent
                                    </span>
                                @endif
                            </td>
                            <td class="py-4 px-6 text-slate-400 dark:text-slate-500">
                                {{ $att->updated_at ? $att->updated_at->format('M d, Y, h:i A') : 'N/A' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="py-12 text-center text-slate-400 dark:text-slate-500 text-xs">
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
