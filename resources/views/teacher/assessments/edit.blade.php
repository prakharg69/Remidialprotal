@extends('layouts.dashboard')

@section('title', 'Edit Marks')
@section('header_title', 'Edit Assessment Marks')

@section('content')
<div class="max-w-xl mx-auto space-y-6 animate-fade-in">
    <!-- Header -->
    <div class="flex items-center gap-4">
        <a href="{{ route('teacher.assessments') }}" class="inline-flex h-9 w-9 items-center justify-center rounded-xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-950 text-slate-500 hover:text-slate-900 dark:hover:text-white transition-all shadow-xxs">
            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        </a>
        <div>
            <h2 class="text-lg font-bold text-slate-900 dark:text-white">Modify Assessment</h2>
            <p class="text-xs text-slate-400 mt-0.5">Edit marks received by {{ $assessment->student->name ?? 'Student' }}.</p>
        </div>
    </div>

    <!-- Form Card -->
    <div class="bg-white dark:bg-slate-950 rounded-2xl border border-slate-100 dark:border-slate-800 p-6 shadow-sm">
        <form method="POST" action="{{ route('teacher.assessments.update', $assessment->id) }}" class="space-y-5">
            @csrf
            @method('PUT')

            <!-- Student (locked for edit to keep it consistent, but sent in form) -->
            <div>
                <label class="block text-sm font-semibold text-slate-700 dark:text-slate-350">
                    Student Profile
                </label>
                <input type="text" 
                       disabled 
                       class="block mt-1.5 w-full rounded-xl border-slate-200 dark:border-slate-850 bg-slate-50 dark:bg-slate-900 text-slate-500 dark:text-slate-400 text-sm py-2.5 px-4" 
                       value="{{ $assessment->student->name ?? 'N/A' }}" />
                <input type="hidden" name="student_id" value="{{ $assessment->student_id }}" />
            </div>

            <!-- Subject Selection -->
            <div>
                <label for="subject_id" class="block text-sm font-semibold text-slate-700 dark:text-slate-350">
                    Subject Area
                </label>
                <select id="subject_id" 
                        name="subject_id" 
                        required 
                        class="block mt-1.5 w-full rounded-xl border-slate-200 dark:border-slate-850 bg-white dark:bg-slate-900 text-slate-900 dark:text-slate-100 shadow-xxs focus:border-indigo-500 focus:ring-indigo-500 text-sm py-2.5 px-4">
                    @foreach($subjects as $subj)
                        <option value="{{ $subj->id }}" {{ old('subject_id', $assessment->subject_id) === $subj->id ? 'selected' : '' }}>
                            {{ $subj->code }} - {{ $subj->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Current Grades Alert Banner -->
            <div class="rounded-xl border border-indigo-100 bg-indigo-50/50 dark:bg-indigo-950/20 dark:border-indigo-900/30 p-4 text-xs">
                <span class="font-bold text-indigo-700 dark:text-indigo-400 block mb-1">Current Recorded Scores:</span>
                <div class="grid grid-cols-3 gap-2 text-slate-600 dark:text-slate-350 font-medium">
                    <div>CA1: <span class="font-bold text-slate-900 dark:text-white">{{ is_null($assessment->ca1) ? '—' : $assessment->ca1 . ' / 30' }}</span></div>
                    <div>CA2: <span class="font-bold text-slate-900 dark:text-white">{{ is_null($assessment->ca2) ? '—' : $assessment->ca2 . ' / 30' }}</span></div>
                    <div>End Term: <span class="font-bold text-slate-900 dark:text-white">{{ is_null($assessment->end_term) ? '—' : $assessment->end_term . ' / 40' }}</span></div>
                </div>
            </div>

            <!-- Component Selector & Score Fields -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="component" class="block text-sm font-semibold text-slate-700 dark:text-slate-355">
                        Assessment Component to Edit
                    </label>
                    <select id="component" 
                            name="component" 
                            required 
                            onchange="updateScoreLimits()"
                            class="block mt-1.5 w-full rounded-xl border-slate-200 dark:border-slate-850 bg-white dark:bg-slate-900 text-slate-900 dark:text-slate-100 shadow-xxs focus:border-indigo-500 focus:ring-indigo-500 text-sm py-2.5 px-4">
                        <option value="ca1" {{ old('component') === 'ca1' ? 'selected' : '' }}>CA1 (out of 30)</option>
                        <option value="ca2" {{ old('component') === 'ca2' ? 'selected' : '' }}>CA2 (out of 30)</option>
                        <option value="end_term" {{ old('component') === 'end_term' ? 'selected' : '' }}>End Term Exam (out of 40)</option>
                    </select>
                </div>

                <div>
                    <label for="score" id="score_label" class="block text-sm font-semibold text-slate-700 dark:text-slate-355">
                        New Score
                    </label>
                    <input id="score" 
                           type="number" 
                           step="0.1" 
                           name="score" 
                           value="{{ old('score') }}"
                           required 
                           min="0" 
                           max="30" 
                           class="block mt-1.5 w-full rounded-xl border-slate-200 dark:border-slate-850 bg-white dark:bg-slate-900 text-slate-900 dark:text-slate-100 shadow-xxs focus:border-indigo-500 focus:ring-indigo-500 text-sm py-2.5 px-4" />
                </div>
            </div>

            <script>
                function updateScoreLimits() {
                    const componentSelect = document.getElementById('component');
                    const scoreInput = document.getElementById('score');
                    const scoreLabel = document.getElementById('score_label');
                    const currentScores = {
                        ca1: @json($assessment->ca1),
                        ca2: @json($assessment->ca2),
                        end_term: @json($assessment->end_term)
                    };
                    
                    const comp = componentSelect.value;
                    scoreInput.value = currentScores[comp] !== null ? currentScores[comp] : '';
                    
                    if (comp === 'end_term') {
                        scoreInput.max = "40";
                        scoreInput.placeholder = "e.g. 32.5 (out of 40)";
                        scoreLabel.innerText = "New Score (out of 40)";
                    } else {
                        scoreInput.max = "30";
                        scoreInput.placeholder = "e.g. 24.5 (out of 30)";
                        scoreLabel.innerText = "New Score (out of 30)";
                    }
                }
                document.addEventListener('DOMContentLoaded', updateScoreLimits);
            </script>

            <!-- Remedial Resource attached by Teacher -->
            <div>
                <label for="remedial_resource" class="block text-sm font-semibold text-slate-700 dark:text-slate-350">
                    Remedial Resource / Study Instructions
                </label>
                <textarea id="remedial_resource" 
                          name="remedial_resource" 
                          rows="2"
                          class="block mt-1.5 w-full rounded-xl border-slate-200 dark:border-slate-850 bg-white dark:bg-slate-900 text-slate-900 dark:text-slate-100 shadow-xxs focus:border-indigo-500 focus:ring-indigo-500 text-sm py-2.5 px-4"
                          placeholder="e.g. Focus on electric circuit diagrams and complete basic formulas worksheet.">{{ old('remedial_resource', $assessment->remedial_resource) }}</textarea>
            </div>

            <!-- Buttons -->
            <div class="flex items-center justify-end gap-3.5 border-t border-slate-100 dark:border-slate-800 pt-5">
                <a href="{{ route('teacher.assessments') }}" class="rounded-xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-950 text-slate-700 dark:text-slate-350 px-4 py-2.5 text-sm font-semibold hover:bg-slate-50 dark:hover:bg-slate-900 transition-all">
                    Cancel
                </a>
                <button type="submit" class="rounded-xl bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 transition-all">
                    Save Changes
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
