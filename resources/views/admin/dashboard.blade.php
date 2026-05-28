@extends('layouts.dashboard')

@section('title', 'Admin Dashboard')
@section('header_title', 'Admin Overview')

@section('content')
<div class="space-y-8 animate-fade-in">
    <!-- Quick Statistics Grid -->
    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-4">
        <!-- Students Stats Card -->
        <div class="relative overflow-hidden rounded-2xl bg-white dark:bg-slate-950 p-6 shadow-sm border border-slate-100 dark:border-slate-800 flex items-center gap-5 group hover:shadow-md transition-all duration-200">
            <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-indigo-50 dark:bg-indigo-950/50 text-indigo-600 dark:text-indigo-400 group-hover:scale-105 transition-all">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                </svg>
            </div>
            <div>
                <p class="text-xs font-semibold uppercase tracking-wider text-slate-400 dark:text-slate-500">Total Students</p>
                <h3 class="text-2xl font-bold tracking-tight text-slate-900 dark:text-white mt-1">{{ $totalStudents }}</h3>
            </div>
        </div>

        <!-- Teachers Stats Card -->
        <div class="relative overflow-hidden rounded-2xl bg-white dark:bg-slate-950 p-6 shadow-sm border border-slate-100 dark:border-slate-800 flex items-center gap-5 group hover:shadow-md transition-all duration-200">
            <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-indigo-50 dark:bg-indigo-950/50 text-indigo-600 dark:text-indigo-400 group-hover:scale-105 transition-all">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                </svg>
            </div>
            <div>
                <p class="text-xs font-semibold uppercase tracking-wider text-slate-400 dark:text-slate-500">Total Teachers</p>
                <h3 class="text-2xl font-bold tracking-tight text-slate-900 dark:text-white mt-1">{{ $totalTeachers }}</h3>
            </div>
        </div>

        <!-- Assessments Stats Card -->
        <div class="relative overflow-hidden rounded-2xl bg-white dark:bg-slate-950 p-6 shadow-sm border border-slate-100 dark:border-slate-800 flex items-center gap-5 group hover:shadow-md transition-all duration-200">
            <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-indigo-50 dark:bg-indigo-950/50 text-indigo-600 dark:text-indigo-400 group-hover:scale-105 transition-all">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <div>
                <p class="text-xs font-semibold uppercase tracking-wider text-slate-400 dark:text-slate-500">Total Assessments</p>
                <h3 class="text-2xl font-bold tracking-tight text-slate-900 dark:text-white mt-1">{{ $totalAssessments }}</h3>
            </div>
        </div>

        <!-- Remedial Tasks Stats Card -->
        <div class="relative overflow-hidden rounded-2xl bg-white dark:bg-slate-950 p-6 shadow-sm border border-slate-100 dark:border-slate-800 flex items-center gap-5 group hover:shadow-md transition-all duration-200">
            <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-indigo-50 dark:bg-indigo-950/50 text-indigo-600 dark:text-indigo-400 group-hover:scale-105 transition-all">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2" />
                </svg>
            </div>
            <div>
                <p class="text-xs font-semibold uppercase tracking-wider text-slate-400 dark:text-slate-500">Remedial Tasks</p>
                <h3 class="text-2xl font-bold tracking-tight text-slate-900 dark:text-white mt-1">{{ $totalTasks }}</h3>
            </div>
        </div>
    </div>

    <!-- Administrative Quick Actions & Recent Remarks Grid -->
    <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
        <!-- Quick Actions Panel -->
        <div class="rounded-2xl border border-slate-100 dark:border-slate-800 bg-white dark:bg-slate-950 p-6 shadow-sm flex flex-col justify-between">
            <div>
                <h3 class="text-base font-bold text-slate-900 dark:text-white">Quick Actions</h3>
                <p class="text-xs text-slate-400 mt-0.5">Shortcuts for essential administrative tasks.</p>
            </div>

            <div class="mt-6 space-y-3">
                <a href="{{ route('admin.teachers.create') }}" class="flex items-center justify-center gap-2 w-full rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white font-semibold text-sm py-2.5 shadow-sm transition-all duration-150">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                    Add New Teacher
                </a>
                <a href="{{ route('admin.students.create') }}" class="flex items-center justify-center gap-2 w-full rounded-xl bg-slate-900 hover:bg-slate-800 dark:bg-slate-800 dark:hover:bg-slate-700 text-white font-semibold text-sm py-2.5 shadow-sm transition-all duration-150 border border-slate-200 dark:border-slate-700">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                    Add New Student
                </a>
            </div>
        </div>

        <!-- Recent Feedback Logs -->
        <div class="lg:col-span-2 rounded-2xl border border-slate-100 dark:border-slate-800 bg-white dark:bg-slate-950 p-6 shadow-sm">
            <h3 class="text-base font-bold text-slate-900 dark:text-white">Recent Feedback Remarks</h3>
            <p class="text-xs text-slate-400 mt-0.5">Latest comments submitted by teachers on student profiles.</p>

            <div class="mt-6 flow-root">
                @if($recentFeedback->count() > 0)
                    <ul role="list" class="-my-5 divide-y divide-slate-100 dark:divide-slate-800">
                        @foreach($recentFeedback as $fb)
                            <li class="py-4">
                                <div class="flex items-start gap-4">
                                    <div class="flex-shrink-0 h-9 w-9 rounded-full bg-slate-100 dark:bg-slate-900 flex items-center justify-center text-slate-600 dark:text-slate-400 font-bold text-sm">
                                        {{ strtoupper(substr($fb->teacher->name ?? 'T', 0, 1)) }}
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-semibold text-slate-900 dark:text-white">
                                            Teacher <span class="text-indigo-600 dark:text-indigo-400">{{ $fb->teacher->name ?? 'N/A' }}</span>
                                            on <span class="text-slate-700 dark:text-slate-300 font-medium">{{ $fb->student->name ?? 'N/A' }}</span>
                                        </p>
                                        <p class="text-xs text-slate-500 dark:text-slate-400 italic mt-0.5">
                                            "{{ $fb->remark }}"
                                        </p>
                                    </div>
                                    <div class="text-xxs font-medium text-slate-400 whitespace-nowrap">
                                        {{ $fb->created_at ? $fb->created_at->diffForHumans() : '' }}
                                    </div>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                @else
                    <div class="py-8 text-center text-xs text-slate-400">
                        No feedback logs registered in the database yet.
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
