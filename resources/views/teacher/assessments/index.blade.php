@extends('layouts.dashboard')

@section('title', 'Assessments & Marks')
@section('header_title', 'Student Assessments')

@section('content')
<div class="grid grid-cols-1 gap-6 lg:grid-cols-3 animate-fade-in text-zinc-900 dark:text-zinc-100">
    <!-- Left Column: Add Marks Form -->
    <div class="lg:col-span-1 space-y-6">
        <div>
            <h2 class="text-lg font-bold text-zinc-900 dark:text-white">Record Assessment</h2>
            <p class="text-xs text-zinc-450 dark:text-zinc-500 mt-0.5">Submit new assessment marks for students.</p>
        </div>

        <div class="bg-white dark:bg-zinc-900 rounded-2xl border border-zinc-200 dark:border-zinc-800 p-6 shadow-sm">
            <form method="POST" action="{{ route('teacher.assessments.store') }}" class="space-y-5">
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

                <!-- Subject Selection -->
                <div>
                    <label for="subject_id" class="block text-xxs font-black uppercase tracking-wider text-zinc-500 dark:text-zinc-400 mb-2">
                        Subject Area
                    </label>
                    <select id="subject_id" 
                            name="subject_id" 
                            required 
                            class="block mt-1.5 w-full rounded-xl border-zinc-250 dark:border-zinc-800 bg-white dark:bg-zinc-950 text-zinc-900 dark:text-zinc-100 shadow-xxs focus:border-zinc-500 focus:ring-1 focus:ring-zinc-500 text-sm py-2.5 px-4 outline-none transition-all duration-150">
                        <option value="">-- Select Subject --</option>
                        @foreach($subjects as $subj)
                            <option value="{{ $subj->id }}">{{ $subj->code }} - {{ $subj->name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Component Selector & Score Fields -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="component" class="block text-xxs font-black uppercase tracking-wider text-zinc-500 dark:text-zinc-400 mb-2">
                            Component
                        </label>
                        <select id="component" 
                                name="component" 
                                required 
                                onchange="updateScoreLimits()"
                                class="block mt-1.5 w-full rounded-xl border-zinc-250 dark:border-zinc-800 bg-white dark:bg-zinc-950 text-zinc-900 dark:text-zinc-100 shadow-xxs focus:border-zinc-500 focus:ring-1 focus:ring-zinc-500 text-sm py-2.5 px-4 outline-none transition-all duration-150">
                            <option value="ca1" {{ old('component') === 'ca1' ? 'selected' : '' }}>CA1 (out of 30)</option>
                            <option value="ca2" {{ old('component') === 'ca2' ? 'selected' : '' }}>CA2 (out of 30)</option>
                            <option value="end_term" {{ old('component') === 'end_term' ? 'selected' : '' }}>End Term (out of 40)</option>
                        </select>
                    </div>

                    <div>
                        <label for="score" id="score_label" class="block text-xxs font-black uppercase tracking-wider text-zinc-500 dark:text-zinc-400 mb-2">
                            Obtained Score
                        </label>
                        <input id="score" 
                               type="number" 
                               step="0.1" 
                               name="score" 
                               value="{{ old('score') }}"
                               required 
                               min="0" 
                               max="30" 
                               class="block mt-1.5 w-full rounded-xl border-zinc-250 dark:border-zinc-800 bg-white dark:bg-zinc-950 text-zinc-900 dark:text-zinc-100 shadow-xxs focus:border-zinc-500 focus:ring-1 focus:ring-zinc-500 text-sm py-2.5 px-4 outline-none transition-all duration-150"
                               placeholder="e.g. 24.5" />
                    </div>
                </div>

                <script>
                    function updateScoreLimits() {
                        const componentSelect = document.getElementById('component');
                        const scoreInput = document.getElementById('score');
                        const scoreLabel = document.getElementById('score_label');
                        
                        if (componentSelect.value === 'end_term') {
                            scoreInput.max = "40";
                            scoreInput.placeholder = "e.g. 32.5 (out of 40)";
                            scoreLabel.innerText = "Obtained Score (out of 40)";
                        } else {
                            scoreInput.max = "30";
                            scoreInput.placeholder = "e.g. 24.5 (out of 30)";
                            scoreLabel.innerText = "Obtained Score (out of 30)";
                        }
                    }
                    // Run once on load to ensure old inputs match correctly
                    document.addEventListener('DOMContentLoaded', updateScoreLimits);
                </script>

                <!-- Remedial Resource attached by Teacher -->
                <div>
                    <label for="remedial_resource" class="block text-xxs font-black uppercase tracking-wider text-zinc-500 dark:text-zinc-400 mb-2">
                        Remedial Resource / Study Instructions
                    </label>
                    <textarea id="remedial_resource" 
                              name="remedial_resource" 
                              rows="3"
                              class="block mt-1.5 w-full rounded-xl border-zinc-250 dark:border-zinc-800 bg-white dark:bg-zinc-950 text-zinc-900 dark:text-zinc-100 shadow-xxs focus:border-zinc-500 focus:ring-1 focus:ring-zinc-500 text-xs p-4 outline-none transition-all duration-150 leading-relaxed"
                              placeholder="e.g. Focus on electric circuit diagrams and complete basic formulas worksheet."></textarea>
                </div>

                <!-- Submit -->
                <div class="pt-2 border-t border-zinc-200 dark:border-zinc-800">
                    <button type="submit" class="flex w-full justify-center rounded-xl bg-zinc-900 dark:bg-zinc-100 hover:bg-zinc-800 dark:hover:bg-zinc-200 text-white dark:text-zinc-900 px-4 py-2.5 text-sm font-semibold shadow-sm transition-all duration-150">
                        Save Marks
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Right Column: List of Marks -->
    <div class="lg:col-span-2 space-y-6">
        <div>
            <h2 class="text-lg font-bold text-zinc-900 dark:text-white">Assessments Log</h2>
            <p class="text-xs text-zinc-450 dark:text-zinc-500 mt-0.5">Logs of recently updated subject assessment marks.</p>
        </div>

        <div class="bg-white dark:bg-zinc-900 rounded-2xl border border-zinc-200 dark:border-zinc-800 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b border-zinc-200 dark:border-zinc-800 bg-zinc-50/50 dark:bg-zinc-900/50 text-xxs font-semibold uppercase tracking-wider text-zinc-400 dark:text-zinc-500">
                            <th class="py-4 px-6">Student</th>
                            <th class="py-4 px-6">Subject</th>
                            <th class="py-4 px-4 text-center">CA1 (30)</th>
                            <th class="py-4 px-4 text-center">CA2 (30)</th>
                            <th class="py-4 px-4 text-center">End (40)</th>
                            <th class="py-4 px-6 text-center">Graded Total</th>
                            <th class="py-4 px-6 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-zinc-200 dark:divide-zinc-800 text-sm">
                        @forelse($assessments as $ast)
                            <tr class="hover:bg-zinc-50/50 dark:hover:bg-zinc-850/20 transition-colors">
                                <td class="py-4 px-6 font-semibold text-zinc-900 dark:text-white">
                                    {{ $ast->student->name ?? 'N/A' }}
                                </td>
                                <td class="py-4 px-6 font-semibold">
                                    <span class="block text-zinc-900 dark:text-white">{{ $ast->subjectRelation->code ?? 'N/A' }}</span>
                                    <span class="block text-xxs text-zinc-400 dark:text-zinc-500 font-medium mt-0.5">{{ $ast->subjectRelation->name ?? $ast->subject }}</span>
                                </td>
                                <td class="py-4 px-4 text-center font-medium text-zinc-550 dark:text-zinc-400">
                                    {{ is_null($ast->ca1) ? '—' : $ast->ca1 }}
                                </td>
                                <td class="py-4 px-4 text-center font-medium text-zinc-550 dark:text-zinc-400">
                                    {{ is_null($ast->ca2) ? '—' : $ast->ca2 }}
                                </td>
                                <td class="py-4 px-4 text-center font-medium text-zinc-550 dark:text-zinc-400">
                                    {{ is_null($ast->end_term) ? '—' : $ast->end_term }}
                                </td>
                                <td class="py-4 px-6 text-center font-bold">
                                    <span class="inline-flex items-center rounded-lg px-2 py-0.5 text-xs font-bold border {{ $ast->percentage < 40 ? 'bg-rose-500/10 text-rose-600 dark:text-rose-455 border-rose-500/20' : 'bg-emerald-500/10 text-emerald-600 dark:text-emerald-455 border-emerald-500/20' }}">
                                        {{ $ast->obtained }} / {{ $ast->max_possible }}
                                    </span>
                                </td>
                                <td class="py-4 px-6 text-right space-x-2.5 whitespace-nowrap">
                                    <a href="{{ route('teacher.assessments.edit', $ast->id) }}" class="inline-flex items-center rounded-lg bg-zinc-100 hover:bg-zinc-200 dark:bg-zinc-800 dark:hover:bg-zinc-750 text-zinc-750 dark:text-zinc-300 px-2.5 py-1.5 text-xs font-semibold transition-all">
                                        Edit
                                    </a>
                                    <form method="POST" action="{{ route('teacher.assessments.destroy', $ast->id) }}" class="inline-block" onsubmit="return confirm('Are you sure you want to delete this assessment record?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="inline-flex items-center rounded-lg bg-rose-50 hover:bg-rose-100/80 text-rose-700 border border-rose-200 dark:bg-rose-500/10 dark:hover:bg-rose-500/20 dark:text-rose-455 dark:border-rose-500/25 px-2.5 py-1.5 text-xs font-semibold transition-all">
                                            Delete
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="py-12 text-center text-zinc-400 dark:text-zinc-500 text-xs">
                                    No assessment records found in the database.
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
