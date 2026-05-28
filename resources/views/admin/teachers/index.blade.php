@extends('layouts.dashboard')

@section('title', 'Manage Teachers')
@section('header_title', 'Manage Teachers')

@section('content')
<div class="space-y-6 animate-fade-in">
    <!-- Header Controls -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h2 class="text-lg font-bold text-slate-900 dark:text-white">Teachers Directory</h2>
            <p class="text-xs text-slate-400 mt-0.5">A complete list of registered teachers in the system.</p>
        </div>
        <div>
            <a href="{{ route('admin.teachers.create') }}" class="inline-flex items-center gap-2 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white font-semibold text-sm py-2.5 px-4 shadow-sm transition-all duration-150">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                Add Teacher
            </a>
        </div>
    </div>

    <!-- Data Card -->
    <div class="bg-white dark:bg-slate-950 rounded-2xl border border-slate-100 dark:border-slate-800 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-slate-100 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-900/20 text-xxs font-semibold uppercase tracking-wider text-slate-400 dark:text-slate-500">
                        <th class="py-4 px-6">Name</th>
                        <th class="py-4 px-6">Email Address</th>
                        <th class="py-4 px-6">Authorized Subjects</th>
                        <th class="py-4 px-6">Date Added</th>
                        <th class="py-4 px-6 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800 text-sm">
                    @forelse($teachers as $teacher)
                        <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-900/10 transition-colors">
                            <td class="py-4 px-6 font-semibold text-slate-900 dark:text-white">
                                <div class="flex items-center gap-3">
                                    <div class="h-8 w-8 rounded-full bg-indigo-50 dark:bg-indigo-950/30 flex items-center justify-center text-indigo-600 dark:text-indigo-400 font-bold text-xs">
                                        {{ strtoupper(substr($teacher->name, 0, 1)) }}
                                    </div>
                                    <span>{{ $teacher->name }}</span>
                                </div>
                            </td>
                            <td class="py-4 px-6 text-slate-500 dark:text-slate-400 font-medium">
                                {{ $teacher->email }}
                            </td>
                            <td class="py-4 px-6">
                                <div class="flex flex-wrap gap-1.5 max-w-xs">
                                    @forelse($teacher->subjects ?? [] as $subCode)
                                        <span class="inline-flex items-center rounded-md bg-indigo-50 dark:bg-indigo-950/40 px-2 py-0.5 text-xxs font-bold text-indigo-600 dark:text-indigo-400 border border-indigo-100/50 dark:border-indigo-900/30">
                                            {{ $subCode }}
                                        </span>
                                    @empty
                                        <span class="text-xxs text-slate-400 font-medium italic">None Assigned</span>
                                    @endforelse
                                </div>
                            </td>
                            <td class="py-4 px-6 text-slate-400 dark:text-slate-500">
                                {{ $teacher->created_at ? $teacher->created_at->format('M d, Y') : 'N/A' }}
                            </td>
                            <td class="py-4 px-6 text-right space-x-2.5">
                                <a href="{{ route('admin.teachers.edit', $teacher->id) }}" class="inline-flex items-center rounded-lg bg-slate-100 hover:bg-slate-200 dark:bg-slate-900 dark:hover:bg-slate-800 text-slate-700 dark:text-slate-300 px-3 py-1.5 text-xs font-semibold transition-all">
                                    Edit
                                </a>
                                <form method="POST" action="{{ route('admin.teachers.destroy', $teacher->id) }}" class="inline-block" onsubmit="return confirm('Are you sure you want to delete this teacher? This action cannot be undone.');">
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
                            <td colspan="4" class="py-12 text-center text-slate-400 dark:text-slate-500 text-xs">
                                No teachers registered in the database.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
