@extends('layouts.dashboard')

@section('title', 'Add Student')
@section('header_title', 'Add New Student')

@section('content')
<div class="max-w-2xl mx-auto space-y-6 animate-fade-in">
    <!-- Header -->
    <div class="flex items-center gap-4">
        <a href="{{ route('admin.students') }}" class="inline-flex h-9 w-9 items-center justify-center rounded-xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-950 text-slate-500 hover:text-slate-900 dark:hover:text-white transition-all shadow-xxs">
            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        </a>
        <div>
            <h2 class="text-lg font-bold text-slate-900 dark:text-white">Student Registration</h2>
            <p class="text-xs text-slate-400 mt-0.5">Register a new student and set their core academic profile attributes.</p>
        </div>
    </div>

    <!-- Form Card -->
    <div class="bg-white dark:bg-slate-950 rounded-2xl border border-slate-100 dark:border-slate-800 p-6 shadow-sm">
        <form method="POST" action="{{ route('admin.students.store') }}" class="space-y-6">
            @csrf

            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                <!-- Name -->
                <div class="sm:col-span-2">
                    <label for="name" class="block text-sm font-semibold text-slate-700 dark:text-slate-300">
                        Full Name
                    </label>
                    <input id="name" 
                           type="text" 
                           name="name" 
                           value="{{ old('name') }}" 
                           required 
                           autofocus
                           class="block mt-1.5 w-full rounded-xl border-slate-200 dark:border-slate-850 bg-white dark:bg-slate-900 text-slate-900 dark:text-slate-100 shadow-xxs focus:border-indigo-500 focus:ring-indigo-500 text-sm py-2.5 px-4"
                           placeholder="e.g. Prakhar Gussain" />
                </div>

                <!-- Email Address -->
                <div class="sm:col-span-2">
                    <label for="email" class="block text-sm font-semibold text-slate-700 dark:text-slate-300">
                        Email Address
                    </label>
                    <input id="email" 
                           type="email" 
                           name="email" 
                           value="{{ old('email') }}" 
                           required 
                           class="block mt-1.5 w-full rounded-xl border-slate-200 dark:border-slate-850 bg-white dark:bg-slate-900 text-slate-900 dark:text-slate-100 shadow-xxs focus:border-indigo-500 focus:ring-indigo-500 text-sm py-2.5 px-4"
                           placeholder="e.g. prakhar@gmail.com" />
                </div>

                <!-- Class -->
                <div>
                    <label for="class_code" class="block text-sm font-semibold text-slate-700 dark:text-slate-300">
                        Class / Course
                    </label>
                    <select id="class_code" 
                            name="class_code" 
                            required 
                            class="block mt-1.5 w-full rounded-xl border-slate-200 dark:border-slate-850 bg-white dark:bg-slate-900 text-slate-900 dark:text-slate-100 shadow-xxs focus:border-indigo-500 focus:ring-indigo-500 text-sm py-2.5 px-4">
                        <option value="" disabled selected>Select a Class Section...</option>
                        @foreach($classes as $class)
                            <option value="{{ $class->code }}" {{ old('class_code') == $class->code ? 'selected' : '' }}>
                                {{ $class->code }} - {{ $class->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Roll Number -->
                <div>
                    <label for="roll_number" class="block text-sm font-semibold text-slate-700 dark:text-slate-300">
                        Roll Number
                    </label>
                    <input id="roll_number" 
                           type="text" 
                           name="roll_number" 
                           value="{{ old('roll_number') }}" 
                           required 
                           class="block mt-1.5 w-full rounded-xl border-slate-200 dark:border-slate-850 bg-white dark:bg-slate-900 text-slate-900 dark:text-slate-100 shadow-xxs focus:border-indigo-500 focus:ring-indigo-500 text-sm py-2.5 px-4"
                           placeholder="e.g. 101" />
                </div>

                <!-- Phone -->
                <div>
                    <label for="phone" class="block text-sm font-semibold text-slate-700 dark:text-slate-300">
                        Phone Number
                    </label>
                    <input id="phone" 
                           type="text" 
                           name="phone" 
                           value="{{ old('phone') }}" 
                           class="block mt-1.5 w-full rounded-xl border-slate-200 dark:border-slate-850 bg-white dark:bg-slate-900 text-slate-900 dark:text-slate-100 shadow-xxs focus:border-indigo-500 focus:ring-indigo-500 text-sm py-2.5 px-4"
                           placeholder="e.g. 9876543210" />
                </div>

                <!-- Address -->
                <div class="sm:col-span-2">
                    <label for="address" class="block text-sm font-semibold text-slate-700 dark:text-slate-300">
                        Home Address
                    </label>
                    <textarea id="address" 
                              name="address" 
                              rows="3"
                              class="block mt-1.5 w-full rounded-xl border-slate-200 dark:border-slate-850 bg-white dark:bg-slate-900 text-slate-900 dark:text-slate-100 shadow-xxs focus:border-indigo-500 focus:ring-indigo-500 text-sm py-2.5 px-4"
                              placeholder="e.g. Dehradun, Uttarakhand, India">{{ old('address') }}</textarea>
                </div>
            </div>

            <!-- Auto Password Alert -->
            <div class="rounded-xl border border-indigo-50 bg-indigo-50/50 dark:bg-indigo-950/20 dark:border-indigo-900/30 p-4 flex gap-3 text-indigo-800 dark:text-indigo-400">
                <svg class="h-5 w-5 flex-shrink-0 text-indigo-600 dark:text-indigo-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <div class="text-xs">
                    <span class="font-bold">Password Logic:</span> When you save this student, their password will automatically become their email prefix (e.g. <span class="font-semibold underline">prakhar</span> for <span class="font-semibold underline">prakhar@gmail.com</span>), hashed before storing.
                </div>
            </div>

            <!-- Buttons -->
            <div class="flex items-center justify-end gap-3.5 border-t border-slate-100 dark:border-slate-800 pt-5">
                <a href="{{ route('admin.students') }}" class="rounded-xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-950 text-slate-700 dark:text-slate-350 px-4 py-2.5 text-sm font-semibold hover:bg-slate-50 dark:hover:bg-slate-900 transition-all">
                    Cancel
                </a>
                <button type="submit" class="rounded-xl bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600 transition-all">
                    Create Student
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
