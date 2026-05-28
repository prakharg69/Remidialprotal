@extends('layouts.dashboard')

@section('title', 'Quiz Assessment Results')
@section('header_title', 'Assessment Results')

@section('content')
<div class="space-y-8 animate-fade-in text-slate-100">

    @if($attempt->status === 'terminated')
        <!-- Full-Screen access lockout block overlay -->
        <div class="relative overflow-hidden rounded-3xl bg-slate-900 border border-rose-500/30 p-8 sm:p-12 shadow-2xl flex flex-col items-center justify-center text-center space-y-8 min-h-[500px]">
            <div class="absolute inset-0 bg-[radial-gradient(circle_at_center,rgba(244,63,94,0.08)_0%,transparent_70%)] pointer-events-none"></div>
            
            <div class="h-24 w-24 rounded-3xl bg-rose-500/10 border border-rose-500/20 flex items-center justify-center text-rose-500 shadow-inner animate-pulse">
                <svg class="h-12 w-12" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                </svg>
            </div>
            
            <div class="space-y-3 max-w-lg">
                <span class="inline-flex items-center rounded-lg bg-rose-500/20 px-3 py-1 text-xxs font-black text-rose-455 uppercase tracking-widest border border-rose-500/30">
                    🚫 ACCESS DENIED: INTEGRITY LOCKOUT ACTIVE
                </span>
                <h2 class="text-2xl sm:text-3xl font-black text-white tracking-tight mt-2">Assessment Results Permanent Lockout</h2>
                <p class="text-xs text-rose-350 leading-relaxed">
                    Your assessment session was forcefully terminated due to exceeding the security policy threshold (**{{ $attempt->tab_switch_count }} focus loss / window switch violations**).
                </p>
                <p class="text-xs text-slate-400 leading-relaxed mt-3">
                    To preserve testing integrity, students disqualified for security violations are strictly locked out of reviewing correct answers, question contents, and score breakdowns. 
                    Your final evaluated score (**{{ $attempt->score }} / {{ $attempt->max_score }}**) has been logged and sent to your instructor.
                </p>
            </div>

            <div class="mt-6 pt-4 w-full max-w-sm border-t border-slate-850 flex flex-col gap-4">
                <div class="flex items-center justify-between text-xxs text-slate-400 font-semibold bg-slate-950/60 border border-slate-850 px-4 py-3 rounded-xl">
                    <span>Integrity Logs:</span>
                    <span class="text-rose-500 font-black">Disqualified (4 Switches)</span>
                </div>
                <a href="{{ route('student.quizzes.index') }}" class="w-full inline-flex items-center justify-center gap-2 rounded-xl bg-slate-800 hover:bg-slate-750 border border-slate-700 text-white font-extrabold text-xs py-3.5 px-6 shadow transition-all uppercase tracking-wider">
                    &larr; Return to Quizzes
                </a>
            </div>
        </div>
    @else
        <!-- Hero Card containing Quiz details -->
        <div class="relative overflow-hidden rounded-3xl bg-slate-900 border border-slate-800 p-6 sm:p-8 shadow-xl">
            <div class="absolute right-0 top-0 translate-x-1/3 -translate-y-1/3 h-72 w-72 rounded-full bg-indigo-500/10 blur-3xl pointer-events-none"></div>
            <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
                <div>
                    <span class="text-xxs font-bold uppercase tracking-wider text-indigo-400">
                        {{ $quiz->subjectRelation->name ?? 'Course Subject' }}
                    </span>
                    <h2 class="text-2xl font-black text-white tracking-tight mt-1">{{ $quiz->title }}</h2>
                    <p class="text-xs text-slate-400 mt-1">Submitted on: {{ $attempt->submitted_at ? $attempt->submitted_at->format('M d, Y h:i A') : 'N/A' }}</p>
                </div>
                
                <div class="flex-shrink-0 flex items-center gap-3">
                    <a href="{{ route('student.quizzes.index') }}" class="inline-flex items-center gap-2 rounded-xl bg-slate-850 hover:bg-slate-800 border border-slate-800 hover:border-slate-755 text-slate-300 font-extrabold text-xs py-3 px-6 shadow transition-all uppercase tracking-wider">
                        &larr; Back to Quizzes
                    </a>
                </div>
            </div>
        </div>

        <!-- Summary Grid -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            
            <!-- Score Card -->
            <div class="bg-slate-900 border border-slate-800 rounded-2xl p-6 shadow-md flex flex-col justify-between">
                <div>
                    <span class="block text-xxxxs uppercase tracking-wider text-slate-500 font-extrabold">Overall Score</span>
                    <div class="flex items-baseline gap-1 mt-4">
                        <span class="text-4xl font-black text-white tracking-tight">{{ $attempt->score }}</span>
                        <span class="text-slate-500 font-bold text-sm">/ {{ $attempt->max_score }}</span>
                    </div>
                </div>
                <div class="mt-6 pt-4 border-t border-slate-800/80">
                    @php
                        $percentage = round(($attempt->score / max(1, $attempt->max_score)) * 100);
                    @endphp
                    <div class="flex items-center justify-between">
                        <span class="text-xxs text-slate-400 font-semibold">Percentage Score:</span>
                        <span class="text-sm font-black text-indigo-400">{{ $percentage }}%</span>
                    </div>
                    <div class="w-full bg-slate-950 rounded-full h-1.5 mt-2 overflow-hidden border border-slate-800">
                        <div class="h-1.5 rounded-full bg-gradient-to-r from-indigo-500 to-indigo-600" style="width: {{ $percentage }}%"></div>
                    </div>
                </div>
            </div>

            <!-- Performance Badge Card -->
            <div class="bg-slate-900 border border-slate-800 rounded-2xl p-6 shadow-md flex flex-col justify-between">
                <div>
                    <span class="block text-xxxxs uppercase tracking-wider text-slate-500 font-extrabold">Performance Badge</span>
                    <div class="mt-4">
                        @if($attempt->isExcellent())
                            <div class="inline-flex items-center gap-2 rounded-xl bg-emerald-500/10 border border-emerald-500/30 px-3 py-1.5 text-xs font-black text-emerald-450 uppercase tracking-wider">
                                <span class="h-2.5 w-2.5 rounded-full bg-emerald-500 animate-pulse"></span>
                                EXCELLENT WORK
                            </div>
                            <p class="text-xxxxs text-slate-500 font-bold mt-2 uppercase tracking-wide leading-relaxed">Excellent Work Badge earned! You demonstrated deep topic competence.</p>
                        @elseif($attempt->needsImprovement())
                            <div class="inline-flex items-center gap-2 rounded-xl bg-amber-500/10 border border-amber-500/30 px-3 py-1.5 text-xs font-black text-amber-500 uppercase tracking-wider">
                                <span class="h-2.5 w-2.5 rounded-full bg-amber-500"></span>
                                FOCUS NEEDED
                            </div>
                            <p class="text-xxxxs text-slate-500 font-bold mt-2 uppercase tracking-wide leading-relaxed">Needs Improvement Badge. Focus on assignment resources and teacher guidance.</p>
                        @else
                            <div class="inline-flex items-center gap-2 rounded-xl bg-blue-500/10 border border-blue-500/30 px-3 py-1.5 text-xs font-black text-blue-400 uppercase tracking-wider">
                                <span class="h-2.5 w-2.5 rounded-full bg-blue-500"></span>
                                PASSED / GOOD
                            </div>
                            <p class="text-xxxxs text-slate-500 font-bold mt-2 uppercase tracking-wide leading-relaxed">Passed with satisfactory performance. Continue practicing for masterly scores.</p>
                        @endif
                    </div>
                </div>
                <div class="mt-6 pt-4 border-t border-slate-800/80">
                    <div class="flex items-center justify-between text-xxs">
                        <span class="text-slate-400 font-semibold">Remedial Student Status:</span>
                        <span class="font-bold {{ $attempt->needsImprovement() ? 'text-rose-400' : 'text-slate-300' }}">
                            {{ $attempt->needsImprovement() ? 'Assigned Support Tasks' : 'No Remedials Required' }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Integrity Index Card -->
            <div class="bg-slate-900 border border-slate-800 rounded-2xl p-6 shadow-md flex flex-col justify-between">
                <div>
                    <span class="block text-xxxxs uppercase tracking-wider text-slate-500 font-extrabold">Integrity & Session Logs</span>
                    <div class="mt-4 space-y-3">
                        <div class="flex items-center justify-between">
                            <span class="text-xxs text-slate-400 font-semibold">Tab Switch Warnings:</span>
                            @if($attempt->tab_switch_count == 0)
                                <span class="inline-flex items-center rounded bg-emerald-500/10 border border-emerald-500/20 px-2 py-0.5 text-xxxxs font-black text-emerald-450 uppercase">
                                    0 / 3 Safe
                                </span>
                            @elseif($attempt->tab_switch_count == 1)
                                <span class="inline-flex items-center rounded bg-amber-500/10 border border-amber-500/20 px-2 py-0.5 text-xxxxs font-black text-amber-500 uppercase">
                                    1 Warning Logged
                                </span>
                            @elseif($attempt->tab_switch_count == 2)
                                <span class="inline-flex items-center rounded bg-orange-500/10 border border-orange-500/20 px-2 py-0.5 text-xxxxs font-black text-orange-500 uppercase">
                                    2 Warnings Logged
                                </span>
                            @elseif($attempt->tab_switch_count == 3)
                                <span class="inline-flex items-center rounded bg-red-500/10 border border-red-500/20 px-2 py-0.5 text-xxxxs font-black text-red-500 uppercase">
                                    3 Warnings Logged
                                </span>
                            @else
                                <span class="inline-flex items-center rounded bg-rose-500/10 border border-rose-500/20 px-2 py-0.5 text-xxxxs font-black text-rose-500 uppercase animate-pulse">
                                    Terminated
                                </span>
                            @endif
                        </div>

                        <div class="flex items-center justify-between">
                            <span class="text-xxs text-slate-400 font-semibold">Integrity Index:</span>
                            <span class="text-xxs font-extrabold {{ $attempt->tab_switch_count >= 3 ? 'text-rose-500' : 'text-emerald-400' }}">
                                @if($attempt->tab_switch_count == 0)
                                    Perfect Integrity (100%)
                                	@elseif($attempt->tab_switch_count == 1)
                                    High Integrity (80%)
                                @elseif($attempt->tab_switch_count == 2)
                                    Moderately Safe (60%)
                                @elseif($attempt->tab_switch_count == 3)
                                    Strict Warning (30%)
                                @else
                                    Terminated (0%)
                                @endif
                            </span>
                        </div>
                    </div>
                </div>
                <div class="mt-6 pt-4 border-t border-slate-800/80">
                    <div class="flex items-center justify-between text-xxs">
                        <span class="text-slate-400 font-semibold">Time Spent:</span>
                        <span class="font-bold text-slate-200">
                            @if($attempt->started_at && $attempt->submitted_at)
                                {{ $attempt->started_at->diffInMinutes($attempt->submitted_at) }} mins 
                                {{ $attempt->started_at->diffInSeconds($attempt->submitted_at) % 60 }} secs
                            @else
                                N/A
                            @endif
                        </span>
                    </div>
                </div>
            </div>

        </div>

        <!-- Section Divider -->
        <div class="border-t border-slate-850 pt-8">
            <h3 class="text-lg font-black text-white tracking-tight flex items-center gap-2.5">
                <span class="h-6 w-6 rounded-lg bg-indigo-500/10 flex items-center justify-center text-indigo-400 border border-indigo-500/20 shadow-inner">
                    <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2"/></svg>
                </span>
                Detailed Question-by-Question Review
            </h3>
            <p class="text-xxs text-slate-500 font-semibold mt-1">Review your selections against the correct option indexes. The system uses strict evaluation.</p>
        </div>

        <!-- Detailed MCQ list -->
        <div class="space-y-6">
            @foreach($quiz->questions as $index => $q)
                @php
                    // Fetch student answer
                    $studentAns = collect($attempt->answers)->firstWhere('question_id', $q['id']);
                    $selectedVal = $studentAns ? $studentAns['selected_option'] : null;
                    $isCorrect = $studentAns ? $studentAns['is_correct'] : false;
                    $correctVal = intval($q['correct_option'] ?? -1);
                @endphp
                <div class="bg-slate-900 border {{ $selectedVal === null ? 'border-slate-800' : ($isCorrect ? 'border-emerald-500/20' : 'border-rose-500/20') }} rounded-2xl p-6 shadow-md space-y-5">
                    
                    <!-- Question Title & Status Badge -->
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                        <div class="flex items-start gap-2.5">
                            <span class="h-6 w-6 rounded-lg bg-slate-955/60 flex items-center justify-center font-black text-xs text-indigo-400 border border-slate-800 flex-shrink-0 mt-0.5">
                                {{ $index + 1 }}
                            </span>
                            <h4 class="text-sm font-bold text-white leading-snug">{{ $q['question_text'] }}</h4>
                        </div>

                        <!-- Status badges -->
                        <div class="flex-shrink-0">
                            @if($selectedVal === null)
                                <span class="inline-flex items-center gap-1 rounded-lg bg-slate-950/50 border border-slate-850 px-2.5 py-1 text-xxxxs font-black text-slate-400 uppercase tracking-wider">
                                    ⚪ Unanswered / Skipped
                                </span>
                            @elseif($isCorrect)
                                <span class="inline-flex items-center gap-1 rounded-lg bg-emerald-500/10 border border-emerald-500/20 px-2.5 py-1 text-xxxxs font-black text-emerald-450 uppercase tracking-wider">
                                    <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                    Correct Answer
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1 rounded-lg bg-rose-500/10 border border-rose-500/20 px-2.5 py-1 text-xxxxs font-black text-rose-500 uppercase tracking-wider">
                                    <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                                    Incorrect
                                </span>
                            @endif
                        </div>
                    </div>

                    <!-- 4 Options displaying correctness -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @foreach($q['options'] as $optIndex => $optionText)
                            @php
                                $isThisSelected = ($selectedVal !== null && intval($selectedVal) === $optIndex);
                                $isThisCorrect = ($optIndex === $correctVal);
                                
                                $optionClass = 'border-slate-850 bg-slate-950/20 text-slate-400';
                                $badgeText = '';
                                
                                if ($isThisSelected && $isThisCorrect) {
                                    // Selected & Correct
                                    $optionClass = 'border-emerald-500 bg-emerald-950/20 text-white';
                                    $badgeText = 'Your Correct Answer';
                                } elseif ($isThisSelected && !$isThisCorrect) {
                                    // Selected & Incorrect
                                    $optionClass = 'border-rose-500 bg-rose-955/20 text-white';
                                    $badgeText = 'Your Answer (Incorrect)';
                                } elseif (!$isThisSelected && $isThisCorrect) {
                                    // Correct but not selected
                                    $optionClass = 'border-emerald-500/60 border-dashed bg-slate-950/40 text-slate-300';
                                    $badgeText = 'Correct Option';
                                }
                            @endphp
                            
                            <div class="relative flex items-center justify-between gap-3 rounded-xl border p-4 transition-all duration-150 {{ $optionClass }}">
                                <div class="flex items-center gap-3">
                                    <span class="h-5 w-5 rounded bg-slate-950/80 border border-slate-850 flex items-center justify-center font-bold text-xxxxs uppercase text-slate-500">
                                        {{ chr(65 + $optIndex) }}
                                    </span>
                                    <span class="text-xs font-semibold leading-relaxed">{{ $optionText }}</span>
                                </div>

                                @if($badgeText)
                                    <span class="text-xxxxs uppercase tracking-wider font-extrabold px-2 py-0.5 rounded
                                        {{ str_contains($badgeText, 'Correct') ? 'bg-emerald-500/10 text-emerald-450 border border-emerald-500/20' : 'bg-rose-500/10 text-rose-500 border border-rose-500/20' }}">
                                        {{ $badgeText }}
                                    </span>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>
    @endif

</div>>

</div>
@endsection
