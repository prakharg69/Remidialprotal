@extends('layouts.dashboard')

@section('title', 'Quiz Submissions & Cheating Logs')
@section('header_title', 'Quiz Submissions & Cheating Logs')

@section('content')
<div class="space-y-8 animate-fade-in text-zinc-900 dark:text-zinc-100" x-data="{ showLogsModal: false, activeAttemptId: '', logsList: [], loadingLogs: false, fetchLogs(attemptId) {
    this.activeAttemptId = attemptId;
    this.showLogsModal = true;
    this.loadingLogs = true;
    this.logsList = [];
    fetch('/teacher/quizzes/attempts/' + attemptId + '/violations')
        .then(res => res.json())
        .then(data => {
            this.logsList = data;
            this.loadingLogs = false;
        })
        .catch(err => {
            console.error(err);
            this.loadingLogs = false;
        });
} }">

    <!-- Hero Header -->
    <div class="relative overflow-hidden rounded-3xl bg-zinc-900 border border-zinc-850 p-6 sm:p-8 shadow-sm">
        <div class="absolute right-0 top-0 translate-x-1/3 -translate-y-1/3 h-72 w-72 rounded-full bg-zinc-500/10 blur-3xl pointer-events-none"></div>
        <div class="relative z-10 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h2 class="text-2xl font-black text-white tracking-tight">Quiz Attempts & Cheating Logs</h2>
                <p class="text-xs text-zinc-400 mt-1">Monitor real-time student grades, durations, scores, and track browser tab-switch violations.</p>
            </div>
            <div>
                <a href="{{ route('teacher.quizzes.index') }}" class="inline-flex items-center gap-2 rounded-xl bg-zinc-800 hover:bg-zinc-755 border border-zinc-700 text-zinc-300 font-extrabold text-xs py-2.5 px-4 shadow transition-all uppercase tracking-wider">
                    <svg class="h-4.5 w-4.5 text-zinc-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                    Back to Quizzes
                </a>
            </div>
        </div>
    </div>

    <!-- Filters Panel -->
    <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-2xl p-5 shadow-sm">
        <form action="{{ route('teacher.quizzes.attempts') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-4 gap-4 items-end">
            <div>
                <label class="block text-xxs font-black uppercase tracking-wider text-zinc-500 dark:text-zinc-400 mb-2">Filter Subject</label>
                <select name="subject_id" class="w-full rounded-xl bg-white dark:bg-zinc-950 border border-zinc-200 dark:border-zinc-800 focus:border-zinc-500 focus:ring-1 focus:ring-zinc-500 text-xs text-zinc-900 dark:text-zinc-100 px-4 py-2.5 outline-none transition-all">
                    <option value="">All Subjects</option>
                    @foreach($subjects as $subj)
                        <option value="{{ $subj->id }}" {{ request('subject_id') == $subj->id ? 'selected' : '' }}>{{ $subj->code }} - {{ $subj->name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-xxs font-black uppercase tracking-wider text-zinc-500 dark:text-zinc-400 mb-2">Filter Class</label>
                <select name="class_code" class="w-full rounded-xl bg-white dark:bg-zinc-950 border border-zinc-200 dark:border-zinc-800 focus:border-zinc-500 focus:ring-1 focus:ring-zinc-500 text-xs text-zinc-900 dark:text-zinc-100 px-4 py-2.5 outline-none transition-all">
                    <option value="">All Classes</option>
                    @foreach($classCodes as $code)
                        <option value="{{ $code }}" {{ request('class_code') == $code ? 'selected' : '' }}>{{ $code }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-xxs font-black uppercase tracking-wider text-zinc-500 dark:text-zinc-400 mb-2">Filter Student Type</label>
                <select name="student_type" class="w-full rounded-xl bg-white dark:bg-zinc-950 border border-zinc-200 dark:border-zinc-800 focus:border-zinc-500 focus:ring-1 focus:ring-zinc-500 text-xs text-zinc-900 dark:text-zinc-100 px-4 py-2.5 outline-none transition-all">
                    <option value="">All Types</option>
                    <option value="normal" {{ request('student_type') == 'normal' ? 'selected' : '' }}>Normal Students</option>
                    <option value="remedial" {{ request('student_type') == 'remedial' ? 'selected' : '' }}>Remedial Students</option>
                </select>
            </div>

            <div>
                <button type="submit" class="w-full inline-flex items-center justify-center gap-2 rounded-xl bg-zinc-900 dark:bg-zinc-100 hover:bg-zinc-800 dark:hover:bg-zinc-200 text-white dark:text-zinc-955 font-extrabold text-xs py-3 px-4 shadow transition-all uppercase tracking-wider border border-transparent dark:border-zinc-350">
                    Apply Filters
                </button>
            </div>
        </form>
    </div>

    <!-- Attempts Table/List -->
    <div class="overflow-hidden rounded-2xl bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 shadow-sm">
        <div class="min-w-full overflow-x-auto">
            <table class="min-w-full divide-y divide-zinc-200 dark:divide-zinc-800 text-left text-xs">
                <thead class="bg-zinc-50/50 dark:bg-zinc-900/50 font-bold text-zinc-450 dark:text-zinc-500 uppercase tracking-wider text-xxs border-b border-zinc-200 dark:border-zinc-800">
                    <tr>
                        <th scope="col" class="py-4 px-6">Student</th>
                        <th scope="col" class="py-4 px-6">Quiz Title & Target</th>
                        <th scope="col" class="py-4 px-6">Grade / Score</th>
                        <th scope="col" class="py-4 px-6">Status</th>
                        <th scope="col" class="py-4 px-6">Tab Switches</th>
                        <th scope="col" class="py-4 px-6">Attempt Timestamps</th>
                        <th scope="col" class="py-4 px-6 text-right">Violation Details</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-200 dark:divide-zinc-800 text-sm text-zinc-900 dark:text-zinc-100">
                    @forelse($attempts as $item)
                        @php
                            $isExcellent = $item->isExcellent();
                            $needsImprovement = $item->needsImprovement();
                            $isLate = $item->isLate();
                        @endphp
                        <tr class="hover:bg-zinc-50/50 dark:hover:bg-zinc-850/20 transition-colors">
                            <td class="py-4 px-6 whitespace-nowrap">
                                <div class="flex items-center gap-3">
                                    <div class="h-9 w-9 rounded-xl bg-zinc-105 dark:bg-zinc-800 flex items-center justify-center font-black text-xs text-zinc-800 dark:text-zinc-300 border border-zinc-200 dark:border-zinc-700 shadow-xxs">
                                        {{ strtoupper(substr($item->student->name ?? 'ST', 0, 2)) }}
                                    </div>
                                    <div>
                                        <div class="font-bold text-zinc-900 dark:text-white">{{ $item->student->name ?? 'N/A' }}</div>
                                        <div class="text-xxxxs uppercase tracking-wider font-extrabold text-zinc-455 dark:text-zinc-500 mt-0.5">Section: {{ $item->student->class_code ?? 'N/A' }}</div>
                                    </div>
                                </div>
                            </td>

                            <td class="py-4 px-6">
                                <div class="font-bold text-zinc-900 dark:text-white">{{ $item->quiz->title ?? 'N/A' }}</div>
                                <div class="flex items-center gap-1.5 mt-1">
                                    <span class="inline-flex items-center rounded-md px-1.5 py-0.5 text-xxs font-extrabold uppercase bg-indigo-50 dark:bg-indigo-500/10 text-indigo-700 dark:text-indigo-400 border border-indigo-200/60 dark:border-indigo-500/20">
                                        {{ $item->quiz->subjectRelation->code ?? 'SUB' }}
                                    </span>
                                    @if($item->quiz->student_type === 'remedial')
                                        <span class="inline-flex items-center rounded-md px-1.5 py-0.5 text-xxs font-extrabold uppercase bg-rose-50 dark:bg-rose-500/10 text-rose-700 dark:text-rose-400 border border-rose-200/60 dark:border-rose-500/20">
                                            Remedial
                                        </span>
                                    @else
                                        <span class="inline-flex items-center rounded-md px-1.5 py-0.5 text-xxs font-extrabold uppercase bg-emerald-50 dark:bg-emerald-500/10 text-emerald-700 dark:text-emerald-400 border border-emerald-200/60 dark:border-emerald-500/20">
                                            Normal
                                        </span>
                                    @endif
                                </div>
                            </td>

                            <td class="py-4 px-6 whitespace-nowrap">
                                <div class="flex items-baseline gap-1 text-base font-black text-zinc-900 dark:text-white">
                                    {{ $item->score }}
                                    <span class="text-xs font-semibold text-zinc-400 dark:text-zinc-500">/ {{ $item->max_score }}</span>
                                </div>
                                <div class="flex flex-wrap gap-1 mt-1.5">
                                    @if($isExcellent)
                                        <span class="inline-flex rounded bg-emerald-500/10 border border-emerald-500/20 px-1.5 py-0.5 text-xxxxs font-black text-emerald-600 dark:text-emerald-400 uppercase tracking-wider">
                                            Excellent
                                        </span>
                                    @endif
                                    @if($needsImprovement)
                                        <span class="inline-flex rounded bg-rose-500/10 border border-rose-500/20 px-1.5 py-0.5 text-xxxxs font-black text-rose-600 dark:text-rose-455 uppercase tracking-wider">
                                            Weak
                                        </span>
                                    @endif
                                </div>
                            </td>

                            <td class="py-4 px-6 whitespace-nowrap">
                                @if($item->status === 'in_progress')
                                    <span class="inline-flex items-center gap-1 rounded-lg px-2.5 py-0.5 font-bold uppercase bg-amber-500/10 text-amber-600 dark:text-amber-400 border border-amber-500/20 animate-pulse">
                                        Attempting
                                    </span>
                                @elseif($item->status === 'submitted')
                                    <span class="inline-flex items-center gap-1 rounded-lg px-2.5 py-0.5 font-bold uppercase bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 border border-emerald-500/20">
                                        Graded
                                    </span>
                                @elseif($item->status === 'terminated')
                                    <span class="inline-flex items-center gap-1 rounded-lg px-2.5 py-0.5 font-black uppercase bg-rose-500/10 text-rose-600 dark:text-rose-455 border border-rose-500/30">
                                        Terminated
                                    </span>
                                @endif
                            </td>

                            <td class="py-4 px-6 whitespace-nowrap">
                                @php
                                    $switches = $item->tab_switch_count ?? 0;
                                @endphp
                                @if($switches === 0)
                                    <span class="inline-flex items-center rounded-lg bg-emerald-500/10 border border-emerald-500/20 px-2.5 py-0.5 text-xxs font-black text-emerald-600 dark:text-emerald-400 shadow-sm">
                                        0 Switches
                                    </span>
                                @elseif($switches === 1)
                                    <span class="inline-flex items-center rounded-lg bg-amber-500/10 border border-amber-500/20 px-2.5 py-0.5 text-xxs font-black text-amber-600 dark:text-amber-400 shadow-sm">
                                        1 Switch (Warned)
                                    </span>
                                @elseif($switches === 2)
                                    <span class="inline-flex items-center rounded-lg bg-orange-500/10 border border-orange-500/20 px-2.5 py-0.5 text-xxs font-black text-orange-600 dark:text-orange-400 shadow-sm">
                                        2 Switches (Warned)
                                    </span>
                                @else
                                    <span class="inline-flex items-center rounded-lg bg-rose-500/10 border border-rose-500/30 px-2.5 py-0.5 text-xxs font-black text-rose-600 dark:text-rose-455 shadow-sm">
                                        {{ $switches }} Switches (Locked Out)
                                    </span>
                                @endif
                            </td>

                            <td class="py-4 px-6 whitespace-nowrap text-xxs font-medium text-zinc-450 dark:text-zinc-500">
                                <div class="space-y-0.5 leading-normal">
                                    <div>Started: <strong class="text-zinc-700 dark:text-zinc-300">{{ $item->started_at ? $item->started_at->format('M d, h:i A') : 'N/A' }}</strong></div>
                                    <div>Ended: <strong class="text-zinc-700 dark:text-zinc-300">{{ $item->submitted_at ? $item->submitted_at->format('M d, h:i A') : 'N/A' }}</strong></div>
                                    @if($isLate)
                                        <div class="text-orange-500 font-extrabold uppercase mt-0.5">Submitted Late</div>
                                    @endif
                                </div>
                            </td>

                            <td class="py-4 px-6 whitespace-nowrap text-right">
                                @if($item->tab_switch_count > 0)
                                    <button @click="fetchLogs('{{ $item->id }}')" class="inline-flex items-center gap-1 text-xs font-black text-rose-600 hover:text-rose-500 dark:text-rose-400 dark:hover:text-rose-300 uppercase tracking-wider transition-colors">
                                        View Switch Timestamps &rarr;
                                    </button>
                                @else
                                    <span class="text-xxs text-zinc-400 dark:text-zinc-600 font-medium italic">No Violations Logged</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-12 text-center text-zinc-400 dark:text-zinc-500 font-medium text-xs">
                                No quiz submissions or attempts logged matching filters.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Switch logs modal (AlpineJS Backdrop) -->
    <div x-show="showLogsModal" x-transition.opacity class="fixed inset-0 z-50 bg-zinc-950/80 backdrop-blur-xs" style="display: none;"></div>

    <!-- Switch logs modal (AlpineJS container) -->
    <div x-show="showLogsModal" 
         x-transition:enter="transition ease-out duration-250"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 scale-100"
         x-transition:leave-end="opacity-0 scale-95"
         class="fixed inset-0 z-50 overflow-y-auto flex items-center justify-center p-4 sm:p-6" 
         style="display: none;">
        
        <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-3xl w-full max-w-lg shadow-2xl p-6 relative text-zinc-900 dark:text-zinc-100"
             @click.away="showLogsModal = false">
             
            <button @click="showLogsModal = false" class="absolute right-4 top-4 p-1.5 rounded-xl bg-zinc-100 hover:bg-zinc-200 dark:bg-zinc-800 dark:hover:bg-zinc-750 text-zinc-500 dark:text-zinc-400 hover:text-zinc-955 dark:hover:text-white transition-all shadow-xxs border border-zinc-200/50 dark:border-zinc-700/50">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>

            <h3 class="text-base font-black text-rose-600 dark:text-rose-455 uppercase tracking-wider mb-4">Anti-Cheat Tab Switch Logs</h3>
            <p class="text-xxs text-zinc-450 dark:text-zinc-500 mb-6">Details of browser focus losses and switch actions recorded for student attempt.</p>

            <div class="space-y-3">
                <div x-show="loadingLogs" class="py-6 text-center text-xs text-zinc-400 dark:text-zinc-500">
                    Loading violation log timestamps...
                </div>

                <div x-show="!loadingLogs && logsList.length > 0" class="divide-y divide-zinc-200 dark:divide-zinc-800">
                    <template x-for="log in logsList" :key="log.switch_number">
                        <div class="py-3 flex justify-between items-center text-xxs font-medium">
                            <span class="inline-flex items-center gap-1 text-zinc-800 dark:text-zinc-200 font-bold">
                                <span class="h-2 w-2 rounded-full bg-rose-500 animate-pulse"></span>
                                Switch Action #<span x-text="log.switch_number"></span>
                            </span>
                            <span class="text-rose-600 dark:text-rose-400 font-black uppercase tracking-wider" x-text="log.switched_at"></span>
                        </div>
                    </template>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
