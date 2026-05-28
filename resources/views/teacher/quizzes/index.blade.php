@extends('layouts.dashboard')

@section('title', 'Manage Quizzes')
@section('header_title', 'Manage Quizzes')

@section('content')
<div class="space-y-8 animate-fade-in text-slate-100" x-data="{ showCreateModal: false }">
    
    <!-- Hero Header -->
    <div class="relative overflow-hidden rounded-3xl bg-slate-900 border border-slate-800 p-6 sm:p-8 shadow-xl">
        <div class="absolute right-0 top-0 translate-x-1/3 -translate-y-1/3 h-72 w-72 rounded-full bg-indigo-500/10 blur-3xl pointer-events-none"></div>
        <div class="relative z-10 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h2 class="text-2xl font-black text-white tracking-tight">Quiz Management</h2>
                <p class="text-xs text-slate-400 mt-1">Create MCQs, set time constraints, set deadlines, and target remedial or normal students.</p>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('teacher.quizzes.attempts') }}" class="inline-flex items-center gap-2 rounded-xl bg-slate-800 hover:bg-slate-750 border border-slate-700 text-indigo-400 font-extrabold text-xs py-2.5 px-4 shadow transition-all uppercase tracking-wider">
                    <svg class="h-4.5 w-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    View Quiz Attempts
                </a>
                <button @click="showCreateModal = true" class="inline-flex items-center gap-2 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-extrabold text-xs py-2.5 px-4 shadow transition-all uppercase tracking-wider">
                    <svg class="h-4.5 w-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                    Create New Quiz
                </button>
            </div>
        </div>
    </div>

    <!-- Filters Panel -->
    <div class="bg-slate-900 border border-slate-800 rounded-2xl p-5 shadow-sm">
        <form action="{{ route('teacher.quizzes.index') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-4 gap-4 items-end">
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

    <!-- Quizzes Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($quizzes as $quiz)
            <div class="bg-slate-900 border border-slate-800 hover:border-slate-700 rounded-2xl p-6 shadow-md transition-all flex flex-col justify-between group">
                <div>
                    <div class="flex justify-between items-start gap-4 mb-4">
                        <span class="inline-flex items-center rounded-lg bg-indigo-500/10 px-2 py-0.5 text-xxs font-extrabold text-indigo-400 border border-indigo-500/20 uppercase">
                            {{ $quiz->subjectRelation->code ?? 'SUB' }}
                        </span>
                        
                        @if($quiz->student_type === 'remedial')
                            <span class="inline-flex items-center rounded-lg bg-rose-500/10 px-2 py-0.5 text-xxs font-extrabold text-rose-400 border border-rose-500/20 uppercase">
                                🔒 Remedial
                            </span>
                        @else
                            <span class="inline-flex items-center rounded-lg bg-emerald-500/10 px-2 py-0.5 text-xxs font-extrabold text-emerald-450 border border-emerald-500/20 uppercase">
                                🌐 Normal
                            </span>
                        @endif
                    </div>

                    <h3 class="text-lg font-bold text-white tracking-tight leading-snug group-hover:text-indigo-400 transition-colors">{{ $quiz->title }}</h3>
                    <p class="text-xs text-slate-400 mt-2 leading-relaxed line-clamp-3 italic">"{{ $quiz->instructions ?? 'No instructions provided.' }}"</p>
                    
                    <div class="mt-5 space-y-2.5 border-t border-slate-800/80 pt-4 text-xxs text-slate-400">
                        <div class="flex items-center justify-between">
                            <span class="font-medium">Target Section:</span>
                            <span class="font-bold text-slate-200 uppercase">{{ $quiz->class_code }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="font-medium">MCQ Questions:</span>
                            <span class="font-bold text-slate-200">{{ count($quiz->questions ?? []) }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="font-medium">Allowed Time:</span>
                            <span class="font-bold text-slate-200">{{ $quiz->duration }} Minutes</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="font-medium text-rose-400">Quiz Deadline:</span>
                            <span class="font-bold text-rose-400">{{ $quiz->deadline ? $quiz->deadline->format('M d, Y h:i A') : 'N/A' }}</span>
                        </div>
                    </div>
                </div>

                <div class="mt-6 border-t border-slate-800/85 pt-4 flex items-center justify-between">
                    <span class="text-xxs text-slate-500 font-semibold">Created {{ $quiz->created_at ? $quiz->created_at->diffForHumans() : '' }}</span>
                    <a href="{{ route('teacher.quizzes.attempts', ['subject_id' => $quiz->subject_id, 'class_code' => $quiz->class_code]) }}" class="inline-flex items-center gap-1 text-xxs font-black text-indigo-400 hover:text-indigo-300 uppercase tracking-wider">
                        View Attempts &rarr;
                    </a>
                </div>
            </div>
        @empty
            <div class="col-span-1 md:col-span-2 lg:col-span-3 py-16 text-center bg-slate-900 border border-slate-850 rounded-2xl">
                <div class="h-12 w-12 rounded-full bg-slate-850 flex items-center justify-center mx-auto text-slate-500 mb-3">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2"/></svg>
                </div>
                <h4 class="text-sm font-bold text-slate-400">No Quizzes Published Yet</h4>
                <p class="text-xxs text-slate-500 mt-1">Publish MCQs with browser cheating protection logs to start evaluating.</p>
            </div>
        @endforelse
    </div>

    <!-- Create Quiz Modal Backdrop (AlpineJS) -->
    <div x-show="showCreateModal" x-transition.opacity class="fixed inset-0 z-50 bg-slate-950/80 backdrop-blur-sm" style="display: none;"></div>

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
        
        <div class="bg-slate-900 border border-slate-800 rounded-3xl w-full max-w-4xl max-h-[85vh] overflow-y-auto shadow-2xl p-6 sm:p-8 space-y-6"
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
            
            <div class="flex items-center justify-between border-b border-slate-800 pb-4">
                <div>
                    <h3 class="text-xl font-black text-white">Create New Assessment Quiz</h3>
                    <p class="text-xxs text-slate-400 mt-0.5">Define metadata, duration, anti-cheat limits, and embed MCQs.</p>
                </div>
                <button @click="showCreateModal = false" class="p-1.5 rounded-lg bg-slate-850 text-slate-400 hover:text-white hover:bg-slate-800 transition-all">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <form action="{{ route('teacher.quizzes.store') }}" method="POST" class="space-y-6">
                @csrf
                
                <!-- Metadata Fields -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <label class="block text-xxs font-black uppercase tracking-wider text-slate-400 mb-2">Subject Course</label>
                        <select name="subject_id" required class="w-full rounded-xl bg-slate-950 border border-slate-800 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 text-xs text-white px-4 py-2.5 outline-none transition-all">
                            @foreach($subjects as $subj)
                                <option value="{{ $subj->id }}">{{ $subj->code }} - {{ $subj->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-xxs font-black uppercase tracking-wider text-slate-400 mb-2">Target Section Class</label>
                        <select name="class_code" required class="w-full rounded-xl bg-slate-950 border border-slate-800 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 text-xs text-white px-4 py-2.5 outline-none transition-all">
                            @foreach($classCodes as $code)
                                <option value="{{ $code }}">{{ $code }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-xxs font-black uppercase tracking-wider text-slate-400 mb-2">Target Student Type</label>
                        <select name="student_type" required class="w-full rounded-xl bg-slate-950 border border-slate-800 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 text-xs text-white px-4 py-2.5 outline-none transition-all">
                            <option value="normal">Normal Students</option>
                            <option value="remedial">Remedial Students (Slow Learners)</option>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-xxs font-black uppercase tracking-wider text-slate-400 mb-2">Quiz Title</label>
                        <input type="text" name="title" required placeholder="E.g. DBMS Normalization CA2 Quiz" class="w-full rounded-xl bg-slate-950 border border-slate-800 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 text-xs text-white px-4 py-2.5 outline-none transition-all">
                    </div>

                    <div>
                        <label class="block text-xxs font-black uppercase tracking-wider text-slate-400 mb-2">Quiz Allowed Duration</label>
                        <select name="duration" required class="w-full rounded-xl bg-slate-950 border border-slate-800 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 text-xs text-white px-4 py-2.5 outline-none transition-all">
                            <option value="5">5 Minutes</option>
                            <option value="10">10 Minutes</option>
                            <option value="20">20 Minutes</option>
                            <option value="40">40 Minutes</option>
                            <option value="60">60 Minutes</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block text-xxs font-black uppercase tracking-wider text-slate-400 mb-2">Instructions for Student</label>
                    <textarea name="instructions" rows="2" placeholder="Write any instructions (e.g. Do not switch tabs. Tab switching triggers lock-out)..." class="w-full rounded-xl bg-slate-950 border border-slate-800 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 text-xs text-white p-4 outline-none transition-all leading-relaxed"></textarea>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-xxs font-black uppercase tracking-wider text-slate-400 mb-2">Deadline Date</label>
                        <input type="date" name="deadline_date" required min="{{ date('Y-m-d') }}" class="w-full rounded-xl bg-slate-950 border border-slate-800 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 text-xs text-white px-4 py-2.5 outline-none transition-all">
                    </div>

                    <div>
                        <label class="block text-xxs font-black uppercase tracking-wider text-slate-400 mb-2">Deadline Time</label>
                        <input type="time" name="deadline_time" required class="w-full rounded-xl bg-slate-950 border border-slate-800 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 text-xs text-white px-4 py-2.5 outline-none transition-all">
                    </div>
                </div>

                <!-- MCQs Questions Block -->
                <div class="border-t border-slate-800 pt-6">
                    <div class="flex items-center justify-between mb-6">
                        <div>
                            <h4 class="text-sm font-bold text-white">MCQ Questions Listing</h4>
                            <p class="text-xxs text-slate-500">Add questions, 4 text options, and check the correct option radio button.</p>
                        </div>
                        <button type="button" @click="addQuestion()" class="inline-flex items-center gap-1.5 rounded-lg bg-slate-800 hover:bg-slate-750 text-indigo-400 border border-slate-700 px-3 py-1.5 text-xxs font-extrabold uppercase transition-all">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                            Add MCQ
                        </button>
                    </div>

                    <div class="space-y-6">
                        <template x-for="(q, index) in questions" :key="index">
                            <div class="bg-slate-950/30 rounded-2xl p-5 border border-slate-800/80 space-y-4 relative">
                                <div class="flex justify-between items-center">
                                    <span class="text-xxs font-black text-indigo-400 uppercase tracking-widest" x-text="'MCQ Question #' + (index + 1)"></span>
                                    <button type="button" @click="removeQuestion(index)" class="p-1 rounded bg-slate-850 hover:bg-red-950/20 text-slate-400 hover:text-rose-500 border border-slate-800 transition-all">
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </div>

                                <div>
                                    <label class="block text-xxs font-bold text-slate-400 mb-1">Question Content</label>
                                    <input type="text" :name="'questions[' + index + '][question_text]'" required placeholder="E.g. What does SQL stand for?" class="w-full rounded-xl bg-slate-950 border border-slate-800 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 text-xs text-white px-4 py-2 outline-none transition-all">
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-xxs font-bold text-slate-400 mb-1 flex items-center justify-between">
                                            <span>Option 1 (A)</span>
                                            <label class="inline-flex items-center gap-1 cursor-pointer">
                                                <input type="radio" :name="'questions[' + index + '][correct_option]'" value="0" checked class="h-3 w-3 text-indigo-600 border-slate-800 bg-slate-950 focus:ring-indigo-500">
                                                <span class="text-xxxxs uppercase tracking-wider font-extrabold text-slate-500">Correct Choice</span>
                                            </label>
                                        </label>
                                        <input type="text" :name="'questions[' + index + '][options][0]'" required placeholder="Option 1" class="w-full rounded-xl bg-slate-950 border border-slate-800 focus:border-indigo-500 text-xs text-white px-4 py-2 outline-none">
                                    </div>

                                    <div>
                                        <label class="block text-xxs font-bold text-slate-400 mb-1 flex items-center justify-between">
                                            <span>Option 2 (B)</span>
                                            <label class="inline-flex items-center gap-1 cursor-pointer">
                                                <input type="radio" :name="'questions[' + index + '][correct_option]'" value="1" class="h-3 w-3 text-indigo-600 border-slate-800 bg-slate-950 focus:ring-indigo-500">
                                                <span class="text-xxxxs uppercase tracking-wider font-extrabold text-slate-500">Correct Choice</span>
                                            </label>
                                        </label>
                                        <input type="text" :name="'questions[' + index + '][options][1]'" required placeholder="Option 2" class="w-full rounded-xl bg-slate-950 border border-slate-800 focus:border-indigo-500 text-xs text-white px-4 py-2 outline-none">
                                    </div>

                                    <div>
                                        <label class="block text-xxs font-bold text-slate-400 mb-1 flex items-center justify-between">
                                            <span>Option 3 (C)</span>
                                            <label class="inline-flex items-center gap-1 cursor-pointer">
                                                <input type="radio" :name="'questions[' + index + '][correct_option]'" value="2" class="h-3 w-3 text-indigo-600 border-slate-800 bg-slate-950 focus:ring-indigo-500">
                                                <span class="text-xxxxs uppercase tracking-wider font-extrabold text-slate-500">Correct Choice</span>
                                            </label>
                                        </label>
                                        <input type="text" :name="'questions[' + index + '][options][2]'" required placeholder="Option 3" class="w-full rounded-xl bg-slate-950 border border-slate-800 focus:border-indigo-500 text-xs text-white px-4 py-2 outline-none">
                                    </div>

                                    <div>
                                        <label class="block text-xxs font-bold text-slate-400 mb-1 flex items-center justify-between">
                                            <span>Option 4 (D)</span>
                                            <label class="inline-flex items-center gap-1 cursor-pointer">
                                                <input type="radio" :name="'questions[' + index + '][correct_option]'" value="3" class="h-3 w-3 text-indigo-600 border-slate-800 bg-slate-950 focus:ring-indigo-500">
                                                <span class="text-xxxxs uppercase tracking-wider font-extrabold text-slate-500">Correct Choice</span>
                                            </label>
                                        </label>
                                        <input type="text" :name="'questions[' + index + '][options][3]'" required placeholder="Option 4" class="w-full rounded-xl bg-slate-950 border border-slate-800 focus:border-indigo-500 text-xs text-white px-4 py-2 outline-none">
                                    </div>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>

                <div class="flex justify-end gap-3 pt-4 border-t border-slate-800">
                    <button type="button" @click="showCreateModal = false" class="rounded-xl bg-slate-850 hover:bg-slate-800 border border-slate-700 text-slate-400 font-extrabold text-xs py-2.5 px-6 shadow transition-all uppercase tracking-wider">
                        Cancel
                    </button>
                    <button type="submit" class="inline-flex items-center gap-2 rounded-xl bg-gradient-to-r from-indigo-500 to-indigo-600 hover:from-indigo-600 hover:to-indigo-700 text-white font-extrabold text-xs py-2.5 px-6 shadow-md transition-all uppercase tracking-wider">
                        Publish Quiz Assessment
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
