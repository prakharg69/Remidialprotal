@extends('layouts.dashboard')

@section('title', 'Add Student')
@section('header_title', 'Add New Student')

@section('content')
<div class="max-w-2xl mx-auto space-y-6 animate-fade-in text-zinc-900 dark:text-zinc-100">
    <!-- Header -->
    <div class="flex items-center gap-4">
        <a href="{{ route('admin.students') }}" class="inline-flex h-9 w-9 items-center justify-center rounded-xl border border-zinc-200 dark:border-zinc-800 bg-white dark:bg-zinc-900 text-zinc-500 dark:text-zinc-400 hover:text-zinc-900 dark:hover:text-white transition-all shadow-sm">
            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        </a>
        <div>
            <h2 class="text-lg font-bold text-zinc-900 dark:text-white">Student Registration</h2>
            <p class="text-xs text-zinc-500 dark:text-zinc-400 mt-0.5">Register a new student and set their core academic profile attributes.</p>
        </div>
    </div>

    <!-- Form Card -->
    <div class="bg-white dark:bg-zinc-900 rounded-2xl border border-zinc-200 dark:border-zinc-800 p-6 shadow-sm">
        <form method="POST" action="{{ route('admin.students.store') }}" class="space-y-6">
            @csrf

            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                <!-- Name -->
                <div class="sm:col-span-2">
                    <label for="name" class="block text-sm font-semibold text-zinc-700 dark:text-zinc-300">
                        Full Name
                    </label>
                    <input id="name" 
                           type="text" 
                           name="name" 
                           value="{{ old('name') }}" 
                           required 
                           autofocus
                           class="block mt-1.5 w-full rounded-xl border border-zinc-300 dark:border-zinc-800 bg-zinc-50 dark:bg-zinc-950 text-zinc-900 dark:text-zinc-100 shadow-xxs focus:border-indigo-500 focus:ring-indigo-500 text-sm py-2.5 px-4 placeholder-zinc-400 dark:placeholder-zinc-650"
                           placeholder="e.g. Prakhar Gussain" />
                </div>

                <!-- Email Address -->
                <div class="sm:col-span-2">
                    <label for="email" class="block text-sm font-semibold text-zinc-700 dark:text-zinc-300">
                        Email Address
                    </label>
                    <input id="email" 
                           type="email" 
                           name="email" 
                           value="{{ old('email') }}" 
                           required 
                           class="block mt-1.5 w-full rounded-xl border border-zinc-300 dark:border-zinc-800 bg-zinc-50 dark:bg-zinc-950 text-zinc-900 dark:text-zinc-100 shadow-xxs focus:border-indigo-500 focus:ring-indigo-500 text-sm py-2.5 px-4 placeholder-zinc-400 dark:placeholder-zinc-650"
                           placeholder="e.g. prakhar@gmail.com" />
                </div>

                <!-- Class -->
                <div>
                    <label for="class_code" class="block text-sm font-semibold text-zinc-700 dark:text-zinc-300">
                        Class / Course
                    </label>
                    <select id="class_code" 
                            name="class_code" 
                            required 
                            class="block mt-1.5 w-full rounded-xl border border-zinc-300 dark:border-zinc-800 bg-zinc-50 dark:bg-zinc-950 text-zinc-900 dark:text-zinc-100 shadow-xxs focus:border-indigo-500 focus:ring-indigo-500 text-sm py-2.5 px-4">
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
                    <label for="roll_number" class="block text-sm font-semibold text-zinc-700 dark:text-zinc-300">
                        Roll Number
                    </label>
                    <input id="roll_number" 
                           type="text" 
                           name="roll_number" 
                           value="{{ old('roll_number') }}" 
                           required 
                           class="block mt-1.5 w-full rounded-xl border border-zinc-300 dark:border-zinc-800 bg-zinc-50 dark:bg-zinc-950 text-zinc-900 dark:text-zinc-100 shadow-xxs focus:border-indigo-500 focus:ring-indigo-500 text-sm py-2.5 px-4 placeholder-zinc-450"
                           placeholder="e.g. 101" />
                </div>

                <!-- Phone -->
                <div>
                    <label for="phone" class="block text-sm font-semibold text-zinc-700 dark:text-zinc-300">
                        Phone Number
                    </label>
                    <input id="phone" 
                           type="text" 
                           name="phone" 
                           value="{{ old('phone') }}" 
                           class="block mt-1.5 w-full rounded-xl border border-zinc-300 dark:border-zinc-800 bg-zinc-50 dark:bg-zinc-950 text-zinc-900 dark:text-zinc-100 shadow-xxs focus:border-indigo-500 focus:ring-indigo-500 text-sm py-2.5 px-4 placeholder-zinc-450"
                           placeholder="e.g. 9876543210" />
                </div>

                <!-- Address -->
                <div class="sm:col-span-2">
                    <label for="address" class="block text-sm font-semibold text-zinc-700 dark:text-zinc-300">
                        Home Address
                    </label>
                    <textarea id="address" 
                              name="address" 
                              rows="3"
                              class="block mt-1.5 w-full rounded-xl border border-zinc-300 dark:border-zinc-800 bg-zinc-50 dark:bg-zinc-950 text-zinc-900 dark:text-zinc-100 shadow-xxs focus:border-indigo-500 focus:ring-indigo-500 text-sm py-2.5 px-4 placeholder-zinc-450"
                              placeholder="e.g. Dehradun, Uttarakhand, India">{{ old('address') }}</textarea>
                </div>
            </div>

            <!-- Auto Password Alert -->
            <div class="rounded-xl border border-indigo-200 bg-indigo-50 dark:bg-indigo-500/10 dark:border-indigo-500/20 p-4 flex gap-3 text-indigo-700 dark:text-indigo-400">
                <svg class="h-5 w-5 flex-shrink-0 text-indigo-600 dark:text-indigo-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <div class="text-xs font-semibold">
                    <span class="font-bold">Password Logic:</span> When you save this student, their password will automatically become their email prefix (e.g. <span class="font-semibold underline">prakhar</span> for <span class="font-semibold underline">prakhar@gmail.com</span>), hashed before storing.
                </div>
            </div>

            <!-- Buttons -->
            <div class="flex items-center justify-end gap-3.5 border-t border-zinc-200 dark:border-zinc-800 pt-5">
                <a href="{{ route('admin.students') }}" class="rounded-xl border border-zinc-200 dark:border-zinc-800 bg-zinc-100 dark:bg-zinc-800 text-zinc-700 dark:text-zinc-400 px-4 py-2.5 text-sm font-semibold hover:bg-zinc-200 dark:hover:bg-zinc-700 transition-all">
                    Cancel
                </a>
                <button type="submit" class="rounded-xl bg-indigo-600 hover:bg-indigo-700 px-5 py-2.5 text-sm font-black text-white shadow-lg transition-all duration-150 uppercase tracking-wider">
                    Create Student
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
