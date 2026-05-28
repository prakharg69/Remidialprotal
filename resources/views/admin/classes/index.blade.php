@extends('layouts.dashboard')

@section('title', 'Manage Classes')
@section('header_title', 'Manage Classes')

@section('content')
<div class="space-y-6 animate-fade-in">
    <!-- Header Controls -->
    <div>
        <h2 class="text-lg font-bold text-slate-900 dark:text-white">Classes & Sections Registry</h2>
        <p class="text-xs text-slate-400 mt-0.5">Physically register new class sections or view current student counts across enrollment categories.</p>
    </div>

    <!-- Dual Column Layout -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- Left: Classes list (takes 2 cols on desktop) -->
        <div class="lg:col-span-2 space-y-4">
            <div class="bg-white dark:bg-slate-950 rounded-2xl border border-slate-100 dark:border-slate-800 shadow-sm overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b border-slate-100 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-900/20 text-xxs font-semibold uppercase tracking-wider text-slate-400 dark:text-slate-500">
                                <th class="py-4 px-6">Class Code</th>
                                <th class="py-4 px-6">Section / Description Name</th>
                                <th class="py-4 px-6 text-center">Student Count</th>
                                <th class="py-4 px-6 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-slate-800 text-sm">
                            @forelse($classes as $class)
                                <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-900/10 transition-colors">
                                    <td class="py-4 px-6 font-bold text-indigo-600 dark:text-indigo-400">
                                        <span class="inline-flex items-center rounded-lg bg-indigo-50 dark:bg-indigo-950/40 px-2.5 py-1 text-xs border border-indigo-100/50 dark:border-indigo-900/30">
                                            {{ $class->code }}
                                        </span>
                                    </td>
                                    <td class="py-4 px-6 font-semibold text-slate-700 dark:text-slate-350">
                                        {{ $class->name }}
                                    </td>
                                    <td class="py-4 px-6 text-center">
                                        <span class="inline-flex items-center rounded-full bg-slate-100 dark:bg-slate-900 px-3 py-1 text-xs font-bold text-slate-700 dark:text-slate-400">
                                            {{ $class->student_count ?? 0 }} Student(s)
                                        </span>
                                    </td>
                                    <td class="py-4 px-6 text-right">
                                        <form method="POST" action="{{ route('admin.classes.destroy', $class->id) }}" onsubmit="return confirm('Are you sure you want to delete the class section \'{{ $class->code }}\'?');" class="inline-block">
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
                                        No registered class sections found in database. Use the registry form to add one.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Right: Create class form (takes 1 col) -->
        <div>
            <div class="bg-white dark:bg-slate-950 rounded-2xl border border-slate-100 dark:border-slate-800 p-6 shadow-sm space-y-6 sticky top-20">
                <div>
                    <h3 class="text-sm font-bold text-slate-900 dark:text-white uppercase tracking-wider">Register Class Section</h3>
                    <p class="text-xxs text-slate-400 mt-1">Create a new class section so students can be registered and assigned quizzes.</p>
                </div>

                <form method="POST" action="{{ route('admin.classes.store') }}" class="space-y-4">
                    @csrf

                    <!-- Class Code -->
                    <div>
                        <label for="code" class="block text-xs font-semibold text-slate-700 dark:text-slate-350">
                            Class Code / Academic ID
                        </label>
                        <input id="code" 
                               type="text" 
                               name="code" 
                               required 
                               class="block mt-1.5 w-full rounded-xl border-slate-200 dark:border-slate-850 bg-white dark:bg-slate-900 text-slate-900 dark:text-slate-100 shadow-xxs focus:border-indigo-500 focus:ring-indigo-500 text-xs py-2.5 px-4" 
                               placeholder="e.g. BCA-3A" />
                        <span class="block text-xxs text-slate-400 dark:text-slate-500 mt-1">Unique short uppercase identifier.</span>
                    </div>

                    <!-- Description Name -->
                    <div>
                        <label for="name" class="block text-xs font-semibold text-slate-700 dark:text-slate-350">
                            Section / Description Name
                        </label>
                        <input id="name" 
                               type="text" 
                               name="name" 
                               required 
                               class="block mt-1.5 w-full rounded-xl border-slate-200 dark:border-slate-850 bg-white dark:bg-slate-900 text-slate-900 dark:text-slate-100 shadow-xxs focus:border-indigo-500 focus:ring-indigo-500 text-xs py-2.5 px-4" 
                               placeholder="e.g. BCA 3rd Year (Section A)" />
                        <span class="block text-xxs text-slate-400 dark:text-slate-500 mt-1">A description to identify the section enrollment.</span>
                    </div>

                    <!-- Action Button -->
                    <button type="submit" class="flex w-full items-center justify-center gap-2 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white font-semibold text-xs py-2.5 shadow-sm transition-all duration-150 mt-2">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                        Register Class
                    </button>
                </form>
            </div>
        </div>
        
    </div>
</div>
@endsection
