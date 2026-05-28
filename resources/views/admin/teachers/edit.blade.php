@extends('layouts.dashboard')
Custom styles can be placed here if needed.
@section('title', 'Edit Teacher')
@section('header_title', 'Edit Teacher Profile')

@section('content')
<div class="max-w-2xl mx-auto space-y-6 animate-fade-in text-zinc-900 dark:text-zinc-100">
    <!-- Header -->
    <div class="flex items-center gap-4">
        <a href="{{ route('admin.teachers') }}" class="inline-flex h-9 w-9 items-center justify-center rounded-xl border border-zinc-200 dark:border-zinc-800 bg-white dark:bg-zinc-900 text-zinc-500 dark:text-zinc-400 hover:text-zinc-900 dark:hover:text-white transition-all shadow-sm">
            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        </a>
        <div>
            <h2 class="text-lg font-bold text-zinc-900 dark:text-white">Modify Teacher Account</h2>
            <p class="text-xs text-zinc-500 dark:text-zinc-400 mt-0.5">Edit general credentials for {{ $teacher->name }}.</p>
        </div>
    </div>

    <!-- Form Card -->
    <div class="bg-white dark:bg-zinc-900 rounded-2xl border border-zinc-200 dark:border-zinc-800 p-6 shadow-sm">
        <form method="POST" action="{{ route('admin.teachers.update', $teacher->id) }}" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- Name -->
            <div>
                <label for="name" class="block text-sm font-semibold text-zinc-700 dark:text-zinc-300">
                    Full Name
                </label>
                <input id="name" 
                       type="text" 
                       name="name" 
                       value="{{ old('name', $teacher->name) }}" 
                       required 
                       class="block mt-1.5 w-full rounded-xl border border-zinc-300 dark:border-zinc-800 bg-zinc-50 dark:bg-zinc-950 text-zinc-900 dark:text-zinc-100 shadow-xxs focus:border-indigo-500 focus:ring-indigo-500 text-sm py-2.5 px-4 placeholder-zinc-400" />
            </div>

            <!-- Email Address -->
            <div>
                <label for="email" class="block text-sm font-semibold text-zinc-700 dark:text-zinc-300">
                    Email Address
                </label>
                <input id="email" 
                       type="email" 
                       name="email" 
                       value="{{ old('email', $teacher->email) }}" 
                       required 
                       class="block mt-1.5 w-full rounded-xl border border-zinc-300 dark:border-zinc-800 bg-zinc-50 dark:bg-zinc-950 text-zinc-900 dark:text-zinc-100 shadow-xxs focus:border-indigo-500 focus:ring-indigo-500 text-sm py-2.5 px-4 placeholder-zinc-400" />
            </div>

            <!-- Subjects Authorization Checklist -->
            <div>
                <label class="block text-sm font-semibold text-zinc-700 dark:text-zinc-300">
                    Authorized Teaching Subjects
                </label>
                <div class="mt-2.5 grid grid-cols-1 sm:grid-cols-2 gap-3 max-h-48 overflow-y-auto p-4 rounded-xl border border-zinc-200 dark:border-zinc-800 bg-zinc-50 dark:bg-zinc-950/30">
                    @forelse($subjects as $subject)
                        <label class="relative flex items-start py-1 px-1 cursor-pointer select-none">
                            <div class="flex h-5 items-center">
                                <input type="checkbox" 
                                       name="subjects[]" 
                                       value="{{ $subject->code }}"
                                       id="subject_{{ $subject->code }}"
                                       class="h-4 w-4 rounded border-zinc-300 text-indigo-600 focus:ring-indigo-500 bg-zinc-100 dark:bg-zinc-950"
                                       {{ is_array(old('subjects', $teacher->subjects)) && in_array($subject->code, old('subjects', $teacher->subjects ?? [])) ? 'checked' : '' }} />
                            </div>
                            <div class="ml-2.5 text-xs">
                                <span class="font-extrabold text-zinc-800 dark:text-zinc-300">{{ $subject->code }}</span>
                                <span class="text-zinc-500 dark:text-zinc-500 font-medium"> - {{ $subject->name }}</span>
                            </div>
                        </label>
                    @empty
                        <div class="sm:col-span-2 text-center text-zinc-500 text-xxs py-2">
                            No subjects registered. Go to Manage Subjects to register one first.
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Buttons -->
            <div class="flex items-center justify-end gap-3.5 border-t border-zinc-200 dark:border-zinc-800 pt-5">
                <a href="{{ route('admin.teachers') }}" class="rounded-xl border border-zinc-200 dark:border-zinc-800 bg-zinc-100 dark:bg-zinc-800 text-zinc-700 dark:text-zinc-400 px-4 py-2.5 text-sm font-semibold hover:bg-zinc-200 dark:hover:bg-zinc-800 transition-all">
                    Cancel
                </a>
                <button type="submit" class="rounded-xl bg-indigo-600 hover:bg-indigo-700 px-5 py-2.5 text-sm font-black text-white shadow-lg transition-all duration-150 uppercase tracking-wider">
                    Save Changes
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
