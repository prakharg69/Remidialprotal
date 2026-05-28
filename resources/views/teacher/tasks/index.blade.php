@extends('layouts.dashboard')

@section('title', 'Remedial Tasks')
@section('header_title', 'Remedial Task Assignment')

@section('content')
<div class="grid grid-cols-1 gap-6 lg:grid-cols-3 animate-fade-in">
    <!-- Left Column: Assign Task Form -->
    <div class="lg:col-span-1 space-y-6">
        <div>
            <h2 class="text-lg font-bold text-slate-900 dark:text-white">Assign Task</h2>
            <p class="text-xs text-slate-400 mt-0.5">Create custom homework or corrective capacity building tasks.</p>
        </div>

        <div class="bg-white dark:bg-slate-950 rounded-2xl border border-slate-100 dark:border-slate-800 p-6 shadow-sm">
            <form method="POST" action="{{ route('teacher.tasks.store') }}" class="space-y-5">
                @csrf

                <!-- Subject Selection -->
                <div>
                    <label for="subject_id" class="block text-sm font-semibold text-slate-700 dark:text-slate-350">
                        Subject (Course Code)
                    </label>
                    <select id="subject_id" 
                            name="subject_id" 
                            required 
                            onchange="syncStudentsBySubject()"
                            class="block mt-1.5 w-full rounded-xl border-slate-200 dark:border-slate-850 bg-white dark:bg-slate-900 text-slate-900 dark:text-slate-100 shadow-xxs focus:border-indigo-500 focus:ring-indigo-500 text-sm py-2.5 px-4 font-bold">
                        <option value="">-- Select Subject --</option>
                        @foreach($subjects as $subj)
                            <option value="{{ $subj->id }}">{{ $subj->code }} - {{ $subj->name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Multiple Students Selection -->
                <div class="space-y-2">
                     <div class="flex items-center justify-between">
                         <label class="block text-sm font-semibold text-slate-700 dark:text-slate-350">
                             Select Students to Assign
                         </label>
                         <button type="button"
                                 onclick="selectAllFailing()"
                                 class="text-xxs font-extrabold uppercase tracking-wider text-indigo-600 dark:text-indigo-400 hover:underline">
                             Select All Failing
                         </button>
                     </div>
                     <!-- Class Filter Dropdown -->
                     <div class="mt-2">
                         <label for="class_filter" class="block text-sm font-semibold text-slate-700 dark:text-slate-350">Filter by Class</label>
                         <select id="class_filter" onchange="filterByClass()" class="block mt-1.5 w-full rounded-xl border-slate-200 dark:border-slate-850 bg-white dark:bg-slate-900 text-slate-900 dark:text-slate-100 shadow-xxs focus:border-indigo-500 focus:ring-indigo-500 text-sm py-2.5 px-4 font-medium">
                             <option value="all">All Classes</option>
                             @foreach($classCodes as $code)
                                 <option value="{{ $code }}">{{ $code }}</option>
                             @endforeach
                         </select>
                     </div>

                    <!-- Beautiful scrollable checkbox list of students -->
                    <div class="rounded-xl border border-slate-200 dark:border-slate-850 bg-slate-50/50 dark:bg-slate-900/50 overflow-y-auto max-h-60 p-4 space-y-3 shadow-inner">
                        @foreach($students as $st)
                            <div class="flex items-center justify-between student-row gap-4 py-2 border-b border-slate-200/50 dark:border-slate-850/50 last:border-none px-1 rounded-lg transition-all" 
                                 data-student-id="{{ $st->id }}"
                                 data-class="{{ $st->student->class_code ?? '' }}">
                                <div class="flex items-center gap-3">
                                    <input type="checkbox" 
                                           id="st_cb_{{ $st->id }}" 
                                           name="student_ids[]" 
                                           value="{{ $st->id }}" 
                                           class="h-4.5 w-4.5 rounded-md border-slate-300 dark:border-slate-750 bg-white dark:bg-slate-900 text-indigo-600 focus:ring-indigo-500" />
                                    <label for="st_cb_{{ $st->id }}" class="cursor-pointer text-sm font-semibold text-slate-900 dark:text-slate-200">
                                        {{ $st->name }} <span class="text-xxs text-slate-450 dark:text-slate-500 font-bold">({{ $st->student->class_code ?? 'N/A' }})</span>
                                    </label>
                                </div>
                                <div class="student-badge-container flex-shrink-0">
                                    <!-- Dynamic Badge Injected via JS -->
                                    <span class="inline-flex items-center rounded-full bg-slate-100 dark:bg-slate-800 px-2 py-0.5 text-xxs font-semibold text-slate-500 dark:text-slate-400 border border-slate-200/50 dark:border-slate-700/50">
                                        On Track
                                    </span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <span class="block text-xxs text-slate-400 dark:text-slate-500">Checking a subject will automatically select and highlight all students currently failing in that course area. You can customize the selections.</span>
                </div>

                <!-- Task Title -->
                <div>
                    <label for="title" class="block text-sm font-semibold text-slate-700 dark:text-slate-300">
                        Task Title
                    </label>
                    <input id="title" 
                           type="text" 
                           name="title" 
                           required 
                           class="block mt-1.5 w-full rounded-xl border-slate-200 dark:border-slate-850 bg-white dark:bg-slate-900 text-slate-900 dark:text-slate-100 shadow-xxs focus:border-indigo-500 focus:ring-indigo-500 text-sm py-2.5 px-4"
                           placeholder="e.g. Maths Remedial Sheet 1" />
                </div>

                <!-- Task Description -->
                <div>
                    <label for="description" class="block text-sm font-semibold text-slate-700 dark:text-slate-300">
                        Description / Instructions
                    </label>
                    <textarea id="description" 
                              name="description" 
                              required 
                              rows="4"
                              class="block mt-1.5 w-full rounded-xl border-slate-200 dark:border-slate-850 bg-white dark:bg-slate-900 text-slate-900 dark:text-slate-100 shadow-xxs focus:border-indigo-500 focus:ring-indigo-500 text-sm py-2.5 px-4"
                              placeholder="Describe remedial guidelines, links to read, or exercise numbers..."></textarea>
                </div>

                <!-- Submit -->
                <div class="pt-2 border-t border-slate-100 dark:border-slate-800">
                    <button type="submit" class="flex w-full justify-center rounded-xl bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 transition-all duration-150">
                        Assign Remedial Task
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Right Column: List of Tasks -->
    <div class="lg:col-span-2 space-y-6">
        <div>
            <h2 class="text-lg font-bold text-slate-900 dark:text-white">Active Assignments Log</h2>
            <p class="text-xs text-slate-400 mt-0.5">All capacity-building tasks issued in the system.</p>
        </div>

        <div class="bg-white dark:bg-slate-950 rounded-2xl border border-slate-100 dark:border-slate-800 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b border-slate-100 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-900/20 text-xxs font-semibold uppercase tracking-wider text-slate-400 dark:text-slate-500">
                            <th class="py-4 px-6">Student</th>
                            <th class="py-4 px-6">Task Details</th>
                            <th class="py-4 px-6">Class</th>
                            <th class="py-4 px-6">Status Badge</th>
                            <th class="py-4 px-6">Assigned Date</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800 text-sm">
                        @forelse($tasks as $task)
                            <tr class="task-row hover:bg-slate-50/50 dark:hover:bg-slate-900/10 transition-colors" data-class="{{ $task->student->class_code ?? '' }}">
                                <!-- Student Name -->
                                <td class="py-4 px-6 font-semibold text-slate-900 dark:text-white">
                                    {{ $task->student->name ?? 'N/A' }}
                                </td>
                                <!-- Task Details -->
                                <td class="py-4 px-6 max-w-xs whitespace-normal">
                                    <div class="flex items-center gap-2">
                                        <span class="block font-bold text-indigo-655 dark:text-indigo-400">{{ $task->title }}</span>
                                        @if($task->subject)
                                            <span class="inline-flex items-center rounded-md bg-indigo-500/10 px-1.5 py-0.5 text-xxs font-bold text-indigo-400 border border-indigo-500/20 uppercase tracking-wider">{{ $task->subject->code }}</span>
                                        @endif
                                    </div>
                                    <span class="block text-xs text-slate-500 dark:text-slate-400 mt-1 italic">{{ $task->description }}</span>
                                </td>
                                <!-- Status Badge -->
                                <td class="py-4 px-6">
                                    @if($task->status === 'completed')
                                        <span class="inline-flex items-center rounded-lg bg-emerald-50 dark:bg-emerald-950/20 px-2.5 py-0.5 text-xs font-semibold text-emerald-700 dark:text-emerald-450 border border-emerald-100 dark:border-emerald-900/30 animate-pulse">
                                            Completed
                                        </span>
                                    @else
                                        <span class="inline-flex items-center rounded-lg bg-amber-50 dark:bg-amber-950/20 px-2.5 py-0.5 text-xs font-semibold text-amber-700 dark:text-amber-400 border border-amber-100 dark:border-amber-900/30">
                                            Pending
                                        </span>
                                    @endif
                                </td>
                                <!-- Assigned Date -->
                                <td class="py-4 px-6 text-slate-400 dark:text-slate-500 whitespace-nowrap">
                                    {{ $task->created_at ? $task->created_at->format('M d, Y') : 'N/A' }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="py-12 text-center text-slate-400 dark:text-slate-500 text-xs">
                                    No tasks currently assigned.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
    const failingMap = @json($failingMap);

    function syncStudentsBySubject() {
        const subjectSelect = document.getElementById('subject_id');
        const selectedSubjectId = subjectSelect.value;
        const studentRows = document.querySelectorAll('.student-row');

        studentRows.forEach(row => {
            const studentId = row.getAttribute('data-student-id');
            const checkbox = row.querySelector('input[type="checkbox"]');
            const badgeContainer = row.querySelector('.student-badge-container');

            if (!selectedSubjectId) {
                checkbox.checked = false;
                badgeContainer.innerHTML = `
                    <span class="inline-flex items-center rounded-full bg-slate-100 dark:bg-slate-800 px-2.5 py-0.5 text-xxs font-semibold text-slate-500 dark:text-slate-400 border border-slate-200/50 dark:border-slate-700/50">
                        Select Subject First
                    </span>
                `;
                row.classList.remove('bg-rose-500/5', 'border-rose-500/20');
                return;
            }

            const failingStudents = failingMap[selectedSubjectId] || [];

            if (failingStudents.includes(studentId)) {
                checkbox.checked = true;
                badgeContainer.innerHTML = `
                    <span class="inline-flex items-center rounded-full bg-rose-500/10 px-2.5 py-0.5 text-xxs font-extrabold text-rose-500 border border-rose-500/25 uppercase tracking-wider font-bold">
                        Failing (< 40%)
                    </span>
                `;
                row.classList.add('bg-rose-500/5', 'border-rose-500/20');
            } else {
                checkbox.checked = false;
                badgeContainer.innerHTML = `
                    <span class="inline-flex items-center rounded-full bg-emerald-500/10 px-2.5 py-0.5 text-xxs font-extrabold text-emerald-500 border border-emerald-500/25 uppercase tracking-wider font-bold">
                        On Track
                    </span>
                `;
                row.classList.remove('bg-rose-500/5', 'border-rose-500/20');
            }
        });
    }

    function selectAllFailing() {
        const subjectSelect = document.getElementById('subject_id');
        const selectedSubjectId = subjectSelect.value;
        if (!selectedSubjectId) {
            alert('Please select a Subject/Course Code first!');
            return;
        }

        const failingStudents = failingMap[selectedSubjectId] || [];
        const studentRows = document.querySelectorAll('.student-row');

        studentRows.forEach(row => {
            const studentId = row.getAttribute('data-student-id');
            const checkbox = row.querySelector('input[type="checkbox"]');
            if (failingStudents.includes(studentId)) {
                checkbox.checked = true;
            }
        });
    }

    function filterByClass() {
        const classSelect = document.getElementById('class_filter');
        const selectedClass = classSelect.value;
        const studentRows = document.querySelectorAll('.student-row');

        studentRows.forEach(row => {
            const rowClass = row.getAttribute('data-class') || '';
            if (selectedClass === 'all' || rowClass === selectedClass) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    }

    document.addEventListener('DOMContentLoaded', () => {
        syncStudentsBySubject();
        // Ensure initial class filter works (default all)
        filterByClass();
    });
</script>
@endsection
