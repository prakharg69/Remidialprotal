<!DOCTYPE html>
<html lang="en" class="h-full transition-colors duration-200">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Slow Learners Remedial Portal') }} - @yield('title', 'Dashboard')</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Tailwind & Alpine (via Vite) -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
    <script>
        // On page load or when changing themes, best to add inline in `head` to avoid FOUC
        if (localStorage.getItem('theme') === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>
</head>
<body class="h-full text-zinc-900 dark:text-zinc-100 bg-zinc-50 dark:bg-zinc-950 antialiased transition-colors duration-200" x-data="{ mobileMenuOpen: false }">

    <div class="min-h-full">
        <!-- Sidebar for Desktop -->
        <div class="hidden lg:fixed lg:inset-y-0 lg:flex lg:w-72 lg:flex-col lg:border-r lg:border-zinc-200 dark:lg:border-zinc-800 bg-zinc-100 dark:bg-zinc-950 lg:pt-5 lg:pb-4 shadow-sm z-20">
            <div class="flex flex-shrink-0 items-center px-6">
                <!-- Branding / App Logo -->
                <div class="flex items-center gap-2.5">
                    <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-gradient-to-tr from-zinc-700 to-zinc-900 dark:from-zinc-800 dark:to-zinc-950 shadow-sm text-white border border-zinc-600 dark:border-zinc-800">
                        <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4.263 15.541a1.99 1.99 0 0 0 .714 1.055l7.5 5.25a2 2 0 0 0 2.246 0l7.5-5.25a1.99 1.99 0 0 0 .714-1.055L22.5 12l-7.5-5.25a2 2 0 0 0-2.246 0L5.25 12l-0.987 3.541Z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 3.75v16.5m0-16.5L6.75 9m5.25-5.25L17.25 9" />
                        </svg>
                    </div>
                    <div>
                        <span class="text-base font-bold tracking-tight text-zinc-900 dark:text-white">Remedial Portal</span>
                        <span class="block text-xxs font-semibold uppercase tracking-wider text-zinc-500 dark:text-zinc-400 -mt-0.5">Capacity Building</span>
                    </div>
                </div>
            </div>

            <!-- Profile Info in Sidebar -->
            <div class="mt-6 flex flex-col border-b border-zinc-200 dark:border-zinc-900 px-6 pb-5">
                <div class="flex items-center gap-3.5">
                    <div class="h-11 w-11 rounded-full bg-gradient-to-br from-zinc-200 to-zinc-300 dark:from-zinc-700 dark:to-zinc-900 flex items-center justify-center text-zinc-800 dark:text-white font-bold shadow-inner border border-zinc-300 dark:border-zinc-800/40">
                        {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                    </div>
                    <div class="truncate">
                        <h4 class="text-sm font-semibold text-zinc-900 dark:text-white truncate">{{ auth()->user()->name }}</h4>
                        <span class="inline-flex items-center rounded-full bg-zinc-200/60 dark:bg-white/10 px-2 py-0.5 text-xs font-semibold text-zinc-700 dark:text-zinc-200 capitalize border border-zinc-300/45 dark:border-white/10 mt-0.5">
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
                            Dashboard
                        </x-sidebar-link>

                        <x-sidebar-link href="{{ route('admin.students') }}" active="{{ request()->routeIs('admin.students*') }}">
                            Students
                        </x-sidebar-link>

                        <x-sidebar-link href="{{ route('admin.teachers') }}" active="{{ request()->routeIs('admin.teachers*') }}">
                            Teachers
                        </x-sidebar-link>

                        <x-sidebar-link href="{{ route('admin.classes') }}" active="{{ request()->routeIs('admin.classes*') }}">
                            Classes
                        </x-sidebar-link>

                        <x-sidebar-link href="{{ route('admin.subjects') }}" active="{{ request()->routeIs('admin.subjects*') }}">
                            Subjects
                        </x-sidebar-link>

                        <x-sidebar-link href="{{ route('admin.assessments') }}" active="{{ request()->routeIs('admin.assessments*') }}">
                            Assessments
                        </x-sidebar-link>

                        <x-sidebar-link href="{{ route('admin.attendance') }}" active="{{ request()->routeIs('admin.attendance*') }}">
                            Attendance
                        </x-sidebar-link>

                        <x-sidebar-link href="{{ route('admin.tasks') }}" active="{{ request()->routeIs('admin.tasks*') }}">
                            Tasks
                        </x-sidebar-link>

                        <x-sidebar-link href="{{ route('admin.feedback') }}" active="{{ request()->routeIs('admin.feedback*') }}">
                            Feedback
                        </x-sidebar-link>

                    @elseif($role === 'teacher')
                        <!-- Teacher Navigation -->
                        <x-sidebar-link href="{{ route('teacher.dashboard') }}" active="{{ request()->routeIs('teacher.dashboard') }}">
                            Dashboard
                        </x-sidebar-link>

                        <x-sidebar-link href="{{ route('teacher.assessments') }}" active="{{ request()->routeIs('teacher.assessments*') }}">
                            Assessments
                        </x-sidebar-link>

                        <x-sidebar-link href="{{ route('teacher.attendance') }}" active="{{ request()->routeIs('teacher.attendance*') }}">
                            Attendance
                        </x-sidebar-link>

                        <x-sidebar-link href="{{ route('teacher.tasks') }}" active="{{ request()->routeIs('teacher.tasks*') }}">
                            Assign Remedials
                        </x-sidebar-link>

                        <x-sidebar-link href="{{ route('teacher.assignments') }}" active="{{ request()->routeIs('teacher.assignments*') }}">
                            Create Assignment
                        </x-sidebar-link>

                        <x-sidebar-link href="{{ route('teacher.assignments.submissions') }}" active="{{ request()->routeIs('teacher.assignments.submissions*') }}">
                            Assignment Submissions
                        </x-sidebar-link>

                        <x-sidebar-link href="{{ route('teacher.remedials.submissions') }}" active="{{ request()->routeIs('teacher.remedials.submissions*') }}">
                            Remedial Review Panel
                        </x-sidebar-link>

                        <x-sidebar-link href="{{ route('teacher.feedback') }}" active="{{ request()->routeIs('teacher.feedback*') }}">
                            Feedback
                        </x-sidebar-link>

                        <x-sidebar-link href="{{ route('teacher.quizzes.index') }}" active="{{ request()->routeIs('teacher.quizzes.index*') }}">
                            Manage Quizzes
                        </x-sidebar-link>

                        <x-sidebar-link href="{{ route('teacher.quizzes.attempts') }}" active="{{ request()->routeIs('teacher.quizzes.attempts*') }}">
                            Quiz Attempts
                        </x-sidebar-link>

                    @elseif($role === 'student')
                        <!-- Student Navigation -->
                        <x-sidebar-link href="{{ route('student.dashboard') }}" active="{{ request()->routeIs('student.dashboard') }}">
                            Dashboard
                        </x-sidebar-link>

                        <x-sidebar-link href="{{ route('student.marks') }}" active="{{ request()->routeIs('student.marks*') }}">
                            My Marks
                        </x-sidebar-link>

                        <x-sidebar-link href="{{ route('student.attendance') }}" active="{{ request()->routeIs('student.attendance*') }}">
                            Attendance
                        </x-sidebar-link>

                        <x-sidebar-link href="{{ route('student.tasks') }}" active="{{ request()->routeIs('student.tasks*') }}">
                            Tasks
                        </x-sidebar-link>

                        <x-sidebar-link href="{{ route('student.quizzes.index') }}" active="{{ request()->routeIs('student.quizzes.index*') || request()->routeIs('student.quizzes.attempt*') || request()->routeIs('student.quizzes.result*') }}">
                            My Quizzes
                        </x-sidebar-link>

                        <x-sidebar-link href="{{ route('student.feedback') }}" active="{{ request()->routeIs('student.feedback*') }}">
                            Feedback
                        </x-sidebar-link>
                    @endif
                </nav>
            </div>

            <!-- Logout Link in Sidebar Footer -->
            <div class="flex-shrink-0 border-t border-zinc-200 dark:border-zinc-900 p-4">
                <form method="POST" action="{{ route('logout') }}" class="w-full">
                    @csrf
                    <button type="submit" class="flex w-full items-center rounded-lg px-4 py-2.5 text-sm font-semibold text-red-600 hover:bg-zinc-200/50 dark:hover:bg-white/5 transition-all">
                        Logout
                    </button>
                </form>
            </div>
        </div>

        <!-- Main content area -->
        <div class="flex flex-col lg:pl-72">
            <!-- Top Navbar (header for mobile/desktop) -->
            <div class="sticky top-0 z-10 flex h-16 flex-shrink-0 bg-white dark:bg-zinc-950 border-b border-zinc-200 dark:border-zinc-800 lg:border-none shadow-sm lg:shadow-none px-4 sm:px-6 lg:px-8">
                <!-- Mobile Menu Button -->
                <button type="button" class="lg:hidden -ml-2.5 mr-2.5 inline-flex items-center justify-center rounded-lg p-2 text-zinc-500 dark:text-zinc-400 hover:bg-zinc-100 dark:hover:bg-zinc-900 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-zinc-500" @click="mobileMenuOpen = true">
                    <span class="sr-only">Open sidebar</span>
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                    </svg>
                </button>

                <!-- Page Header Title (Breadcrumbs) -->
                <div class="flex flex-1 items-center justify-between">
                    <div>
                        <h1 class="text-lg lg:text-xl font-bold text-zinc-900 dark:text-white">
                            @yield('header_title', 'Slow Learners Remedial Portal')
                        </h1>
                    </div>

                    <!-- Right Side Top Menu (Theme display or quick actions) -->
                    <div class="ml-4 flex items-center gap-4">
                        <div class="text-xs text-zinc-500 dark:text-zinc-400 font-semibold hidden sm:block">
                            {{ date('l, d F Y') }}
                        </div>

                        <!-- Theme Toggle Button -->
                        <button id="theme-toggle" type="button" class="text-zinc-500 dark:text-zinc-400 hover:bg-zinc-100 dark:hover:bg-zinc-900 focus:outline-none focus:ring-2 focus:ring-zinc-500 rounded-xl p-2 transition-all duration-200 shadow-sm border border-zinc-200 dark:border-zinc-800 bg-white dark:bg-zinc-900" aria-label="Toggle dark mode">
                            <!-- Sun Icon (visible in dark mode) -->
                            <svg id="theme-toggle-light-icon" class="hidden h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.46 5.05l-.707-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z" fill-rule="evenodd" clip-rule="evenodd"></path>
                            </svg>
                            <!-- Moon Icon (visible in light mode) -->
                            <svg id="theme-toggle-dark-icon" class="hidden h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"></path>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Page Main Body Content -->
            <main class="flex-1 py-8 px-4 sm:px-6 lg:px-8">
                <!-- Session Alert Notifications -->
                @if(session('success'))
                    <div class="mb-6 rounded-xl border border-emerald-100 bg-emerald-50 dark:bg-emerald-950/20 dark:border-emerald-900/30 p-4 text-emerald-800 dark:text-emerald-400 flex items-center gap-3 animate-fade-in">
                        <svg class="h-5 w-5 flex-shrink-0 text-emerald-600 dark:text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span class="text-sm font-semibold">{{ session('success') }}</span>
                    </div>
                @endif

                @if($errors->any())
                    <div class="mb-6 rounded-xl border border-red-100 bg-red-50 dark:bg-red-950/20 dark:border-red-900/30 p-4 text-red-800 dark:text-red-400 animate-fade-in">
                        <div class="flex items-center gap-3 mb-2">
                            <svg class="h-5 w-5 flex-shrink-0 text-red-600 dark:text-red-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
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
                <div class="relative flex-1 flex flex-col max-w-xs w-full bg-zinc-100 dark:bg-zinc-950 pt-5 pb-4 transition duration-300 transform border-r border-zinc-200 dark:border-zinc-900" x-show="mobileMenuOpen" x-transition:enter="transition ease-in-out duration-300" x-transition:enter-start="-translate-x-full" x-transition:enter-end="translate-x-0" x-transition:leave="transition ease-in-out duration-200" x-transition:leave-start="translate-x-0" x-transition:leave-end="-translate-x-full">
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
                            <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-gradient-to-tr from-zinc-700 to-zinc-900 dark:from-zinc-800 dark:to-zinc-950 shadow-sm text-white border border-zinc-650 dark:border-zinc-800">
                                <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.263 15.541a1.99 1.99 0 0 0 .714 1.055l7.5 5.25a2 2 0 0 0 2.246 0l7.5-5.25a1.99 1.99 0 0 0 .714-1.055L22.5 12l-7.5-5.25a2 2 0 0 0-2.246 0L5.25 12l-0.987 3.541Z" />
                                </svg>
                            </div>
                            <span class="text-base font-bold tracking-tight text-zinc-900 dark:text-white">Remedial Portal</span>
                        </div>
                    </div>

                    <div class="mt-6 flex-1 flex flex-col overflow-y-auto px-4">
                        <nav class="space-y-1">
                            <!-- Duplicate links for mobile menu -->
                            @if($role === 'admin')
                                <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'group flex items-center rounded-lg bg-zinc-900 dark:bg-white/10 border border-transparent dark:border-white/10 px-4 py-2.5 text-sm font-semibold text-white transition-all duration-150 shadow-sm' : 'group flex items-center rounded-lg px-4 py-2.5 text-sm font-medium text-zinc-600 dark:text-zinc-400 hover:bg-zinc-200/50 hover:text-zinc-900 dark:hover:bg-white/5 dark:hover:text-white border border-transparent transition-all duration-150' }}">Dashboard</a>
                                <a href="{{ route('admin.students') }}" class="{{ request()->routeIs('admin.students*') ? 'group flex items-center rounded-lg bg-zinc-900 dark:bg-white/10 border border-transparent dark:border-white/10 px-4 py-2.5 text-sm font-semibold text-white transition-all duration-150 shadow-sm' : 'group flex items-center rounded-lg px-4 py-2.5 text-sm font-medium text-zinc-600 dark:text-zinc-400 hover:bg-zinc-200/50 hover:text-zinc-900 dark:hover:bg-white/5 dark:hover:text-white border border-transparent transition-all duration-150' }}">Students</a>
                                <a href="{{ route('admin.teachers') }}" class="{{ request()->routeIs('admin.teachers*') ? 'group flex items-center rounded-lg bg-zinc-900 dark:bg-white/10 border border-transparent dark:border-white/10 px-4 py-2.5 text-sm font-semibold text-white transition-all duration-150 shadow-sm' : 'group flex items-center rounded-lg px-4 py-2.5 text-sm font-medium text-zinc-600 dark:text-zinc-400 hover:bg-zinc-200/50 hover:text-zinc-900 dark:hover:bg-white/5 dark:hover:text-white border border-transparent transition-all duration-150' }}">Teachers</a>
                                <a href="{{ route('admin.classes') }}" class="{{ request()->routeIs('admin.classes*') ? 'group flex items-center rounded-lg bg-zinc-900 dark:bg-white/10 border border-transparent dark:border-white/10 px-4 py-2.5 text-sm font-semibold text-white transition-all duration-150 shadow-sm' : 'group flex items-center rounded-lg px-4 py-2.5 text-sm font-medium text-zinc-600 dark:text-zinc-400 hover:bg-zinc-200/50 hover:text-zinc-900 dark:hover:bg-white/5 dark:hover:text-white border border-transparent transition-all duration-150' }}">Classes</a>
                                <a href="{{ route('admin.subjects') }}" class="{{ request()->routeIs('admin.subjects*') ? 'group flex items-center rounded-lg bg-zinc-900 dark:bg-white/10 border border-transparent dark:border-white/10 px-4 py-2.5 text-sm font-semibold text-white transition-all duration-150 shadow-sm' : 'group flex items-center rounded-lg px-4 py-2.5 text-sm font-medium text-zinc-600 dark:text-zinc-400 hover:bg-zinc-200/50 hover:text-zinc-900 dark:hover:bg-white/5 dark:hover:text-white border border-transparent transition-all duration-150' }}">Subjects</a>
                                <a href="{{ route('admin.assessments') }}" class="{{ request()->routeIs('admin.assessments*') ? 'group flex items-center rounded-lg bg-zinc-900 dark:bg-white/10 border border-transparent dark:border-white/10 px-4 py-2.5 text-sm font-semibold text-white transition-all duration-150 shadow-sm' : 'group flex items-center rounded-lg px-4 py-2.5 text-sm font-medium text-zinc-600 dark:text-zinc-400 hover:bg-zinc-200/50 hover:text-zinc-900 dark:hover:bg-white/5 dark:hover:text-white border border-transparent transition-all duration-150' }}">Assessments</a>
                                <a href="{{ route('admin.attendance') }}" class="{{ request()->routeIs('admin.attendance*') ? 'group flex items-center rounded-lg bg-zinc-900 dark:bg-white/10 border border-transparent dark:border-white/10 px-4 py-2.5 text-sm font-semibold text-white transition-all duration-150 shadow-sm' : 'group flex items-center rounded-lg px-4 py-2.5 text-sm font-medium text-zinc-600 dark:text-zinc-400 hover:bg-zinc-200/50 hover:text-zinc-900 dark:hover:bg-white/5 dark:hover:text-white border border-transparent transition-all duration-150' }}">Attendance</a>
                                <a href="{{ route('admin.tasks') }}" class="{{ request()->routeIs('admin.tasks*') ? 'group flex items-center rounded-lg bg-zinc-900 dark:bg-white/10 border border-transparent dark:border-white/10 px-4 py-2.5 text-sm font-semibold text-white transition-all duration-150 shadow-sm' : 'group flex items-center rounded-lg px-4 py-2.5 text-sm font-medium text-zinc-600 dark:text-zinc-400 hover:bg-zinc-200/50 hover:text-zinc-900 dark:hover:bg-white/5 dark:hover:text-white border border-transparent transition-all duration-150' }}">Tasks</a>
                                <a href="{{ route('admin.feedback') }}" class="{{ request()->routeIs('admin.feedback*') ? 'group flex items-center rounded-lg bg-zinc-900 dark:bg-white/10 border border-transparent dark:border-white/10 px-4 py-2.5 text-sm font-semibold text-white transition-all duration-150 shadow-sm' : 'group flex items-center rounded-lg px-4 py-2.5 text-sm font-medium text-zinc-600 dark:text-zinc-400 hover:bg-zinc-200/50 hover:text-zinc-900 dark:hover:bg-white/5 dark:hover:text-white border border-transparent transition-all duration-150' }}">Feedback</a>
                            @elseif($role === 'teacher')
                                <a href="{{ route('teacher.dashboard') }}" class="{{ request()->routeIs('teacher.dashboard') ? 'group flex items-center rounded-lg bg-zinc-900 dark:bg-white/10 border border-transparent dark:border-white/10 px-4 py-2.5 text-sm font-semibold text-white transition-all duration-150 shadow-sm' : 'group flex items-center rounded-lg px-4 py-2.5 text-sm font-medium text-zinc-600 dark:text-zinc-400 hover:bg-zinc-200/50 hover:text-zinc-900 dark:hover:bg-white/5 dark:hover:text-white border border-transparent transition-all duration-150' }}">Dashboard</a>
                                <a href="{{ route('teacher.assessments') }}" class="{{ request()->routeIs('teacher.assessments*') ? 'group flex items-center rounded-lg bg-zinc-900 dark:bg-white/10 border border-transparent dark:border-white/10 px-4 py-2.5 text-sm font-semibold text-white transition-all duration-150 shadow-sm' : 'group flex items-center rounded-lg px-4 py-2.5 text-sm font-medium text-zinc-600 dark:text-zinc-400 hover:bg-zinc-200/50 hover:text-zinc-900 dark:hover:bg-white/5 dark:hover:text-white border border-transparent transition-all duration-150' }}">Assessments</a>
                                <a href="{{ route('teacher.attendance') }}" class="{{ request()->routeIs('teacher.attendance*') ? 'group flex items-center rounded-lg bg-zinc-900 dark:bg-white/10 border border-transparent dark:border-white/10 px-4 py-2.5 text-sm font-semibold text-white transition-all duration-150 shadow-sm' : 'group flex items-center rounded-lg px-4 py-2.5 text-sm font-medium text-zinc-600 dark:text-zinc-400 hover:bg-zinc-200/50 hover:text-zinc-900 dark:hover:bg-white/5 dark:hover:text-white border border-transparent transition-all duration-150' }}">Attendance</a>
                                <a href="{{ route('teacher.tasks') }}" class="{{ request()->routeIs('teacher.tasks*') ? 'group flex items-center rounded-lg bg-zinc-900 dark:bg-white/10 border border-transparent dark:border-white/10 px-4 py-2.5 text-sm font-semibold text-white transition-all duration-150 shadow-sm' : 'group flex items-center rounded-lg px-4 py-2.5 text-sm font-medium text-zinc-600 dark:text-zinc-400 hover:bg-zinc-200/50 hover:text-zinc-900 dark:hover:bg-white/5 dark:hover:text-white border border-transparent transition-all duration-150' }}">Assign Remedials</a>
                                <a href="{{ route('teacher.assignments') }}" class="{{ request()->routeIs('teacher.assignments*') ? 'group flex items-center rounded-lg bg-zinc-900 dark:bg-white/10 border border-transparent dark:border-white/10 px-4 py-2.5 text-sm font-semibold text-white transition-all duration-150 shadow-sm' : 'group flex items-center rounded-lg px-4 py-2.5 text-sm font-medium text-zinc-600 dark:text-zinc-400 hover:bg-zinc-200/50 hover:text-zinc-900 dark:hover:bg-white/5 dark:hover:text-white border border-transparent transition-all duration-150' }}">Create Assignment</a>
                                <a href="{{ route('teacher.assignments.submissions') }}" class="{{ request()->routeIs('teacher.assignments.submissions*') ? 'group flex items-center rounded-lg bg-zinc-900 dark:bg-white/10 border border-transparent dark:border-white/10 px-4 py-2.5 text-sm font-semibold text-white transition-all duration-150 shadow-sm' : 'group flex items-center rounded-lg px-4 py-2.5 text-sm font-medium text-zinc-600 dark:text-zinc-400 hover:bg-zinc-200/50 hover:text-zinc-900 dark:hover:bg-white/5 dark:hover:text-white border border-transparent transition-all duration-150' }}">Assignment Submissions</a>
                                <a href="{{ route('teacher.remedials.submissions') }}" class="{{ request()->routeIs('teacher.remedials.submissions*') ? 'group flex items-center rounded-lg bg-zinc-900 dark:bg-white/10 border border-transparent dark:border-white/10 px-4 py-2.5 text-sm font-semibold text-white transition-all duration-150 shadow-sm' : 'group flex items-center rounded-lg px-4 py-2.5 text-sm font-medium text-zinc-600 dark:text-zinc-400 hover:bg-zinc-200/50 hover:text-zinc-900 dark:hover:bg-white/5 dark:hover:text-white border border-transparent transition-all duration-150' }}">Remedial Review Panel</a>
                                <a href="{{ route('teacher.quizzes.index') }}" class="{{ request()->routeIs('teacher.quizzes.index*') ? 'group flex items-center rounded-lg bg-zinc-900 dark:bg-white/10 border border-transparent dark:border-white/10 px-4 py-2.5 text-sm font-semibold text-white transition-all duration-150 shadow-sm' : 'group flex items-center rounded-lg px-4 py-2.5 text-sm font-medium text-zinc-600 dark:text-zinc-400 hover:bg-zinc-200/50 hover:text-zinc-900 dark:hover:bg-white/5 dark:hover:text-white border border-transparent transition-all duration-150' }}">Manage Quizzes</a>
                                <a href="{{ route('teacher.quizzes.attempts') }}" class="{{ request()->routeIs('teacher.quizzes.attempts*') ? 'group flex items-center rounded-lg bg-zinc-900 dark:bg-white/10 border border-transparent dark:border-white/10 px-4 py-2.5 text-sm font-semibold text-white transition-all duration-150 shadow-sm' : 'group flex items-center rounded-lg px-4 py-2.5 text-sm font-medium text-zinc-600 dark:text-zinc-400 hover:bg-zinc-200/50 hover:text-zinc-900 dark:hover:bg-white/5 dark:hover:text-white border border-transparent transition-all duration-150' }}">Quiz Attempts</a>
                                <a href="{{ route('teacher.feedback') }}" class="{{ request()->routeIs('teacher.feedback*') ? 'group flex items-center rounded-lg bg-zinc-900 dark:bg-white/10 border border-transparent dark:border-white/10 px-4 py-2.5 text-sm font-semibold text-white transition-all duration-150 shadow-sm' : 'group flex items-center rounded-lg px-4 py-2.5 text-sm font-medium text-zinc-600 dark:text-zinc-400 hover:bg-zinc-200/50 hover:text-zinc-900 dark:hover:bg-white/5 dark:hover:text-white border border-transparent transition-all duration-150' }}">Feedback</a>
                            @elseif($role === 'student')
                                <a href="{{ route('student.dashboard') }}" class="{{ request()->routeIs('student.dashboard') ? 'group flex items-center rounded-lg bg-zinc-900 dark:bg-white/10 border border-transparent dark:border-white/10 px-4 py-2.5 text-sm font-semibold text-white transition-all duration-150 shadow-sm' : 'group flex items-center rounded-lg px-4 py-2.5 text-sm font-medium text-zinc-600 dark:text-zinc-400 hover:bg-zinc-200/50 hover:text-zinc-900 dark:hover:bg-white/5 dark:hover:text-white border border-transparent transition-all duration-150' }}">Dashboard</a>
                                <a href="{{ route('student.marks') }}" class="{{ request()->routeIs('student.marks*') ? 'group flex items-center rounded-lg bg-zinc-900 dark:bg-white/10 border border-transparent dark:border-white/10 px-4 py-2.5 text-sm font-semibold text-white transition-all duration-150 shadow-sm' : 'group flex items-center rounded-lg px-4 py-2.5 text-sm font-medium text-zinc-600 dark:text-zinc-400 hover:bg-zinc-200/50 hover:text-zinc-900 dark:hover:bg-white/5 dark:hover:text-white border border-transparent transition-all duration-150' }}">My Marks</a>
                                <a href="{{ route('student.attendance') }}" class="{{ request()->routeIs('student.attendance*') ? 'group flex items-center rounded-lg bg-zinc-900 dark:bg-white/10 border border-transparent dark:border-white/10 px-4 py-2.5 text-sm font-semibold text-white transition-all duration-150 shadow-sm' : 'group flex items-center rounded-lg px-4 py-2.5 text-sm font-medium text-zinc-600 dark:text-zinc-400 hover:bg-zinc-200/50 hover:text-zinc-900 dark:hover:bg-white/5 dark:hover:text-white border border-transparent transition-all duration-150' }}">Attendance</a>
                                <a href="{{ route('student.tasks') }}" class="{{ request()->routeIs('student.tasks*') ? 'group flex items-center rounded-lg bg-zinc-900 dark:bg-white/10 border border-transparent dark:border-white/10 px-4 py-2.5 text-sm font-semibold text-white transition-all duration-150 shadow-sm' : 'group flex items-center rounded-lg px-4 py-2.5 text-sm font-medium text-zinc-600 dark:text-zinc-400 hover:bg-zinc-200/50 hover:text-zinc-900 dark:hover:bg-white/5 dark:hover:text-white border border-transparent transition-all duration-150' }}">Tasks</a>
                                <a href="{{ route('student.quizzes.index') }}" class="{{ request()->routeIs('student.quizzes.index*') || request()->routeIs('student.quizzes.attempt*') || request()->routeIs('student.quizzes.result*') ? 'group flex items-center rounded-lg bg-zinc-900 dark:bg-white/10 border border-transparent dark:border-white/10 px-4 py-2.5 text-sm font-semibold text-white transition-all duration-150 shadow-sm' : 'group flex items-center rounded-lg px-4 py-2.5 text-sm font-medium text-zinc-600 dark:text-zinc-400 hover:bg-zinc-200/50 hover:text-zinc-900 dark:hover:bg-white/5 dark:hover:text-white border border-transparent transition-all duration-150' }}">My Quizzes</a>
                                <a href="{{ route('student.feedback') }}" class="{{ request()->routeIs('student.feedback*') ? 'group flex items-center rounded-lg bg-zinc-900 dark:bg-white/10 border border-transparent dark:border-white/10 px-4 py-2.5 text-sm font-semibold text-white transition-all duration-150 shadow-sm' : 'group flex items-center rounded-lg px-4 py-2.5 text-sm font-medium text-zinc-600 dark:text-zinc-400 hover:bg-zinc-200/50 hover:text-zinc-900 dark:hover:bg-white/5 dark:hover:text-white border border-transparent transition-all duration-150' }}">Feedback</a>
                            @endif
                        </nav>
                    </div>

                    <div class="flex-shrink-0 border-t border-zinc-200 dark:border-zinc-800 p-4">
                        <form method="POST" action="{{ route('logout') }}" class="w-full">
                            @csrf
                            <button type="submit" class="flex w-full items-center rounded-lg px-4 py-2.5 text-sm font-semibold text-red-650 hover:bg-zinc-200/50 dark:hover:bg-white/5 transition-all">
                                Logout
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Theme Switcher Action Script -->
    <script>
        var themeToggleDarkIcon = document.getElementById('theme-toggle-dark-icon');
        var themeToggleLightIcon = document.getElementById('theme-toggle-light-icon');

        // Change the icons based on current class list
        if (document.documentElement.classList.contains('dark')) {
            themeToggleLightIcon.classList.remove('hidden');
        } else {
            themeToggleDarkIcon.classList.remove('hidden');
        }

        var themeToggleBtn = document.getElementById('theme-toggle');

        themeToggleBtn.addEventListener('click', function() {
            // Toggle icons inside button
            themeToggleDarkIcon.classList.toggle('hidden');
            themeToggleLightIcon.classList.toggle('hidden');

            // If set via localStorage previously
            if (localStorage.getItem('theme')) {
                if (localStorage.getItem('theme') === 'light') {
                    document.documentElement.classList.add('dark');
                    localStorage.setItem('theme', 'dark');
                } else {
                    document.documentElement.classList.remove('dark');
                    localStorage.setItem('theme', 'light');
                }
            } else {
                // If not set previously
                if (document.documentElement.classList.contains('dark')) {
                    document.documentElement.classList.remove('dark');
                    localStorage.setItem('theme', 'light');
                } else {
                    document.documentElement.classList.add('dark');
                    localStorage.setItem('theme', 'dark');
                }
            }
        });
    </script>
</body>
</html>
