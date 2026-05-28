@extends('layouts.dashboard')

@section('title', 'Create & Manage Assignments')
@section('header_title', 'Create & Manage Assignments')

@section('content')
<div class="space-y-8 animate-fade-in text-slate-100">
    
    <!-- Hero and Page Header -->
    <div class="relative overflow-hidden rounded-3xl bg-slate-900 border border-slate-800 p-6 sm:p-8 shadow-xl">
        <div class="absolute right-0 top-0 translate-x-1/3 -translate-y-1/3 h-72 w-72 rounded-full bg-indigo-500/10 blur-3xl pointer-events-none"></div>
        <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h2 class="text-2xl font-black text-white tracking-tight">Assignment Command Center</h2>
                <p class="text-xs text-slate-400 mt-1">Publish new course assignments, set due dates, and specify maximum marks for students.</p>
            </div>
            <div>
                <a href="{{ route('teacher.assignments.submissions') }}" class="inline-flex items-center gap-2 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-extrabold text-xs py-2.5 px-4 shadow transition-all uppercase tracking-wider">
                    <svg class="h-4.5 w-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                    Go to Assignment Review Panel
                </a>
            </div>
        </div>
    </div>

    <!-- Structured Grid: Create Form on Left, Current Assignments List on Right -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <!-- Create Assignment Column -->
        <div class="lg:col-span-1">
            <div class="bg-slate-900 border border-slate-800 rounded-2xl p-6 shadow-md sticky top-6">
                <h3 class="text-base font-extrabold text-white mb-6 flex items-center gap-2">
                    <svg class="h-5 w-5 text-indigo-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                    Publish New Assignment
                </h3>

                <form action="{{ route('teacher.assignments.store') }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-xxs font-black uppercase tracking-wider text-slate-400 mb-2">Assignment Title</label>
                        <input type="text" name="title" required placeholder="e.g. CA1 Lab Report on Trees" class="w-full rounded-xl bg-slate-950 border border-slate-800 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 text-xs text-white px-4 py-2.5 outline-none transition-all">
                    </div>

                    <div>
                        <label class="block text-xxs font-black uppercase tracking-wider text-slate-400 mb-2">Subject / Course Category</label>
                        <select name="subject" required class="w-full rounded-xl bg-slate-950 border border-slate-800 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 text-xs text-white px-4 py-2.5 outline-none transition-all">
                            <option value="" disabled selected>Select Subject...</option>
                            @foreach($subjects as $subj)
                                <option value="{{ $subj->name }}">{{ $subj->name }} ({{ $subj->code }})</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-xxs font-black uppercase tracking-wider text-slate-400 mb-2">Target Student Class / Section</label>
                        <select name="class_code" required class="w-full rounded-xl bg-slate-950 border border-slate-800 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 text-xs text-white px-4 py-2.5 outline-none transition-all">
                            <option value="" disabled selected>Select Target Class...</option>
                            @foreach($classCodes as $cc)
                                <option value="{{ $cc }}">{{ $cc }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xxs font-black uppercase tracking-wider text-slate-400 mb-2">Due Date</label>
                            <input type="datetime-local" name="due_date" required class="w-full rounded-xl bg-slate-950 border border-slate-800 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 text-xs text-white px-3 py-2.5 outline-none transition-all">
                        </div>
                        <div>
                            <label class="block text-xxs font-black uppercase tracking-wider text-slate-400 mb-2">Max Marks</label>
                            <input type="number" name="max_score" required min="1" value="100" placeholder="100" class="w-full rounded-xl bg-slate-950 border border-slate-800 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 text-xs text-white px-3 py-2.5 outline-none transition-all">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xxs font-black uppercase tracking-wider text-slate-400 mb-2">Instructions & Description</label>
                        <textarea name="description" rows="5" required placeholder="Type assignment details, guidelines, or reference resource URLs here..." class="w-full rounded-xl bg-slate-950 border border-slate-800 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 text-xs text-white p-4 outline-none transition-all leading-relaxed"></textarea>
                    </div>

                    <button type="submit" class="w-full inline-flex items-center justify-center gap-2 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-extrabold text-xs py-2.5 px-4 shadow transition-all uppercase tracking-wider">
                        Publish to Students
                    </button>
                </form>
            </div>
        </div>

        <!-- Assignments List Column -->
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-slate-900 border border-slate-800 rounded-2xl p-6 shadow-md">
                <h3 class="text-base font-extrabold text-white mb-6">Active Assignments Catalog</h3>
                
                <div class="space-y-4">
                    @forelse($assignments as $assignment)
                        <div class="bg-slate-950/40 border border-slate-800 hover:border-slate-700 rounded-xl p-5 shadow-sm transition-all flex flex-col justify-between">
                            <div class="flex justify-between items-start gap-4">
                                <div>
                                    <div class="flex items-center gap-2 mb-2">
                                        <span class="inline-flex items-center rounded-lg bg-indigo-500/10 px-2 py-0.5 text-xxs font-extrabold text-indigo-400 border border-indigo-500/20 uppercase">
                                            {{ $assignment->subject }}
                                        </span>
                                        <span class="inline-flex items-center rounded-lg bg-emerald-500/10 px-2 py-0.5 text-xxs font-extrabold text-emerald-400 border border-emerald-500/20 uppercase">
                                            Class: {{ $assignment->class_code }}
                                        </span>
                                    </div>
                                    <h4 class="text-base font-black text-white leading-snug">{{ $assignment->title }}</h4>
                                </div>
                                <span class="inline-flex items-center rounded-lg bg-indigo-500/10 border border-indigo-500/20 px-2.5 py-1 text-xxs font-bold text-slate-350">
                                    Due: {{ $assignment->due_date ? $assignment->due_date->format('M d, Y h:i A') : 'N/A' }}
                                </span>
                            </div>

                            <p class="text-xs text-slate-400 mt-3 leading-relaxed whitespace-pre-line">{{ Str::limit($assignment->description, 200) }}</p>

                            <div class="mt-4 border-t border-slate-900 pt-4 flex items-center justify-between text-xxs text-slate-500">
                                <div>
                                    <span>Max Score: <strong class="text-indigo-400 font-extrabold">{{ $assignment->max_score }} Marks</strong></span>
                                </div>
                                <div>
                                    <a href="{{ route('teacher.assignments.submissions', ['search' => $assignment->title]) }}" class="inline-flex items-center gap-1 text-indigo-400 font-extrabold hover:underline">
                                        View Submissions
                                        <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                                    </a>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="py-12 text-center bg-slate-950/20 border border-slate-850 rounded-xl">
                            <div class="h-12 w-12 rounded-full bg-slate-900 flex items-center justify-center mx-auto text-slate-500 mb-3">
                                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2"/></svg>
                            </div>
                            <h4 class="text-sm font-bold text-slate-400">No Assignments Published Yet</h4>
                            <p class="text-xxs text-slate-500 mt-1">Use the panel on the left to publish your first assignment to students.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
