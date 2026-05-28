@extends('layouts.dashboard')

@section('title', 'Edit Student')
@section('header_title', 'Edit Student Profile')

@section('content')
<div class="max-w-2xl mx-auto space-y-6 animate-fade-in text-zinc-900 dark:text-zinc-100">
    <!-- Header -->
    <div class="flex items-center gap-4">
        <a href="{{ route('admin.students') }}" class="inline-flex h-9 w-9 items-center justify-center rounded-xl border border-zinc-200 dark:border-zinc-800 bg-white dark:bg-zinc-900 text-zinc-500 dark:text-zinc-400 hover:text-zinc-900 dark:hover:text-white transition-all shadow-sm">
            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        </a>
        <div>
            <h2 class="text-lg font-bold text-zinc-900 dark:text-white">Modify Student Profile</h2>
            <p class="text-xs text-zinc-500 dark:text-zinc-400 mt-0.5">Edit academic details and contact info for {{ $studentUser->name }}.</p>
        </div>
    </div>

    <!-- Form Card -->
    <div class="bg-white dark:bg-zinc-900 rounded-2xl border border-zinc-200 dark:border-zinc-800 p-6 shadow-sm">
        <form method="POST" action="{{ route('admin.students.update', $studentUser->id) }}" class="space-y-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                <!-- Name -->
                <div class="sm:col-span-2">
                    <label for="name" class="block text-sm font-semibold text-zinc-700 dark:text-zinc-300">
                        Full Name
                    </label>
                    <input id="name" 
                           type="text" 
                           name="name" 
                           value="{{ old('name', $studentUser->name) }}" 
                           required 
                           class="block mt-1.5 w-full rounded-xl border border-zinc-300 dark:border-zinc-800 bg-zinc-50 dark:bg-zinc-950 text-zinc-900 dark:text-zinc-100 shadow-xxs focus:border-indigo-500 focus:ring-indigo-500 text-sm py-2.5 px-4 placeholder-zinc-400 dark:placeholder-zinc-600" />
                </div>

                <!-- Email Address -->
                <div class="sm:col-span-2">
                    <label for="email" class="block text-sm font-semibold text-zinc-700 dark:text-zinc-300">
                        Email Address
                    </label>
                    <input id="email" 
                           type="email" 
                           name="email" 
                           value="{{ old('email', $studentUser->email) }}" 
                           required 
                           class="block mt-1.5 w-full rounded-xl border border-zinc-300 dark:border-zinc-800 bg-zinc-50 dark:bg-zinc-950 text-zinc-900 dark:text-zinc-100 shadow-xxs focus:border-indigo-500 focus:ring-indigo-500 text-sm py-2.5 px-4 placeholder-zinc-400 dark:placeholder-zinc-600" />
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
                        <option value="" disabled>Select a Class Section...</option>
                        @foreach($classes as $class)
                            <option value="{{ $class->code }}" {{ old('class_code', $profile->class_code ?? '') == $class->code ? 'selected' : '' }}>
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
                           value="{{ old('roll_number', $profile->roll_number ?? '') }}" 
                           required 
                           class="block mt-1.5 w-full rounded-xl border border-zinc-300 dark:border-zinc-800 bg-zinc-50 dark:bg-zinc-950 text-zinc-900 dark:text-zinc-100 shadow-xxs focus:border-indigo-500 focus:ring-indigo-500 text-sm py-2.5 px-4 placeholder-zinc-400" />
                </div>

                <!-- Phone -->
                <div>
                    <label for="phone" class="block text-sm font-semibold text-zinc-700 dark:text-zinc-300">
                        Phone Number
                    </label>
                    <input id="phone" 
                           type="text" 
                           name="phone" 
                           value="{{ old('phone', $profile->phone ?? '') }}" 
                           class="block mt-1.5 w-full rounded-xl border border-zinc-300 dark:border-zinc-800 bg-zinc-50 dark:bg-zinc-950 text-zinc-900 dark:text-zinc-100 shadow-xxs focus:border-indigo-500 focus:ring-indigo-500 text-sm py-2.5 px-4 placeholder-zinc-400" />
                </div>

                <!-- Address -->
                <div class="sm:col-span-2">
                    <label for="address" class="block text-sm font-semibold text-zinc-700 dark:text-zinc-300">
                        Home Address
                    </label>
                    <textarea id="address" 
                              name="address" 
                              rows="3"
                              class="block mt-1.5 w-full rounded-xl border border-zinc-300 dark:border-zinc-800 bg-zinc-50 dark:bg-zinc-950 text-zinc-900 dark:text-zinc-100 shadow-xxs focus:border-indigo-500 focus:ring-indigo-500 text-sm py-2.5 px-4 placeholder-zinc-400">{{ old('address', $profile->address ?? '') }}</textarea>
                </div>
            </div>

            <!-- Buttons -->
            <div class="flex items-center justify-end gap-3.5 border-t border-zinc-200 dark:border-zinc-800 pt-5">
                <a href="{{ route('admin.students') }}" class="rounded-xl border border-zinc-200 dark:border-zinc-800 bg-zinc-100 dark:bg-zinc-800 text-zinc-700 dark:text-zinc-400 px-4 py-2.5 text-sm font-semibold hover:bg-zinc-200 dark:hover:bg-zinc-700 transition-all">
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
