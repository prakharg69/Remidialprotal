@extends('layouts.dashboard')

@section('title', 'Assessments Directory')
@section('header_title', 'All Student Assessments')

@section('content')
<div class="space-y-6 animate-fade-in">
    <div>
        <h2 class="text-lg font-bold text-slate-900 dark:text-white">Assessments Records</h2>
        <p class="text-xs text-slate-400 mt-0.5">Global list of all recorded subject marks in the system.</p>
    </div>

    <div class="bg-white dark:bg-slate-950 rounded-2xl border border-slate-100 dark:border-slate-800 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-slate-100 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-900/20 text-xxs font-semibold uppercase tracking-wider text-slate-400 dark:text-slate-500">
                        <th class="py-4 px-6">Student Name</th>
                        <th class="py-4 px-6">Subject</th>
                        <th class="py-4 px-4 text-center">CA1 (30)</th>
                        <th class="py-4 px-4 text-center">CA2 (30)</th>
                        <th class="py-4 px-4 text-center">End Term (40)</th>
                        <th class="py-4 px-6 text-center">Total (100)</th>
                        <th class="py-4 px-6">Date Recorded</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800 text-sm">
                    @forelse($assessments as $ast)
                        <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-900/10 transition-colors">
                            <td class="py-4 px-6 font-semibold text-slate-900 dark:text-white">
                                {{ $ast->student->name ?? 'N/A' }}
                            </td>
                            <td class="py-4 px-6 font-semibold text-indigo-600 dark:text-indigo-400">
                                {{ $ast->subject }}
                            </td>
                            <td class="py-4 px-4 text-center font-medium text-slate-655 dark:text-slate-350">
                                {{ $ast->ca1 ?? 0 }}
                            </td>
                            <td class="py-4 px-4 text-center font-medium text-slate-655 dark:text-slate-350">
                                {{ $ast->ca2 ?? 0 }}
                            </td>
                            <td class="py-4 px-4 text-center font-medium text-slate-655 dark:text-slate-350">
                                {{ $ast->end_term ?? 0 }}
                            </td>
                            <td class="py-4 px-6 text-center font-bold">
                                <span class="{{ ($ast->total ?? 0) < 40 ? 'text-rose-600 dark:text-rose-400 bg-rose-50 dark:bg-rose-950/20 px-2 py-0.5 rounded-lg border border-rose-100 dark:border-rose-900/30' : 'text-emerald-600 dark:text-emerald-400 bg-emerald-50 dark:bg-emerald-950/20 px-2 py-0.5 rounded-lg border border-emerald-100 dark:border-emerald-900/30' }}">
                                    {{ $ast->total ?? 0 }} / 100
                                </span>
                            </td>
                            <td class="py-4 px-6 text-slate-400 dark:text-slate-500">
                                {{ $ast->created_at ? $ast->created_at->format('M d, Y, h:i A') : 'N/A' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="py-12 text-center text-slate-400 dark:text-slate-500 text-xs">
                                No assessment marks recorded in the database yet.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
