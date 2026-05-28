@extends('layouts.dashboard')

@section('title', 'Assignment Submissions Review')
@section('header_title', 'Assignment Submissions Review')

@section('content')
<div class="space-y-8 animate-fade-in text-slate-100">
    
    <!-- Hero Header -->
    <div class="relative overflow-hidden rounded-3xl bg-slate-900 border border-slate-800 p-6 sm:p-8 shadow-xl">
        <div class="absolute right-0 top-0 translate-x-1/3 -translate-y-1/3 h-72 w-72 rounded-full bg-indigo-500/10 blur-3xl pointer-events-none"></div>
        <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h2 class="text-2xl font-black text-white tracking-tight">Assignment Review Panel</h2>
                <p class="text-xs text-slate-400 mt-1">Review student file submissions, auto-detect late uploads, score work, and save private remarks.</p>
            </div>
            <div>
                <a href="{{ route('teacher.assignments') }}" class="inline-flex items-center gap-2 rounded-xl bg-slate-800 hover:bg-slate-700 border border-slate-700 text-indigo-400 font-extrabold text-xs py-2.5 px-4 shadow transition-all uppercase tracking-wider">
                    <svg class="h-4.5 w-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                    Back to Assignments
                </a>
            </div>
        </div>
    </div>

    <!-- Search / Filter Navigation -->
    <div class="bg-slate-900 border border-slate-800 rounded-2xl p-5 shadow-sm">
        <form action="{{ route('teacher.assignments.submissions') }}" method="GET" class="grid grid-cols-1 md:grid-cols-3 gap-4 items-end">
            <div>
                <label class="block text-xxs font-black uppercase tracking-wider text-slate-400 mb-2">Search Students / Titles</label>
                <input type="text" name="search" value="{{ $search }}" placeholder="Type student name or assignment title..." class="w-full rounded-xl bg-slate-950 border border-slate-800 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 text-xs text-white px-4 py-2.5 outline-none transition-all">
            </div>

            <div>
                <label class="block text-xxs font-black uppercase tracking-wider text-slate-400 mb-2">Filter by Status</label>
                <select name="status" class="w-full rounded-xl bg-slate-950 border border-slate-800 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 text-xs text-white px-4 py-2.5 outline-none transition-all">
                    <option value="">All Submissions</option>
                    <option value="submitted" {{ $statusFilter === 'submitted' ? 'selected' : '' }}>Submitted (Pending Review)</option>
                    <option value="accepted" {{ $statusFilter === 'accepted' ? 'selected' : '' }}>Accepted</option>
                    <option value="rejected" {{ $statusFilter === 'rejected' ? 'selected' : '' }}>Rejected</option>
                </select>
            </div>

            <div>
                <button type="submit" class="w-full inline-flex items-center justify-center gap-2 rounded-xl bg-indigo-650 hover:bg-indigo-600 text-white font-extrabold text-xs py-3 px-4 shadow transition-all uppercase tracking-wider">
                    Apply Filters
                </button>
            </div>
        </form>
    </div>

    <!-- Submissions List -->
    <div class="space-y-4">
        @forelse($submissions as $item)
            @php
                $assignment = $item['assignment'];
                $student = $item['student'];
                $sub = $item['submission'];
                $status = $item['status'];

                $isLate = $sub && $sub->isLate();
                $isExcellent = $sub && $sub->isExcellent();
                $needsImprovement = $sub && $sub->needsImprovement();
            @endphp
            <div class="bg-slate-900 border border-slate-800 rounded-2xl p-6 shadow-md hover:border-slate-700 transition-all" x-data="{ open: false }">
                <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-6">
                    <!-- Submission Metadata -->
                    <div class="flex-1">
                        <div class="flex flex-wrap items-center gap-3">
                            <span class="inline-flex h-9 w-9 items-center justify-center rounded-xl bg-indigo-500/10 font-black text-xs text-indigo-400 border border-indigo-500/20">
                                {{ strtoupper(substr($student->name ?? 'ST', 0, 2)) }}
                            </span>
                            <div>
                                <h4 class="text-base font-black text-white leading-tight">
                                    {{ $student->name ?? 'Unknown Student' }}
                                </h4>
                                <p class="text-xxs text-slate-500 mt-0.5">
                                    Target Class: {{ $student->class_code ?? 'N/A' }} | Email: {{ $student->email ?? 'N/A' }}
                                </p>
                            </div>
                        </div>

                        <div class="mt-4 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4 text-xxs">
                            <div>
                                <span class="block text-slate-500 uppercase tracking-wider font-bold">Assignment</span>
                                <span class="block text-slate-200 font-semibold mt-1">{{ $assignment->title ?? 'N/A' }}</span>
                            </div>
                            <div>
                                <span class="block text-slate-500 uppercase tracking-wider font-bold">Submitted Date</span>
                                <span class="block text-slate-200 font-semibold mt-1">
                                    {{ $sub && $sub->submitted_at ? $sub->submitted_at->format('M d, Y h:i A') : 'Not Submitted Yet' }}
                                </span>
                            </div>
                            <div>
                                <span class="block text-slate-500 uppercase tracking-wider font-bold">Status Badge</span>
                                @if($status === 'pending_upload')
                                    <span class="inline-flex items-center rounded-lg mt-1 px-2.5 py-0.5 font-bold uppercase bg-red-500/10 text-red-500 border border-red-500/20">
                                        ⏳ Pending Upload
                                    </span>
                                @elseif($status === 'submitted')
                                    <span class="inline-flex items-center rounded-lg mt-1 px-2.5 py-0.5 font-bold uppercase bg-amber-500/10 text-amber-500 border border-amber-500/20">
                                        📥 Submitted (Review)
                                    </span>
                                @elseif($status === 'accepted')
                                    <span class="inline-flex items-center rounded-lg mt-1 px-2.5 py-0.5 font-bold uppercase bg-emerald-500/10 text-emerald-500 border border-emerald-500/20">
                                        ✅ Graded & Accepted
                                    </span>
                                @else
                                    <span class="inline-flex items-center rounded-lg mt-1 px-2.5 py-0.5 font-bold uppercase bg-rose-500/10 text-rose-500 border border-rose-500/20">
                                        ❌ Rejected (Revision)
                                    </span>
                                @endif
                            </div>
                            <div>
                                <span class="block text-slate-500 uppercase tracking-wider font-bold">Auto-Badges</span>
                                <div class="flex flex-wrap gap-1.5 mt-1">
                                    @if($isLate)
                                        <span class="inline-flex items-center rounded bg-orange-500/10 px-1.5 py-0.5 text-xxs font-extrabold text-orange-400 border border-orange-500/20 uppercase tracking-wider">
                                            ⏳ Late Submission
                                        </span>
                                    @endif
                                    @if($isExcellent)
                                        <span class="inline-flex items-center rounded bg-amber-500/10 px-1.5 py-0.5 text-xxs font-extrabold text-amber-400 border border-amber-500/20 uppercase tracking-wider">
                                            ⭐ Excellent Work
                                        </span>
                                    @endif
                                    @if($needsImprovement)
                                        <span class="inline-flex items-center rounded bg-rose-500/10 px-1.5 py-0.5 text-xxs font-extrabold text-rose-400 border border-rose-500/20 uppercase tracking-wider">
                                            ⚠️ Needs Improvement
                                        </span>
                                    @endif
                                    @if(!$sub)
                                        <span class="text-slate-600 font-medium italic">No submission yet</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Cloudinary File Actions & Grade Trigger -->
                    <div class="flex flex-wrap lg:flex-col items-center lg:items-end gap-3 flex-shrink-0">
                        @if($sub)
                            <div class="flex items-center gap-2">
                                <a href="{{ asset($sub->file_url) }}" target="_blank" class="inline-flex items-center gap-1.5 rounded-xl bg-slate-800 hover:bg-slate-700/80 border border-slate-700 px-3.5 py-2 text-xxs font-bold text-indigo-400 uppercase tracking-wider transition-all">
                                    <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                    View File
                                </a>
                                <a href="{{ asset($sub->file_url) }}" download class="inline-flex items-center gap-1.5 rounded-xl bg-slate-800 hover:bg-slate-700/80 border border-slate-700 px-3.5 py-2 text-xxs font-bold text-slate-300 uppercase tracking-wider transition-all">
                                    <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                                    Download
                                </a>
                            </div>
                            
                            <button @click="open = !open" class="inline-flex items-center gap-1 text-xs font-black text-indigo-400 hover:text-indigo-300 uppercase tracking-wider transition-all focus:outline-none">
                                <span x-text="open ? 'Close Grading' : 'Grade Submission'"></span>
                                <svg class="h-4 w-4 transition-transform duration-200" :class="open ? 'transform rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                            </button>
                        @else
                            <span class="inline-flex items-center gap-1 text-xxs font-black text-red-500 uppercase bg-red-500/10 px-3 py-2 rounded-xl border border-red-500/10">
                                ⏳ Waiting for Upload
                            </span>
                        @endif
                    </div>
                </div>

                <!-- Accordion Expandable Grading Panel -->
                @if($sub)
                    <div x-show="open" x-transition:enter="transition ease-out duration-150" x-transition:enter-start="opacity-0 max-h-0 overflow-hidden" x-transition:enter-end="opacity-100 max-h-screen" x-transition:leave="transition ease-in duration-100" x-transition:leave-start="opacity-100 max-h-screen" x-transition:leave-end="opacity-0 max-h-0 overflow-hidden" class="mt-6 border-t border-slate-800 pt-6">
                        <div class="bg-slate-950/40 rounded-2xl p-5 border border-slate-800/80">
                            <form action="{{ route('teacher.assignments.review', $sub->id) }}" method="POST" class="space-y-4">
                                @csrf
                                
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                    <!-- Action Selector -->
                                    <div>
                                        <label class="block text-xxs font-black uppercase tracking-wider text-slate-400 mb-2">Review Assessment Action</label>
                                        <div class="flex items-center gap-4">
                                            <label class="inline-flex items-center gap-2 cursor-pointer">
                                                <input type="radio" name="action" value="accept" {{ $sub->status === 'accepted' || $sub->status === 'submitted' ? 'checked' : '' }} class="h-4 w-4 text-indigo-600 border-slate-800 bg-slate-950 focus:ring-indigo-500">
                                                <span class="text-xs font-bold text-emerald-450 uppercase">Accept Submission</span>
                                            </label>
                                            <label class="inline-flex items-center gap-2 cursor-pointer">
                                                <input type="radio" name="action" value="reject" {{ $sub->status === 'rejected' ? 'checked' : '' }} class="h-4 w-4 text-rose-600 border-slate-800 bg-slate-950 focus:ring-rose-500">
                                                <span class="text-xs font-bold text-rose-455 uppercase">Reject / Revision</span>
                                            </label>
                                        </div>
                                    </div>

                                    <!-- Score Grade input -->
                                    <div>
                                        <label class="block text-xxs font-black uppercase tracking-wider text-slate-400 mb-2">
                                            Assign Score (Out of {{ $assignment->max_score ?? 100 }})
                                        </label>
                                        <input type="number" name="score" required min="0" max="{{ $assignment->max_score ?? 100 }}" step="0.5" value="{{ $sub->score ?? '' }}" class="w-full rounded-xl bg-slate-950 border border-slate-800 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 text-xs text-white px-4 py-2.5 outline-none transition-all">
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <!-- Public Feedback -->
                                    <div>
                                        <label class="block text-xxs font-black uppercase tracking-wider text-slate-400 mb-2">Student Feedback (Visible to Student)</label>
                                        <textarea name="feedback" rows="4" placeholder="Write feedback comments, recommendations, or grading comments for the student..." class="w-full rounded-xl bg-slate-950 border border-slate-800 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 text-xs text-white p-4 outline-none transition-all leading-relaxed">{{ $sub->feedback }}</textarea>
                                    </div>

                                    <!-- Private Teacher Notes -->
                                    <div>
                                        <div class="flex items-center justify-between mb-2">
                                            <label class="block text-xxs font-black uppercase tracking-wider text-indigo-400">Teacher Notes on Submission (Private)</label>
                                            <span class="text-xxs text-slate-500 font-bold uppercase tracking-wider flex items-center gap-1">
                                                🔒 Private (Only Visible to Teachers)
                                            </span>
                                        </div>
                                        <textarea name="teacher_notes" rows="4" placeholder="Attach private thoughts, specific slow learner notes, or internal tracking details. Students will NEVER see these notes." class="w-full rounded-xl bg-slate-950 border border-slate-800 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 text-xs text-white p-4 outline-none transition-all leading-relaxed">{{ $sub->teacher_notes }}</textarea>
                                    </div>
                                </div>

                                <div class="flex justify-end pt-2">
                                    <button type="submit" class="inline-flex items-center gap-2 rounded-xl bg-gradient-to-r from-indigo-500 to-indigo-600 hover:from-indigo-600 hover:to-indigo-700 text-white font-extrabold text-xs py-2.5 px-6 shadow-md transition-all uppercase tracking-wider">
                                        Save Review & Mark Complete
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                @endif
            </div>
        @empty
            <div class="py-16 text-center bg-slate-900 border border-slate-850 rounded-2xl">
                <div class="h-12 w-12 rounded-full bg-slate-800 flex items-center justify-center mx-auto text-slate-500 mb-3">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2"/></svg>
                </div>
                <h4 class="text-sm font-bold text-slate-400">No Submissions Matching Filters</h4>
                <p class="text-xxs text-slate-500 mt-1">Students will appear here once they upload files to Cloudinary.</p>
            </div>
        @endforelse
    </div>

    <!-- Pagination Footer -->
    <div class="mt-6">
        {{ $submissions->appends(request()->query())->links() }}
    </div>
</div>
@endsection
