@extends('layouts.dashboard')

@section('title', 'Assessments Directory')
@section('header_title', 'All Student Assessments')

@section('content')
<div class="space-y-6 animate-fade-in">
    <!-- Header -->
    <div>
        <h2 class="text-lg font-bold text-zinc-900 dark:text-white">Assessments Records</h2>
        <p class="text-xs text-zinc-500 dark:text-zinc-400 mt-0.5">Global list of all recorded subject marks in the system.</p>
    </div>

    <!-- Data Table -->
    <div class="bg-white dark:bg-zinc-900 rounded-2xl border border-zinc-200 dark:border-zinc-800 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-zinc-200 dark:border-zinc-800 bg-zinc-50/50 dark:bg-zinc-950/20 text-xxs font-semibold uppercase tracking-wider text-zinc-500 dark:text-zinc-400">
                        <th class="py-4 px-6">Student Name</th>
                        <th class="py-4 px-6">Subject</th>
                        <th class="py-4 px-4 text-center">CA1 (30)</th>
                        <th class="py-4 px-4 text-center">CA2 (30)</th>
                        <th class="py-4 px-4 text-center">End Term (40)</th>
                        <th class="py-4 px-6 text-center">Total (100)</th>
                        <th class="py-4 px-6">Date Recorded</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-200 dark:divide-zinc-800 text-sm">
                    @forelse($assessments as $ast)
                        <tr class="hover:bg-zinc-50/50 dark:hover:bg-zinc-800/10 transition-colors">
                            <td class="py-4 px-6 font-bold text-zinc-900 dark:text-white">
                                {{ $ast->student->name ?? 'N/A' }}
                            </td>
                            <td class="py-4 px-6 font-extrabold text-indigo-600 dark:text-indigo-400">
                                {{ $ast->subject }}
                            </td>
                            <td class="py-4 px-4 text-center font-medium text-zinc-700 dark:text-zinc-350">
                                {{ $ast->ca1 ?? 0 }}
                            </td>
                            <td class="py-4 px-4 text-center font-medium text-zinc-700 dark:text-zinc-350">
                                {{ $ast->ca2 ?? 0 }}
                            </td>
                            <td class="py-4 px-4 text-center font-medium text-zinc-700 dark:text-zinc-350">
                                {{ $ast->end_term ?? 0 }}
                            </td>
                            <td class="py-4 px-6 text-center font-bold">
                                <span class="inline-block px-2 py-0.5 rounded-lg border {{ ($ast->total ?? 0) < 40 ? 'text-rose-700 bg-rose-50 dark:text-rose-400 dark:bg-rose-500/10 border-rose-200 dark:border-rose-500/20' : 'text-emerald-700 bg-emerald-50 dark:text-emerald-400 dark:bg-emerald-500/10 border-emerald-200 dark:border-emerald-500/20' }}">
                                    {{ $ast->total ?? 0 }} / 100
                                </span>
                            </td>
                            <td class="py-4 px-6 text-zinc-400 dark:text-zinc-500">
                                {{ $ast->created_at ? $ast->created_at->format('M d, Y, h:i A') : 'N/A' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-12 text-center text-zinc-500 dark:text-zinc-400 text-xs">
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
