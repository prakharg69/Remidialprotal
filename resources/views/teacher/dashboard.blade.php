@extends('layouts.dashboard')

@section('title', 'Teacher Dashboard')
@section('header_title', 'Teacher Overview')

@section('content')
<div class="space-y-8 animate-fade-in text-zinc-900 dark:text-zinc-100">
    <!-- Quick Stats Grid -->
    <div class="grid grid-cols-1 gap-5 sm:grid-cols-3">
        <!-- Assigned Students -->
        <div class="bg-white dark:bg-zinc-900 p-6 rounded-2xl border border-zinc-200 dark:border-zinc-800 shadow-sm flex items-center gap-5 transition-all hover:scale-[1.01] duration-200">
            <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-indigo-50 border border-indigo-100 dark:bg-indigo-500/10 dark:border-indigo-500/20 text-indigo-650 dark:text-indigo-400 shadow-xxs">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
            </div>
            <div>
                <p class="text-xs font-semibold uppercase tracking-wider text-zinc-450 dark:text-zinc-500">Assigned Students</p>
                <h3 class="text-2xl font-bold tracking-tight text-zinc-900 dark:text-white mt-1">{{ $students->count() }}</h3>
            </div>
        </div>

        <!-- Slow Learners Count -->
        <div class="bg-white dark:bg-zinc-900 p-6 rounded-2xl border border-zinc-200 dark:border-zinc-800 shadow-sm flex items-center gap-5 transition-all hover:scale-[1.01] duration-200">
            <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-rose-50 border border-rose-100 dark:bg-rose-500/10 dark:border-rose-500/20 text-rose-650 dark:text-rose-400 shadow-xxs">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
            </div>
            <div>
                <p class="text-xs font-semibold uppercase tracking-wider text-zinc-450 dark:text-zinc-500">Slow Learners (< 40%)</p>
                <h3 class="text-2xl font-bold tracking-tight text-zinc-900 dark:text-white mt-1">{{ $slowLearnersCount }}</h3>
            </div>
        </div>

        <!-- Normal Students Count -->
        <div class="bg-white dark:bg-zinc-900 p-6 rounded-2xl border border-zinc-200 dark:border-zinc-800 shadow-sm flex items-center gap-5 transition-all hover:scale-[1.01] duration-200">
            <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-emerald-50 border border-emerald-100 dark:bg-emerald-500/10 dark:border-emerald-500/20 text-emerald-650 dark:text-emerald-400 shadow-xxs">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <div>
                <p class="text-xs font-semibold uppercase tracking-wider text-zinc-450 dark:text-zinc-500">Normal Students</p>
                <h3 class="text-2xl font-bold tracking-tight text-zinc-700 dark:text-zinc-300 mt-1">{{ $normalCount }}</h3>
            </div>
        </div>
    </div>

    <!-- Student List Directory with Slow Learner detection -->
    <div class="space-y-4">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="text-lg font-bold text-zinc-900 dark:text-white">Active Student Monitoring</h2>
                <p class="text-xs text-zinc-450 dark:text-zinc-500 mt-0.5">Students are classified automatically based on their average assessments marks.</p>
            </div>
            <!-- Section Filter Dropdown -->
            <div class="flex items-center gap-3">
                <label for="class_code" class="text-xs font-semibold text-zinc-500 dark:text-zinc-400 uppercase tracking-wider whitespace-nowrap">Filter Class:</label>
                <form method="GET" action="{{ route('teacher.dashboard') }}" id="classFilterForm">
                    <select name="class_code" 
                            id="class_code" 
                            onchange="document.getElementById('classFilterForm').submit()"
                            class="rounded-xl border-zinc-200 dark:border-zinc-800 bg-white dark:bg-zinc-900 text-zinc-900 dark:text-zinc-100 shadow-xxs focus:border-zinc-500 focus:ring-zinc-500 text-xs py-1.5 px-3.5 font-bold outline-none transition-all">
                        <option value="all" {{ $selectedClass === 'all' ? 'selected' : '' }}>All Sections</option>
                        @foreach($classCodes as $cc)
                            <option value="{{ $cc }}" {{ $selectedClass === $cc ? 'selected' : '' }}>{{ $cc }}</option>
                        @endforeach
                    </select>
                </form>
            </div>
        </div>

        <div class="bg-white dark:bg-zinc-900 rounded-2xl border border-zinc-200 dark:border-zinc-800 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b border-zinc-200 dark:border-zinc-800 bg-zinc-50/50 dark:bg-zinc-900/50 text-xxs font-semibold uppercase tracking-wider text-zinc-400 dark:text-zinc-500">
                            <th class="py-4 px-6">Roll & Name</th>
                            <th class="py-4 px-6">Class</th>
                            <th class="py-4 px-6">Avg Marks</th>
                            <th class="py-4 px-6">Attendance %</th>
                            <th class="py-4 px-6">Status Badge</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-zinc-200 dark:divide-zinc-800 text-sm">
                        @forelse($students as $student)
                            <tr class="hover:bg-zinc-50/50 dark:hover:bg-zinc-850/20 transition-colors">
                                <!-- Roll & Name -->
                                <td class="py-4 px-6 font-semibold text-zinc-900 dark:text-white">
                                    <div class="flex items-center gap-3">
                                        <div class="h-8 w-8 rounded-lg bg-zinc-100 dark:bg-zinc-800 flex items-center justify-center text-zinc-800 dark:text-zinc-300 font-bold text-xs">
                                            {{ $student->student->roll_number ?? '—' }}
                                        </div>
                                        <span>{{ $student->name }}</span>
                                    </div>
                                </td>
                                <!-- Class -->
                                <td class="py-4 px-6 text-zinc-500 dark:text-zinc-400 font-medium">
                                    {{ $student->student->class ?? 'N/A' }}
                                </td>
                                <!-- Avg Marks -->
                                <td class="py-4 px-6 font-bold text-zinc-850 dark:text-zinc-200">
                                    @if(!is_null($student->average_marks))
                                        @if($student->average_marks < 40)
                                            <span class="inline-flex items-center rounded-lg bg-rose-50 text-rose-700 border border-rose-200/60 dark:bg-rose-500/10 dark:text-rose-400 dark:border-rose-500/25 px-2 py-0.5 font-bold">
                                                {{ $student->average_marks }}%
                                            </span>
                                        @else
                                            <span class="inline-flex items-center rounded-lg bg-emerald-50 text-emerald-700 border border-emerald-200/60 dark:bg-emerald-500/10 dark:text-emerald-400 dark:border-emerald-500/25 px-2 py-0.5 font-bold">
                                                {{ $student->average_marks }}%
                                            </span>
                                        @endif
                                    @else
                                        <span class="text-zinc-400 dark:text-zinc-600 text-xs font-normal">No marks</span>
                                    @endif
                                </td>
                                <!-- Attendance % -->
                                <td class="py-4 px-6 font-semibold text-zinc-700 dark:text-zinc-350">
                                    <div class="flex items-center gap-2">
                                        <span class="text-xs">{{ $student->attendance_percentage }}%</span>
                                        <div class="w-16 bg-zinc-100 dark:bg-zinc-850 rounded-full h-1.5 overflow-hidden">
                                            <div class="h-full rounded-full bg-zinc-700 dark:bg-zinc-400" style="width: {{ $student->attendance_percentage }}%"></div>
                                        </div>
                                    </div>
                                </td>
                                <!-- Status Badge -->
                                <td class="py-4 px-6">
                                    @if($student->status === 'Slow Learner')
                                        <span class="inline-flex items-center rounded-lg bg-rose-50 dark:bg-rose-500/10 text-rose-700 dark:text-rose-400 px-2.5 py-0.5 text-xs font-bold border border-rose-250 dark:border-rose-500/20 shadow-xxs">
                                            Slow Learner
                                        </span>
                                    @elseif($student->status === 'Normal Student')
                                        <span class="inline-flex items-center rounded-lg bg-emerald-50 dark:bg-emerald-500/10 text-emerald-700 dark:text-emerald-400 px-2.5 py-0.5 text-xs font-bold border border-emerald-250 dark:border-emerald-500/20 shadow-xxs">
                                            Normal Student
                                        </span>
                                    @else
                                        <span class="inline-flex items-center rounded-lg bg-zinc-100/70 dark:bg-zinc-800/40 text-zinc-500 dark:text-zinc-400 px-2.5 py-0.5 text-xs font-semibold border border-zinc-200 dark:border-zinc-800">
                                            No Data
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-12 text-center text-zinc-400 dark:text-zinc-500 text-xs">
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
