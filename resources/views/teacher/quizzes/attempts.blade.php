@extends('layouts.dashboard')

@section('title', 'Quiz Submissions & Cheating Logs')
@section('header_title', 'Quiz Submissions & Cheating Logs')

@section('content')
<div class="space-y-8 animate-fade-in text-slate-100" x-data="{ showLogsModal: false, activeAttemptId: '', logsList: [], loadingLogs: false, fetchLogs(attemptId) {
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
    <div class="relative overflow-hidden rounded-3xl bg-slate-900 border border-slate-800 p-6 sm:p-8 shadow-xl">
        <div class="absolute right-0 top-0 translate-x-1/3 -translate-y-1/3 h-72 w-72 rounded-full bg-rose-500/5 blur-3xl pointer-events-none"></div>
        <div class="relative z-10 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h2 class="text-2xl font-black text-white tracking-tight">🔒 Quiz Attempts & Cheating Logs</h2>
                <p class="text-xs text-slate-400 mt-1">Monitor real-time student grades, durations, scores, and track browser tab-switch violations.</p>
            </div>
            <div>
                <a href="{{ route('teacher.quizzes.index') }}" class="inline-flex items-center gap-2 rounded-xl bg-slate-800 hover:bg-slate-750 border border-slate-700 text-rose-455 font-extrabold text-xs py-2.5 px-4 shadow transition-all uppercase tracking-wider">
                    <svg class="h-4.5 w-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                    Back to Quizzes
                </a>
            </div>
        </div>
    </div>

    <!-- Filters Panel -->
    <div class="bg-slate-900 border border-slate-800 rounded-2xl p-5 shadow-sm">
        <form action="{{ route('teacher.quizzes.attempts') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-4 gap-4 items-end">
            <div>
                <label class="block text-xxs font-black uppercase tracking-wider text-slate-400 mb-2">Filter Subject</label>
                <select name="subject_id" class="w-full rounded-xl bg-slate-950 border border-slate-800 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 text-xs text-white px-4 py-2.5 outline-none transition-all">
                    <option value="">All Subjects</option>
                    @foreach($subjects as $subj)
                        <option value="{{ $subj->id }}" {{ request('subject_id') == $subj->id ? 'selected' : '' }}>{{ $subj->code }} - {{ $subj->name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-xxs font-black uppercase tracking-wider text-slate-400 mb-2">Filter Class</label>
                <select name="class_code" class="w-full rounded-xl bg-slate-950 border border-slate-800 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 text-xs text-white px-4 py-2.5 outline-none transition-all">
                    <option value="">All Classes</option>
                    @foreach($classCodes as $code)
                        <option value="{{ $code }}" {{ request('class_code') == $code ? 'selected' : '' }}>{{ $code }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-xxs font-black uppercase tracking-wider text-slate-400 mb-2">Filter Student Type</label>
                <select name="student_type" class="w-full rounded-xl bg-slate-950 border border-slate-800 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 text-xs text-white px-4 py-2.5 outline-none transition-all">
                    <option value="">All Types</option>
                    <option value="normal" {{ request('student_type') == 'normal' ? 'selected' : '' }}>Normal Students</option>
                    <option value="remedial" {{ request('student_type') == 'remedial' ? 'selected' : '' }}>Remedial Students</option>
                </select>
            </div>

            <div>
                <button type="submit" class="w-full inline-flex items-center justify-center gap-2 rounded-xl bg-indigo-650 hover:bg-indigo-600 text-white font-extrabold text-xs py-3 px-4 shadow transition-all uppercase tracking-wider">
                    Apply Filters
                </button>
            </div>
        </form>
    </div>

    <!-- Attempts Table/List -->
    <div class="overflow-hidden rounded-2xl bg-slate-900 border border-slate-800 shadow-md">
        <div class="min-w-full overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-800 text-left text-xs">
                <thead class="bg-slate-950 font-bold text-slate-400 uppercase tracking-wider text-xxs">
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
                <tbody class="divide-y divide-slate-850">
                    @forelse($attempts as $item)
                        @php
                            $isExcellent = $item->isExcellent();
                            $needsImprovement = $item->needsImprovement();
                            $isLate = $item->isLate();
                        @endphp
                        <tr class="hover:bg-slate-850/50 transition-colors">
                            <td class="py-4 px-6 whitespace-nowrap">
                                <div class="flex items-center gap-3">
                                    <div class="h-9 w-9 rounded-xl bg-indigo-500/10 flex items-center justify-center font-black text-xs text-indigo-400 border border-indigo-500/20">
                                        {{ strtoupper(substr($item->student->name ?? 'ST', 0, 2)) }}
                                    </div>
                                    <div>
                                        <div class="font-bold text-white">{{ $item->student->name ?? 'N/A' }}</div>
                                        <div class="text-xxxxs uppercase tracking-wider font-extrabold text-slate-500 mt-0.5">Section: {{ $item->student->class_code ?? 'N/A' }}</div>
                                    </div>
                                </div>
                            </td>

                            <td class="py-4 px-6">
                                <div class="font-bold text-slate-200">{{ $item->quiz->title ?? 'N/A' }}</div>
                                <div class="flex items-center gap-1.5 mt-1">
                                    <span class="inline-flex items-center rounded-md px-1.5 py-0.5 text-xxs font-extrabold uppercase bg-slate-850 border border-slate-800 text-slate-400">
                                        {{ $item->quiz->subjectRelation->code ?? 'SUB' }}
                                    </span>
                                    @if($item->quiz->student_type === 'remedial')
                                        <span class="inline-flex items-center rounded-md px-1.5 py-0.5 text-xxs font-extrabold uppercase bg-rose-500/10 text-rose-455 border border-rose-500/20">
                                            Remedial
                                        </span>
                                    @else
                                        <span class="inline-flex items-center rounded-md px-1.5 py-0.5 text-xxs font-extrabold uppercase bg-emerald-500/10 text-emerald-450 border border-emerald-500/20">
                                            Normal
                                        </span>
                                    @endif
                                </div>
                            </td>

                            <td class="py-4 px-6 whitespace-nowrap">
                                <div class="flex items-baseline gap-1 text-base font-black text-white">
                                    {{ $item->score }}
                                    <span class="text-xs font-semibold text-slate-500">/ {{ $item->max_score }}</span>
                                </div>
                                <div class="flex flex-wrap gap-1 mt-1.5">
                                    @if($isExcellent)
                                        <span class="inline-flex rounded bg-gradient-to-r from-amber-500/20 to-orange-500/20 border border-amber-500/30 px-1.5 py-0.5 text-xxxxs font-black text-amber-300 uppercase tracking-wider animate-pulse">
                                            ⭐ Excellent
                                        </span>
                                    @endif
                                    @if($needsImprovement)
                                        <span class="inline-flex rounded bg-rose-500/10 border border-rose-500/20 px-1.5 py-0.5 text-xxxxs font-black text-rose-400 uppercase tracking-wider">
                                            ⚠️ Weak
                                        </span>
                                    @endif
                                </div>
                            </td>

                            <td class="py-4 px-6 whitespace-nowrap">
                                @if($item->status === 'in_progress')
                                    <span class="inline-flex items-center gap-1 rounded-lg px-2.5 py-0.5 font-bold uppercase bg-amber-500/10 text-amber-500 border border-amber-500/20 animate-pulse">
                                        ⏳ Attempting
                                    </span>
                                @elseif($item->status === 'submitted')
                                    <span class="inline-flex items-center gap-1 rounded-lg px-2.5 py-0.5 font-bold uppercase bg-emerald-500/10 text-emerald-500 border border-emerald-500/20">
                                        ✅ Graded
                                    </span>
                                @elseif($item->status === 'terminated')
                                    <span class="inline-flex items-center gap-1 rounded-lg px-2.5 py-0.5 font-black uppercase bg-red-500/10 text-red-500 border border-red-500/30">
                                        🚫 Terminated
                                    </span>
                                @endif
                            </td>

                            <td class="py-4 px-6 whitespace-nowrap">
                                @php
                                    $switches = $item->tab_switch_count ?? 0;
                                @endphp
                                @if($switches === 0)
                                    <span class="inline-flex items-center rounded-lg bg-emerald-500/10 border border-emerald-500/20 px-2.5 py-0.5 text-xxs font-black text-emerald-500">
                                        0 Switches
                                    </span>
                                @elseif($switches === 1)
                                    <span class="inline-flex items-center rounded-lg bg-amber-500/10 border border-amber-500/20 px-2.5 py-0.5 text-xxs font-black text-amber-500">
                                        1 Switch (Warned)
                                    </span>
                                @elseif($switches === 2)
                                    <span class="inline-flex items-center rounded-lg bg-orange-500/10 border border-orange-500/20 px-2.5 py-0.5 text-xxs font-black text-orange-400">
                                        2 Switches (Warned)
                                    </span>
                                @else
                                    <span class="inline-flex items-center rounded-lg bg-red-500/10 border border-red-500/30 px-2.5 py-0.5 text-xxs font-black text-red-400">
                                        {{ $switches }} Switches (Locked Out)
                                    </span>
                                @endif
                            </td>

                            <td class="py-4 px-6 whitespace-nowrap text-xxs font-medium text-slate-400">
                                <div class="space-y-0.5 leading-normal">
                                    <div>Started: <strong class="text-slate-200">{{ $item->started_at ? $item->started_at->format('M d, h:i A') : 'N/A' }}</strong></div>
                                    <div>Ended: <strong class="text-slate-200">{{ $item->submitted_at ? $item->submitted_at->format('M d, h:i A') : 'N/A' }}</strong></div>
                                    @if($isLate)
                                        <div class="text-orange-400 font-extrabold uppercase">⏳ Submitted Late</div>
                                    @endif
                                </div>
                            </td>

                            <td class="py-4 px-6 whitespace-nowrap text-right">
                                @if($item->tab_switch_count > 0)
                                    <button @click="fetchLogs('{{ $item->id }}')" class="inline-flex items-center gap-1 text-xs font-black text-rose-400 hover:text-rose-350 uppercase tracking-wider">
                                        View Switch Timestamps &rarr;
                                    </button>
                                @else
                                    <span class="text-xxs text-slate-600 font-medium italic">No Violations Logged</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-8 text-center text-slate-500 font-medium">
                                No quiz submissions or attempts logged matching filters.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Switch logs modal (AlpineJS Backdrop) -->
    <div x-show="showLogsModal" x-transition.opacity class="fixed inset-0 z-50 bg-slate-950/80 backdrop-blur-sm" style="display: none;"></div>

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
        
        <div class="bg-slate-900 border border-slate-800 rounded-3xl w-full max-w-lg shadow-2xl p-6 relative"
             @click.away="showLogsModal = false">
             
            <button @click="showLogsModal = false" class="absolute right-4 top-4 p-1 rounded bg-slate-850 text-slate-400 hover:text-white transition-all">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>

            <h3 class="text-base font-black text-rose-455 uppercase tracking-wider mb-4">🚫 Anti-Cheat Tab Switch Logs</h3>
            <p class="text-xxs text-slate-400 mb-6">Details of browser focus losses and switch actions recorded for student attempt.</p>

            <div class="space-y-3">
                <template x-if="loadingLogs">
                    <div class="py-6 text-center text-xs text-slate-500">
                        Loading violation log timestamps...
                    </div>
                </template>

                <template x-if="!loadingLogs && logsList.length > 0">
                    <div class="divide-y divide-slate-850">
                        <template x-for="log in logsList" :key="log.switch_number">
                            <div class="py-3 flex justify-between items-center text-xxs font-medium">
                                <span class="inline-flex items-center gap-1 text-slate-200 font-bold">
                                    <span class="h-2 w-2 rounded-full bg-rose-500 animate-pulse"></span>
                                    Switch Action #<span x-text="log.switch_number"></span>
                                </span>
                                <span class="text-rose-400 font-black uppercase tracking-wider" x-text="log.switched_at"></span>
                            </div>
                        </template>
                    </div>
                </template>
            </div>
        </div>
    </div>
</div>
@endsection
