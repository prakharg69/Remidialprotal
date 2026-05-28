@extends('layouts.dashboard')

@section('title', 'Manage Subjects')
@section('header_title', 'Manage Subjects')

@section('content')
<div class="space-y-6 animate-fade-in">
    <!-- Header Controls -->
    <div>
        <h2 class="text-lg font-bold text-slate-900 dark:text-white">Academic Subjects Registry</h2>
        <p class="text-xs text-slate-400 mt-0.5">Manage subjects and define global assessment maximum boundaries (CA1, CA2, End Term).</p>
    </div>

    <!-- Dual Column Layout -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- Left: Subjects list (takes 2 cols on desktop) -->
        <div class="lg:col-span-2 space-y-4">
            <div class="bg-white dark:bg-slate-950 rounded-2xl border border-slate-100 dark:border-slate-800 shadow-sm overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b border-slate-100 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-900/20 text-xxs font-semibold uppercase tracking-wider text-slate-400 dark:text-slate-500">
                                <th class="py-4 px-6">Subject Code</th>
                                <th class="py-4 px-6">Subject Name</th>
                                <th class="py-4 px-6 text-center">CA1 Max</th>
                                <th class="py-4 px-6 text-center">CA2 Max</th>
                                <th class="py-4 px-6 text-center">End Term Max</th>
                                <th class="py-4 px-6 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-slate-800 text-sm">
                            @forelse($subjects as $subject)
                                <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-900/10 transition-colors">
                                    <td class="py-4 px-6 font-bold text-indigo-600 dark:text-indigo-400">
                                        <span class="inline-flex items-center rounded-lg bg-indigo-50 dark:bg-indigo-950/40 px-2.5 py-1 text-xs border border-indigo-100/50 dark:border-indigo-900/30">
                                            {{ $subject->code }}
                                        </span>
                                    </td>
                                    <td class="py-4 px-6 font-semibold text-slate-700 dark:text-slate-350">
                                        {{ $subject->name }}
                                    </td>
                                    <td class="py-4 px-6 text-center font-medium text-slate-600 dark:text-slate-400">
                                        {{ $subject->ca1_max ?? 30 }}
                                    </td>
                                    <td class="py-4 px-6 text-center font-medium text-slate-600 dark:text-slate-400">
                                        {{ $subject->ca2_max ?? 30 }}
                                    </td>
                                    <td class="py-4 px-6 text-center font-medium text-slate-600 dark:text-slate-400">
                                        {{ $subject->end_term_max ?? 40 }}
                                    </td>
                                    <td class="py-4 px-6 text-right">
                                        <form method="POST" action="{{ route('admin.subjects.destroy', $subject->id) }}" onsubmit="return confirm('Are you sure you want to delete the subject \'{{ $subject->code }} - {{ $subject->name }}\'?');" class="inline-block">
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
                                        No registered academic subjects found in database. Use the registry form to add one.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Right: Create Subject form (takes 1 col) -->
        <div>
            <div class="bg-white dark:bg-slate-950 rounded-2xl border border-slate-100 dark:border-slate-800 p-6 shadow-sm space-y-6 sticky top-20">
                <div>
                    <h3 class="text-sm font-bold text-slate-900 dark:text-white uppercase tracking-wider">Register Academic Subject</h3>
                    <p class="text-xxs text-slate-400 mt-1">Add a new curriculum course code with customized examination limits.</p>
                </div>

                <form method="POST" action="{{ route('admin.subjects.store') }}" class="space-y-4">
                    @csrf

                    <!-- Subject Code -->
                    <div>
                        <label for="code" class="block text-xs font-semibold text-slate-700 dark:text-slate-350">
                            Course Code
                        </label>
                        <input id="code" 
                               type="text" 
                               name="code" 
                               required 
                               class="block mt-1.5 w-full rounded-xl border-slate-200 dark:border-slate-850 bg-white dark:bg-slate-900 text-slate-900 dark:text-slate-100 shadow-xxs focus:border-indigo-500 focus:ring-indigo-500 text-xs py-2.5 px-4" 
                               placeholder="e.g. CSE204" />
                        <span class="block text-xxs text-slate-400 dark:text-slate-500 mt-1">Unique course identifier prefix (e.g. MTH101, CSE304).</span>
                    </div>

                    <!-- Subject Name -->
                    <div>
                        <label for="name" class="block text-xs font-semibold text-slate-700 dark:text-slate-350">
                            Course / Subject Title
                        </label>
                        <input id="name" 
                               type="text" 
                               name="name" 
                               required 
                               class="block mt-1.5 w-full rounded-xl border-slate-200 dark:border-slate-850 bg-white dark:bg-slate-900 text-slate-900 dark:text-slate-100 shadow-xxs focus:border-indigo-500 focus:ring-indigo-500 text-xs py-2.5 px-4" 
                               placeholder="e.g. Database Management Systems" />
                    </div>

                    <!-- Mark Boundaries Grid -->
                    <div class="grid grid-cols-3 gap-2 pt-2 border-t border-slate-100 dark:border-slate-800/60">
                        <!-- CA1 -->
                        <div>
                            <label for="ca1_max" class="block text-xxs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider">
                                CA1 Max
                            </label>
                            <input id="ca1_max" 
                                   type="number" 
                                   name="ca1_max" 
                                   value="30"
                                   required 
                                   class="block mt-1.5 w-full rounded-xl border-slate-200 dark:border-slate-850 bg-white dark:bg-slate-900 text-slate-900 dark:text-slate-100 shadow-xxs focus:border-indigo-500 focus:ring-indigo-500 text-xs py-2.5 px-2 text-center" />
                        </div>

                        <!-- CA2 -->
                        <div>
                            <label for="ca2_max" class="block text-xxs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider">
                                CA2 Max
                            </label>
                            <input id="ca2_max" 
                                   type="number" 
                                   name="ca2_max" 
                                   value="30"
                                   required 
                                   class="block mt-1.5 w-full rounded-xl border-slate-200 dark:border-slate-850 bg-white dark:bg-slate-900 text-slate-900 dark:text-slate-100 shadow-xxs focus:border-indigo-500 focus:ring-indigo-500 text-xs py-2.5 px-2 text-center" />
                        </div>

                        <!-- End Term -->
                        <div>
                            <label for="end_term_max" class="block text-xxs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider">
                                End Max
                            </label>
                            <input id="end_term_max" 
                                   type="number" 
                                   name="end_term_max" 
                                   value="40"
                                   required 
                                   class="block mt-1.5 w-full rounded-xl border-slate-200 dark:border-slate-850 bg-white dark:bg-slate-900 text-slate-900 dark:text-slate-100 shadow-xxs focus:border-indigo-500 focus:ring-indigo-500 text-xs py-2.5 px-2 text-center" />
                        </div>
                    </div>

                    <!-- Action Button -->
                    <button type="submit" class="flex w-full items-center justify-center gap-2 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white font-semibold text-xs py-2.5 shadow-sm transition-all duration-150 mt-2">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                        Register Subject
                    </button>
                </form>
            </div>
        </div>
        
    </div>
</div>
@endsection
