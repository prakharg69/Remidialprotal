@extends('layouts.dashboard')

@section('title', 'Manage Quizzes')
@section('header_title', 'Manage Quizzes')

@section('content')
<div class="space-y-8 animate-fade-in text-zinc-900 dark:text-zinc-100" x-data="{ showCreateModal: false }">
    
    <!-- Hero Header -->
    <div class="relative overflow-hidden rounded-3xl bg-zinc-900 border border-zinc-800 p-6 sm:p-8 shadow-xl">
        <div class="absolute right-0 top-0 translate-x-1/3 -translate-y-1/3 h-72 w-72 rounded-full bg-zinc-500/10 blur-3xl pointer-events-none"></div>
        <div class="relative z-10 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h2 class="text-2xl font-black text-white tracking-tight">Quiz Management</h2>
                <p class="text-xs text-zinc-400 mt-1">Create MCQs, set time constraints, set deadlines, and target remedial or normal students.</p>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('teacher.quizzes.attempts') }}" class="inline-flex items-center gap-2 rounded-xl bg-zinc-100 hover:bg-zinc-200 dark:bg-zinc-800 dark:hover:bg-zinc-750 border border-zinc-250 dark:border-zinc-700 text-zinc-800 dark:text-zinc-300 font-extrabold text-xs py-2.5 px-4 shadow transition-all uppercase tracking-wider">
                    <svg class="h-4.5 w-4.5 text-zinc-800 dark:text-zinc-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    View Quiz Attempts
                </a>
                <button @click="showCreateModal = true" class="inline-flex items-center gap-2 rounded-xl bg-zinc-100 hover:bg-zinc-200 text-zinc-950 font-extrabold text-xs py-2.5 px-4 shadow transition-all uppercase tracking-wider">
                    <svg class="h-4.5 w-4.5 text-zinc-950" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                    Create New Quiz
                </button>
            </div>
        </div>
    </div>

    <!-- Filters Panel -->
    <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-2xl p-5 shadow-sm">
        <form action="{{ route('teacher.quizzes.index') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-4 gap-4 items-end">
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
                <button type="submit" class="w-full inline-flex items-center justify-center gap-2 rounded-xl bg-zinc-900 dark:bg-zinc-100 hover:bg-zinc-850 dark:hover:bg-zinc-200 text-white dark:text-zinc-900 font-extrabold text-xs py-3 px-4 shadow transition-all uppercase tracking-wider border border-transparent dark:border-zinc-300">
                    Apply Filters
                </button>
            </div>
        </form>
    </div>

    <!-- Quizzes Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($quizzes as $quiz)
            <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 hover:border-zinc-350 dark:hover:border-zinc-700 rounded-2xl p-6 shadow-sm transition-all flex flex-col justify-between group">
                <div>
                    <div class="flex justify-between items-start gap-4 mb-4">
                        <span class="inline-flex items-center rounded-lg bg-zinc-100 dark:bg-zinc-800 px-2 py-0.5 text-xxs font-extrabold text-zinc-750 dark:text-zinc-350 border border-zinc-250 dark:border-zinc-700 uppercase">
                            {{ $quiz->subjectRelation->code ?? 'SUB' }}
                        </span>
                        
                        @if($quiz->student_type === 'remedial')
                            <span class="inline-flex items-center rounded-lg bg-zinc-900 dark:bg-zinc-100 px-2 py-0.5 text-xxs font-extrabold text-white dark:text-zinc-900 shadow-sm border border-zinc-800 dark:border-zinc-200 uppercase">
                                Remedial
                            </span>
                        @else
                            <span class="inline-flex items-center rounded-lg bg-zinc-100 dark:bg-zinc-800 px-2 py-0.5 text-xxs font-extrabold text-zinc-800 dark:text-zinc-350 border border-zinc-200 dark:border-zinc-700 uppercase">
                                Normal
                            </span>
                        @endif
                    </div>

                    <h3 class="text-lg font-bold text-zinc-900 dark:text-white tracking-tight leading-snug group-hover:text-zinc-700 dark:group-hover:text-zinc-300 transition-colors">{{ $quiz->title }}</h3>
                    <p class="text-xs text-zinc-450 dark:text-zinc-500 mt-2 leading-relaxed line-clamp-3 italic">"{{ $quiz->instructions ?? 'No instructions provided.' }}"</p>
                    
                    <div class="mt-5 space-y-2.5 border-t border-zinc-200 dark:border-zinc-800/80 pt-4 text-xxs text-zinc-450 dark:text-zinc-500">
                        <div class="flex items-center justify-between">
                            <span class="font-medium">Target Section:</span>
                            <span class="font-bold text-zinc-900 dark:text-zinc-200 uppercase">{{ $quiz->class_code }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="font-medium">MCQ Questions:</span>
                            <span class="font-bold text-zinc-900 dark:text-zinc-200">{{ count($quiz->questions ?? []) }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="font-medium">Allowed Time:</span>
                            <span class="font-bold text-zinc-900 dark:text-zinc-200">{{ $quiz->duration }} Minutes</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="font-medium text-zinc-900 dark:text-zinc-300">Quiz Deadline:</span>
                            <span class="font-bold text-zinc-850 dark:text-zinc-200">{{ $quiz->deadline ? $quiz->deadline->format('M d, Y h:i A') : 'N/A' }}</span>
                        </div>
                    </div>
                </div>

                <div class="mt-6 border-t border-zinc-200 dark:border-zinc-800/85 pt-4 flex items-center justify-between">
                    <span class="text-xxs text-zinc-400 dark:text-zinc-550 font-semibold">Created {{ $quiz->created_at ? $quiz->created_at->diffForHumans() : '' }}</span>
                    <a href="{{ route('teacher.quizzes.attempts', ['subject_id' => $quiz->subject_id, 'class_code' => $quiz->class_code]) }}" class="inline-flex items-center gap-1 text-xxs font-black text-zinc-750 dark:text-zinc-300 hover:underline uppercase tracking-wider">
                        View Attempts &rarr;
                    </a>
                </div>
            </div>
        @empty
            <div class="col-span-1 md:col-span-2 lg:col-span-3 py-16 text-center bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-850 rounded-2xl">
                <div class="h-12 w-12 rounded-full bg-zinc-100 dark:bg-zinc-850 flex items-center justify-center mx-auto text-zinc-400 dark:text-zinc-500 mb-3">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2"/></svg>
                </div>
                <h4 class="text-sm font-bold text-zinc-700 dark:text-zinc-300">No Quizzes Published Yet</h4>
                <p class="text-xxs text-zinc-450 dark:text-zinc-500 mt-1">Publish MCQs with browser cheating protection logs to start evaluating.</p>
            </div>
        @endforelse
    </div>

    <!-- Create Quiz Modal Backdrop (AlpineJS) -->
    <div x-show="showCreateModal" x-transition.opacity class="fixed inset-0 z-50 bg-zinc-950/80 backdrop-blur-sm" style="display: none;"></div>

    <!-- Create Quiz Modal Container -->
    <div x-show="showCreateModal" 
         x-transition:enter="transition ease-out duration-250"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 scale-100"
         x-transition:leave-end="opacity-0 scale-95"
         class="fixed inset-0 z-50 overflow-y-auto flex items-center justify-center p-4 sm:p-6" 
         style="display: none;">
        
        <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-3xl w-full max-w-4xl max-h-[85vh] overflow-y-auto shadow-2xl p-6 sm:p-8 space-y-6"
             @click.away="showCreateModal = false"
             x-data="{ 
                 questions: [
                     { question_text: '', options: ['', '', '', ''], correct_option: 0 }
                 ],
                 addQuestion() {
                     this.questions.push({ question_text: '', options: ['', '', '', ''], correct_option: 0 });
                 },
                 removeQuestion(index) {
                     if (this.questions.length > 1) {
                         this.questions.splice(index, 1);
                     }
                 }
             }">
            
            <div class="flex items-center justify-between border-b border-zinc-200 dark:border-zinc-800 pb-4">
                <div>
                    <h3 class="text-xl font-black text-zinc-900 dark:text-white">Create New Assessment Quiz</h3>
                    <p class="text-xxs text-zinc-450 dark:text-zinc-500 mt-0.5">Define metadata, duration, anti-cheat limits, and embed MCQs.</p>
                </div>
                <button @click="showCreateModal = false" class="p-1.5 rounded-lg bg-zinc-100 dark:bg-zinc-800 text-zinc-550 hover:text-zinc-850 dark:hover:text-white transition-all">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <form action="{{ route('teacher.quizzes.store') }}" method="POST" class="space-y-6 text-left">
                @csrf
                
                <!-- Metadata Fields -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <label class="block text-xxs font-black uppercase tracking-wider text-zinc-500 dark:text-zinc-400 mb-2">Subject Course</label>
                        <select name="subject_id" required class="w-full rounded-xl bg-white dark:bg-zinc-950 border border-zinc-200 dark:border-zinc-800 focus:border-zinc-500 focus:ring-1 focus:ring-zinc-500 text-xs text-zinc-900 dark:text-zinc-100 px-4 py-2.5 outline-none transition-all">
                            @foreach($subjects as $subj)
                                <option value="{{ $subj->id }}">{{ $subj->code }} - {{ $subj->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-xxs font-black uppercase tracking-wider text-zinc-500 dark:text-zinc-400 mb-2">Target Section Class</label>
                        <select name="class_code" required class="w-full rounded-xl bg-white dark:bg-zinc-950 border border-zinc-200 dark:border-zinc-800 focus:border-zinc-500 focus:ring-1 focus:ring-zinc-500 text-xs text-zinc-900 dark:text-zinc-100 px-4 py-2.5 outline-none transition-all">
                            @foreach($classCodes as $code)
                                <option value="{{ $code }}">{{ $code }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-xxs font-black uppercase tracking-wider text-zinc-500 dark:text-zinc-400 mb-2">Target Student Type</label>
                        <select name="student_type" required class="w-full rounded-xl bg-white dark:bg-zinc-950 border border-zinc-200 dark:border-zinc-800 focus:border-zinc-500 focus:ring-1 focus:ring-zinc-500 text-xs text-zinc-900 dark:text-zinc-100 px-4 py-2.5 outline-none transition-all">
                            <option value="normal">Normal Students</option>
                            <option value="remedial">Remedial Students (Slow Learners)</option>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-xxs font-black uppercase tracking-wider text-zinc-500 dark:text-zinc-400 mb-2">Quiz Title</label>
                        <input type="text" name="title" required placeholder="E.g. DBMS Normalization CA2 Quiz" class="w-full rounded-xl bg-white dark:bg-zinc-950 border border-zinc-200 dark:border-zinc-800 focus:border-zinc-500 focus:ring-1 focus:ring-zinc-500 text-xs text-zinc-900 dark:text-zinc-100 px-4 py-2.5 outline-none transition-all">
                    </div>

                    <div>
                        <label class="block text-xxs font-black uppercase tracking-wider text-zinc-500 dark:text-zinc-400 mb-2">Quiz Allowed Duration</label>
                        <select name="duration" required class="w-full rounded-xl bg-white dark:bg-zinc-950 border border-zinc-200 dark:border-zinc-800 focus:border-zinc-500 focus:ring-1 focus:ring-zinc-500 text-xs text-zinc-900 dark:text-zinc-100 px-4 py-2.5 outline-none transition-all">
                            <option value="5">5 Minutes</option>
                            <option value="10">10 Minutes</option>
                            <option value="20">20 Minutes</option>
                            <option value="40">40 Minutes</option>
                            <option value="60">60 Minutes</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block text-xxs font-black uppercase tracking-wider text-zinc-500 dark:text-zinc-400 mb-2">Instructions for Student</label>
                    <textarea name="instructions" rows="2" placeholder="Write any instructions (e.g. Do not switch tabs. Tab switching triggers lock-out)..." class="w-full rounded-xl bg-white dark:bg-zinc-950 border border-zinc-200 dark:border-zinc-800 focus:border-zinc-500 focus:ring-1 focus:ring-zinc-500 text-xs text-zinc-900 dark:text-zinc-100 p-4 outline-none transition-all leading-relaxed"></textarea>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-xxs font-black uppercase tracking-wider text-zinc-500 dark:text-zinc-400 mb-2">Deadline Date</label>
                        <input type="date" name="deadline_date" required min="{{ date('Y-m-d') }}" class="w-full rounded-xl bg-white dark:bg-zinc-950 border border-zinc-200 dark:border-zinc-800 focus:border-zinc-500 focus:ring-1 focus:ring-zinc-500 text-xs text-zinc-900 dark:text-zinc-100 px-4 py-2.5 outline-none transition-all">
                    </div>

                    <div>
                        <label class="block text-xxs font-black uppercase tracking-wider text-zinc-500 dark:text-zinc-400 mb-2">Deadline Time</label>
                        <input type="time" name="deadline_time" required class="w-full rounded-xl bg-white dark:bg-zinc-950 border border-zinc-200 dark:border-zinc-800 focus:border-zinc-500 focus:ring-1 focus:ring-zinc-500 text-xs text-zinc-900 dark:text-zinc-100 px-4 py-2.5 outline-none transition-all">
                    </div>
                </div>

                <!-- MCQs Questions Block -->
                <div class="border-t border-zinc-200 dark:border-zinc-800 pt-6">
                    <div class="flex items-center justify-between mb-6">
                        <div>
                            <h4 class="text-sm font-bold text-zinc-900 dark:text-white">MCQ Questions Listing</h4>
                            <p class="text-xxs text-zinc-450 dark:text-zinc-500">Add questions, 4 text options, and check the correct option radio button.</p>
                        </div>
                        <button type="button" @click="addQuestion()" class="inline-flex items-center gap-1.5 rounded-lg bg-zinc-100 hover:bg-zinc-200 dark:bg-zinc-800 dark:hover:bg-zinc-750 text-zinc-800 dark:text-zinc-300 border border-zinc-250 dark:border-zinc-700 px-3 py-1.5 text-xxs font-extrabold uppercase transition-all">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                            Add MCQ
                        </button>
                    </div>

                    <div class="space-y-6">
                        <template x-for="(q, index) in questions" :key="index">
                            <div class="bg-zinc-50/50 dark:bg-zinc-950/30 rounded-2xl p-5 border border-zinc-200 dark:border-zinc-800/80 space-y-4 relative">
                                <div class="flex justify-between items-center">
                                    <span class="text-xxs font-black text-zinc-500 dark:text-zinc-400 uppercase tracking-widest" x-text="'MCQ Question #' + (index + 1)"></span>
                                    <button type="button" @click="removeQuestion(index)" class="p-1 rounded bg-zinc-100 hover:bg-zinc-200 dark:bg-zinc-800 dark:hover:bg-zinc-750 text-zinc-500 dark:text-zinc-400 hover:text-red-500 dark:hover:text-red-400 border border-zinc-200 dark:border-zinc-700 transition-all">
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </div>

                                <div>
                                    <label class="block text-xxs font-bold text-zinc-450 dark:text-zinc-500 mb-1">Question Content</label>
                                    <input type="text" :name="'questions[' + index + '][question_text]'" required placeholder="E.g. What does SQL stand for?" class="w-full rounded-xl bg-white dark:bg-zinc-950 border border-zinc-200 dark:border-zinc-800 focus:border-zinc-500 focus:ring-1 focus:ring-zinc-500 text-xs text-zinc-900 dark:text-zinc-100 px-4 py-2 outline-none transition-all">
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-xxs font-bold text-zinc-450 dark:text-zinc-500 mb-1 flex items-center justify-between">
                                            <span>Option 1 (A)</span>
                                            <label class="inline-flex items-center gap-1 cursor-pointer">
                                                <input type="radio" :name="'questions[' + index + '][correct_option]'" value="0" checked class="h-3 w-3 text-zinc-900 dark:text-white border-zinc-200 dark:border-zinc-800 bg-white dark:bg-zinc-950 focus:ring-zinc-500">
                                                <span class="text-xxxxs uppercase tracking-wider font-extrabold text-zinc-550 dark:text-zinc-400">Correct Choice</span>
                                            </label>
                                        </label>
                                        <input type="text" :name="'questions[' + index + '][options][0]'" required placeholder="Option 1" class="w-full rounded-xl bg-white dark:bg-zinc-950 border border-zinc-200 dark:border-zinc-800 focus:border-zinc-500 text-xs text-zinc-900 dark:text-zinc-100 px-4 py-2 outline-none">
                                    </div>

                                    <div>
                                        <label class="block text-xxs font-bold text-zinc-450 dark:text-zinc-500 mb-1 flex items-center justify-between">
                                            <span>Option 2 (B)</span>
                                            <label class="inline-flex items-center gap-1 cursor-pointer">
                                                <input type="radio" :name="'questions[' + index + '][correct_option]'" value="1" class="h-3 w-3 text-zinc-900 dark:text-white border-zinc-200 dark:border-zinc-800 bg-white dark:bg-zinc-950 focus:ring-zinc-500">
                                                <span class="text-xxxxs uppercase tracking-wider font-extrabold text-zinc-550 dark:text-zinc-400">Correct Choice</span>
                                            </label>
                                        </label>
                                        <input type="text" :name="'questions[' + index + '][options][1]'" required placeholder="Option 2" class="w-full rounded-xl bg-white dark:bg-zinc-950 border border-zinc-200 dark:border-zinc-800 focus:border-zinc-500 text-xs text-zinc-900 dark:text-zinc-100 px-4 py-2 outline-none">
                                    </div>

                                    <div>
                                        <label class="block text-xxs font-bold text-zinc-450 dark:text-zinc-500 mb-1 flex items-center justify-between">
                                            <span>Option 3 (C)</span>
                                            <label class="inline-flex items-center gap-1 cursor-pointer">
                                                <input type="radio" :name="'questions[' + index + '][correct_option]'" value="2" class="h-3 w-3 text-zinc-900 dark:text-white border-zinc-200 dark:border-zinc-800 bg-white dark:bg-zinc-950 focus:ring-zinc-500">
                                                <span class="text-xxxxs uppercase tracking-wider font-extrabold text-zinc-550 dark:text-zinc-400">Correct Choice</span>
                                            </label>
                                        </label>
                                        <input type="text" :name="'questions[' + index + '][options][2]'" required placeholder="Option 3" class="w-full rounded-xl bg-white dark:bg-zinc-950 border border-zinc-200 dark:border-zinc-800 focus:border-zinc-500 text-xs text-zinc-900 dark:text-zinc-100 px-4 py-2 outline-none">
                                    </div>

                                    <div>
                                        <label class="block text-xxs font-bold text-zinc-450 dark:text-zinc-500 mb-1 flex items-center justify-between">
                                            <span>Option 4 (D)</span>
                                            <label class="inline-flex items-center gap-1 cursor-pointer">
                                                <input type="radio" :name="'questions[' + index + '][correct_option]'" value="3" class="h-3 w-3 text-zinc-900 dark:text-white border-zinc-200 dark:border-zinc-800 bg-white dark:bg-zinc-950 focus:ring-zinc-500">
                                                <span class="text-xxxxs uppercase tracking-wider font-extrabold text-zinc-550 dark:text-zinc-400">Correct Choice</span>
                                            </label>
                                        </label>
                                        <input type="text" :name="'questions[' + index + '][options][3]'" required placeholder="Option 4" class="w-full rounded-xl bg-white dark:bg-zinc-950 border border-zinc-200 dark:border-zinc-800 focus:border-zinc-500 text-xs text-zinc-900 dark:text-zinc-100 px-4 py-2 outline-none">
                                    </div>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>

                <div class="flex justify-end gap-3 pt-4 border-t border-zinc-200 dark:border-zinc-800">
                    <button type="button" @click="showCreateModal = false" class="rounded-xl bg-zinc-100 hover:bg-zinc-200 dark:bg-zinc-800 dark:hover:bg-zinc-750 text-zinc-750 dark:text-zinc-350 font-extrabold text-xs py-2.5 px-6 shadow transition-all uppercase tracking-wider border border-zinc-250 dark:border-zinc-700">
                        Cancel
                    </button>
                    <button type="submit" class="inline-flex items-center gap-2 rounded-xl bg-zinc-900 dark:bg-zinc-100 hover:bg-zinc-800 dark:hover:bg-zinc-200 text-white dark:text-zinc-950 font-extrabold text-xs py-2.5 px-6 shadow-md transition-all uppercase tracking-wider border border-transparent dark:border-zinc-350">
                        Publish Quiz Assessment
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
