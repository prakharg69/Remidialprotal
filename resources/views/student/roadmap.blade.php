@extends('layouts.dashboard')

@section('title', 'My Learning Roadmap')
@section('header_title', 'Learning Roadmap')

@section('content')
<div class="space-y-8 animate-fade-in text-slate-100">
    <!-- Header Card -->
    <div class="relative overflow-hidden rounded-3xl bg-slate-900 border border-slate-800 p-6 sm:p-8 shadow-xl">
        <div class="absolute right-0 top-0 translate-x-1/3 -translate-y-1/3 h-72 w-72 rounded-full bg-indigo-500/10 blur-3xl pointer-events-none"></div>
        <div class="absolute left-1/3 bottom-0 translate-y-1/2 h-48 w-48 rounded-full bg-violet-500/10 blur-3xl pointer-events-none"></div>
        <div class="relative z-10">
            <h2 class="text-2xl font-black text-white tracking-tight mb-2">
                Your Personalized Learning Roadmap
            </h2>
            <p class="text-xs text-slate-400">
                Powered by the external Roadmap service. The diagram reflects your current progress and suggested next steps.
            </p>
        </div>
    </div>

    <!-- Roadmap Card -->
    <div class="bg-slate-900 border border-slate-800 rounded-2xl p-6 shadow-md">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-base font-bold text-white">Roadmap Overview</h3>
            <button id="refreshRoadmap" class="inline-flex items-center gap-1.5 rounded-xl bg-indigo-800 hover:bg-indigo-700 text-xs font-medium text-white px-3 py-1.5 transition-colors" onclick="loadRoadmap()">
                Refresh
                <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h5M20 20v-5h-5"/></svg>
            </button>
        </div>
        <div id="roadmapContainer" class="relative min-h-[300px] flex items-center justify-center">
            <div id="loadingSpinner" class="animate-spin rounded-full h-12 w-12 border-t-2 border-b-2 border-indigo-500"></div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/mermaid@10/dist/mermaid.min.js"></script>
<script>
    const roadmapData = @json($roadmap ?? []);
    const container = document.getElementById('roadmapContainer');
    const spinner = document.getElementById('loadingSpinner');

    function renderDiagram(diagram) {
        container.innerHTML = `<div class="mermaid">${diagram}</div>`;
        mermaid.initialize({ startOnLoad: true, theme: 'dark', securityLevel: 'strict' });
    }

    function loadRoadmap() {
        spinner.style.display = 'block';
        // If the roadmap data is already present from server, use it; otherwise fallback to placeholder.
        if (roadmapData.diagram) {
            renderDiagram(roadmapData.diagram);
            spinner.style.display = 'none';
        } else {
            // Fallback placeholder diagram
            const placeholder = `graph LR\n    A[Start] --> B[Core Subjects]\n    B --> C[Intermediate]\n    C --> D[Advanced]\n    D --> E[Completion]`;
            renderDiagram(placeholder);
            spinner.style.display = 'none';
        }
    }

    document.addEventListener('DOMContentLoaded', loadRoadmap);
</script>
@endsection
