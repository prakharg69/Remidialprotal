@extends('layouts.dashboard')

@section('title', 'Teacher Feedback')
@section('header_title', 'Student Progress Feedback')

@section('content')
<div class="grid grid-cols-1 gap-6 lg:grid-cols-3 animate-fade-in">
    <!-- Left Column: Add Remarks Form -->
    <div class="lg:col-span-1 space-y-6">
        <div>
            <h2 class="text-lg font-bold text-slate-900 dark:text-white">Submit Remark</h2>
            <p class="text-xs text-slate-400 mt-0.5">Post progress remarks on student performance.</p>
        </div>

        <div class="bg-white dark:bg-slate-950 rounded-2xl border border-slate-100 dark:border-slate-800 p-6 shadow-sm">
            <form method="POST" action="{{ route('teacher.feedback.store') }}" class="space-y-5">
                @csrf

                <!-- Student Selection -->
                <div>
                    <label for="student_id" class="block text-sm font-semibold text-slate-700 dark:text-slate-300">
                        Select Student
                    </label>
                    <select id="student_id" 
                            name="student_id" 
                            required 
                            class="block mt-1.5 w-full rounded-xl border-slate-200 dark:border-slate-850 bg-white dark:bg-slate-900 text-slate-900 dark:text-slate-100 shadow-xxs focus:border-indigo-500 focus:ring-indigo-500 text-sm py-2.5 px-4">
                        <option value="">-- Select Student --</option>
                        @foreach($students as $st)
                            <option value="{{ $st->id }}">{{ $st->name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Feedback Remark Input -->
                <div>
                    <label for="remark" class="block text-sm font-semibold text-slate-700 dark:text-slate-300">
                        Feedback Remark
                    </label>
                    <textarea id="remark" 
                              name="remark" 
                              required 
                              rows="5"
                              class="block mt-1.5 w-full rounded-xl border-slate-200 dark:border-slate-850 bg-white dark:bg-slate-900 text-slate-900 dark:text-slate-100 shadow-xxs focus:border-indigo-500 focus:ring-indigo-500 text-sm py-2.5 px-4"
                              placeholder="e.g. Needs improvement in Mathematics..."></textarea>
                </div>

                <!-- Submit -->
                <div class="pt-2 border-t border-slate-100 dark:border-slate-800">
                    <button type="submit" class="flex w-full justify-center rounded-xl bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 transition-all duration-150">
                        Submit Feedback
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Right Column: List of Remarks -->
    <div class="lg:col-span-2 space-y-6">
        <div>
            <h2 class="text-lg font-bold text-slate-900 dark:text-white">Your Feedbacks Log</h2>
            <p class="text-xs text-slate-400 mt-0.5">Logs of all comments you have issued on students.</p>
        </div>

        <div class="bg-white dark:bg-slate-950 rounded-2xl border border-slate-100 dark:border-slate-800 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b border-slate-100 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-900/20 text-xxs font-semibold uppercase tracking-wider text-slate-400 dark:text-slate-500">
                            <th class="py-4 px-6">Student Name</th>
                            <th class="py-4 px-6">Your Feedback Remark</th>
                            <th class="py-4 px-6">Date Recorded</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800 text-sm">
                        @forelse($feedbacks as $fb)
                            <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-900/10 transition-colors">
                                <td class="py-4 px-6 font-semibold text-slate-900 dark:text-white">
                                    {{ $fb->student->name ?? 'N/A' }}
                                </td>
                                <td class="py-4 px-6 text-slate-650 dark:text-slate-350 italic max-w-sm whitespace-normal">
                                    "{{ $fb->remark }}"
                                </td>
                                <td class="py-4 px-6 text-slate-400 dark:text-slate-500 whitespace-nowrap">
                                    {{ $fb->created_at ? $fb->created_at->format('M d, Y, h:i A') : 'N/A' }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="py-12 text-center text-slate-400 dark:text-slate-500 text-xs">
                                    You have not submitted any feedbacks yet.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
