@extends('layouts.dashboard')

@section('title', 'Solve Remedial Task')
@section('header_title', 'Solve Remedial Task')

@section('content')
<div class="max-w-3xl mx-auto space-y-8 animate-fade-in text-slate-100">
    <!-- Back Navigation Link -->
    <div class="flex items-center gap-4">
        <a href="{{ route('student.tasks') }}" class="inline-flex h-10 w-10 items-center justify-center rounded-2xl border border-slate-800 bg-slate-900 text-slate-400 hover:text-white transition-all shadow-md">
            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
        </a>
        <div>
            <h2 class="text-xl font-black text-white tracking-tight">Solve Task</h2>
            <p class="text-xs text-slate-400 mt-0.5">Assigned subject material for capacity building.</p>
        </div>
    </div>

    <!-- Error / Success Validation Messages -->
    @if($errors->any())
        <div class="bg-rose-500/10 border border-rose-500/30 rounded-2xl p-4 text-xs font-semibold text-rose-455">
            <ul class="list-disc pl-4 space-y-1">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Task Details Card -->
    <div class="bg-slate-900 border border-slate-800 rounded-3xl p-6 sm:p-8 shadow-xl relative overflow-hidden">
        <div class="absolute right-0 top-0 translate-x-1/4 -translate-y-1/4 h-48 w-48 rounded-full bg-indigo-500/5 blur-3xl pointer-events-none"></div>

        <div class="relative z-10 space-y-6">
            <div class="flex flex-wrap items-center justify-between gap-4">
                <div class="flex items-center gap-3">
                    <span class="inline-flex items-center rounded-xl bg-indigo-500/10 border border-indigo-500/20 px-3 py-1.5 text-xs font-black text-indigo-400 uppercase tracking-wider">
                        {{ $task->subject->code ?? 'GEN101' }}
                    </span>
                    <span class="text-sm font-semibold text-slate-400">
                        {{ $task->subject->name ?? 'General' }}
                    </span>
                </div>
                <div>
                    @if($task->status === 'completed')
                        <span class="inline-flex items-center gap-1 rounded-full bg-emerald-500/10 border border-emerald-500/20 px-3.5 py-1 text-xs font-black text-emerald-400 uppercase tracking-wider">
                            <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                            Task Completed
                        </span>
                    @else
                        <span class="inline-flex items-center gap-1 rounded-full bg-amber-500/10 border border-amber-500/20 px-3.5 py-1 text-xs font-black text-amber-500 uppercase tracking-wider animate-pulse">
                            Pending Submission
                        </span>
                    @endif
                </div>
            </div>

            <div class="border-t border-slate-850 pt-5">
                <h3 class="text-lg font-black text-white leading-tight">{{ $task->title }}</h3>
                <p class="text-sm text-slate-350 leading-relaxed mt-3 whitespace-pre-line italic">
                    "{{ $task->description }}"
                </p>
            </div>

            <div class="grid grid-cols-2 gap-4 border-t border-slate-850 pt-5 text-xs">
                <div>
                    <span class="block text-slate-500 font-bold uppercase tracking-wider text-xxs">Assigned By</span>
                    <span class="block text-slate-300 font-bold mt-1">Tr. {{ $task->teacher->name ?? 'System' }}</span>
                </div>
                <div>
                    <span class="block text-slate-500 font-bold uppercase tracking-wider text-xxs">Assigned Date</span>
                    <span class="block text-slate-300 font-bold mt-1">{{ $task->created_at ? $task->created_at->format('F d, Y, h:i A') : 'N/A' }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Submission Solver Form Card -->
    <div class="bg-slate-900 border border-slate-800 rounded-3xl p-6 sm:p-8 shadow-xl">
        @if($task->status === 'completed')
            <!-- Read-Only Completed Submission details -->
            <div class="space-y-6">
                <div>
                    <h3 class="text-base font-bold text-white">Your Submitted Solution</h3>
                    <p class="text-xxs text-slate-500 mt-0.5">This task has already been completed. Below are your submitted details.</p>
                </div>

                <div class="space-y-5 border-t border-slate-850 pt-5">
                    @if($task->submission_text)
                        <div>
                            <span class="block text-slate-500 font-bold uppercase tracking-wider text-xxs">Solution Explanation</span>
                            <div class="mt-2 p-4 bg-slate-950 border border-slate-850 rounded-2xl text-sm text-slate-300 whitespace-pre-line leading-relaxed">
                                {{ $task->submission_text }}
                            </div>
                        </div>
                    @endif

                    @if($task->submission_file)
                        <div>
                            <span class="block text-slate-500 font-bold uppercase tracking-wider text-xxs">Uploaded File Asset</span>
                            <div class="mt-2 flex items-center justify-between p-4 bg-slate-950 border border-slate-850 rounded-2xl">
                                <div class="flex items-center gap-3">
                                    <div class="p-2 rounded-xl bg-slate-900 text-indigo-400">
                                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                    </div>
                                    <div class="min-w-0">
                                        <span class="block text-xs font-semibold text-slate-200 truncate">Solution File</span>
                                        <span class="block text-xxs text-slate-500 mt-0.5">Asset saved securely.</span>
                                    </div>
                                </div>
                                <a href="{{ asset('storage/' . $task->submission_file) }}" target="_blank" class="inline-flex items-center gap-1 text-xxs font-black text-indigo-400 bg-indigo-500/10 hover:bg-indigo-500/20 px-3 py-1.5 rounded-xl border border-indigo-500/20 transition-all uppercase tracking-wider">
                                    View File
                                </a>
                            </div>
                        </div>
                    @endif

                    <div class="border-t border-slate-850 pt-5 flex items-center justify-between text-xs text-slate-500">
                        <span>Completed on: <strong>{{ $task->completed_at ? \Carbon\Carbon::parse($task->completed_at)->format('M d, Y \a\t h:i A') : 'N/A' }}</strong></span>
                    </div>
                </div>
            </div>
        @else
            <!-- Form to Submit Solver -->
            <form method="POST" action="{{ route('student.tasks.submit', $task->id) }}" enctype="multipart/form-data" class="space-y-6">
                @csrf
                <div>
                    <h3 class="text-base font-bold text-white">Submit Remedial Work</h3>
                    <p class="text-xxs text-slate-500 mt-0.5">Write a written explanation or upload your solution sheet (PDF/JPEG/PNG) below.</p>
                </div>

                <div class="space-y-5 border-t border-slate-850 pt-5">
                    <!-- Text Area Option -->
                    <div>
                        <label for="submission_text" class="block text-sm font-semibold text-slate-350">
                            Written Solution Explanation
                        </label>
                        <textarea id="submission_text" 
                                  name="submission_text" 
                                  rows="5"
                                  class="block mt-2 w-full rounded-2xl border-slate-800 bg-slate-950 text-slate-100 shadow-xxs focus:border-indigo-500 focus:ring-indigo-500 text-sm py-3 px-4 placeholder-slate-650"
                                  placeholder="Type your solution formulas, calculations or explanations here...">{{ old('submission_text') }}</textarea>
                    </div>

                    <!-- File Uploader Option -->
                    <div>
                        <label class="block text-sm font-semibold text-slate-350">
                            Upload Solution Sheet
                        </label>
                        <p class="text-xxs text-slate-500 mt-0.5">Accepted Formats: **PDF, JPEG, or PNG** only (Max size: 5MB).</p>
                        
                        <div class="mt-2 flex justify-center rounded-2xl border-2 border-dashed border-slate-800 bg-slate-950 px-6 py-8">
                            <div class="space-y-2 text-center">
                                <svg class="mx-auto h-10 w-10 text-slate-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 16.5V9.75m0 0l3 3m-3-3l-3 3M6.75 19.5a4.5 4.5 0 01-1.41-8.775 5.25 5.25 0 0110.233-2.33 3 3 0 013.758 3.848A3.752 3.752 0 0118 19.5H6.75z"/></svg>
                                <div class="flex text-sm text-slate-400 justify-center">
                                    <label for="submission_file" class="relative cursor-pointer rounded-md font-semibold text-indigo-400 hover:text-indigo-300 focus-within:outline-none">
                                        <span>Select solution file</span>
                                        <input id="submission_file" name="submission_file" type="file" accept=".pdf,image/png,image/jpeg,image/jpg" class="sr-only" />
                                    </label>
                                </div>
                                <span id="file-name-label" class="block text-xxs text-slate-500 font-semibold italic mt-1.5">No file chosen</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="pt-5 border-t border-slate-850 flex items-center justify-end gap-3.5">
                    <a href="{{ route('student.tasks') }}" class="rounded-xl border border-slate-800 bg-slate-905 text-slate-400 px-4 py-2.5 text-sm font-semibold hover:bg-slate-850 transition-all">
                        Cancel
                    </a>
                    <button type="submit" class="rounded-xl bg-indigo-600 hover:bg-indigo-500 px-5 py-2.5 text-sm font-black text-white shadow-lg shadow-indigo-500/20 transition-all duration-150 uppercase tracking-wider">
                        Submit & Complete Task
                    </button>
                </div>
            </form>
        @endif
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const fileInput = document.getElementById('submission_file');
        const fileNameLabel = document.getElementById('file-name-label');

        if (fileInput) {
            fileInput.addEventListener('change', function (e) {
                if (e.target.files && e.target.files.length > 0) {
                    fileNameLabel.innerText = "Selected File: " + e.target.files[0].name;
                    fileNameLabel.classList.remove('text-slate-500');
                    fileNameLabel.classList.add('text-indigo-400');
                } else {
                    fileNameLabel.innerText = "No file chosen";
                    fileNameLabel.classList.remove('text-indigo-400');
                    fileNameLabel.classList.add('text-slate-500');
                }
            });
        }
    });
</script>
@endsection
