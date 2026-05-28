<!DOCTYPE html>
<html lang="en" class="h-full dark bg-slate-950">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Slow Learners Remedial Portal') }} - @yield('title', 'Dashboard')</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Tailwind & Alpine (via Vite) -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
    </style>
</head>
<body class="h-full text-slate-100 bg-slate-950 antialiased" x-data="{ mobileMenuOpen: false }">

    <div class="min-h-full">
        <!-- Sidebar for Desktop -->
        <div class="hidden lg:fixed lg:inset-y-0 lg:flex lg:w-72 lg:flex-col lg:border-r lg:border-slate-200 dark:lg:border-slate-800 lg:bg-white dark:lg:bg-slate-950 lg:pt-5 lg:pb-4 shadow-sm">
            <div class="flex flex-shrink-0 items-center px-6">
                <!-- Branding / App Logo -->
                <div class="flex items-center gap-2.5">
                    <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-gradient-to-tr from-indigo-500 to-indigo-600 shadow-md shadow-indigo-200 dark:shadow-none text-white">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4.263 15.541a1.99 1.99 0 0 0 .714 1.055l7.5 5.25a2 2 0 0 0 2.246 0l7.5-5.25a1.99 1.99 0 0 0 .714-1.055L22.5 12l-7.5-5.25a2 2 0 0 0-2.246 0L5.25 12l-0.987 3.541Z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 3.75v16.5m0-16.5L6.75 9m5.25-5.25L17.25 9" />
                        </svg>
                    </div>
                    <div>
                        <span class="text-base font-bold tracking-tight text-slate-900 dark:text-white">Remedial Portal</span>
                        <span class="block text-xxs font-semibold uppercase tracking-wider text-indigo-600 dark:text-indigo-400 -mt-0.5">Capacity Building</span>
                    </div>
                </div>
            </div>

            <!-- Profile Info in Sidebar -->
            <div class="mt-6 flex flex-col border-b border-slate-100 dark:border-slate-800 px-6 pb-5">
                <div class="flex items-center gap-3.5">
                    <div class="h-11 w-11 rounded-full bg-gradient-to-br from-indigo-400 to-indigo-600 flex items-center justify-center text-white font-bold shadow-inner">
                        {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                    </div>
                    <div class="truncate">
                        <h4 class="text-sm font-semibold text-slate-900 dark:text-white truncate">{{ auth()->user()->name }}</h4>
                        <span class="inline-flex items-center rounded-full bg-indigo-50 dark:bg-indigo-950/50 px-2 py-0.5 text-xs font-medium text-indigo-700 dark:text-indigo-300 capitalize border border-indigo-100/50 dark:border-indigo-900/30 mt-0.5">
                            {{ auth()->user()->role }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Navigation Links -->
            <div class="mt-6 flex flex-1 flex-col overflow-y-auto px-4">
                <nav class="space-y-1">
                    @php
                        $role = auth()->user()->role;
                    @endphp

                    @if($role === 'admin')
                        <!-- Admin Navigation -->
                        <x-sidebar-link href="{{ route('admin.dashboard') }}" active="{{ request()->routeIs('admin.dashboard') }}">
                            <svg class="mr-3 h-5 w-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                            Dashboard
                        </x-sidebar-link>

                        <x-sidebar-link href="{{ route('admin.students') }}" active="{{ request()->routeIs('admin.students*') }}">
                            <svg class="mr-3 h-5 w-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                            Students
                        </x-sidebar-link>

                        <x-sidebar-link href="{{ route('admin.teachers') }}" active="{{ request()->routeIs('admin.teachers*') }}">
                            <svg class="mr-3 h-5 w-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                            Teachers
                        </x-sidebar-link>

                        <x-sidebar-link href="{{ route('admin.classes') }}" active="{{ request()->routeIs('admin.classes*') }}">
                            <svg class="mr-3 h-5 w-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                            Classes
                        </x-sidebar-link>

                        <x-sidebar-link href="{{ route('admin.subjects') }}" active="{{ request()->routeIs('admin.subjects*') }}">
                            <svg class="mr-3 h-5 w-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                            Subjects
                        </x-sidebar-link>

                        <x-sidebar-link href="{{ route('admin.assessments') }}" active="{{ request()->routeIs('admin.assessments*') }}">
                            <svg class="mr-3 h-5 w-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            Assessments
                        </x-sidebar-link>

                        <x-sidebar-link href="{{ route('admin.attendance') }}" active="{{ request()->routeIs('admin.attendance*') }}">
                            <svg class="mr-3 h-5 w-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            Attendance
                        </x-sidebar-link>

                        <x-sidebar-link href="{{ route('admin.tasks') }}" active="{{ request()->routeIs('admin.tasks*') }}">
                            <svg class="mr-3 h-5 w-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                            Tasks
                        </x-sidebar-link>

                        <x-sidebar-link href="{{ route('admin.feedback') }}" active="{{ request()->routeIs('admin.feedback*') }}">
                            <svg class="mr-3 h-5 w-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"/></svg>
                            Feedback
                        </x-sidebar-link>

                    @elseif($role === 'teacher')
                        <!-- Teacher Navigation -->
                        <x-sidebar-link href="{{ route('teacher.dashboard') }}" active="{{ request()->routeIs('teacher.dashboard') }}">
                            <svg class="mr-3 h-5 w-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                            Dashboard
                        </x-sidebar-link>

                        <x-sidebar-link href="{{ route('teacher.assessments') }}" active="{{ request()->routeIs('teacher.assessments*') }}">
                            <svg class="mr-3 h-5 w-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            Assessments
                        </x-sidebar-link>

                        <x-sidebar-link href="{{ route('teacher.attendance') }}" active="{{ request()->routeIs('teacher.attendance*') }}">
                            <svg class="mr-3 h-5 w-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            Attendance
                        </x-sidebar-link>

                        <x-sidebar-link href="{{ route('teacher.tasks') }}" active="{{ request()->routeIs('teacher.tasks*') }}">
                            <svg class="mr-3 h-5 w-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                            Assign Remedials
                        </x-sidebar-link>

                        <x-sidebar-link href="{{ route('teacher.assignments') }}" active="{{ request()->routeIs('teacher.assignments*') }}">
                            <svg class="mr-3 h-5 w-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                            Create Assignment
                        </x-sidebar-link>

                        <x-sidebar-link href="{{ route('teacher.assignments.submissions') }}" active="{{ request()->routeIs('teacher.assignments.submissions*') }}">
                            <svg class="mr-3 h-5 w-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2"/></svg>
                            Assignment Submissions
                        </x-sidebar-link>

                        <x-sidebar-link href="{{ route('teacher.remedials.submissions') }}" active="{{ request()->routeIs('teacher.remedials.submissions*') }}">
                            <svg class="mr-3 h-5 w-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            Remedial Review Panel
                        </x-sidebar-link>

                        <x-sidebar-link href="{{ route('teacher.feedback') }}" active="{{ request()->routeIs('teacher.feedback*') }}">
                            <svg class="mr-3 h-5 w-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"/></svg>
                            Feedback
                        </x-sidebar-link>

                        <x-sidebar-link href="{{ route('teacher.quizzes.index') }}" active="{{ request()->routeIs('teacher.quizzes.index*') }}">
                            <svg class="mr-3 h-5 w-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            Manage Quizzes
                        </x-sidebar-link>

                        <x-sidebar-link href="{{ route('teacher.quizzes.attempts') }}" active="{{ request()->routeIs('teacher.quizzes.attempts*') }}">
                            <svg class="mr-3 h-5 w-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            Quiz Attempts
                        </x-sidebar-link>

                    @elseif($role === 'student')
                        <!-- Student Navigation -->
                        <x-sidebar-link href="{{ route('student.dashboard') }}" active="{{ request()->routeIs('student.dashboard') }}">
                            <svg class="mr-3 h-5 w-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                            Dashboard
                        </x-sidebar-link>

                        <x-sidebar-link href="{{ route('student.marks') }}" active="{{ request()->routeIs('student.marks*') }}">
                            <svg class="mr-3 h-5 w-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            My Marks
                        </x-sidebar-link>

                        <x-sidebar-link href="{{ route('student.attendance') }}" active="{{ request()->routeIs('student.attendance*') }}">
                            <svg class="mr-3 h-5 w-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            Attendance
                        </x-sidebar-link>

                        <x-sidebar-link href="{{ route('student.tasks') }}" active="{{ request()->routeIs('student.tasks*') }}">
                            <svg class="mr-3 h-5 w-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                            Tasks
                        </x-sidebar-link>

                        <x-sidebar-link href="{{ route('student.quizzes.index') }}" active="{{ request()->routeIs('student.quizzes.index*') || request()->routeIs('student.quizzes.attempt*') || request()->routeIs('student.quizzes.result*') }}">
                            <svg class="mr-3 h-5 w-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            My Quizzes
                        </x-sidebar-link>

                        <x-sidebar-link href="{{ route('student.feedback') }}" active="{{ request()->routeIs('student.feedback*') }}">
                            <svg class="mr-3 h-5 w-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"/></svg>
                            Feedback
                        </x-sidebar-link>
                    @endif
                </nav>
            </div>

            <!-- Logout Link in Sidebar Footer -->
            <div class="flex-shrink-0 border-t border-slate-100 dark:border-slate-800 p-4">
                <form method="POST" action="{{ route('logout') }}" class="w-full">
                    @csrf
                    <button type="submit" class="flex w-full items-center rounded-lg px-4 py-2.5 text-sm font-semibold text-red-600 hover:bg-red-50 dark:hover:bg-red-950/20 transition-all">
                        <svg class="mr-3 h-5 w-5 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                        </svg>
                        Logout
                    </button>
                </form>
            </div>
        </div>

        <!-- Main content area -->
        <div class="flex flex-col lg:pl-72">
            <!-- Top Navbar (header for mobile/desktop) -->
            <div class="sticky top-0 z-10 flex h-16 flex-shrink-0 bg-white dark:bg-slate-950 border-b border-slate-200 dark:border-slate-800 lg:border-none shadow-sm lg:shadow-none px-4 sm:px-6 lg:px-8">
                <!-- Mobile Menu Button -->
                <button type="button" class="lg:hidden -ml-2.5 mr-2.5 inline-flex items-center justify-center rounded-lg p-2 text-slate-500 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-900 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-indigo-500" @click="mobileMenuOpen = true">
                    <span class="sr-only">Open sidebar</span>
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                    </svg>
                </button>

                <!-- Page Header Title (Breadcrumbs) -->
                <div class="flex flex-1 items-center justify-between">
                    <div>
                        <h1 class="text-lg lg:text-xl font-bold text-slate-900 dark:text-white">
                            @yield('header_title', 'Slow Learners Remedial Portal')
                        </h1>
                    </div>

                    <!-- Right Side Top Menu (Theme display or quick actions) -->
                    <div class="ml-4 flex items-center gap-4">
                        <div class="text-xs text-slate-500 dark:text-slate-400 font-medium hidden sm:block">
                            {{ date('l, d F Y') }}
                        </div>
                    </div>
                </div>
            </div>

            <!-- Page Main Body Content -->
            <main class="flex-1 py-8 px-4 sm:px-6 lg:px-8">
                <!-- Session Alert Notifications -->
                @if(session('success'))
                    <div class="mb-6 rounded-xl border border-emerald-100 bg-emerald-50 dark:bg-emerald-950/20 dark:border-emerald-900/30 p-4 text-emerald-800 dark:text-emerald-400 flex items-center gap-3 animate-fade-in">
                        <svg class="h-5 w-5 flex-shrink-0 text-emerald-600 dark:text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span class="text-sm font-semibold">{{ session('success') }}</span>
                    </div>
                @endif

                @if($errors->any())
                    <div class="mb-6 rounded-xl border border-red-100 bg-red-50 dark:bg-red-950/20 dark:border-red-900/30 p-4 text-red-800 dark:text-red-400 animate-fade-in">
                        <div class="flex items-center gap-3 mb-2">
                            <svg class="h-5 w-5 flex-shrink-0 text-red-600 dark:text-red-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <span class="text-sm font-semibold">Please correct the errors below:</span>
                        </div>
                        <ul class="list-disc pl-5 text-xs space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <!-- Yielding Main Body -->
                @yield('content')
            </main>
        </div>

        <!-- Mobile Sidebar Overlay (Alpine.js controlled) -->
        <div class="relative z-40 lg:hidden" role="dialog" aria-modal="true" x-show="mobileMenuOpen" style="display: none;">
            <div class="fixed inset-0 bg-slate-900/80 backdrop-blur-xs transition-opacity duration-300" x-show="mobileMenuOpen" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" @click="mobileMenuOpen = false"></div>

            <div class="fixed inset-0 flex z-40">
                <div class="relative flex-1 flex flex-col max-w-xs w-full bg-white dark:bg-slate-950 pt-5 pb-4 transition duration-300 transform" x-show="mobileMenuOpen" x-transition:enter="transition ease-in-out duration-300" x-transition:enter-start="-translate-x-full" x-transition:enter-end="translate-x-0" x-transition:leave="transition ease-in-out duration-200" x-transition:leave-start="translate-x-0" x-transition:leave-end="-translate-x-full">
                    <!-- Close button -->
                    <div class="absolute top-0 right-0 -mr-12 pt-2">
                        <button type="button" class="ml-1 flex items-center justify-center h-10 w-10 rounded-full focus:outline-none focus:ring-2 focus:ring-inset focus:ring-white" @click="mobileMenuOpen = false">
                            <span class="sr-only">Close sidebar</span>
                            <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    <div class="flex-shrink-0 flex items-center px-6">
                        <div class="flex items-center gap-2.5">
                            <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-gradient-to-tr from-indigo-500 to-indigo-600 shadow-md text-white">
                                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.263 15.541a1.99 1.99 0 0 0 .714 1.055l7.5 5.25a2 2 0 0 0 2.246 0l7.5-5.25a1.99 1.99 0 0 0 .714-1.055L22.5 12l-7.5-5.25a2 2 0 0 0-2.246 0L5.25 12l-0.987 3.541Z" />
                                </svg>
                            </div>
                            <span class="text-base font-bold tracking-tight text-slate-900 dark:text-white">Remedial Portal</span>
                        </div>
                    </div>

                    <div class="mt-6 flex-1 flex flex-col overflow-y-auto px-4">
                        <nav class="space-y-1">
                            <!-- Duplicate links for mobile menu -->
                            @if($role === 'admin')
                                <a href="{{ route('admin.dashboard') }}" class="group flex items-center rounded-lg px-4 py-2.5 text-sm font-semibold text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-900">Dashboard</a>
                                <a href="{{ route('admin.students') }}" class="group flex items-center rounded-lg px-4 py-2.5 text-sm font-semibold text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-900">Students</a>
                                <a href="{{ route('admin.teachers') }}" class="group flex items-center rounded-lg px-4 py-2.5 text-sm font-semibold text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-900">Teachers</a>
                                <a href="{{ route('admin.classes') }}" class="group flex items-center rounded-lg px-4 py-2.5 text-sm font-semibold text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-900">Classes</a>
                                <a href="{{ route('admin.subjects') }}" class="group flex items-center rounded-lg px-4 py-2.5 text-sm font-semibold text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-900">Subjects</a>
                                <a href="{{ route('admin.assessments') }}" class="group flex items-center rounded-lg px-4 py-2.5 text-sm font-semibold text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-900">Assessments</a>
                                <a href="{{ route('admin.attendance') }}" class="group flex items-center rounded-lg px-4 py-2.5 text-sm font-semibold text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-900">Attendance</a>
                                <a href="{{ route('admin.tasks') }}" class="group flex items-center rounded-lg px-4 py-2.5 text-sm font-semibold text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-900">Tasks</a>
                                <a href="{{ route('admin.feedback') }}" class="group flex items-center rounded-lg px-4 py-2.5 text-sm font-semibold text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-900">Feedback</a>
                            @elseif($role === 'teacher')
                                <a href="{{ route('teacher.dashboard') }}" class="group flex items-center rounded-lg px-4 py-2.5 text-sm font-semibold text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-900">Dashboard</a>
                                <a href="{{ route('teacher.assessments') }}" class="group flex items-center rounded-lg px-4 py-2.5 text-sm font-semibold text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-900">Assessments</a>
                                <a href="{{ route('teacher.attendance') }}" class="group flex items-center rounded-lg px-4 py-2.5 text-sm font-semibold text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-900">Attendance</a>
                                <a href="{{ route('teacher.tasks') }}" class="group flex items-center rounded-lg px-4 py-2.5 text-sm font-semibold text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-900">Assign Remedials</a>
                                <a href="{{ route('teacher.assignments') }}" class="group flex items-center rounded-lg px-4 py-2.5 text-sm font-semibold text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-900">Create Assignment</a>
                                <a href="{{ route('teacher.assignments.submissions') }}" class="group flex items-center rounded-lg px-4 py-2.5 text-sm font-semibold text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-900">Assignment Submissions</a>
                                <a href="{{ route('teacher.remedials.submissions') }}" class="group flex items-center rounded-lg px-4 py-2.5 text-sm font-semibold text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-900">Remedial Review Panel</a>
                                <a href="{{ route('teacher.quizzes.index') }}" class="group flex items-center rounded-lg px-4 py-2.5 text-sm font-semibold text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-900">Manage Quizzes</a>
                                <a href="{{ route('teacher.quizzes.attempts') }}" class="group flex items-center rounded-lg px-4 py-2.5 text-sm font-semibold text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-900">Quiz Attempts</a>
                                <a href="{{ route('teacher.feedback') }}" class="group flex items-center rounded-lg px-4 py-2.5 text-sm font-semibold text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-900">Feedback</a>
                            @elseif($role === 'student')
                                <a href="{{ route('student.dashboard') }}" class="group flex items-center rounded-lg px-4 py-2.5 text-sm font-semibold text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-900">Dashboard</a>
                                <a href="{{ route('student.marks') }}" class="group flex items-center rounded-lg px-4 py-2.5 text-sm font-semibold text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-900">My Marks</a>
                                <a href="{{ route('student.attendance') }}" class="group flex items-center rounded-lg px-4 py-2.5 text-sm font-semibold text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-900">Attendance</a>
                                <a href="{{ route('student.tasks') }}" class="group flex items-center rounded-lg px-4 py-2.5 text-sm font-semibold text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-900">Tasks</a>
                                <a href="{{ route('student.quizzes.index') }}" class="group flex items-center rounded-lg px-4 py-2.5 text-sm font-semibold text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-900">My Quizzes</a>
                                <a href="{{ route('student.feedback') }}" class="group flex items-center rounded-lg px-4 py-2.5 text-sm font-semibold text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-900">Feedback</a>
                                <a href="{{ route('student.roadmap') }}" class="group flex items-center rounded-lg px-4 py-2.5 text-sm font-semibold text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-900">Roadmap</a>
                            @endif
                        </nav>
                    </div>

                    <div class="flex-shrink-0 border-t border-slate-100 dark:border-slate-800 p-4">
                        <form method="POST" action="{{ route('logout') }}" class="w-full">
                            @csrf
                            <button type="submit" class="flex w-full items-center rounded-lg px-4 py-2.5 text-sm font-semibold text-red-600 hover:bg-red-50 dark:hover:bg-red-950/20">
                                Logout
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

</body>
</html>
