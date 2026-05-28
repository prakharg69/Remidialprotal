@extends('layouts.dashboard')

@section('title', 'Teacher Feedback')
@section('header_title', 'Student Progress Feedback')

@section('content')
<div class="grid grid-cols-1 gap-6 lg:grid-cols-3 animate-fade-in text-zinc-900 dark:text-zinc-100">
    <!-- Left Column: Add Remarks Form -->
    <div class="lg:col-span-1 space-y-6">
        <div>
            <h2 class="text-lg font-bold text-zinc-900 dark:text-white">Submit Remark</h2>
            <p class="text-xs text-zinc-450 dark:text-zinc-500 mt-0.5">Post progress remarks on student performance.</p>
        </div>

        <div class="bg-white dark:bg-zinc-900 rounded-2xl border border-zinc-200 dark:border-zinc-800 p-6 shadow-sm">
            <form method="POST" action="{{ route('teacher.feedback.store') }}" class="space-y-5">
                @csrf

                <!-- Student Selection -->
                <div>
                    <label for="student_id" class="block text-xxs font-black uppercase tracking-wider text-zinc-500 dark:text-zinc-400 mb-2">
                        Select Student
                    </label>
                    <select id="student_id" 
                            name="student_id" 
                            required 
                            class="block mt-1.5 w-full rounded-xl border-zinc-250 dark:border-zinc-800 bg-white dark:bg-zinc-950 text-zinc-900 dark:text-zinc-100 shadow-xxs focus:border-zinc-500 focus:ring-1 focus:ring-zinc-500 text-sm py-2.5 px-4 outline-none transition-all duration-150">
                        <option value="">-- Select Student --</option>
                        @foreach($students as $st)
                            <option value="{{ $st->id }}">{{ $st->name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Feedback Remark Input -->
                <div>
                    <label for="remark" class="block text-xxs font-black uppercase tracking-wider text-zinc-500 dark:text-zinc-400 mb-2">
                        Feedback Remark
                    </label>
                    <textarea id="remark" 
                              name="remark" 
                              required 
                              rows="5"
                              class="block mt-1.5 w-full rounded-xl border-zinc-250 dark:border-zinc-800 bg-white dark:bg-zinc-950 text-zinc-900 dark:text-zinc-100 shadow-xxs focus:border-zinc-500 focus:ring-1 focus:ring-zinc-500 text-sm py-2.5 px-4 outline-none transition-all duration-150 leading-relaxed"
                              placeholder="e.g. Needs improvement in Mathematics..."></textarea>
                </div>

                <!-- Submit -->
                <div class="pt-2 border-t border-zinc-200 dark:border-zinc-800">
                    <button type="submit" class="flex w-full justify-center rounded-xl bg-zinc-900 dark:bg-zinc-100 hover:bg-zinc-800 dark:hover:bg-zinc-200 text-white dark:text-zinc-900 px-4 py-2.5 text-sm font-semibold shadow-sm transition-all duration-150 border border-transparent dark:border-zinc-300">
                        Submit Feedback
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Right Column: List of Remarks -->
    <div class="lg:col-span-2 space-y-6">
        <div>
            <h2 class="text-lg font-bold text-zinc-900 dark:text-white">Your Feedbacks Log</h2>
            <p class="text-xs text-zinc-450 dark:text-zinc-500 mt-0.5">Logs of all comments you have issued on students.</p>
        </div>

        <div class="bg-white dark:bg-zinc-900 rounded-2xl border border-zinc-200 dark:border-zinc-800 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b border-zinc-200 dark:border-zinc-800 bg-zinc-50/50 dark:bg-zinc-900/50 text-xxs font-semibold uppercase tracking-wider text-zinc-400 dark:text-zinc-500">
                            <th class="py-4 px-6">Student Name</th>
                            <th class="py-4 px-6">Your Feedback Remark</th>
                            <th class="py-4 px-6">Date Recorded</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-zinc-200 dark:divide-zinc-800 text-sm">
                        @forelse($feedbacks as $fb)
                            <tr class="hover:bg-zinc-50/50 dark:hover:bg-zinc-850/20 transition-colors">
                                <td class="py-4 px-6 font-semibold text-zinc-900 dark:text-white">
                                    {{ $fb->student->name ?? 'N/A' }}
                                </td>
                                <td class="py-4 px-6 text-zinc-600 dark:text-zinc-400 italic max-w-sm whitespace-normal font-medium">
                                    "{{ $fb->remark }}"
                                </td>
                                <td class="py-4 px-6 text-zinc-400 dark:text-zinc-550 whitespace-nowrap">
                                    {{ $fb->created_at ? $fb->created_at->format('M d, Y, h:i A') : 'N/A' }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="py-12 text-center text-zinc-400 dark:text-zinc-500 text-xs">
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
