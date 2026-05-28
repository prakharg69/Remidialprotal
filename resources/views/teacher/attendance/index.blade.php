@extends('layouts.dashboard')

@section('title', 'Daily Attendance')
@section('header_title', 'Student Attendance')

@section('content')
<div class="grid grid-cols-1 gap-6 lg:grid-cols-3 animate-fade-in">
    <!-- Left Column: Attendance Logging Form -->
    <div class="lg:col-span-2 space-y-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="text-lg font-bold text-slate-900 dark:text-white">Mark Daily Attendance</h2>
                <p class="text-xs text-slate-400 mt-0.5">Record present or absent status for all students on a specific date.</p>
            </div>
            <!-- Section Filter Dropdown -->
            <div class="flex items-center gap-3">
                <label for="class_code" class="text-xs font-semibold text-slate-405 dark:text-slate-400 uppercase tracking-wider whitespace-nowrap">Active Section:</label>
                <form method="GET" action="{{ route('teacher.attendance') }}" id="classFilterForm">
                    <select name="class_code" 
                            id="class_code" 
                            onchange="document.getElementById('classFilterForm').submit()"
                            class="rounded-xl border-slate-200 dark:border-slate-850 bg-white dark:bg-slate-900 text-slate-900 dark:text-slate-100 shadow-xxs focus:border-indigo-500 focus:ring-indigo-500 text-xs py-1.5 px-3.5 font-bold">
                        @foreach($classCodes as $cc)
                            <option value="{{ $cc }}" {{ $selectedClass === $cc ? 'selected' : '' }}>{{ $cc }}</option>
                        @endforeach
                    </select>
                </form>
            </div>
        </div>

        <div class="bg-white dark:bg-slate-950 rounded-2xl border border-slate-100 dark:border-slate-800 p-6 shadow-sm">
            <form method="POST" action="{{ route('teacher.attendance.store') }}" class="space-y-6">
                @csrf
                <input type="hidden" name="class_code" value="{{ $selectedClass }}">

                <!-- Date Selector -->
                <div class="max-w-xs">
                    <label for="date" class="block text-sm font-semibold text-slate-700 dark:text-slate-300">
                        Attendance Date
                    </label>
                    <input id="date" 
                           type="date" 
                           name="date" 
                           value="{{ old('date', date('Y-m-d')) }}" 
                           required 
                           class="block mt-1.5 w-full rounded-xl border-slate-200 dark:border-slate-850 bg-white dark:bg-slate-900 text-slate-900 dark:text-slate-100 shadow-xxs focus:border-indigo-500 focus:ring-indigo-500 text-sm py-2.5 px-4" />
                </div>

                <!-- Students Table List -->
                <div class="border border-slate-100 dark:border-slate-800 rounded-xl overflow-hidden mt-6">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b border-slate-100 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-900/10 text-xxs font-semibold uppercase tracking-wider text-slate-400 dark:text-slate-500">
                                <th class="py-3 px-5">Student Name</th>
                                <th class="py-3 px-5 text-right">Attendance Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-slate-800 text-sm">
                            @forelse($students as $st)
                                <tr>
                                    <td class="py-4 px-5 font-semibold text-slate-900 dark:text-white">
                                        {{ $st->name }}
                                    </td>
                                    <td class="py-4 px-5 text-right">
                                        <div class="inline-flex items-center gap-4">
                                            <!-- Present Radio -->
                                            <label class="inline-flex items-center cursor-pointer group">
                                                <input type="radio" 
                                                       name="status[{{ $st->id }}]" 
                                                       value="present" 
                                                       required
                                                       checked
                                                       class="h-4 w-4 border-slate-350 dark:border-slate-700 text-emerald-600 focus:ring-emerald-500 bg-white dark:bg-slate-900" />
                                                <span class="ms-1.5 text-xs font-semibold text-slate-600 dark:text-slate-400 group-hover:text-slate-800 dark:group-hover:text-slate-200">Present</span>
                                            </label>

                                            <!-- Absent Radio -->
                                            <label class="inline-flex items-center cursor-pointer group">
                                                <input type="radio" 
                                                       name="status[{{ $st->id }}]" 
                                                       value="absent" 
                                                       required
                                                       class="h-4 w-4 border-slate-350 dark:border-slate-700 text-rose-600 focus:ring-rose-500 bg-white dark:bg-slate-900" />
                                                <span class="ms-1.5 text-xs font-semibold text-slate-600 dark:text-slate-400 group-hover:text-slate-800 dark:group-hover:text-slate-200">Absent</span>
                                            </label>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="2" class="py-8 text-center text-slate-400 text-xs">
                                        No students registered in the database.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Submit Button -->
                <div class="pt-4 border-t border-slate-100 dark:border-slate-800 flex justify-end">
                    <button type="submit" class="rounded-xl bg-indigo-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 transition-all">
                        Submit Daily Attendance
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Right Column: Recent Activity Logs -->
    <div class="lg:col-span-1 space-y-6">
        <div>
            <h2 class="text-lg font-bold text-slate-900 dark:text-white">Recent Attendance Logs</h2>
            <p class="text-xs text-slate-400 mt-0.5">Logs of recently compiled attendance records.</p>
        </div>

        <div class="bg-white dark:bg-slate-950 rounded-2xl border border-slate-100 dark:border-slate-800 shadow-sm p-6 max-h-128 overflow-y-auto">
            @if($attendanceLogs->count() > 0)
                <div class="flow-root">
                    <ul role="list" class="-my-4 divide-y divide-slate-100 dark:divide-slate-800">
                        @foreach($attendanceLogs as $log)
                            <li class="py-3">
                                <div class="flex items-center justify-between gap-4">
                                    <div class="min-w-0">
                                        <p class="text-xs font-semibold text-slate-900 dark:text-white truncate">
                                            {{ $log->student->name ?? 'N/A' }}
                                        </p>
                                        <p class="text-xxs text-slate-400 dark:text-slate-500 font-medium">
                                            Date: {{ $log->date }}
                                        </p>
                                    </div>
                                    <div>
                                        @if($log->status === 'present')
                                            <span class="inline-flex items-center rounded-md bg-emerald-50 dark:bg-emerald-950/20 px-2 py-0.5 text-xxs font-semibold text-emerald-700 dark:text-emerald-400 border border-emerald-100 dark:border-emerald-900/30">
                                                Present
                                            </span>
                                        @else
                                            <span class="inline-flex items-center rounded-md bg-rose-50 dark:bg-rose-950/20 px-2 py-0.5 text-xxs font-semibold text-rose-700 dark:text-rose-400 border border-rose-100 dark:border-rose-900/30">
                                                Absent
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @else
                <p class="text-center text-xs text-slate-400 py-6">
                    No attendance records marked yet.
                </p>
            @endif
        </div>
    </div>
</div>
@endsection
