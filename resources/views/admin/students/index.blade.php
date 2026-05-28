@extends('layouts.dashboard')

@section('title', 'Manage Students')
@section('header_title', 'Manage Students')

@section('content')
<div class="space-y-6 animate-fade-in">
    <!-- Header Controls -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h2 class="text-lg font-bold text-slate-900 dark:text-white">Students Directory</h2>
            <p class="text-xs text-slate-400 mt-0.5">View profiles, average assessment marks, and slow learner status classifications.</p>
        </div>
        <div>
            <a href="{{ route('admin.students.create') }}" class="inline-flex items-center gap-2 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white font-semibold text-sm py-2.5 px-4 shadow-sm transition-all duration-150">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                Add Student
            </a>
        </div>
    </div>

    <!-- Data Card Table -->
    <div class="bg-white dark:bg-slate-950 rounded-2xl border border-slate-100 dark:border-slate-800 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-slate-100 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-900/20 text-xxs font-semibold uppercase tracking-wider text-slate-400 dark:text-slate-500">
                        <th class="py-4 px-6">Roll & Name</th>
                        <th class="py-4 px-6">Class</th>
                        <th class="py-4 px-6">Email / Contact</th>
                        <th class="py-4 px-6">Avg Marks</th>
                        <th class="py-4 px-6">Status Badge</th>
                        <th class="py-4 px-6 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800 text-sm">
                    @forelse($students as $student)
                        <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-900/10 transition-colors">
                            <!-- Roll & Name -->
                            <td class="py-4 px-6 font-semibold text-slate-900 dark:text-white">
                                <div class="flex items-center gap-3.5">
                                    <div class="h-9 w-9 rounded-xl bg-indigo-50 dark:bg-indigo-950/30 flex items-center justify-center text-indigo-650 dark:text-indigo-400 font-bold text-xs shadow-xxs">
                                        {{ $student->student->roll_number ?? '—' }}
                                    </div>
                                    <div>
                                        <span class="block">{{ $student->name }}</span>
                                        <span class="block text-xxs text-slate-400 dark:text-slate-500 font-medium">Roll: {{ $student->student->roll_number ?? 'Not Set' }}</span>
                                    </div>
                                </div>
                            </td>
                            <!-- Class -->
                            <td class="py-4 px-6 text-slate-600 dark:text-slate-350 font-medium">
                                {{ $student->student->class ?? 'N/A' }}
                            </td>
                            <!-- Contact -->
                            <td class="py-4 px-6">
                                <span class="block text-xs font-semibold text-slate-700 dark:text-slate-300">{{ $student->email }}</span>
                                <span class="block text-xxs text-slate-400 dark:text-slate-500 mt-0.5">{{ $student->student->phone ?? 'No Phone' }}</span>
                            </td>
                            <!-- Average Marks -->
                            <td class="py-4 px-6 font-bold text-slate-850 dark:text-slate-200">
                                @if(!is_null($student->average_marks))
                                    <span class="{{ $student->average_marks < 40 ? 'text-rose-600 dark:text-rose-455' : 'text-emerald-600 dark:text-emerald-450' }}">
                                        {{ $student->average_marks }}%
                                    </span>
                                @else
                                    <span class="text-slate-400 dark:text-slate-600 text-xs font-normal">No marks</span>
                                @endif
                            </td>
                            <!-- Status Badge -->
                            <td class="py-4 px-6">
                                @if($student->status === 'Slow Learner')
                                    <span class="inline-flex items-center rounded-full bg-rose-50 dark:bg-rose-950/45 px-2.5 py-0.5 text-xs font-semibold text-rose-700 dark:text-rose-350 border border-rose-100 dark:border-rose-900/30">
                                        Slow Learner
                                    </span>
                                @elseif($student->status === 'Normal Student')
                                    <span class="inline-flex items-center rounded-full bg-emerald-50 dark:bg-emerald-950/45 px-2.5 py-0.5 text-xs font-semibold text-emerald-700 dark:text-emerald-350 border border-emerald-100 dark:border-emerald-900/30">
                                        Normal
                                    </span>
                                @else
                                    <span class="inline-flex items-center rounded-full bg-slate-50 dark:bg-slate-900 px-2.5 py-0.5 text-xs font-semibold text-slate-400 border border-slate-200 dark:border-slate-800">
                                        No Data
                                    </span>
                                @endif
                            </td>
                            <!-- Actions -->
                            <td class="py-4 px-6 text-right space-x-2.5">
                                <a href="{{ route('admin.students.edit', $student->id) }}" class="inline-flex items-center rounded-lg bg-slate-100 hover:bg-slate-200 dark:bg-slate-900 dark:hover:bg-slate-800 text-slate-700 dark:text-slate-300 px-3 py-1.5 text-xs font-semibold transition-all">
                                    Edit
                                </a>
                                <form method="POST" action="{{ route('admin.students.destroy', $student->id) }}" class="inline-block" onsubmit="return confirm('Are you sure you want to delete this student and all associated assessments, attendance records, tasks, and feedbacks?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="inline-flex items-center rounded-lg bg-red-50 hover:bg-red-100 dark:bg-red-950/20 dark:hover:bg-red-950/30 text-red-700 dark:text-red-400 px-3 py-1.5 text-xs font-semibold transition-all">
                                        Delete
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-12 text-center text-slate-400 dark:text-slate-500 text-xs">
                                No students registered in the database.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
