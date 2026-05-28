@extends('layouts.dashboard')

@section('title', 'Manage Teachers')
@section('header_title', 'Manage Teachers')

@section('content')
<div class="space-y-6 animate-fade-in text-zinc-900 dark:text-zinc-100">
    <!-- Header Controls -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h2 class="text-lg font-bold text-zinc-900 dark:text-white">Teachers Directory</h2>
            <p class="text-xs text-zinc-500 dark:text-zinc-400 mt-0.5">A complete list of registered teachers in the system.</p>
        </div>
        <div>
            <a href="{{ route('admin.teachers.create') }}" class="inline-flex items-center gap-2 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-extrabold text-xs py-3 px-4 shadow transition-all duration-150 uppercase tracking-wider">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                Add Teacher
            </a>
        </div>
    </div>

    <!-- Data Card -->
    <div class="bg-white dark:bg-zinc-900 rounded-2xl border border-zinc-200 dark:border-zinc-800 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-zinc-200 dark:border-zinc-800 bg-zinc-50/50 dark:bg-zinc-950/20 text-xxs font-semibold uppercase tracking-wider text-zinc-500 dark:text-zinc-400">
                        <th class="py-4 px-6">Name</th>
                        <th class="py-4 px-6">Email Address</th>
                        <th class="py-4 px-6">Authorized Subjects</th>
                        <th class="py-4 px-6">Date Added</th>
                        <th class="py-4 px-6 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-200 dark:divide-zinc-800 text-sm">
                    @forelse($teachers as $teacher)
                        <tr class="hover:bg-zinc-50/50 dark:hover:bg-zinc-800/10 transition-colors">
                            <td class="py-4 px-6 font-bold text-zinc-900 dark:text-white">
                                <div class="flex items-center gap-3">
                                    <div class="h-8 w-8 rounded-full bg-indigo-50 dark:bg-indigo-500/10 flex items-center justify-center text-indigo-700 dark:text-indigo-400 font-bold text-xs border border-indigo-200 dark:border-indigo-500/20 shadow-xxs">
                                        {{ strtoupper(substr($teacher->name, 0, 1)) }}
                                    </div>
                                    <span>{{ $teacher->name }}</span>
                                </div>
                            </td>
                            <td class="py-4 px-6 text-zinc-600 dark:text-zinc-400 font-medium">
                                {{ $teacher->email }}
                            </td>
                            <td class="py-4 px-6">
                                <div class="flex flex-wrap gap-1.5 max-w-xs">
                                    @forelse($teacher->subjects ?? [] as $subCode)
                                        <span class="inline-flex items-center rounded-md bg-indigo-50 dark:bg-indigo-500/10 px-2 py-0.5 text-xxs font-extrabold text-indigo-700 dark:text-indigo-400 border border-indigo-200 dark:border-indigo-500/20 uppercase">
                                            {{ $subCode }}
                                        </span>
                                    @empty
                                        <span class="text-xxs text-zinc-500 dark:text-zinc-400 font-medium italic">None Assigned</span>
                                    @endforelse
                                </div>
                            </td>
                            <td class="py-4 px-6 text-zinc-400 dark:text-zinc-500 font-semibold">
                                {{ $teacher->created_at ? $teacher->created_at->format('M d, Y') : 'N/A' }}
                            </td>
                            <td class="py-4 px-6 text-right space-x-2.5">
                                <a href="{{ route('admin.teachers.edit', $teacher->id) }}" class="inline-flex items-center rounded-lg bg-zinc-100 hover:bg-zinc-200 dark:bg-zinc-800 dark:hover:bg-zinc-700 text-zinc-700 dark:text-zinc-300 border border-zinc-200 dark:border-zinc-700 px-3 py-1.5 text-xs font-bold transition-all uppercase tracking-wider">
                                    Edit
                                </a>
                                <form method="POST" action="{{ route('admin.teachers.destroy', $teacher->id) }}" class="inline-block" onsubmit="return confirm('Are you sure you want to delete this teacher? This action cannot be undone.');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="inline-flex items-center rounded-lg bg-rose-50 hover:bg-rose-100 dark:bg-rose-500/10 dark:hover:bg-rose-500/20 text-rose-700 dark:text-rose-400 border border-rose-200 dark:border-rose-500/20 px-3 py-1.5 text-xs font-bold transition-all uppercase tracking-wider">
                                        Delete
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-12 text-center text-zinc-500 dark:text-zinc-400 text-xs">
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
