@extends('layouts.dashboard')

@section('title', 'Academic Quizzes Portal')
@section('header_title', 'My Quizzes Portal')

@section('content')
<div class="space-y-8 animate-fade-in text-zinc-900 dark:text-zinc-100">

    <!-- Hero Header -->
    <div class="relative overflow-hidden rounded-3xl bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 p-6 sm:p-8 shadow-sm dark:shadow-xl">
        <div class="absolute right-0 top-0 translate-x-1/3 -translate-y-1/3 h-72 w-72 rounded-full bg-indigo-500/10 blur-3xl pointer-events-none"></div>
        <div class="relative z-10 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h2 class="text-2xl font-black text-zinc-900 dark:text-white tracking-tight">Academic Quizzes Portal</h2>
                <p class="text-xs text-zinc-550 dark:text-zinc-400 mt-1">Access secure MCQ assessments assigned to your class section. Quizzes have strict time and deadline bounds.</p>
            </div>
            <div>
                @if($studentType === 'remedial')
                    <div class="inline-flex items-center gap-2 rounded-2xl bg-rose-50 dark:bg-rose-500/10 border border-rose-200 dark:border-rose-500/30 px-4 py-2 text-xs font-black text-rose-700 dark:text-rose-455 backdrop-blur-md uppercase tracking-wider animate-pulse">
                        <span class="h-2 w-2 rounded-full bg-rose-500"></span>
                        Remedial Quizzes Enabled
                    </div>
                @else
                    <div class="inline-flex items-center gap-2 rounded-2xl bg-emerald-50 dark:bg-emerald-500/10 border border-emerald-200 dark:border-emerald-500/30 px-4 py-2 text-xs font-black text-emerald-700 dark:text-emerald-400 backdrop-blur-md uppercase tracking-wider">
                        <span class="h-2 w-2 rounded-full bg-emerald-500"></span>
                        Normal Quizzes
                    </div>
                @endif
            </div>
        </div>
    </div>

    @if($errors->any())
        <div class="rounded-xl border border-rose-200 dark:border-rose-900/50 bg-rose-50 dark:bg-rose-955/20 p-4 text-rose-700 dark:text-rose-400 text-xs font-semibold leading-relaxed">
            <ul class="list-disc pl-5 space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Quizzes Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($quizzes as $quiz)
            @php
                $hasAttempted = !is_null($quiz->attempt);
                $isExpired = \Carbon\Carbon::now()->gt($quiz->deadline);
                $status = 'not_started';
                if ($hasAttempted) {
                    $status = $quiz->attempt->status; // 'in_progress', 'submitted', 'terminated'
                }
            @endphp
            <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-2xl p-6 shadow-sm dark:shadow-md flex flex-col justify-between group hover:border-zinc-350 dark:hover:border-zinc-700 transition-all duration-150">
                <div>
                    <div class="flex justify-between items-start gap-4 mb-4">
                        <span class="inline-flex items-center rounded-lg bg-indigo-50 dark:bg-indigo-500/10 px-2 py-0.5 text-xxs font-extrabold text-indigo-700 dark:text-indigo-400 border border-indigo-200 dark:border-indigo-500/20 uppercase">
                            {{ $quiz->subjectRelation->name ?? 'Course Subject' }}
                        </span>

                        <!-- Attempt Status Badges -->
                        @if($status === 'not_started')
                            @if($isExpired)
                                <span class="inline-flex items-center rounded-lg bg-zinc-100 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 px-2 py-0.5 text-xxs font-black text-zinc-500 dark:text-zinc-400 uppercase">
                                    Expired
                                </span>
                            @else
                                <span class="inline-flex items-center rounded-lg bg-blue-50 dark:bg-blue-500/10 border border-blue-200 dark:border-blue-500/20 px-2 py-0.5 text-xxs font-black text-blue-700 dark:text-blue-400 uppercase">
                                    Not Started
                                </span>
                            @endif
                        @elseif($status === 'in_progress')
                            <span class="inline-flex items-center rounded-lg bg-amber-50 dark:bg-amber-500/10 border border-amber-200 dark:border-amber-500/20 px-2 py-0.5 text-xxs font-black text-amber-700 dark:text-amber-500 uppercase animate-pulse">
                                In Progress
                            </span>
                        @elseif($status === 'submitted')
                            <span class="inline-flex items-center rounded-lg bg-emerald-50 dark:bg-emerald-500/10 border border-emerald-200 dark:border-emerald-500/20 px-2 py-0.5 text-xxs font-black text-emerald-700 dark:text-emerald-400 uppercase">
                                Graded
                            </span>
                        @elseif($status === 'terminated')
                            <span class="inline-flex items-center rounded-lg bg-rose-50 dark:bg-rose-500/10 border border-rose-200 dark:border-rose-500/20 px-2 py-0.5 text-xxs font-black text-rose-700 dark:text-rose-455 uppercase">
                                Locked Out
                            </span>
                        @endif
                    </div>

                    <h3 class="text-base font-bold text-zinc-900 dark:text-white tracking-tight leading-snug group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors">{{ $quiz->title }}</h3>
                    <p class="text-xs text-zinc-500 dark:text-zinc-400 mt-2 italic leading-relaxed line-clamp-3">Instructions: "{{ $quiz->instructions ?? 'No special instructions.' }}"</p>

                    <div class="mt-5 space-y-2.5 border-t border-zinc-200 dark:border-zinc-800/80 pt-4 text-xxs text-zinc-500 dark:text-zinc-455">
                        <div class="flex items-center justify-between">
                            <span class="font-medium">Total MCQs:</span>
                            <span class="font-bold text-zinc-800 dark:text-zinc-200">{{ count($quiz->questions ?? []) }} Questions</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="font-medium">Allowed Time:</span>
                            <span class="font-bold text-zinc-800 dark:text-zinc-200">{{ $quiz->duration }} Minutes</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="font-medium text-rose-600 dark:text-rose-455">Deadline Time:</span>
                            <span class="font-bold text-rose-600 dark:text-rose-455">{{ $quiz->deadline ? $quiz->deadline->format('M d, Y h:i A') : 'N/A' }}</span>
                        </div>
                    </div>
                </div>

                <div class="mt-6 border-t border-zinc-200 dark:border-zinc-800/85 pt-4 flex items-center justify-between gap-4">
                    @if($hasAttempted && $status !== 'in_progress')
                        <div class="text-xxs text-zinc-500 dark:text-zinc-400">
                            Score: <strong class="text-base font-black text-zinc-900 dark:text-white ml-0.5">{{ $quiz->attempt->score }}</strong> <span class="text-zinc-400 dark:text-zinc-500 font-semibold">/ {{ $quiz->attempt->max_score }}</span>
                        </div>
                        <a href="{{ route('student.quizzes.result', $quiz->attempt->id) }}" class="inline-flex items-center justify-center gap-1 rounded-xl bg-zinc-100 hover:bg-zinc-200 border border-zinc-250 dark:bg-zinc-800 dark:hover:bg-zinc-750 dark:border-zinc-700 text-zinc-750 dark:text-zinc-350 font-bold text-xxs py-2 px-4 shadow transition-all uppercase tracking-wider">
                            View Results
                        </a>
                    @elseif($hasAttempted && $status === 'in_progress')
                        <span class="text-xxs text-amber-600 dark:text-amber-500 font-semibold animate-pulse">Session active</span>
                        <a href="{{ route('student.quizzes.attempt', $quiz->attempt->id) }}" class="inline-flex items-center justify-center gap-1.5 rounded-xl bg-amber-500 hover:bg-amber-600 text-white font-extrabold text-xxs py-2 px-4 shadow shadow-amber-900/10 transition-all uppercase tracking-wider">
                            Resume Quiz &rarr;
                        </a>
                    @else
                        @if($isExpired)
                            <span class="text-xxs text-zinc-400 dark:text-zinc-500 font-semibold italic">Deadline missed</span>
                            <button disabled class="opacity-50 cursor-not-allowed inline-flex items-center justify-center gap-1.5 rounded-xl bg-zinc-100 dark:bg-zinc-850 border border-zinc-200 dark:border-zinc-800 text-zinc-400 dark:text-zinc-550 font-extrabold text-xxs py-2 px-4 shadow uppercase tracking-wider">
                                Expired
                            </button>
                        @else
                            <span class="text-xxs text-zinc-400 dark:text-zinc-500 font-semibold">Ready to start</span>
                            <form action="{{ route('student.quizzes.start', $quiz->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="inline-flex items-center justify-center gap-1.5 rounded-xl bg-gradient-to-r from-indigo-500 to-indigo-600 hover:from-indigo-600 hover:to-indigo-700 text-white font-extrabold text-xxs py-2 px-4 shadow shadow-indigo-900/10 transition-all uppercase tracking-wider">
                                    Start Quiz &rarr;
                                </button>
                            </form>
                        @endif
                    @endif
                </div>
            </div>
        @empty
            <div class="col-span-1 md:col-span-2 lg:col-span-3 py-16 text-center bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-850 rounded-2xl shadow-sm dark:shadow-md">
                <div class="h-12 w-12 rounded-full bg-zinc-100 dark:bg-zinc-850 flex items-center justify-center mx-auto text-zinc-500 mb-3 border border-zinc-200 dark:border-zinc-700">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2"/></svg>
                </div>
                <h4 class="text-sm font-bold text-zinc-500 dark:text-zinc-400">No Active Quizzes Assigned</h4>
                <p class="text-xxs text-zinc-400 dark:text-zinc-500 mt-1">Excellent! There are no pending quizzes assigned to your class and classification.</p>
            </div>
        @endforelse
    </div>
</div>
@endsection
