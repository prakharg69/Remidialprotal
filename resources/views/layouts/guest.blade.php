<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Slow Learners Remedial Portal') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
            body {
                font-family: 'Inter', sans-serif;
            }
        </style>
    </head>
    <body class="font-sans text-slate-100 antialiased bg-slate-950">
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-slate-900/40">
            <div class="flex items-center gap-2.5 mb-2">
                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-gradient-to-tr from-indigo-500 to-indigo-600 shadow-md text-white">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.263 15.541a1.99 1.99 0 0 0 .714 1.055l7.5 5.25a2 2 0 0 0 2.246 0l7.5-5.25a1.99 1.99 0 0 0 .714-1.055L22.5 12l-7.5-5.25a2 2 0 0 0-2.246 0L5.25 12l-0.987 3.541Z" />
                    </svg>
                </div>
                <span class="text-lg font-bold tracking-tight text-white">Remedial Portal</span>
            </div>

            <div class="w-full sm:max-w-md mt-6 px-8 py-8 bg-slate-950 border border-slate-800/60 shadow-xl overflow-hidden sm:rounded-2xl">
                {{ $slot }}
            </div>
        </div>
    </body>
</html>
