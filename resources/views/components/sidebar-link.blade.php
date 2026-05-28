@props(['active' => false, 'href' => '#'])

@php
$classes = ($active ?? false)
            ? 'group flex items-center rounded-lg bg-indigo-50 dark:bg-indigo-950/40 px-4 py-2.5 text-sm font-semibold text-indigo-600 dark:text-indigo-400 border border-indigo-100/50 dark:border-indigo-900/30 transition-all duration-150 shadow-xs'
            : 'group flex items-center rounded-lg px-4 py-2.5 text-sm font-medium text-slate-700 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-900/50 hover:text-slate-900 dark:hover:text-white transition-all duration-150';
@endphp

<a href="{{ $href }}" {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
