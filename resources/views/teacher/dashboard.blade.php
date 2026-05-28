@extends('layouts.dashboard')

@section('title', 'Teacher Dashboard')
@section('header_title', 'Teacher Overview')

@section('content')
<div class="space-y-8 animate-fade-in">
    <!-- Quick Stats Grid -->
    <div class="grid grid-cols-1 gap-5 sm:grid-cols-3">
        <!-- Assigned Students -->
        <div class="bg-white dark:bg-slate-950 p-6 rounded-2xl border border-slate-100 dark:border-slate-800 shadow-sm flex items-center gap-5">
            <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-indigo-50 dark:bg-indigo-950/50 text-indigo-650 dark:text-indigo-400">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
            </div>
            <div>
                <p class="text-xs font-semibold uppercase tracking-wider text-slate-400 dark:text-slate-500">Assigned Students</p>
                <h3 class="text-2xl font-bold tracking-tight text-slate-900 dark:text-white mt-1">{{ $students->count() }}</h3>
            </div>
        </div>

        <!-- Slow Learners Count -->
        <div class="bg-white dark:bg-slate-950 p-6 rounded-2xl border border-slate-100 dark:border-slate-800 shadow-sm flex items-center gap-5">
            <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-rose-50 dark:bg-rose-950/50 text-rose-650 dark:text-rose-455">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
            </div>
            <div>
                <p class="text-xs font-semibold uppercase tracking-wider text-slate-400 dark:text-slate-500">Slow Learners (< 40%)</p>
                <h3 class="text-2xl font-bold tracking-tight text-rose-600 dark:text-rose-400 mt-1">{{ $slowLearnersCount }}</h3>
            </div>
        </div>

        <!-- Normal Students Count -->
        <div class="bg-white dark:bg-slate-950 p-6 rounded-2xl border border-slate-100 dark:border-slate-800 shadow-sm flex items-center gap-5">
            <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-emerald-50 dark:bg-emerald-950/50 text-emerald-650 dark:text-emerald-400">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <div>
                <p class="text-xs font-semibold uppercase tracking-wider text-slate-400 dark:text-slate-500">Normal Students</p>
                <h3 class="text-2xl font-bold tracking-tight text-emerald-650 dark:text-emerald-450 mt-1">{{ $normalCount }}</h3>
            </div>
        </div>
    </div>

    <!-- Student List Directory with Slow Learner detection -->
    <div class="space-y-4">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="text-lg font-bold text-slate-900 dark:text-white">Active Student Monitoring</h2>
                <p class="text-xs text-slate-400 mt-0.5">Students are classified automatically based on their average assessments marks.</p>
            </div>
            <!-- Section Filter Dropdown -->
            <div class="flex items-center gap-3">
                <label for="class_code" class="text-xs font-semibold text-slate-405 dark:text-slate-400 uppercase tracking-wider whitespace-nowrap">Filter Class:</label>
                <form method="GET" action="{{ route('teacher.dashboard') }}" id="classFilterForm">
                    <select name="class_code" 
                            id="class_code" 
                            onchange="document.getElementById('classFilterForm').submit()"
                            class="rounded-xl border-slate-200 dark:border-slate-850 bg-white dark:bg-slate-900 text-slate-900 dark:text-slate-100 shadow-xxs focus:border-indigo-500 focus:ring-indigo-500 text-xs py-1.5 px-3.5 font-bold">
                        <option value="all" {{ $selectedClass === 'all' ? 'selected' : '' }}>All Sections</option>
                        @foreach($classCodes as $cc)
                            <option value="{{ $cc }}" {{ $selectedClass === $cc ? 'selected' : '' }}>{{ $cc }}</option>
                        @endforeach
                    </select>
                </form>
            </div>
        </div>

        <div class="bg-white dark:bg-slate-950 rounded-2xl border border-slate-100 dark:border-slate-800 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b border-slate-100 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-900/20 text-xxs font-semibold uppercase tracking-wider text-slate-400 dark:text-slate-500">
                            <th class="py-4 px-6">Roll & Name</th>
                            <th class="py-4 px-6">Class</th>
                            <th class="py-4 px-6">Avg Marks</th>
                            <th class="py-4 px-6">Attendance %</th>
                            <th class="py-4 px-6">Status Badge</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800 text-sm">
                        @forelse($students as $student)
                            <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-900/10 transition-colors">
                                <!-- Roll & Name -->
                                <td class="py-4 px-6 font-semibold text-slate-900 dark:text-white">
                                    <div class="flex items-center gap-3">
                                        <div class="h-8 w-8 rounded-lg bg-indigo-50 dark:bg-indigo-950/30 flex items-center justify-center text-indigo-650 dark:text-indigo-400 font-bold text-xs">
                                            {{ $student->student->roll_number ?? '—' }}
                                        </div>
                                        <span>{{ $student->name }}</span>
                                    </div>
                                </td>
                                <!-- Class -->
                                <td class="py-4 px-6 text-slate-500 dark:text-slate-400 font-medium">
                                    {{ $student->student->class ?? 'N/A' }}
                                </td>
                                <!-- Avg Marks -->
                                <td class="py-4 px-6 font-bold text-slate-850 dark:text-slate-200">
                                    @if(!is_null($student->average_marks))
                                        <span class="{{ $student->average_marks < 40 ? 'text-rose-600 dark:text-rose-455' : 'text-emerald-600 dark:text-emerald-450' }}">
                                            {{ $student->average_marks }}%
                                        </span>
                                    @else
                                        <span class="text-slate-400 dark:text-slate-650 text-xs font-normal">No marks</span>
                                    @endif
                                </td>
                                <!-- Attendance % -->
                                <td class="py-4 px-6 font-semibold text-slate-700 dark:text-slate-350">
                                    <div class="flex items-center gap-2">
                                        <span class="text-xs">{{ $student->attendance_percentage }}%</span>
                                        <div class="w-16 bg-slate-100 dark:bg-slate-900 rounded-full h-1.5 overflow-hidden">
                                            <div class="h-full rounded-full {{ $student->attendance_percentage < 75 ? 'bg-amber-500' : 'bg-indigo-600' }}" style="width: {{ $student->attendance_percentage }}%"></div>
                                        </div>
                                    </div>
                                </td>
                                <!-- Status Badge -->
                                <td class="py-4 px-6">
                                    @if($student->status === 'Slow Learner')
                                        <span class="inline-flex items-center rounded-full bg-rose-50 dark:bg-rose-950/45 px-2.5 py-0.5 text-xs font-semibold text-rose-700 dark:text-rose-350 border border-rose-100 dark:border-rose-900/30">
                                            Slow Learner
                                        </span>
                                    @elseif($student->status === 'Normal Student')
                                        <span class="inline-flex items-center rounded-full bg-emerald-50 dark:bg-emerald-950/45 px-2.5 py-0.5 text-xs font-semibold text-emerald-700 dark:text-emerald-350 border border-emerald-100 dark:border-emerald-900/30">
                                            Normal Student
                                        </span>
                                    @else
                                        <span class="inline-flex items-center rounded-full bg-slate-50 dark:bg-slate-900 px-2.5 py-0.5 text-xs font-semibold text-slate-400 border border-slate-200 dark:border-slate-800">
                                            No Data
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-12 text-center text-slate-400 dark:text-slate-500 text-xs">
                                    No students assigned to the system.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
