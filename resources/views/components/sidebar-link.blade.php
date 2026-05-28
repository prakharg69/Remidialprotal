@props(['active' => false, 'href' => '#'])

@php
$classes = ($active ?? false)
            ? 'group flex items-center rounded-lg bg-zinc-900 dark:bg-white/10 border border-transparent dark:border-white/10 px-4 py-2.5 text-sm font-semibold text-white transition-all duration-150 shadow-sm'
            : 'group flex items-center rounded-lg px-4 py-2.5 text-sm font-medium text-zinc-600 dark:text-zinc-400 hover:bg-zinc-200/50 hover:text-zinc-900 dark:hover:bg-white/5 dark:hover:text-white border border-transparent transition-all duration-150';
@endphp

<a href="{{ $href }}" {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
