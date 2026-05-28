@extends('layouts.dashboard')

@section('title', 'Daily Attendance')
@section('header_title', 'Student Attendance')

@section('content')
<div class="grid grid-cols-1 gap-6 lg:grid-cols-3 animate-fade-in text-zinc-900 dark:text-zinc-100">
    <!-- Left Column: Attendance Logging Form -->
    <div class="lg:col-span-2 space-y-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="text-lg font-bold text-zinc-900 dark:text-white">Mark Daily Attendance</h2>
                <p class="text-xs text-zinc-450 dark:text-zinc-500 mt-0.5">Record present or absent status for all students on a specific date.</p>
            </div>
            <!-- Section Filter Dropdown -->
            <div class="flex items-center gap-3">
                <label for="class_code" class="text-xs font-semibold text-zinc-500 dark:text-zinc-400 uppercase tracking-wider whitespace-nowrap">Active Section:</label>
                <form method="GET" action="{{ route('teacher.attendance') }}" id="classFilterForm">
                    <select name="class_code" 
                            id="class_code" 
                            onchange="document.getElementById('classFilterForm').submit()"
                            class="rounded-xl border-zinc-200 dark:border-zinc-800 bg-white dark:bg-zinc-900 text-zinc-900 dark:text-zinc-100 shadow-xxs focus:border-zinc-500 focus:ring-zinc-500 text-xs py-1.5 px-3.5 font-bold outline-none transition-all">
                        @foreach($classCodes as $cc)
                            <option value="{{ $cc }}" {{ $selectedClass === $cc ? 'selected' : '' }}>{{ $cc }}</option>
                        @endforeach
                    </select>
                </form>
            </div>
        </div>

        <div class="bg-white dark:bg-zinc-900 rounded-2xl border border-zinc-200 dark:border-zinc-800 p-6 shadow-sm">
            <form method="POST" action="{{ route('teacher.attendance.store') }}" class="space-y-6">
                @csrf
                <input type="hidden" name="class_code" value="{{ $selectedClass }}">

                <!-- Date Selector -->
                <div class="max-w-xs">
                    <label for="date" class="block text-xxs font-black uppercase tracking-wider text-zinc-500 dark:text-zinc-400 mb-2">
                        Attendance Date
                    </label>
                    <input id="date" 
                           type="date" 
                           name="date" 
                           value="{{ old('date', date('Y-m-d')) }}" 
                           required 
                           class="block mt-1.5 w-full rounded-xl border-zinc-250 dark:border-zinc-800 bg-white dark:bg-zinc-950 text-zinc-900 dark:text-zinc-100 shadow-xxs focus:border-zinc-500 focus:ring-1 focus:ring-zinc-500 text-sm py-2.5 px-4 outline-none transition-all duration-150" />
                </div>

                <!-- Students Table List -->
                <div class="border border-zinc-200 dark:border-zinc-800 rounded-xl overflow-hidden mt-6">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b border-zinc-200 dark:border-zinc-800 bg-zinc-50/50 dark:bg-zinc-900/50 text-xxs font-semibold uppercase tracking-wider text-zinc-400 dark:text-zinc-500">
                                <th class="py-3 px-5">Student Name</th>
                                <th class="py-3 px-5 text-right">Attendance Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-zinc-200 dark:divide-zinc-800 text-sm">
                            @forelse($students as $st)
                                <tr class="hover:bg-zinc-50/50 dark:hover:bg-zinc-850/20 transition-colors">
                                    <td class="py-4 px-5 font-semibold text-zinc-900 dark:text-white">
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
                                                       class="h-4 w-4 border-zinc-300 dark:border-zinc-700 text-emerald-600 focus:ring-emerald-500 dark:focus:ring-emerald-400 bg-white dark:bg-zinc-950 transition-all" />
                                                <span class="ms-1.5 text-xs font-semibold text-zinc-600 dark:text-zinc-400 group-hover:text-emerald-600 dark:group-hover:text-emerald-400 transition-colors">Present</span>
                                            </label>

                                            <!-- Absent Radio -->
                                            <label class="inline-flex items-center cursor-pointer group">
                                                <input type="radio" 
                                                       name="status[{{ $st->id }}]" 
                                                       value="absent" 
                                                       required
                                                       class="h-4 w-4 border-zinc-300 dark:border-zinc-700 text-rose-600 focus:ring-rose-500 dark:focus:ring-rose-400 bg-white dark:bg-zinc-950 transition-all" />
                                                <span class="ms-1.5 text-xs font-semibold text-zinc-600 dark:text-zinc-400 group-hover:text-rose-600 dark:group-hover:text-rose-400 transition-colors">Absent</span>
                                            </label>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="2" class="py-8 text-center text-zinc-400 text-xs">
                                        No students registered in the database.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Submit Button -->
                <div class="pt-4 border-t border-zinc-200 dark:border-zinc-800 flex justify-end">
                    <button type="submit" class="rounded-xl bg-zinc-900 dark:bg-zinc-100 hover:bg-zinc-800 dark:hover:bg-zinc-200 text-white dark:text-zinc-950 font-extrabold text-sm py-2.5 px-5 shadow-sm transition-all border border-transparent dark:border-zinc-300">
                        Submit Daily Attendance
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Right Column: Recent Activity Logs -->
    <div class="lg:col-span-1 space-y-6">
        <div>
            <h2 class="text-lg font-bold text-zinc-900 dark:text-white">Recent Attendance Logs</h2>
            <p class="text-xs text-zinc-450 dark:text-zinc-500 mt-0.5">Logs of recently compiled attendance records.</p>
        </div>

        <div class="bg-white dark:bg-zinc-900 rounded-2xl border border-zinc-200 dark:border-zinc-800 shadow-sm p-6 max-h-128 overflow-y-auto">
            @if($attendanceLogs->count() > 0)
                <div class="flow-root">
                    <ul role="list" class="-my-4 divide-y divide-zinc-200 dark:divide-zinc-800">
                        @foreach($attendanceLogs as $log)
                            <li class="py-3">
                                <div class="flex items-center justify-between gap-4">
                                    <div class="min-w-0">
                                        <p class="text-xs font-semibold text-zinc-900 dark:text-white truncate">
                                            {{ $log->student->name ?? 'N/A' }}
                                        </p>
                                        <p class="text-xxs text-zinc-400 dark:text-zinc-500 font-medium mt-0.5">
                                            Date: {{ $log->date }}
                                        </p>
                                    </div>
                                    <div>
                                        @if($log->status === 'present')
                                            <span class="inline-flex items-center rounded-lg bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 border border-emerald-500/20 px-2.5 py-0.5 text-xxs font-bold shadow-sm">
                                                Present
                                            </span>
                                        @else
                                            <span class="inline-flex items-center rounded-lg bg-rose-500/10 text-rose-600 dark:text-rose-455 border border-rose-500/20 px-2.5 py-0.5 text-xxs font-bold shadow-sm">
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
                <p class="text-center text-xs text-zinc-400 dark:text-zinc-500 py-6">
                    No attendance records marked yet.
                </p>
            @endif
        </div>
    </div>
</div>
@endsection
