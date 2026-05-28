@extends('layouts.dashboard')

@section('title', 'Student Dashboard')
@section('header_title', 'My Academic Dashboard')

@section('content')
<div class="space-y-8 animate-fade-in text-slate-100" x-data="{ activeTab: 'overview' }">
    
    <!-- Bold Welcome & Profile Hero Header -->
    <div class="relative overflow-hidden rounded-3xl bg-slate-900 border border-slate-800 p-6 sm:p-8 shadow-xl">
        <div class="absolute right-0 top-0 translate-x-1/3 -translate-y-1/3 h-72 w-72 rounded-full bg-indigo-500/10 blur-3xl pointer-events-none"></div>
        <div class="absolute left-1/3 bottom-0 translate-y-1/2 h-48 w-48 rounded-full bg-violet-500/10 blur-3xl pointer-events-none"></div>

        <div class="relative z-10 flex flex-col lg:flex-row lg:items-center justify-between gap-6">
            <div class="flex items-center gap-5">
                <div class="h-16 w-16 rounded-2xl bg-gradient-to-tr from-indigo-500 to-violet-600 flex items-center justify-center font-bold text-2xl text-white shadow-lg shadow-indigo-500/20">
                    {{ strtoupper(substr($user->name, 0, 2)) }}
                </div>
                <div>
                    <span class="text-xxs font-bold uppercase tracking-wider text-indigo-400">Welcome Back</span>
                    <h2 class="text-2xl font-black text-white tracking-tight mt-0.5">{{ $user->name }}</h2>
                    <p class="text-xs text-slate-400 font-medium mt-1">
                        Roll Number: <span class="text-slate-200 font-bold">{{ $profile->roll_number ?? 'Not Set' }}</span> | Course: <span class="text-slate-200 font-bold">{{ $profile->class ?? 'N/A' }}</span>
                    </p>
                </div>
            </div>

            <!-- Performance Classification Badge -->
            <div>
                @if($status === 'Slow Learner')
                    <div class="inline-flex items-center gap-2 rounded-2xl bg-rose-500/10 border border-rose-500/30 px-4 py-2 text-xs font-black text-rose-400 backdrop-blur-md uppercase tracking-wider animate-pulse">
                        <span class="h-2 w-2 rounded-full bg-rose-500"></span>
                        Slow Learner (Remedial Mode)
                    </div>
                @elseif($status === 'Normal Student')
                    <div class="inline-flex items-center gap-2 rounded-2xl bg-emerald-500/10 border border-emerald-500/30 px-4 py-2 text-xs font-black text-emerald-400 backdrop-blur-md uppercase tracking-wider">
                        <span class="h-2 w-2 rounded-full bg-emerald-500"></span>
                        On Track
                    </div>
                @else
                    <div class="inline-flex items-center rounded-2xl bg-slate-800 border border-slate-700 px-4 py-2 text-xs font-black text-slate-400 uppercase tracking-wider">
                        No Marks Logged Yet
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- 3-Card Metrics Panel -->
    <div class="grid grid-cols-1 gap-6 sm:grid-cols-3">
        <!-- Attendance Metric Card -->
        <div class="bg-slate-900 border border-slate-800/80 rounded-2xl p-6 shadow-md flex flex-col justify-between group hover:border-slate-700 transition-all duration-150">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Total Attendance</span>
                <div class="p-2 rounded-xl bg-slate-800 text-indigo-400">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                </div>
            </div>
            <div class="mt-6">
                <div class="flex items-baseline gap-2">
                    <h3 class="text-4xl font-black text-white tracking-tight">{{ $attendancePercentage }}%</h3>
                </div>
                <div class="w-full bg-slate-850 rounded-full h-2.5 mt-4 overflow-hidden">
                    <div class="h-full rounded-full transition-all duration-500 {{ $attendancePercentage < 75 ? 'bg-gradient-to-r from-amber-500 to-orange-500' : 'bg-gradient-to-r from-indigo-500 to-indigo-600' }}" style="width: {{ $attendancePercentage }}%"></div>
                </div>
                <p class="text-xxs text-slate-500 mt-3 font-semibold">Attended {{ $presentDays }} out of {{ $totalDays }} classes.</p>
            </div>
        </div>

        <!-- Academic Average Metric Card -->
        <div class="bg-slate-900 border border-slate-800/80 rounded-2xl p-6 shadow-md flex flex-col justify-between group hover:border-slate-700 transition-all duration-150">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Overall Average</span>
                <div class="p-2 rounded-xl bg-slate-800 text-indigo-400">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
            </div>
            <div class="mt-6">
                <div class="flex items-baseline gap-2">
                    @if($assessments->count() > 0)
                        <h3 class="text-4xl font-black tracking-tight {{ $overallAverage < 40 ? 'text-rose-500' : 'text-emerald-500' }}">{{ $overallAverage }}%</h3>
                    @else
                        <h3 class="text-4xl font-black text-slate-500 tracking-tight">N/A</h3>
                    @endif
                </div>
                <div class="w-full bg-slate-850 rounded-full h-2.5 mt-4 overflow-hidden">
                    <div class="h-full rounded-full transition-all duration-500 {{ $overallAverage < 40 ? 'bg-gradient-to-r from-rose-500 to-red-600' : 'bg-gradient-to-r from-emerald-500 to-teal-500' }}" style="width: {{ $overallAverage }}%"></div>
                </div>
                <p class="text-xxs text-slate-500 mt-3 font-semibold">Based on {{ $assessments->count() }} test records.</p>
            </div>
        </div>

        <!-- Remedial Tasks Metric Card -->
        <div class="bg-slate-900 border border-slate-800/80 rounded-2xl p-6 shadow-md flex flex-col justify-between group hover:border-slate-700 transition-all duration-150">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Remedial Active Tasks</span>
                <div class="p-2 rounded-xl bg-slate-800 text-indigo-400">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002-2"/></svg>
                </div>
            </div>
            <div class="mt-6">
                <div class="flex items-baseline gap-2">
                    <h3 class="text-4xl font-black text-white tracking-tight">{{ $pendingRemedials->count() }}</h3>
                    <span class="text-xs font-bold text-slate-400">pending</span>
                </div>
                <div class="mt-4 flex items-center gap-2">
                    <span class="inline-flex items-center rounded-lg bg-amber-500/10 border border-amber-500/20 px-2.5 py-0.5 text-xxs font-bold text-amber-500">
                        {{ $pendingRemedials->count() }} Pending
                    </span>
                    <span class="inline-flex items-center rounded-lg bg-emerald-500/10 border border-emerald-500/20 px-2.5 py-0.5 text-xxs font-bold text-emerald-500">
                        {{ $remedialTasks->count() - $pendingRemedials->count() }} Reviewed/Done
                    </span>
                </div>
                <p class="text-xxs text-slate-500 mt-3 font-semibold">Private assigned remedial worksheets.</p>
            </div>
        </div>
    </div>

    <!-- Navigation Tabs using AlpineJS -->
    <div class="border-b border-slate-800">
        <nav class="-mb-px flex space-x-8" aria-label="Tabs">
            <button @click="activeTab = 'overview'" :class="activeTab === 'overview' ? 'border-indigo-500 text-indigo-400' : 'border-transparent text-slate-400 hover:text-slate-200 hover:border-slate-700'" class="whitespace-nowrap pb-4 px-1 border-b-2 font-bold text-sm transition-all">
                Academic Overview
            </button>
            <button @click="activeTab = 'assignments'" :class="activeTab === 'assignments' ? 'border-indigo-500 text-indigo-400' : 'border-transparent text-slate-400 hover:text-slate-200 hover:border-slate-700'" class="whitespace-nowrap pb-4 px-1 border-b-2 font-bold text-sm transition-all flex items-center gap-2">
                Pending Assignments
                @if($pendingAssignments->count() > 0)
                    <span class="bg-indigo-500 text-white rounded-full px-2 py-0.5 text-xxs font-extrabold">{{ $pendingAssignments->count() }}</span>
                @endif
            </button>
            <button @click="activeTab = 'remedials'" :class="activeTab === 'remedials' ? 'border-indigo-500 text-indigo-400' : 'border-transparent text-slate-400 hover:text-slate-200 hover:border-slate-700'" class="whitespace-nowrap pb-4 px-1 border-b-2 font-bold text-sm transition-all flex items-center gap-2">
                Remedial Tasks
                @if($pendingRemedials->count() > 0)
                    <span class="bg-rose-500 text-white rounded-full px-2 py-0.5 text-xxs font-extrabold">{{ $pendingRemedials->count() }}</span>
                @endif
            </button>
            <button @click="activeTab = 'completed'" :class="activeTab === 'completed' ? 'border-indigo-500 text-indigo-400' : 'border-transparent text-slate-400 hover:text-slate-200 hover:border-slate-700'" class="whitespace-nowrap pb-4 px-1 border-b-2 font-bold text-sm transition-all">
                Completed Work
            </button>
        </nav>
    </div>

    <!-- Tab 1: Academic Overview -->
    <div x-show="activeTab === 'overview'" class="grid grid-cols-1 gap-6 lg:grid-cols-3 transition-all duration-300">
        <!-- Left 2 Columns: Chart & Remedial Tasks Checklist -->
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-slate-900 border border-slate-800 rounded-3xl p-6 shadow-md">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
                    <div>
                        <h3 class="text-base font-bold text-white">Subject-Wise Assessment Progress</h3>
                        <p class="text-xxs text-slate-500 mt-0.5">Visual overview of your grades compared to the 40% passing threshold line.</p>
                    </div>
                    <div>
                        <a href="{{ route('student.marks') }}" class="inline-flex items-center gap-1.5 rounded-xl bg-slate-800 hover:bg-slate-700/80 border border-slate-700 px-3 py-1.5 text-xxs font-bold text-indigo-400 uppercase tracking-wider transition-all">
                            View All Marks
                            <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                        </a>
                    </div>
                </div>

                <div class="relative h-64 w-full">
                    <canvas id="studentPerformanceChart"></canvas>
                </div>
            </div>

            <!-- Basic Checklist (Quick View) -->
            <div class="bg-slate-900 border border-slate-800 rounded-3xl p-6 shadow-md">
                <h3 class="text-base font-bold text-white">Recent Remedial Tasks Checklist</h3>
                <p class="text-xxs text-slate-500 mt-0.5">Quick view of tasks assigned by your teacher. Switch to the "Remedial Tasks" tab to submit.</p>
                <div class="flow-root mt-6">
                    <ul role="list" class="-my-4 divide-y divide-slate-850">
                        @forelse($remedialTasks->take(3) as $task)
                            <li class="py-4">
                                <div class="flex items-center justify-between gap-4">
                                    <div>
                                        <div class="flex items-center gap-2">
                                            <span class="text-sm font-bold text-slate-100">{{ $task->title }}</span>
                                            <span class="inline-flex items-center rounded-md bg-indigo-500/10 px-1.5 py-0.5 text-xxs font-bold text-indigo-400 border border-indigo-500/20 uppercase">{{ $task->subject->code ?? 'SUB' }}</span>
                                        </div>
                                        <p class="text-xs text-slate-400 mt-1 italic leading-relaxed">{{ Str::limit($task->description, 120) }}</p>
                                    </div>
                                    <div>
                                        @if(!$task->userSubmission)
                                            <button @click="activeTab = 'remedials'" class="text-xs font-bold text-indigo-400 hover:text-indigo-300">Submit Now &rarr;</button>
                                        @else
                                            <span class="text-xs font-bold text-emerald-500 capitalize">{{ $task->userSubmission->status }}</span>
                                        @endif
                                    </div>
                                </div>
                            </li>
                        @empty
                            <div class="py-6 text-center text-xs text-slate-500">
                                No active remedial tasks assigned to you! Great job.
                            </div>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>

        <!-- Right 1 Column: Profile & Remarks -->
        <div class="lg:col-span-1 space-y-6">
            <div class="bg-slate-900 border border-slate-800 rounded-3xl p-6 shadow-md">
                <h3 class="text-sm font-bold text-indigo-400 uppercase tracking-wider">Contact & Profile</h3>
                <div class="mt-6 space-y-4 text-xs">
                    <div>
                        <span class="block text-slate-500 uppercase tracking-wider text-xxs font-bold">Email Address</span>
                        <span class="block text-slate-250 font-semibold mt-1 truncate">{{ $user->email }}</span>
                    </div>
                    <div>
                        <span class="block text-slate-500 uppercase tracking-wider text-xxs font-bold">Phone Number</span>
                        <span class="block text-slate-250 font-semibold mt-1">{{ $profile->phone ?? 'Not Available' }}</span>
                    </div>
                    <div>
                        <span class="block text-slate-500 uppercase tracking-wider text-xxs font-bold">Home Address</span>
                        <span class="block text-slate-250 font-semibold mt-1 leading-relaxed">{{ $profile->address ?? 'Not Set' }}</span>
                    </div>
                </div>
            </div>

            <div class="bg-slate-900 border border-slate-800 rounded-3xl p-6 shadow-md">
                <h3 class="text-sm font-bold text-indigo-400 uppercase tracking-wider">Teacher Remarks</h3>
                <div class="mt-6 flow-root max-h-96 overflow-y-auto pr-2 scrollbar-thin">
                    @if($feedbacks->count() > 0)
                        <ul role="list" class="-my-4 divide-y divide-slate-850">
                            @foreach($feedbacks as $fb)
                                <li class="py-4">
                                    <div class="flex items-start gap-3">
                                        <div class="h-8 w-8 rounded-full bg-slate-800 flex-shrink-0 flex items-center justify-center text-slate-300 text-xs font-bold">
                                            {{ strtoupper(substr($fb->teacher->name ?? 'T', 0, 1)) }}
                                        </div>
                                        <div class="min-w-0 flex-1">
                                            <div class="flex items-center justify-between gap-2">
                                                <span class="text-xs font-bold text-white truncate">Tr. {{ $fb->teacher->name ?? 'N/A' }}</span>
                                                <span class="text-xxs text-slate-500 whitespace-nowrap">{{ $fb->created_at ? $fb->created_at->diffForHumans() : '' }}</span>
                                            </div>
                                            <p class="text-xs text-slate-400 italic mt-1.5 leading-normal">
                                                "{{ $fb->remark }}"
                                            </p>
                                        </div>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <div class="py-8 text-center text-xs text-slate-500">
                            No progress remarks compiled by teachers yet. Keep working hard!
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Tab 2: Pending Assignments -->
    <div x-show="activeTab === 'assignments'" class="space-y-6 transition-all duration-300">
        <div class="mb-4">
            <h3 class="text-xl font-black text-white tracking-tight">Active Student Assignments</h3>
            <p class="text-xs text-slate-400">View and upload your completed files for pending assignments. Supported files: PDF, Word (DOC/DOCX), Images. Max 10MB.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            @forelse($pendingAssignments as $assignment)
                <div class="bg-slate-900 border border-slate-800 rounded-2xl p-6 shadow-md hover:border-slate-700 transition-all flex flex-col justify-between">
                    <div>
                        <div class="flex justify-between items-start gap-4">
                            <div>
                                <span class="inline-flex items-center rounded-lg bg-indigo-500/10 px-2 py-0.5 text-xxs font-extrabold text-indigo-400 border border-indigo-500/20 uppercase mb-2">
                                    {{ $assignment->subject }}
                                </span>
                                <h4 class="text-lg font-bold text-white leading-snug">{{ $assignment->title }}</h4>
                            </div>
                            <span class="inline-flex items-center rounded-lg bg-rose-500/10 border border-rose-500/25 px-2 py-0.5 text-xxs font-extrabold text-rose-455">
                                Due {{ $assignment->due_date ? $assignment->due_date->format('M d, Y') : 'N/A' }}
                            </span>
                        </div>

                        <p class="text-xs text-slate-350 mt-3 leading-relaxed whitespace-pre-line">{{ $assignment->description }}</p>

                        <div class="mt-4 flex items-center gap-4 text-xxs text-slate-500">
                            <span>Teacher: <strong class="text-slate-300 font-semibold">{{ $assignment->teacher->name ?? 'N/A' }}</strong></span>
                            <span>Max Marks: <strong class="text-indigo-400 font-extrabold">{{ $assignment->max_score }}</strong></span>
                        </div>

                        <!-- Check if submission was rejected -->
                        @if($assignment->userSubmission && $assignment->userSubmission->status === 'rejected')
                            <div class="mt-4 rounded-xl border border-rose-900/50 bg-rose-950/20 p-3 text-rose-400">
                                <div class="flex items-center gap-2 mb-1">
                                    <svg class="h-4 w-4 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    <span class="text-xs font-black uppercase tracking-wider">Submission Needs Improvement</span>
                                </div>
                                <p class="text-xxs leading-normal mt-1 text-slate-300">
                                    <strong>Teacher Feedback:</strong> "{{ $assignment->userSubmission->feedback ?? 'No feedback given' }}"
                                </p>
                                <div class="mt-2">
                                    <a href="{{ $assignment->userSubmission->file_url }}" target="_blank" class="inline-flex items-center gap-1 text-xxs text-rose-350 font-bold hover:underline">
                                        View Rejected File
                                    </a>
                                </div>
                            </div>
                        @endif
                    </div>

                    <!-- Local File Upload Form -->
                    <div class="mt-6 border-t border-slate-800 pt-6">
                        <form action="{{ route('student.assignments.submit', $assignment->id) }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                            @csrf
                            <div>
                                <label class="block text-xxs font-black uppercase tracking-wider text-slate-400 mb-2">Upload Completed File</label>
                                <div class="relative flex items-center justify-between border border-dashed border-slate-700 hover:border-slate-500 rounded-xl px-4 py-3 bg-slate-950/50 cursor-pointer transition-all">
                                    <input type="file" name="submission_file" required class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png,.gif">
                                    <div class="flex items-center gap-2.5">
                                        <svg class="h-5 w-5 text-indigo-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                                        <span class="text-xs text-slate-400 font-semibold truncate">Choose file (PDF, DOCX, Image)...</span>
                                    </div>
                                </div>
                            </div>
                            <button type="submit" class="w-full inline-flex items-center justify-center gap-2 rounded-xl bg-gradient-to-r from-indigo-500 to-indigo-600 hover:from-indigo-600 hover:to-indigo-700 text-white font-extrabold text-xs py-2.5 px-4 shadow-md transition-all uppercase tracking-wider">
                                Upload Assignment File
                            </button>
                        </form>
                    </div>
                </div>
            @empty
                <div class="col-span-2 py-12 text-center bg-slate-900 border border-slate-800 rounded-2xl">
                    <div class="h-12 w-12 rounded-full bg-slate-800 flex items-center justify-center mx-auto text-slate-400 mb-3">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <h4 class="text-sm font-bold text-white">No Pending Assignments</h4>
                    <p class="text-xxs text-slate-500 mt-1">Excellent! You are all caught up on all assignments.</p>
                </div>
            @endforelse
        </div>
    </div>

    <!-- Tab 3: Remedial Tasks -->
    <div x-show="activeTab === 'remedials'" class="space-y-6 transition-all duration-300">
        <div class="mb-4">
            <h3 class="text-xl font-black text-white tracking-tight">Assigned Remedial Exercises</h3>
            <p class="text-xs text-slate-400">These tasks are assigned privately to you by your teacher to build capacity. **Students should ONLY upload file submissions**. Submissions are completely isolated and private.</p>
        </div>

        <div class="space-y-4">
            @forelse($pendingRemedials as $task)
                @php
                    $isPending = is_null($task->userSubmission) || $task->userSubmission->status === 'rejected';
                @endphp
                <div class="bg-slate-900 border {{ $isPending ? 'border-rose-500/20' : 'border-slate-800' }} rounded-2xl p-6 shadow-md hover:border-slate-700 transition-all flex flex-col md:flex-row md:items-center justify-between gap-6">
                    <div class="space-y-2 flex-1">
                        <div class="flex items-center gap-2">
                            <h4 class="text-base font-bold text-white">{{ $task->title }}</h4>
                            <span class="inline-flex items-center rounded bg-indigo-500/10 px-1.5 py-0.5 text-xxs font-extrabold text-indigo-400 border border-indigo-500/20 uppercase">
                                {{ $task->subject->name ?? 'Subject' }}
                            </span>
                            @if($task->userSubmission)
                                <span class="inline-flex items-center rounded-lg px-2 py-0.5 text-xxs font-black capitalize {{ $task->userSubmission->status === 'rejected' ? 'bg-rose-500/10 text-rose-400 border border-rose-500/20' : 'bg-amber-500/10 text-amber-500 border border-amber-500/20' }}">
                                    Status: {{ $task->userSubmission->status }}
                                </span>
                            @else
                                <span class="inline-flex items-center rounded-lg bg-red-500/10 border border-red-500/20 px-2 py-0.5 text-xxs font-black text-red-500 uppercase">
                                    Not Submitted
                                </span>
                            @endif
                        </div>
                        <p class="text-xs text-slate-400 leading-relaxed italic">Instructions: "{{ $task->description }}"</p>
                        <div class="flex gap-4 text-xxs text-slate-500 mt-2 font-semibold">
                            <span>Assigned by: <strong class="text-slate-350">{{ $task->teacher->name ?? 'N/A' }}</strong></span>
                            <span>Due Date: <strong class="text-rose-455">{{ $task->due_date ? $task->due_date->format('M d, Y') : 'N/A' }}</strong></span>
                        </div>

                        <!-- Check if submission was rejected -->
                        @if($task->userSubmission && $task->userSubmission->status === 'rejected')
                            <div class="mt-3 rounded-xl border border-rose-900/50 bg-rose-950/20 p-3 text-rose-400 max-w-xl">
                                <span class="text-xxs font-black uppercase tracking-wider block">Needs Improvement / Correction</span>
                                <p class="text-xxs mt-1 text-slate-300">
                                    <strong>Teacher Feedback:</strong> "{{ $task->userSubmission->feedback ?? 'No feedback' }}"
                                </p>
                            </div>
                        @endif
                    </div>

                    <div class="flex-shrink-0 w-full md:w-80 border-t md:border-t-0 md:border-l border-slate-800 pt-4 md:pt-0 md:pl-6">
                        @if($isPending || ($task->userSubmission && $task->userSubmission->status === 'submitted'))
                            <!-- Can submit or re-upload before teacher reviews -->
                            <form action="{{ route('student.remedials.submit', $task->id) }}" method="POST" enctype="multipart/form-data" class="space-y-3">
                                @csrf
                                <div>
                                    <label class="block text-xxs font-black uppercase tracking-wider text-slate-400 mb-1.5">
                                        {{ $task->userSubmission ? 'Re-upload Submission File' : 'Upload Submission File' }}
                                    </label>
                                    <div class="relative flex items-center justify-between border border-dashed border-slate-700 hover:border-slate-500 rounded-xl px-3 py-2 bg-slate-950/50 cursor-pointer transition-all">
                                        <input type="file" name="submission_file" required class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png,.gif">
                                        <div class="flex items-center gap-2">
                                            <svg class="h-4.5 w-4.5 text-indigo-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                                            <span class="text-xxs text-slate-400 font-semibold truncate">Select File...</span>
                                        </div>
                                    </div>
                                </div>
                                <button type="submit" class="w-full inline-flex items-center justify-center gap-1.5 rounded-xl bg-gradient-to-r from-rose-500 to-rose-600 hover:from-rose-600 hover:to-rose-700 text-white font-extrabold text-xxs py-2 px-3 shadow shadow-rose-900/10 transition-all uppercase tracking-wider">
                                    {{ $task->userSubmission ? 'Re-upload Submission' : 'Submit Submission' }}
                                </button>
                            </form>
                            @if($task->userSubmission)
                                <div class="mt-2 text-center">
                                    <a href="{{ asset($task->userSubmission->file_url) }}" target="_blank" class="text-xxs text-indigo-400 font-bold hover:underline">
                                        Open My Uploaded File
                                    </a>
                                </div>
                            @endif
                        @else
                            <!-- Reviewed & Accepted -->
                            <div class="rounded-xl border border-emerald-900/50 bg-emerald-950/20 p-4 text-center">
                                <span class="inline-flex items-center gap-1 text-xs font-black text-emerald-450 uppercase tracking-wider">
                                    <svg class="h-4.5 w-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                    Reviewed & Done
                                </span>
                                <div class="mt-2">
                                    <a href="{{ asset($task->userSubmission->file_url) }}" target="_blank" class="inline-flex items-center gap-1 text-xxs text-emerald-350 font-bold hover:underline">
                                        Open Submitted File
                                    </a>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            @empty
                <div class="py-12 text-center bg-slate-900 border border-slate-800 rounded-2xl">
                    <div class="h-12 w-12 rounded-full bg-slate-800 flex items-center justify-center mx-auto text-slate-400 mb-3">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <h4 class="text-sm font-bold text-white">No Assigned Remedial Tasks</h4>
                    <p class="text-xxs text-slate-500 mt-1">Excellent! No remedial action is currently needed for your classes.</p>
                </div>
            @endforelse
        </div>
    </div>

    <!-- Tab 4: Completed Work -->
    <div x-show="activeTab === 'completed'" class="space-y-6 transition-all duration-300">
        <div class="mb-4">
            <h3 class="text-xl font-black text-white tracking-tight">My Completed & Reviewed Work</h3>
            <p class="text-xs text-slate-400">View graded results, teacher marks, feedback, and performance awards for your assignment and remedial submissions.</p>
        </div>

        <div class="overflow-hidden rounded-2xl bg-slate-900 border border-slate-800 shadow-md">
            <div class="min-w-full overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-800 text-left text-xs">
                    <thead class="bg-slate-950 font-bold text-slate-400 uppercase tracking-wider text-xxs">
                        <tr>
                            <th scope="col" class="py-4 px-6">Task Title & Type</th>
                            <th scope="col" class="py-4 px-6">Graded Score</th>
                            <th scope="col" class="py-4 px-6">Reviewed Date</th>
                            <th scope="col" class="py-4 px-6">Teacher Feedback</th>
                            <th scope="col" class="py-4 px-6">Awards & Badges</th>
                            <th scope="col" class="py-4 px-6 text-right">File</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-850">
                        @forelse($completedWork as $work)
                            <tr class="hover:bg-slate-850/50 transition-colors">
                                <td class="py-4 px-6 whitespace-nowrap">
                                    <div class="font-bold text-white">{{ $work['title'] }}</div>
                                    <div class="flex items-center gap-1.5 mt-1">
                                        <span class="inline-flex items-center rounded-md px-1.5 py-0.5 text-xxs font-extrabold uppercase {{ $work['type'] === 'Assignment' ? 'bg-indigo-500/10 text-indigo-400 border border-indigo-500/20' : 'bg-rose-500/10 text-rose-400 border border-rose-500/20' }}">
                                            {{ $work['type'] }}
                                        </span>
                                    </div>
                                </td>
                                <td class="py-4 px-6 whitespace-nowrap">
                                    <div class="text-base font-black text-white">{{ $work['score'] }} <span class="text-xs font-semibold text-slate-500">/ {{ $work['max_score'] }}</span></div>
                                </td>
                                <td class="py-4 px-6 whitespace-nowrap text-slate-400 font-medium">
                                    {{ $work['reviewed_at'] ? $work['reviewed_at']->format('M d, Y') : 'N/A' }}
                                </td>
                                <td class="py-4 px-6 max-w-xs truncate font-medium text-slate-350 italic">
                                    "{{ $work['feedback'] ?? 'No feedback written' }}"
                                </td>
                                <td class="py-4 px-6 whitespace-nowrap">
                                    <div class="flex flex-col gap-1.5">
                                        @if($work['status'] === 'accepted' || $work['status'] === 'completed')
                                            <span class="inline-flex w-fit items-center rounded-lg bg-emerald-500/10 border border-emerald-500/20 px-2 py-0.5 text-xxs font-black text-emerald-450 uppercase">
                                                Accepted
                                            </span>
                                        @elseif($work['status'] === 'rejected')
                                            <span class="inline-flex w-fit items-center rounded-lg bg-rose-500/10 border border-rose-500/20 px-2 py-0.5 text-xxs font-black text-rose-455 uppercase">
                                                Rejected
                                            </span>
                                        @endif

                                        <!-- Excellent Work Award Badge -->
                                        @if($work['is_excellent'])
                                            <span class="inline-flex w-fit items-center gap-1 rounded-lg bg-gradient-to-r from-amber-500/20 to-orange-500/20 border border-amber-500/30 px-2 py-0.5 text-xxs font-black text-amber-300 uppercase tracking-wide animate-pulse">
                                                ⭐ Excellent Work
                                            </span>
                                        @endif

                                        <!-- Needs Improvement Award Badge -->
                                        @if($work['needs_improvement'])
                                            <span class="inline-flex w-fit items-center gap-1 rounded-lg bg-rose-500/10 border border-rose-500/25 px-2 py-0.5 text-xxs font-black text-rose-400 uppercase tracking-wide">
                                                ⚠️ Needs Improvement
                                            </span>
                                        @endif

                                        <!-- Late Submission Auto-badge -->
                                        @if($work['is_late'])
                                            <span class="inline-flex w-fit items-center gap-1 rounded-lg bg-orange-500/10 border border-orange-500/25 px-2 py-0.5 text-xxs font-black text-orange-400 uppercase tracking-wide">
                                                ⏳ Late Submission
                                            </span>
                                        @endif
                                    </div>
                                </td>
                                <td class="py-4 px-6 whitespace-nowrap text-right">
                                    <a href="{{ asset($work['file_url']) }}" target="_blank" class="inline-flex items-center gap-1 text-xs text-indigo-400 font-extrabold hover:text-indigo-300">
                                        Open File
                                        <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="py-8 text-center text-slate-500 font-medium">
                                    You haven't completed or had any graded work reviewed yet.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Import Chart.js via CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const canvasElement = document.getElementById('studentPerformanceChart');
        if (!canvasElement) return;
        
        const ctx = canvasElement.getContext('2d');
        const subjectData = @json($subjectAverages);
        
        const labels = Object.keys(subjectData).slice(0, 10);
        const dataValues = Object.values(subjectData).map(val => val === 'N/A' ? 0 : parseFloat(val)).slice(0, 10);
        
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'My Average Marks (%)',
                    data: dataValues,
                    backgroundColor: dataValues.map(score => score < 40 ? 'rgba(239, 68, 68, 0.45)' : 'rgba(99, 102, 241, 0.45)'),
                    borderColor: dataValues.map(score => score < 40 ? 'rgba(239, 68, 68, 1)' : 'rgba(99, 102, 241, 1)'),
                    borderWidth: 2,
                    borderRadius: 12,
                    barThickness: 32,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        padding: 12,
                        backgroundColor: 'rgba(15, 23, 42, 0.95)',
                        titleColor: '#fff',
                        bodyColor: '#e2e8f0',
                        borderColor: 'rgba(255, 255, 255, 0.08)',
                        borderWidth: 1,
                        displayColors: false,
                        callbacks: {
                            label: function(context) {
                                return ` Score: ${context.parsed.y}%`;
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        grid: {
                            display: false
                        },
                        ticks: {
                            color: '#94a3b8',
                            font: {
                                family: 'Plus Jakarta Sans',
                                weight: '600',
                                size: 11
                            }
                        }
                    },
                    y: {
                        min: 0,
                        max: 100,
                        grid: {
                            color: 'rgba(255, 255, 255, 0.04)'
                        },
                        ticks: {
                            stepSize: 20,
                            color: '#64748b',
                            font: {
                                family: 'Plus Jakarta Sans',
                                size: 10
                            }
                        }
                    }
                }
            },
            plugins: [{
                id: 'remedialThresholdLine',
                afterDraw: function(chart) {
                    const { ctx, chartArea: { left, right }, scales: { y } } = chart;
                    const yVal = y.getPixelForValue(40);
                    
                    ctx.save();
                    ctx.beginPath();
                    ctx.setLineDash([5, 5]);
                    ctx.moveTo(left, yVal);
                    ctx.lineTo(right, yVal);
                    ctx.strokeStyle = 'rgba(239, 68, 68, 0.65)';
                    ctx.lineWidth = 1.5;
                    ctx.stroke();
                    
                    // Label for threshold
                    ctx.fillStyle = 'rgba(239, 68, 68, 0.8)';
                    ctx.font = 'bold 9px Plus Jakarta Sans';
                    ctx.fillText('PASSING THRESHOLD (40%)', left + 8, yVal - 6);
                    ctx.restore();
                }
            }]
        });
    });
</script>
@endsection
